<?php

class Xml_model extends Model {

    public function __construct() {
        parent::__construct();
    }

    // Grab a specified XML file and return it's contents
    //   @string $file (filename of the xml file)
    //   @pointer &$error (reference to $error variable)
    //
    function get_xml_file( 
        $file = '', 
        &$error = false ) 
    {
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
    function convert_to_array ( $xml ) 
    {
        $array = json_decode( json_encode( $xml ) );
        return $array;
    }

    // Get the interval readings from the xml array
    //   @array $array (array returned from get_xml_array)
    //
    function get_readings ( $array ) 
    {
        return $array->entry[3]->content->IntervalBlock->IntervalReading;
    }

}