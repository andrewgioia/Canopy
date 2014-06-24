<?php

class Energy_model extends Model {

    public function __construct() {
        parent::__construct();
    }

    // Get a month's readings from the database
    //
    public function get_readings_for_month (
        $y, 
        $m )
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

    // Get a day's readings from the database
    //
    public function get_readings_for_day (
        $y,
        $m,
        $d )
    {
        $start = date( 'Y-m-d H:i:s', mktime( 0, 0, 0, $m, $d, $y ) );
        $end = date( 'Y-m-d H:i:s', mktime( 23, 59, 59, $m, $d, $y ) );
        $results = $this->_db->select(
           "SELECT *
            FROM energy
            WHERE used >= '".$start."' && used <= '".$end."'" );
        if ( is_array( $results ) ) {
            return $results;
        }
        return false;
    }

    // Insert a reading into the database
    //
    public function insert_entry( 
        $hour, 
        $kwh, 
        $cost ) 
    {
        $data = array(
            'used' => $hour, 
            'kwh' => $kwh,
            'cost' => $cost );
        $insert = $this->_db->insert( 'energy', $data );
        return true;
    }

}