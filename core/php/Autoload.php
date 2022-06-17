<?php

/**
 * Description of autoload
 *
 * @author Gilberto Moreira
 */

_debug("&nbsp; &nbsp; Carregando " . __FILE__ . "\n");

spl_autoload_register(function ($class_name) {
    
    $cn = str_replace('\\', '/', $class_name);
     
    foreach ( explode(PS, get_include_path()) as $path ) {
        $fn=$path.'/'.$cn.'.php';
        if ( is_file($fn)) {
            echo ' <br> &nbsp; Autoloading... "' . $fn .'"';
            require_once( $fn ); 
        }
    }
    
    
  
});
