<?php

class Settings_model extends Model {

    public function __construct() {
        parent::__construct();
    }

    // Get the weekends for a specific month
    //
    function get_weekends_for_month( 
        $year = 2014,
        $month = 01 )
    {
        $days_in_month = date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
        $weekends = array();
        $weekend = 1;

        for ( $i = 1; $i <= $days_in_month; $i++ ) 
        {
            // Get the day of the week
            //
            $weekday = date( 'N', mktime( 0, 0, 0, $month, $i, $year ) );

            // If this day is a Saturday, add it in
            //
            if ( $weekday == 6 ) {
                $weekends[$weekend]['s'] = $i;
            } else if ( $weekday == 7 ) {
                $weekends[$weekend]['u'] = $i;
                $weekend++;
            }
        }

        return $weekends;
    }

    // Get the vacation times for a specific month
    //
    public function get_vacations_for_month(
        $year = 2014,
        $month = 01 )
    {
        $results = $this->_db->select( 
           "SELECT * 
            FROM vacations
            WHERE ( date_start >= '".$year."-".$month."-01' && date_start <= '".$year."-".$month."-31' )
               || ( date_end >= '".$year."-".$month."-01' && date_end <= '".$year."-".$month."-31' ) ");
        if ( is_array( $results ) ) {
            return $results;
        } else {
            return false;
        }
    }

    // Add a new vacation to the database
    //
    public function insert_vacation(
        $start,
        $end,
        $title,
        $empty )
    {
        $data = array(
            'date_start' => $start, 
            'date_end' => $end,
            'title' => $title,
            'house_empty' => $empty );
        $insert = $this->_db->insert( 'vacations', $data );
    }

}