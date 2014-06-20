<?php

class Settings_model extends Model {

    public function __construct() {
        parent::__construct();
    }

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

}