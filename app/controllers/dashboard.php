<?php

class Dashboard extends Controller {

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

    // Main dashboard screen
    //
    public function index( 
        $display_type = 'month', 
        $display_date = '2014-04', 
        $ajax = 0 ) 
    {
        // Cleanse the post variables if we received an ajax request
        //
        if ( $_POST ) {
            $display_type = $_POST[ 'display_type' ];
            $display_date = $_POST[ 'display_date' ];
            $ajax = $_POST[ 'ajax' ];
        }

        // Get date variables;
        // will return the current day/month if none is in display_date
        //
        $month = date( 'm', strtotime( $display_date ) );
        $day = date( 'd', strtotime( $display_date ) );
        $year = date( 'Y', strtotime( $display_date ) );

        // Build chart based on view type
        //
        if ( $display_type == 'month' ) {

            // Get the readings from the database for this month
            //
            $readings = $this->_energy->get_readings_for_month( $year, $month );

            // Get the x and y axis values for this chart
            //
            $plots = array();
            foreach ( $readings as $hour => $vals ) {
                $d = date( 'd', strtotime( $vals->used ) );
                $plots[ date( 'D d', mktime( 0, 0, 0, $month, $d, $year ) ) ] += $vals->kwh;
            }

            // Create the chart variables
            //
            $data[ 'chart' ] = array (
                'title' => "Daily Energy Use&mdash;".
                    date( 'F Y', mktime( 0, 0, 0, $month, $day, $year ) ),
                'x_days' => "'".implode( "','", array_keys( $plots ) )."'",
                'y_vals' => implode( ",", array_values( $plots ) ) );

            // Get the weekends
            //
            $data[ 'weekends' ] = 
                $this->_settings->get_weekends_for_month( $year, $month );

            // Get the weather values
            //
            $data[ 'weather' ] = 
                $this->_weather->get_daily_weather_for_month( $year, $month );

            // Get the vacation times
            //
            $data[ 'vacations' ] = 
                $this->_settings->get_vacations_for_month( $year, $month );

            $chart_view = "dashboard/month";

        } else if ( $display_type == 'day' ) {

            // Get the day's hourly readings from the database
            //
            $readings = $this->_energy->get_readings_for_day( $year, $month, $day );

            // Set the x axis (hours) as key= and y axis (kwh) as values in an array
            //
            $plots = array();
            foreach ( $readings as $hour => $vals ) {
                $plots[ date( 'ga', strtotime( $vals->used ) ) ] = $vals->kwh;
            }

            // Create the chart variables
            //
            $data[ 'chart' ] = array (
                'title' => "Hourly Energy Use&mdash;".
                    date( 'F d, Y', mktime( 0, 0, 0, $month, $day, $year ) ),
                'x_hours' => "'".implode( "','", array_keys( $plots ) )."'",
                'y_kwhs' => implode( ",", array_values( $plots ) ) );

            // Disabling weekend and vacation overlays
            //
            $data[ 'weekends' ] = false;
            $data[ 'vacations' ] = false;

            // Get the hourly weather
            //
            $data[ 'weather' ] = 
                $this->_weather->get_hourly_weather_for_day( $year, $month, $day );

            $chart_view = "dashboard/day";

        } else {

            // we have an error with the file, handle accordingly

        }

        $data[ 'title' ] = 'Dashboard';
        $data[ 'class' ] = 'dashboard';
        $data[ 'y' ] = $year;
        $data[ 'm' ] = $month;
        $data[ 'd' ] = $day;

        if ( $ajax ) {
            $this->view->render( 'dashboard/menu', $data );
            $this->view->render( $chart_view, $data );
        } else {
            $this->view->rendertemplate( 'header', $data );
            $this->view->render( 'dashboard/menu', $data );
            $this->view->render( $chart_view, $data );
            $this->view->rendertemplate( 'footer', $data );
        }
    }

}