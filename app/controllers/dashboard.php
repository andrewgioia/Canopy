<?php

class Dashboard extends Controller {

    private $_xml;

    public function __construct(){
        parent::__construct();
        $this->_xml = $this->loadModel( 'xml_model' );
    }

    public function index( $request = null ) {

        // Get the contents of the selected XML file
        //        
        $contents = $this->_xml->get_xml_file( '2014-04.xml', $error = false );

        // Format the daily readings into a multidimensional array;
        // each element is a day with an array of hours
        //
        if ( ! $error ) {

            // Convert XML into something workable and create the  month array
            //
            $array = $this->_xml->convert_to_array( $contents );
            $entries = $this->_xml->get_readings( $array );
            $month = $this->_xml->get_month( $entries );

            // Get the x and y axis values for this chart
            //
            $days = array();
            $x_vals = array();
            foreach ( $month as $day => $hours ) {
                $x_vals[] = date( 'D d', mktime( 0, 0, 0, 04, $day, 2014 ) );
                if ( is_array( $hours ) ) {
                    foreach ( $hours as $hour => $vals ) {
                        $days[$day] += $vals['kWh'];
                    }
                }
            }

            $data[ 'x_days' ] = "'".implode( "','", $x_vals )."'";
            $data[ 'y_vals' ] = implode( ",", $days );

        } else {

            // we have an error with the file, handle accordingly

        }

        $data[ 'title' ] = 'Dashboard';
        $this->view->rendertemplate( 'header', $data );
        $this->view->render( 'dashboard/dashboard', $data );
        $this->view->rendertemplate( 'footer', $data );
    }

}