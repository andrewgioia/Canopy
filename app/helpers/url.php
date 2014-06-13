<?php

class Url {

    public static function redirect( $url = null, $fullpath = false ) {

        if ( $fullpath == false ){
            header( 'location: '.DIR.$url );
            exit;
        } else {
            header( 'location: '.$url );
            exit;
        }

    }

    // Get the template path for the active template
    //
    public static function get_template_path() {
        return DIR.'app/templates/'.Session::get('template').'/';
    }

    // Get the public asset path
    //
    public static function get_asset_path( $asset = '' ) {
        return DIR.'public/'.$asset;
    }

}