<?php

class Xml_model extends Model {

    public function __construct() {
        parent::__construct();
    }

    //
    // FILE HANDLING FUNCTIONS
    //

    // Grab a specified XML file and return it's contents
    //   @string $file (filename of the xml file)
    //   @pointer &$error (reference to $error variable)
    //
    function get_xml_file( $file = '', &$error = false ) {

        // Verify that a filename was passed in
        //
        if ( ! $file || $file == '' ) {
            $error = "No file was specified";
            return false;
        } 

        // Load in the file if it exists
        //
        $path = DIR."data/".$file;
        $path_headers = @get_headers( $path );

        if ( $path_headers[0] == 'HTTP/1.1 404 Not Found' ) {
            $error = "Failed to open file '".$path."'";
            return false;
        } else {
            $xml = simplexml_load_file( $path );
        }

        // Return the XML
        //
        return $xml;

    }

    // Convert an xml file's contents to a PHP array
    //   @object $xml (object returned by simplexml_load_file
    //
    function convert_to_array ( $xml ) {
        $array = json_decode( json_encode( $xml ) );
        return $array;
    }

    // Get the interval readings from the xml array
    //   @array $array (array returned from get_xml_array)
    //
    function get_readings ( $array ) {
        return $array->entry[3]->content->IntervalBlock->IntervalReading;
    }

    //
    // DATA FUNCTIONS
    //

    // Create a month array filled with days, each of which holds the 
    // wattage and cost by hour
    //   @array $array (readings array from get_readings)
    //
    function get_month( $array ) {

        // Set an array to dump our days into
        //
        $month = array();

        // Loop through the readings and set an array of days.
        // Each of day stores an array of hours, which store an array 
        //   of kWhs and the cost
        //
        foreach ( $array as $num => $entry ) {

            $month[ date( 'd', $entry->timePeriod->start ) ]
                  [ date( 'H', $entry->timePeriod->start ) ] = array(
                    'kWh' => number_format( $entry->value/1000, 0 ),
                    'cost' => number_format( $entry->cost/100000, 4 )
                );

            //echo "$".number_format( $entry->cost/100000, 4 )." | ". 
            //     date( 'm/d h:ia', $entry->timePeriod->start )." | ". 
            //     number_format( $entry->value/1000, 0 )."\n";

        }

        // Return the month array
        //
        return $month;

    }

}