<?php
/**
 * Created by PhpStorm.
 * User: winston.c
 * Date: 19/02/14
 * Time: 11:25 AM
 */

namespace Application\Library;


class Query {

    private static $_instance = null;

    public static function getInstance(){
        if(null === self::$_instance){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function buildWhereQuery( $name, $v ) {
        $whereStr = '';
        $equal_where = array();
        $where = array();

        foreach ( $v as $str ) {
            list( $operator, $value ) = explode( '|', $str );
            if ( !empty( $value ) || $value == '0' ) {
                if ( $operator == '=' ) {
                    $equal_where[] = ' ' . $name . ' ' . $operator . ' "' . $value . '" ';
                } else {
                    $where[] = ' ' . $name . ' ' . $operator . ' "' . $value . '" ';
                }
            }
        }
        if ( !empty( $equal_where ) ) {
            $whereStr .= ' AND (' . implode( 'OR', $equal_where ) . ') ';
        }
        if ( !empty( $where ) ) {
            $whereStr .= ' AND (' . implode( 'AND', $where ) . ') ';
        }
        return $whereStr;
    }

} 