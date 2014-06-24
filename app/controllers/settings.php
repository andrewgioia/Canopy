<?php

class Settings extends Controller {

    private $_xml;
    private $_energy;
    private $_settings;
    private $_weather;

    public function __construct(){
        parent::__construct();
        $this->_xml = $this->loadModel( 'xml_model' );
        $this->_energy = $this->loadModel( 'energy_model' );
        $this->_settings = $this->loadModel( 'settings_model' );
        $this->_weather = $this->loadModel( 'weather_model' );
    }

    // Main settings screen
    //
    public function index() 
    {
        $data[ 'title' ] = 'Settings';

        $this->view->rendertemplate( 'header', $data );
        $this->view->render( 'settings/settings', $data );
        $this->view->rendertemplate( 'footer', $data );
    }

    //
    // Settings actions
    //

    // Add hourly weather values and the daily summary for a day
    //
    public function add_weather_for_day(
        $date = '',                 // day to add               @MMMM d, YYYY
        $ajax = false )             // ajax bit
    {
        // If we have an ajax post, set the date vars
        //
        if ( $_POST ) {
            $date = $_POST[ 'date' ];
            $ajax = $_POST[ 'ajax' ];
        }

        // Get an array of the hourly weather data
        //
        $y = date( 'Y', strtotime( $date ) );
        $m = date( 'm', strtotime( $date ) );
        $d = date( 'd', strtotime( $date ) );
        $array = $this->_weather->pull_weather_for_day( $y, $m, $d, $error = false );

        // If it's good, insert each hour and the daily info
        //
        if ( ! $error ) {
            $date = date( 'Y-m-d', mktime( 0, 0, 0, $m, $d, $y ) );
            if ( is_array( $array ) && count( $array ) > 0 ) {
                foreach ( $array as $hour => $values ) {
                    // Hours
                    if ( is_numeric( $hour ) ) { 
                        $hour = str_pad( $hour, 2, '0', STR_PAD_LEFT );
                        $insert = $this->_weather->insert_hour(
                            $date,
                            $hour,
                            $values[ 'temp'],
                            $values[ 'cond'] );
                    // Day
                    } else if ( $hour == 'day' ) {
                        $insert = $this->_weather->insert_day(
                            $date,
                            $values[ 'mint'],
                            $values[ 'maxt'],
                            $values[ 'noon'],
                            $values[ 'rain'],
                            $values[ 'fog'],
                            $values[ 'snow'],
                            $values[ 'hail'],
                            $values[ 'thunder'],
                            $values[ 'tornado'] );
                    }
                }
            }
            echo 'Weather imported successfully for '.date( 'F jS, Y', strtotime( $date ) );
        }
    }

    // Add a new vacation time period
    //
    public function add_vacation(
        $start_date = '',           // start date               @string 'yyyy-mm-dd'
        $start_time = '00:00',      // start time               @string 'hh:ii'
        $end_date = '',             // end date                 @string 'yyyy-mm-dd'
        $end_time = '00:00',        // end time                 @string 'hh:ii'
        $title = '',                // description              @varchar(256)
        $empty = 1,                 // bit for empty house      @enum(0,1)
        $ajax = false )
    {
        // If we have an ajax post, set the vars
        //
        if ( $_POST ) {
            $start_date = $_POST[ 'start_date' ];
            $start_time = $_POST[ 'start_time' ];
            $end_date = $_POST[ 'end_date' ];
            $end_time = $_POST[ 'end_time' ];
            $title = $_POST[ 'title' ];
            $empty = $_POST[ 'empty' ];
            $ajax = $_POST[ 'ajax' ];
        }

        // Validate the title
        //
        if ( strlen( trim( $title ) ) == 0 ) {
            echo "Please enter a title!";
            return false;
        }

        // Validate the date
        //
        if ( ! strtotime( $start_date ) || ! strtotime( $end_date ) ) {
            echo "Please enter a valid date for the start and end date.";
            return false;
        }

        // Format the data to make sure it fits
        //
        $start = date( 'Y-m-d', strtotime( $start_date ) )." ".$start_time.":00";
        $end = date( 'Y-m-d', strtotime( $end_date ) )." ".$end_time.":00";
        $empty = ( $empty == 0 ) ? 0 : 1;

        // Insert this into the database
        //
        $insert = $this->_settings->insert_vacation(
            $start, $end, $title, $empty, $error = false );

        // Close this out
        //
        if ( ! $error ) {
            echo "Vacation added successfully!";
            return true;
        } else {
            echo "There was a problem adding that vacation. Please try again.";
            return false;
        }
    }

    public function add_kwh_month()
    {
        // Check if there was an error passed
        //
        if ( !isset( $_FILES[ 'month_xml' ][ 'error' ] ) 
                || is_array( $_FILES ['month_xml' ][ 'error' ] ) ) {
            $error = "There was a problem with the file upload.";
            return $error;
        }

        // Get the file content
        //
        $file = file_get_contents( $_FILES[ 'month_xml' ][ 'tmp_name' ] );
        $xml = simplexml_load_string( $file );

        // Convert XML into something workable and create the month array
        //
        $array = $this->_xml->convert_to_array( $xml );
        $entries = $this->_xml->get_readings( $array );

        // Get the x and y axis values for this chart
        //
        foreach ( $entries as $num => $entry ) {
            $hour = date( 'Y-m-d H:i:s', $entry->timePeriod->start );
            $kwh = number_format( $entry->value/1000, 0 );
            $cost = number_format( $entry->cost/100000, 4 );
            $result = $this->_energy->insert_entry( $hour, $kwh, $cost );
        }

        return true;
    }

}