<?php

class Weather_model extends Model {

    private $_api = 'http://api.wunderground.com/api/%s/history_%s/q/PA/Philadelphia.json';

    public function __construct() {
        parent::__construct();
    }

    // Fetch hourly weather data for a day and insert it into the db
    //
    function pull_weather_for_day( 
        $y = 2014,
        $m = 01,
        $d = 01,
        &$error = false )
    {
        // Build the URL to fetch
        //
        $date = date( 'Y', mktime( 0, 0, 0, $m, $d, $y ) )
               .date( 'm', mktime( 0, 0, 0, $m, $d, $y ) )
               .date( 'd', mktime( 0, 0, 0, $m, $d, $y ) );
        $url = sprintf( $this->_api, WU_KEY, $date );

        // Get the url and create a json object
        //
        if ( ! $contents = @file_get_contents( $url ) ) {
            $error = "Error getting the file contents";
            return false;
        }
        $json = json_decode( $contents );

        // Run through the array and build out the hours
        //
        $observations = $json->history->observations;
        $conditions = array();
        if ( is_array( $observations ) && count( $observations ) > 0 ) {
            foreach ( $observations as $key => $data ) {
                $hour = $data->date->hour;
                $conditions[$hour][ 'temp' ] = $data->tempi;
                $conditions[$hour][ 'cond' ] = $data->conds;
            }
        } else {
            $error = "The observations json object was empty";
            return false;
        }

        // Now get the daily summary and make it key 24
        //
        $summary = $json->history->dailysummary;
        if ( is_array( $summary ) && count( $summary ) > 0 ) {
            foreach ( $summary as $data ) {
                $conditions[ 'day' ][ 'mint' ] = $data->mintempi;
                $conditions[ 'day' ][ 'maxt' ] = $data->maxtempi;
                $conditions[ 'day' ][ 'noon' ] = $conditions[12][ 'cond' ];
                $conditions[ 'day' ][ 'rain' ] = $data->rain;
                $conditions[ 'day' ][ 'fog' ] = $data->fog;
                $conditions[ 'day' ][ 'snow' ] = $data->snow;
                $conditions[ 'day' ][ 'hail' ] = $data->hail;
                $conditions[ 'day' ][ 'thunder' ] = $data->thunder;
                $conditions[ 'day' ][ 'tornado' ] = $data->tornado;
            }
        } else {
            $error = "The daily summary json object was empty";
            return false;
        }

        return $conditions;
    }

    // Get daily weather data for a month
    //
    function get_daily_weather_for_month( 
        $y = 2014, 
        $m = 01 )
    {
        $days = date( 't', mktime( 0, 0, 0, $m, 1, $y ) );
        $data = array();
        for ( $i = 1; $i <= $days; $i++ ) {
            $date = date( 'Y-m-d', mktime( 0, 0, 0, $m, $i, $y ) );
            $results = $this->_db->select( 
               "SELECT * 
                FROM weather_days
                WHERE date_day = '".$date."'" );
            if ( is_object( $results[0] ) ) {
                $data[$i] = $results[0];
            }
        }
        return $data;
    }

    // Get the hourly weather data, plus daily summary, for a day
    //
    function get_weather_for_day( $y, $m, $d )
    {
        //$results = $this->_db->select( 'SELECT * FROM weather' );
        //return $results;
    }

    // Insert an hour row into the DB
    //
    function insert_hour( $date, $hour, $temp, $cond )
    {
        $data = array(
            'date_day' => $date, 
            'date_hour' => $hour,
            'temp' => $temp,
            'weather' => $cond );
        $insert = $this->_db->insert( 'weather_hours', $data );
    }

    // Insert a day row into the DB
    //
    function insert_day( $date, $min, $max, $noon, $r, $f, $s, $h, $t, $o )
    {
        $data = array(
            'date_day' => $date, 
            'temp_low' => $min,
            'temp_high' => $max,
            'noon' => $noon,
            'rain' => $r,
            'fog' => $f,
            'snow' => $s,
            'hail' => $h,
            'thunder' => $t,
            'tornado' => $o );
        $insert = $this->_db->insert( 'weather_days', $data );
    }

}