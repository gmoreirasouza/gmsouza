<?php

/**
 * 
 * Framework 3.0
 * 23/05/2022 as 22:00
 * Barreiras/BA
 * Gilberto Moreira de Souza
 * gmsouza@gmail.com
 * 
 * Ambiente para desenvolvimento de aplicações em PHP 
 * Inclui as bibliotecas:
 * 
 * PHP:
 *  AdoDB (Banco de dados);
 *  Smarty (Template HTML)
 * 
 * CSS:
 *  FontAwesome (CSS/Ícones)
 *  Bootstrap (CSS/Interface)
 *  
 * Javascript:
 *  jQuery (script)
 * 
 */
/**
 * Tratamento de erros
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//function exception_error_control($severity, $message, $file, $line)
//{
//    if (!(error_reporting() & $severity)) {
//        // This error code is not included in error_reporting
//        return;
//    }
//    throw new ErrorException($message, 0, $severity, $file, $line);
//}
//exception_error_control("exception_error_control");

if (!session_id()) {

    /**
     * aumenta o tempo do cache para 3 dias ( 60 minutos x 24 horas x 3 dias
     * deve ser feito com a sessao desativada
     */
    session_cache_expire(60 * 24 * 3);

    /**
     * Incia sessao
     */
    session_start();
}



function _debug(string $msg = null, int $debug = 7) {
    global $debug;
    if ($debug == 7) {
        print_r('<br>'.$msg);
    }
}

$debug = 7;

/**
 * Carregando bibliotec de funcoes
 */
require_once 'core/php/biblioteca.php';


/**
 * Carregando aplicacão
 */
require_once './applications/index.php';
