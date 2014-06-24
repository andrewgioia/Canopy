<?php

class Energy_model extends Model {

    public function __construct() {
        parent::__construct();
    }

    // Get a month's readings from the database
    //
    function get_readings_for_month (
        $y = 2014,
        $m = 01 )
    {
        $start = date( 'Y-m-d', mktime( 0, 0, 0, $m, 1, $y ) );
        $end = date( 'Y-m-t', mktime( 0, 0, 0, $m, 1, $y ) );
        $results = $this->_db->select( 
           "SELECT * 
            FROM energy
            WHERE used >= '".$start." 00:00:00' && used <= '".$end." 23:59:59'" );
        if ( is_array( $results ) ) {
            return $results;
        }
        return false;
    }

    // Insert an array of readings into the database; this array is 
    // returned by get_readings();
    //
    function insert_entry( $hour, $kwh, $cost ) 
    {
        $data = array(
            'used' => $hour, 
            'kwh' => $kwh,
            'cost' => $cost );
        $insert = $this->_db->insert( 'energy', $data );
        return true;
    }

}