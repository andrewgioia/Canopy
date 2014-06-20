<?php

class Dashboard extends Controller {

    private $_xml;
    private $_settings;
    private $_weather;

    public function __construct(){
        parent::__construct();
        $this->_xml = $this->loadModel( 'xml_model' );
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
        // Cleanse the post variables if we got an ajax request
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

        // Get the contents of the selected XML file
        //        
        $contents = $this->_xml->get_xml_file( $year.'-'.$month.'.xml', $error = false );

        // Format the daily readings into a multidimensional array;
        // each element is a day with an array of hours
        //
        if ( ! $error ) {

            // Convert XML into something workable and create the  month array
            //
            $array = $this->_xml->convert_to_array( $contents );
            $entries = $this->_xml->get_readings( $array );

            // Build chart based on view type
            //
            if ( $display_type == 'month' ) {

                $readings = $this->_xml->get_month( $entries );

                // Get the x and y axis values for this chart
                //
                $days = array();
                $x_vals = array();
                foreach ( $readings as $day => $hours ) {
                    $x_vals[] = date( 'D d', mktime( 0, 0, 0, $month, $day, $year ) );
                    if ( is_array( $hours ) ) {
                        foreach ( $hours as $hour => $vals ) {
                            $days[$day] += $vals['kWh'];
                        }
                    }
                }

                $data[ 'chart' ] = array (
                    'title' => "Daily Energy Use (".
                        date( 'F Y', mktime( 0, 0, 0, $month, $day, $year ) ).")",
                    'x_days' => "'".implode( "','", $x_vals )."'",
                    'y_vals' => implode( ",", $days ) );

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

            }

        } else {

            // we have an error with the file, handle accordingly

        }

        $data[ 'title' ] = 'Dashboard';
        $data[ 'class' ] = 'dashboard';
        $data[ 'y' ] = $year;
        $data[ 'm' ] = $month;
        $data[ 'd' ] = $day;

        if ( $ajax ) {
            $this->view->render( 'dashboard/dashboard', $data );
        } else {
            $this->view->rendertemplate( 'header', $data );
            $this->view->render( 'dashboard/dashboard', $data );
            $this->view->rendertemplate( 'footer', $data );
        }
    }

}