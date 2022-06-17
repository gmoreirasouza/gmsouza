<?php

if (is_file('./core/php/Autoload.php')) {
    require_once './core/php/Autoload.php';
}

$root='/usr/local/public_html/framework3.0';
define('PS', ':');

set_include_path(get_include_path() . PS . $root.'/core' . PS . $root.'/core/php' );


use php\Config;

$Config = new Config();
//$App = new App();

/**
 * URL acessada
 */
$host = $_SERVER['HTTP_HOST'];

/** 
 * Lista de modulos 
 */
$list = scandir(dirname(__FILE__));

/**
 * Comparando o site/sistema acessado com a lista de modulos
 * modulos/www.dominio.com
 */
foreach ($list as $dir) {
    
    $full_dir = Config::path_root . Config::path_modules . $dir;
    if (is_dir($full_dir) and ( $dir != '.' and $dir != '..')) {
        if ($host == $dir) {
            $App->app = $dir . DS;
        }
    }
}

/**
 * Se nao foi encontrado modulos coincidentes com a url digitada,
 * assume o dominio atual como o modulo
 */
if (!isset($App->app) or empty($App->app)) {
    $App->app = "{$host}" . DS;
}

die('Line: ' . __LINE__);
