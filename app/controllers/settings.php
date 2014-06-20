<?php

class Settings extends Controller {

    private $_settings;
    private $_weather;

    public function __construct(){
        parent::__construct();
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
        $date = 'January 1, 2014',
        $ajax = false )
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

}