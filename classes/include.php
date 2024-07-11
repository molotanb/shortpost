<?php

/**
 * Start classes
 */

if( !function_exists('Class_loader') ) {
    function Class_loader( $name ){
        $FileS = JWR_SP . '/classes/' . $name . '.php';
        if(file_exists($FileS)){
            include_once $FileS;
        }
    }
    spl_autoload_register("Class_loader");
};
