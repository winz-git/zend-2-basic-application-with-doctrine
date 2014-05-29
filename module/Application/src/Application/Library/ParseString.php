<?php
/**
 * Created by PhpStorm.
 * User: winston.c
 * Date: 06/03/14
 * Time: 2:38 PM
 */

namespace Admin\Library;


class ParseString {


    private static $_instance = null;

    private function __construct(){}

    public function getInstance(){
        if(null === self::$_instance){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function UrlEncodedToArray( $url_str ) {
            $result = array();

        if(!empty($url_str)){
            parse_str(html_entity_decode($url_str), $result);
        }

        return $result;
    }

} 