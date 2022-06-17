<?php

//_debug("Carregando " . __FILE__ . "\n");


function _is_module() {
    global $app;

    if (is_dir($app->path_root . $app->path_modules . $app->modulo)) {
        return true;
    } else {
        return false;
    }
}

function _is_application() {
    global $app;

    if (is_file($app->path_root . $app->path_modules . $app->modulo . DS . 'controller' . DS . $app->aplicacao . '_controller.php')) {
        return true;
    } else {
        return false;
    }
}

function _is_lib_function() {

    global $app;

    if (isset($app->parametros[count($app->parametros) - 1])) {
        $flib = $app->parametros[count($app->parametros) - 1];
    } else {
        $flib = null;
    }

    $libf = $app->path_root . $app->modulo . DS . $app->aplicacao . DS . $app->funcao . DS . $flib;

    if (is_file($libf)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Identifica se esta no modo de desenvolvimento
 */
function _development() {

    $parameters = _getParameters();

    if (isset($parameters['mode']) && isset($parameters['key'])) {
        if ($parameters['mode'] == 'Development' and $parameters['key'] == '5493612418180118') {
            _define('DEVELOPMENT', TRUE);
        } else {
            _define('DEVELOPMENT', FALSE);
        }
    } else {
        _define('DEVELOPMENT', FALSE);
    }

    return DEVELOPMENT;
}

/**
 * 
 * @param string $pathname
 * @param int $mode
 * @param bool $recursive
 * @param type $context
 * @return bol
 */
function _mkdir(string $pathname, int $mode = 0777, bool $recursive = false, $context = null) {

    if (!is_dir($pathname)) {

        if ($context) {
            $r = @mkdir($pathname, $mode, $recursive, $context);
        } else {
            $r = @mkdir($pathname, $mode, $recursive);
        }

        if (!is_dir($pathname)) {
            $err = error_get_last();
            echo '<pre>';
            throw new Exception("ERRO ao criar a pasta '$pathname'. \n\n{$err['message']} '{$pathname}'");
            //            trigger_error("Nao foi possivel criar a pasta {$pathname}. Falha de permissao.", E_USER_ERROR);
        }
    }

    return is_dir($pathname) ? $pathname : false;
}

/**
 * Remove diretorio 
 * recursivamente
 */
function _rmdir($src) {

    if (is_dir($src)) {

        $dir = opendir($src);

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    _rmdir($full);
                } else {
                    unlink($full);
                }
            }
        }

        closedir($dir);

        rmdir($src);
    }
}

/**
 * Identifica a extensao do arquivo informado
 * @param string $file_name
 * @return bol
 */
function _fileGetExtension(string $file_name = null) {
    return _file_get_extension($file_name);
}

function _file_get_extension(string $file_name = null) {

    if (gettype($file_name) == 'string') {

        $fname = _file_get_name($file_name);

        // separa o nome do arquivo em arrays 
        $partsFileName = explode('.', $fname);

        if (count($partsFileName) == 1) {
            return NULL;
        }

        // pega a ultima parte do array ( extensao )
        $extension = $partsFileName[count($partsFileName) - 1];

        // retorna o valor
        return $extension;
    }
}

/**
 * Pega o nome do arquivo
 * @param string $file_name
 * @return type
 */
function _file_get_name(string $file_name = null) {

    if (gettype($file_name)) {

        // verificando se o parametro passado é um diretorio. Se for, retorna nulo. 
        if (is_dir($file_name)) {
            $f = NULL;
        } else {
            // pegando o diretorio
            $d = dirname($file_name) . DS;

            // removendo o direotiro da string passada
            $f = str_replace($d, '', $file_name);
        }

        return $f;
    }
}

/**
 * Salva arquivo
 * 
 * @param string $filename
 * @param type $data
 * @param int $flags
 * @param type $context
 * @return int
 */
function _file_put_contents(string $filename, $data, int $flags = 0, $context = null): int {

    //    if (gettype($data)=='array' ) {
    //        $data = implode("\n", $data);
    //    }
    //_print_r([$filename,$encode]);
    // cria pasta destino se nao existir
    if ($flags == CREATE_IF_NOT_EXIST) {
        $d = dirname($filename);
        _mkdir($d, 0777, true);
    }

    /**
     * Se o arquivo existir e for para "criar se nao existir"
     *  - nao faz nada
     */
    if (is_file($filename) and CREATE_IF_NOT_EXIST === $flags) {
        return false;
    }

    /**
     * Se o arquivo existir e for para "criar se nao existir"
     *  - Cria
     */
    if (!is_file($filename) and (FILE_APPEND === $flags or CREATE_IF_NOT_EXIST === $flags)) {
        $r = @file_put_contents($filename, $data);
        if (!is_file($filename)) {
            die("Arquivo $filename nao foi criado!");
        }
        return $r;
    } else {
        $flags = 0;
    }

    $r = @file_put_contents($filename, $data, $flags, $context);
    if (!is_file($filename)) {
        die("Arquivo $filename nao foi criado!!");
    } else {
        _print_r("arquivo criado com sucesso [$filename]");
    }

    return $r;
}

/**
 * Lê dados de um arquivo 
 */
function _file_get_contents(string $filename, bool $use_include_path = FALSE, $context = NULL, int $offset = 0, int $maxlen = NULL) {

    if (is_file($filename)) {
        $r = file_get_contents($filename, $use_include_path, $context, $offset, $maxlen);
    } else {
        $r = ['File not found', $filename];
    }

    return $r;
}

/**
 * Destroi uma ou mais variaveis/arrays de memoria 
 * 
 * @param type $vars
 * @return type
 */
function _unset($vars = false) {

    /**
     * Se nao foi passado parametro, sai sem fazer nada
     */
    if (!$vars) {
        return;
    }

    /**
     * Se o parametro for do tipo string, transforma em array
     */
    if (gettype($vars) == 'string') {
        $vars = [$vars];
    }

    /**
     * Se o parametro for do tipo array,
     * percorre o mesmo e destroi  cada um dos elementos
     */
    if (gettype($vars) == 'array') {
        foreach ($vars as $var) {
            unset($var);
        }
    }
}

function _rmFile($f) {
    return _unlink($f);
}

function _unlink($f) {

    if (is_file($f)) {
        unlink($f);
        if (is_file($f)) {
            return true;
        }
    }

    return false;
}

function _print_r($var = false) {

    if ($var) {
        if (isset($_SERVER['HTTP_HOST'])) {
            if (gettype($var) == 'object' or gettype($var) == 'array') {
                echo "<pre>";
                print_r($var);
                echo "</pre>";
            } else {
                echo "$var\n";
            }
        } else {
            if (gettype($var) == 'object' or gettype($var) == 'array') {
                echo "\n";
                print_r($var);
            } else {
                echo $var;
            }
        }
    }
}

function _debug_l() {

    //    if (isset($_SESSION['usuario'])) {
    //        if ($_SESSION['usuario']['login'] == 'gmsouza' or $_SESSION['usuario']['login'] == 'gmsouza2') {
    //            return true;
    //        }
    //    }




    if (isset($_SERVER['REMOTE_ADDR'])) {

        if ($_SERVER['REMOTE_ADDR'] == '177.66.211.62' or $_SERVER['REMOTE_ADDR'] = '10.14.75.145') {
            return true;
        }
    } else {
        return true;
    }

    return false;
}

function _print_l($var = false) {
    if (_debug_l()) {
        _print_r($var);
    }
}

function _die($message = 'NoMessage') {
    $line = __LINE__ - 1;

    if (_debug_l()) {

        if ($message != 'NoMessage') {
            //            _print_r('Start message:');
            _print_r($message);
            //            _print_r('End message:');
        }

        //https://www.ime.usp.br/~glauber/html/acentos.htm
        die('<br><i>died in "biblioteca.php(' . $line . ') » _die()" </i>');
    }
}

/**
 * registra log em disco
 */
function _log($var = false, $file = false) {

    global $app, $routes;

    $forMe = false;

    // IP remoto(cliente)
    $RIP = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'NO SET';

    if ($file == 'self') {
        $file = false;
        $forMe = true;
    }

    if ($file) {

        $HOST = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'NO_SET';

        $f = gettype($file) == 'boolean' ? "messages-{$HOST}.log" : $file . "-{$HOST}.log";
        $d = $app->path_nucleo . 'log' . DS;

        if (!is_dir($d)) {
            mkdir($d, 0777);
            if (!is_dir($d)) {
                _log("Erro ao criar a pasta {$d} em " . dirname(__FILE__), true);
            }
        }


        $log_file = $d . $f;

        // data corrente
        $DATA = date("Y-m-d H:i:s");

        // sessao PHP 
        $SESSION = _session_id() ? _session_id() : 'NO SESSION';

        // IP local(servidor)
        $SIP = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'NO SET';

        // protocolo
        $protocol = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';

        // URL com protocolo
        $URL = $protocol . '://' . $HOST;

        // usuario autneticado
        $user_login = isset($_SESSION['usuario']) ? $_SESSION['usuario']['login'] : 'Nao autenticado';
        $user_name = isset($_SESSION['usuario']['nomeCompleto']) ? $_SESSION['usuario']['nomeCompleto'] : '';

        $USER = $user_login . ' - ' . $user_name;

        // informacoes basicas
        //_print_r($log_file);
        //_print_r( "\n\n$DATA $RIP $URL {$routes->current} $SESSION $USER" );


        file_put_contents($log_file, "\n\n$DATA $RIP $URL {$routes->current} $SESSION $USER", FILE_APPEND);

        if ($var) {
            //_print_r($log_file);
            file_put_contents($log_file, "\n --> $var", FILE_APPEND);
        }
    } else {

        if ($forMe && _debug_l()) {
            if ($var) {
                echo "<pre>";
                print_r($var);
                echo "</pre>";
            }
        }
    }
}

function _session_id($s = false) {
    if ($s) {
        return session_id($s);
    } else {
        if (session_id()) {
            return session_id();
        } else {
            session_start();
            return session_id();
        }
    }
}

function _session($var = false, $valor = null) {

    _session_control();

    if ($var and!$valor) {

        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        } else {
            return false;
        }
    }

    if (!$var and!$valor) {
        return session_id();
    }

    return _sessionDefine($var, $valor);
}

/*
 * _session aplelido para _sessionDefine
 * 
 */

function _sessionDefine($var = null, $valor = null) {

    // caso nao seja passado parametro, retorna as vari�veis de sess�o
    if ($var == NULL) {
        return $_SESSION;
    }

    /* caseo seja passado somente o nome de uma variavel de sess�o
     * retorna o valor da mesma.
     */
    if (!is_array($var) && $valor === null) {
        if (isset($_SESSION[$var]))
            return $_SESSION[$var];
        else {
            return false;
        }
    }

    if (!is_array($var))
        $var = array($var => $valor);

    foreach ($var as $sess => $value) {
        $_SESSION[$sess] = $value;
    }

    return NULL;
}

/**
 * 
 */
function _session_control() {


    # se a sessao nao existir, inicia.
    if (!session_id()) {
        //      iniciando a sessao
        session_start();
        //      definindo o tempo da sessao
        _session_time_set();
    }

    #   verificando se foi definido o limite para sessao
    if (isset($_SESSION["sessiontime"])) {

        #       ferificando se o tempo expirou
        if (_session_expired()) {
            //          echo 'sessao expirada';
            return false;
        } else {

            #           Redefinindo o tempo da sessao
            $old_time = $_SESSION["sessiontime"] - time();

            _session_time_set();

            #           Se o tempo nao expirou, redefine-o para mais um periodo conforme configuracao/15 minutos
            //          _print_r(['time() + segundos * minutos: ', $_SESSION["sessiontime"], 60, 2, time(), $old_time, $_SESSION["sessiontime"] - time(), $_SESSION["sessiontime"] - ($_SESSION["sessiontime"] - time())]);
            //          _print_r('Sessao renovada para: ' . $_SESSION["sessiontime"]);
            return true;
        }
    }

    return true;
}

/**
 * 
 */
function _session_time_set() {

    $_SESSION['session_log'] = true;

    /**
     * verificando parametros recebido
     */
    $n = func_num_args();
    $parms = func_get_args();

    if ($n === 0) {
        $minutos = 30;
    } else {
        $minutos = $parms[0];
    }

    $segundos = 60;
    $new_time = time() + ($segundos * $minutos);

    if (!isset($_SESSION['session_time_default'])) {
        //echo "\nNao havia tempo de sessao definido";
        $_SESSION['session_time_default'] = $new_time;
        //echo "\nTempo de sessao definido para: " . $new_time;
    } else {
        //echo "\nHavia tempo de sessao definido [{$_SESSION["session_time_default"]}]";
        $_SESSION["sessiontime"] = $new_time;
        $_SESSION['session_time_default'] = $new_time;
        //echo "\nSession redefinida para: " . $new_time;
    }
}

/**
 * Verifica se a sessao esta expidada.
 * 
 * @param none 
 * @return boolean True ou False, dependendo da sessao estar expirada(true), ou não(false)
 */
function _session_expired() {

    if ($_SESSION["sessiontime"] < time()) {
        return true;
    } else {
        return false;
    }
}

/**
 * Indica o tempo restante da sessao
 * @return int Tempo restante da sessao
 */
function _session_time_left() {
    $time_left = 0;
    return $time_left;
}

/**
 * Indica o tempo decorrido
 * @return int Tempo decorrido apos a criação da sessão
 */
function _session_time_elapsed() {
    $time_elapsed = 0;
    return $time_elapsed;
}

/**
 * Pega o tempo padrao definido inicialmente para a sessao
 * @return int Tempo incialmente definido para a sessao
 */
function _session_time_get() {

    if (isset($_SESSION["sessiontime"])) {
        return $_SESSION["sessiontime"];
    } else {
        return 0;
    }
}

/**
 * Reinicia o tepo de sessao ao tempo definido inicialmente.
 * 
 * @return boolean  Verdadeiro/true,  se houver indicação de definição inicial de tempo de sessao.
 *                  Falso, se nao foi definido tempo de sessao.
 */
function _session_time_reset() {

    if (isset($_SESSION['session_time_default'])) {
        $_SESSION['sessiontime'] = $_SESSION['session_time_default'];
        //echo "\nsessao resetada para: " . $_SESSION['session_time_default'];
        return true;
    } else {
        //echo "\nSessao não resetada..." . $_SESSION['session_time_default'];
        return false;
    }
}

/**
 * Limpa e destroi a sessão corrente
 */
function _session_drop($vsession = false) {

    /**
     * Quando eh passado um parametro, ele é utilizado como se fosse um elemento/variavel
     * da sessao e eh destruido.
     * 
     * Subentende-se que a intencao e remover este elemento/variavel
     */
    if ($vsession) {
        if ($_SESSION[$vsession]) {
            unset($_SESSION[$vsession]);
        }
        return;
    }


    if (func_num_args() == 1) {

        $session_var = func_get_args()[0];

        _log('drop session', $_SESSION[$session_var]);
        unset($session_var);

        /**
         * verificando se a sessao foi mesmo removida e 
         * retornano esta situacao como true ou false
         */
        if (isset($_SESSION[$session_var])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Limpando o conteudo da sessão antes de destrui-la 
     */
    session_unset();

    /**
     * Destruindo a sessao
     */
    session_destroy();
}

/**
 * Envia mensagem padrao na tela
 * @param type $message
 * @param type $type
 */
function _message($message, $type = 0) {

    echo '<pre>';
    print_r($message . "\n");
    echo '</pre>';
}

/**
 * Captura os parametros passados na URL
 * @return type
 */
function _getParameters() {

    $parms = [];

    if (isset($_SESSION['parameters'])) {
        if (gettype($_SESSION['parameters']) == 'array') {
            $parms = $_SESSION['parameters'];
        }
    }





    /**
     * Correcao para o processamento abaixo..
     * quando uma variavel e passada mais de uma vez na URL dessa forma eee.xyz.com?a=1&a=2&a=3
     * o sistema apenas reconhece a=1, pois o indice do elemento é o mesmo e será truncado/unificado
     */
    if (isset($_SERVER['QUERY_STRING'])) {
        $ru = urldecode($_SERVER['QUERY_STRING']);
        $ru = explode('&', $ru);
        //_print_l($ru);
    } else if (isset($_SERVER['REQUEST_URI'])) {
        $ru = explode('?', urldecode($_SERVER['REQUEST_URI']));
        if (isset($ru[1])) {
            $ru = explode('&', $ru[1]);
        }
    }

    if (isset($_SERVER['REQUEST_URI'])) {
        $ru = explode('?', urldecode($_SERVER['REQUEST_URI']));
        if (isset($ru[1])) {
            $ru = explode('&', $ru[1]);
        }
    }

    if (isset($ru)) {

        foreach ($ru as $i) {
            $k = substr($i, 0, strpos($i, '='));
            $v = substr($i, strpos($i, '=') + 1);
            if ($k) {

                // solucao para indice duplicado
                if (isset($parms[$k])) {
                    $parms[$k] = $parms[$k] . ',' . $v;
                } else {
                    $parms[$k] = $v;
                }
            }
        }
    }

    $parameters = array_merge($_GET, $_POST, $_REQUEST, $parms);

    return $parameters;
}

/**
 * @return type
 * JSON não aceita acentuação, para tanto foi criada esta função
 * para contornar a situação.
 */
function _json_encode($value, int $options = 0, int $depth = 512): string {

    if ($options == 0) {
        $options = JSON_UNESCAPED_UNICODE;
    }

    return json_encode($value, $options, $depth) . ';';
}

/**
 * Cria um arquivo em branco se o mesmo nao existir
 * 
 * @param string $file
 * @return type
 */
function _touch(string $file = null, string $data = '') {
    if (!is_file($file)) {
        _file_put_contents($file, $data, CREATE_IF_NOT_EXIST);
    }
    return is_file($file) ? TRUE : FALSE;
}

/**
 * Lista de conteudo de uma pasta
 * @param type $diretorio
 * @param type $recursivo
 * @return type
 */
function _diretory_list($diretorio, $recursivo = false) {

    if (!is_dir($diretorio)) {
        return false;
    }
    $d = scandir($diretorio);

    return $d;
}

function _strpos($searched, $contents, $offset = 0) {

    if (is_array($contents)) {
        $found = FALSE;
        foreach ($contents as $content) {
            if (strpos($searched, $content, $offset))
                $found = TRUE;
        }
        return $found;
    } else {
        return strpos($searched, $contents, $offset);
    }
}

function _stristr($string = false, $search = false) {

    if ($search === false or $string === false) {
        return false;
    }

    // convertendo string de busca para array
    if (gettype($search) != 'array') {
        $search = [$search];
    }

    $found = false;
    foreach ($search as $find) {
        if (stristr($string, $find)) {
            $found = true;
        }
    }

    return $found;
}

function _hora($formato = NULL) {
    return _horaAtual($formato);
}

function _horaAtual($formato = NULL) {
    $tm = NULL;
    switch ($formato) {
        case NULL:
        case 'hms':
            $tm = date("H:i:s");
            break;
    }
    return $tm;
}

/**
 * retorna a data corrente
 */
function _date($formato = NULL) {

    global $idiomas;

    $dt = NULL;
    switch (strtoupper($formato)) {
        case 'AMD':
        case 'A-M-D':
            $dt = date("Y-m-d");
            break;
        case 'DMA':
        case 'D-M-A':
        case 'DD/MM/AAAA':
            $dt = date("d/m/Y");
            break;
        case 'DMAHMS':
            $dt = date("d/m/Y H:i:s");
            break;
        case 'AMDHMS':
            $dt = date("Y-m-d H:i:s");
            break;
        case 'EXTENSO':
            $dt = date("d") . ' ' . $idiomas['de'] . ' ' . _dataMesCorrenteExtenso(date('m')) . ' ' . date('Y');
            break;
        case NULL:
            $dt = date("d/m/Y");
            break;
    }

    return $dt;
}

/**
 * retorna a data e hora corrente 
 * @return string 
 */
function _datetime($format = 'DMA') {

    $format = strtoupper($format);

    //$d = date('d/m/Y H:i:s A');

    if ($format == 'DMA' or $format == 'D/M/A' or $format == 'DD/MM/AAAA') {
        $d = date('d/m/Y H:i:s');
    } else if ($format == 'D-M-A' or $format == 'DD-MM-AAAA') {
        $d = date('d-m-Y H:i:s');
    } else if ($format == 'A/M/D' or $format == 'AAAA/MM/DD') {
        $d = date('Y/m/d H:i:s');
    } else if ($format == 'AMD' or $format == 'A-M-D' or $format == 'AAAA-MM-DD') {
        $d = date('Y-m-d H:i:s');
    } else if ($format == 'MDA' or $format == 'M-D-A' or $format == 'MM-DD-AAAA') {
        $d = date('m-d-Y H:i:s');
    } else if ($format == 'M/D/A' or $format == 'MM/DD/AAAA') {
        $d = date('m/d/Y H:i:s');
    } else {
        $d = date('d/m/Y H:i:s');
    }

    return $d;
}

/**
 * retorna a hora corrente
 * @return string
 */
function _time() {
    return date('H:i:s');
}

/**
 * Converte data
 * @return string
 */
function _dataConverte() {

    $argn = func_num_args();
    $args = func_get_args();

    $ano = '';
    $mes = '';
    $dia = '';

    if ($argn == 0) {
        return "";
    } else if ($argn == 1) {
        $data = $args[0];
        $de = "AAAA-MM-DD";
        $para = "DMA";
    } else if ($argn == 2) {
        $de = "AAAA-MM-DD";
        $para = $args[1];
        $data = $args[0];
    } else if ($argn == 3) {
        $data = $args[0];
        $de = $args[1];
        $para = $args[2];
    }

    if (empty($data)) {
        return '';
    }


    $complemento = explode(' ', $data);
    $data = $complemento[0];

    if (isset($complemento[1])) {
        $complemento = $complemento[1];
    } else {
        $complemento = '';
    }


    $de = strtoupper($de);
    $para = strtoupper($para);

    /**
     * Definindo o separador de data
     */
    if (strpos($data, '-')) {
        $s = '-';
        if (!$de) {
            $de = "AMD";
        }
    }
    if (strpos($data, '/')) {
        $s = '/';
        if (!$de) {
            $de = DMA;
        }
    }

    if ($args[1] == '-') {
        $s = $args[1];
    }

    if ($de == "AMD" or $de == "AAAA-MM-DD") {
        //        if ( !isset($s)) {
        //            _print_r([$data,$de]);
        //        }
        $dt = explode($s, $data);
        if (isset($dt[0]) && isset($dt[1]) && isset($dt[2])) {
            $dia = $dt[2];
            $mes = $dt[1];
            $ano = $dt[0];
        }
    }

    if ($de == "DMA" or $de == "DD-MM-AAAA") {

        $dt = explode($s, $data);
        if (isset($dt[0]) && isset($dt[1]) && isset($dt[2])) {
            $ano = $dt[2];
            $mes = $dt[1];
            $dia = $dt[0];
        }
    }

    if ($de == "MDA" or $de == "MM-DD-AAAA") {

        list($mes, $dia, $ano) = explode($s, $data);
        //        if (isset($dt[0]) && isset($dt[1]) && isset($dt[2])) {
        //            $ano = $dt[2];
        //            $mes = $dt[1];
        //            $dia = $dt[0];
        //        }
    }


    if ($para == 'AMD' or $para == 'A-M-D') {
        $r = $ano . "-" . $mes . "-" . $dia;
    }
    if ($para == 'MDA' or $para == 'M-D-A') {
        $r = $mes . "-" . $dia . "-" . $ano;
    }
    if ($para == 'DMA') {
        $r = $dia . "/" . $mes . "/" . $ano;
    }
    if ($para == 'D-M-A') {
        $r = $dia . "-" . $mes . "-" . $ano;
    }

    return $r . ' ' . $complemento;
}

/**
 * Faz logoff do usuario ativo
 */
function logoff() {

    global $html;

    /**
     * Se houver sessao ativa
     *  - remove
     */
    if (_session('usuario')) {
        $html->message("Usuário desconectado do sistema. \n - {$_SESSION['usuario']['nomeCompleto']}");
        unset($_SESSION['usuario']);
        return true;
    }

    /**
     * Nao havia sessao ativa
     */
    return false;
}

/**
 * Criptografia de senha
 * @param type $senha
 * @return type
 */
function _criptografaSenha(string $senha = NULL) {

    if ($senha) {
        // remove os espacos ao redor da senha para criptografar.
        $senha = trim($senha);
        $senha = base64_encode(pack("H*", sha1(trim($senha))));
        //        _print_r( $senha );
        return $senha;
    } else {
        _print_r('Deve ser informada uma senha para criptografia');
    }
}

/**
 * 
 * @global type $html
 * @global type $model
 * @param type $options
 * @return type
 * 
 * [tabela] => cadastros
 * [campos] => cnpj^tipoPessoa^nomeRazaoSocial^nomeFantasia^inscricaoEstadual^inscricaoMunicipal^situacao^observacao
 * [filter] => cnpj^01.628.556/0001-90    
 * [relacionados]=> telefones:telefone,id^ enderecos:tipo,id,cep,logradouro,numero,complemento,cidade,estado^ emails:email,id
 */
function search($options) {

    global $html, $model, $db;
    //    $model = new model;
    //    $html = new html;
    //    $db = new db;

    $tabela = $options['tabela'];
    $campos = str_replace('^', ',', $options['campos']);

    if ($options['relacionados']) {
        if (stristr($options['relacionados'], 'select ')) {
            $q = $options['relacionados'];
        }
        $relacionados = explode('^', $options['relacionados']);
    } else {
        $relacionados = false;
    }

    $s = explode('^', $options['filter']);
    $filterField = $s[0];
    $filterValue = $s[1];

    /**
     * Removendo o campo de filtro da lista de campos da consulta
     */
    $campos = str_replace(',' . $filterField, '', $campos);
    $campos = str_replace($filterField . ',', '', $campos);

    $search = [$tabela => $campos];

    if ($relacionados) {
        foreach ($relacionados as $related) {
            $x = explode(':', $related);
            $tab = trim($x[0]);
            if (isset($x[1])) {
                $cps = trim($x[1]);
            } else {
                $cps = '*';
            }
            $search = array_merge($search, [$tab => $cps]);
        }
    }

    /**
     * LIMPEZA DO FORMULARIO
     *  - montando objeto para limpeza do formulario antes da sua atualizacao
     */
    $clearForm = _json_encode($search);

    if ($relacionados) {
        $filter = [$tabela => [$filterField => $filterValue]];
    } else {
        $filter = [$tabela => [$filterField => $filterValue]];
    }

    if (isset($options['qs'])) {
        $qs = $options['qs'];
    } else {
        $qs = false;
    }


    echo '';

    if ($qs) {
        $q = sprintf($qs, $filterValue);
        $arr = $db->_query($q);
    } else {
        $arr = $model->Find($search, $filter);
    }

    if ($arr) {
        $html->formUpdate = [['clearFormUpdate' => $clearForm], $arr];
    } else {
        // Somente limpando o formulario
        $html->formUpdate = [['clearFormUpdate' => $clearForm]];
    }

    //    echo _json_encode($html->formUpdate);

    return;
}

/**
 * Gera senha aleatoria com um cumprimento padrao de 9 caracteres
 */
function _password_generate($size = 9) {
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($data), 0, $size);
}

/**
 * Gera um hash
 */
function _hash_generate($string = false, $algoritimo = 'sha512') {


    //mudar para 
    //https://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string

    if (!$string) {
        $string = date("YmdHis");
    }

    /**
     * Algoritimos de hash possiveis
     * 
      md2
      md4
      md5
      sha1
      sha256
      sha384
      sha512
      ripemd128
      ripemd160
      ripemd256
      ripemd320
      whirlpool
      tiger128,3
      tiger160,3
      tiger192,3
      tiger128,4
      tiger160,4
      tiger192,4
      snefru
      gost
      adler32
      crc32
      crc32b
      haval128,3
      haval160,3
      haval192,3
      haval224,3
      haval256,3
      haval128,4
      haval160,4
      haval192,4
      haval224,4
      haval256,4
      haval128,5
      haval160,5
      haval192,5
      haval224,5
      haval256,5

      foreach (hash_algos() as $v) {
      echo $v;
      }

     */
    $r = hash($algoritimo, $string, false);

    //printf("%-12s %3d %s\n", $algoritimo, strlen($r), $r);

    return $r;
}

/**
 * retorna o protocolo WEB utilizado
 */
//function _protocolo_web() {
//
//    $p = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'HTTP';
//    return $p . '://';
//}

function _is_email($email) {

    $conta = "/[a-zA-Z0-9\._-]+@";
    $domino = "[a-zA-Z0-9\._-]+.";
    $extensao = "([a-zA-Z]{2,4})$/";
    $pattern = $conta . $domino . $extensao;

    //verifica se e-mail esta no formato correto de escrita
    if (!preg_match($pattern, $email)) {
        return false;
    } else {
        //Valida o dominio
        $dominio = explode('@', $email);
        if (!checkdnsrr($dominio[1], 'A')) {
            return false;
        }
    }

    return true;
}

/**
 * Validando cnpj
 */
function _validaCPF($cpf) {
    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            //$d += $cpf{$c} * (($t + 1) - $c);
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

/**
 * Define ou retorna o valor de uma CONSTANTE
 */
function _define() {

    $n = func_num_args();
    if ($n === 0) {
        return false;
    } else {

        $p = func_get_args();
        $constante = isset($p[0]) ? $p[0] : '';
        $value = isset($p[1]) ? $p[1] : '';

        /**
         * retornando o valor da constante
         */
        if ($n === 1) {
            if (defined($constante)) {
                return $constante;
            }
        }

        /**
         * Se nao existr, define a cosntante
         */
        if ($n === 2) {
            if (!defined($constante)) {
                define($constante, $value);
            } else {
                //_log("Constante {$constante} com valor '$value' já existe!", true);
            }
        }
    }
}

function _protocolo_web() {
    if (isset($_SERVER['HTTPS'])) {

        if ($_SERVER['HTTPS'] === 'on') {
            return 'https';
        } else {
            return 'http';
        }
    } else {
        return 'http';
    }
}

/**
 * Substitui todas as ocorrencias
 */
function _replace($search, $replace, $subject) {
    while (strpos($subject, $search) != false) {
        $subject = str_replace($search, $replace, $subject);
    }
    return $subject;
}

/**
 * identifica se um arquivo já foi incluido por 
 * include* ou require*
 */
function _file_included($file) {

    if ($file) {

        $includeds = get_included_files();
        foreach ($includeds as $included) {
            if (strstr($included, $file)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Conta quantas vezes uma string se repende dentro de outra
 */
function _count($find, $string = false) {

    if ($string) {
        $count = substr_count($string, $find);
        return $count;
    }

    if (getttpe($find) == 'array') {
        return count($find);
    }
}

function _remove_ascentos($string) {

    // assume $str esteja em UTF-8
    $map = array(
        'á' => 'a',
        'à' => 'a',
        'ã' => 'a',
        'â' => 'a',
        'é' => 'e',
        'ê' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ú' => 'u',
        'ü' => 'u',
        'ç' => 'c',
        'Á' => 'A',
        'À' => 'A',
        'Ã' => 'A',
        'Â' => 'A',
        'É' => 'E',
        'Ê' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ú' => 'U',
        'Ü' => 'U',
        'Ç' => 'C',
        'º' => 'º'
    );

    return strtr($string, $map); // funciona corretamente
}

/**
 * Converte uma string em um valor numerico
 */
function _val($string) {

    /**
     * Identificar o separados de casas decimais
     */
    $string = str_replace(',', '', $string);

    if (empty($string)) {
        return 0.00;
    }


    /**
     * Deve ser verificados os valores inteiros e com casas decimais.
     */
    return $string;
}

function _getIP() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

/**
 * Pega lista de perfis do uusario autenticado
 * @return type
 */
function get_perfis() {

    global $html;

    $perfis = [];

    if (isset($_SESSION['usuario'])) {
        if (isset($_SESSION['usuario']['perfis'])) {
            foreach ($_SESSION['usuario']['perfis'] as $perfil) {
                $perfis[$perfil['descricao']] = $perfil;
            }
        }
    }

    $html->vars['perfis'] = $perfis;

    return $perfis;
}

//Remove acentuacao
function remove_accents($string) {
    if (!preg_match('/[\x80-\xff]/', $string))
        return $string;

    if (seems_utf8($string)) {
        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(195) . chr(128) => 'A', chr(195) . chr(129) => 'A',
            chr(195) . chr(130) => 'A', chr(195) . chr(131) => 'A',
            chr(195) . chr(132) => 'A', chr(195) . chr(133) => 'A',
            chr(195) . chr(134) => 'AE', chr(195) . chr(135) => 'C',
            chr(195) . chr(136) => 'E', chr(195) . chr(137) => 'E',
            chr(195) . chr(138) => 'E', chr(195) . chr(139) => 'E',
            chr(195) . chr(140) => 'I', chr(195) . chr(141) => 'I',
            chr(195) . chr(142) => 'I', chr(195) . chr(143) => 'I',
            chr(195) . chr(144) => 'D', chr(195) . chr(145) => 'N',
            chr(195) . chr(146) => 'O', chr(195) . chr(147) => 'O',
            chr(195) . chr(148) => 'O', chr(195) . chr(149) => 'O',
            chr(195) . chr(150) => 'O', chr(195) . chr(153) => 'U',
            chr(195) . chr(154) => 'U', chr(195) . chr(155) => 'U',
            chr(195) . chr(156) => 'U', chr(195) . chr(157) => 'Y',
            chr(195) . chr(158) => 'TH', chr(195) . chr(159) => 's',
            chr(195) . chr(160) => 'a', chr(195) . chr(161) => 'a',
            chr(195) . chr(162) => 'a', chr(195) . chr(163) => 'a',
            chr(195) . chr(164) => 'a', chr(195) . chr(165) => 'a',
            chr(195) . chr(166) => 'ae', chr(195) . chr(167) => 'c',
            chr(195) . chr(168) => 'e', chr(195) . chr(169) => 'e',
            chr(195) . chr(170) => 'e', chr(195) . chr(171) => 'e',
            chr(195) . chr(172) => 'i', chr(195) . chr(173) => 'i',
            chr(195) . chr(174) => 'i', chr(195) . chr(175) => 'i',
            chr(195) . chr(176) => 'd', chr(195) . chr(177) => 'n',
            chr(195) . chr(178) => 'o', chr(195) . chr(179) => 'o',
            chr(195) . chr(180) => 'o', chr(195) . chr(181) => 'o',
            chr(195) . chr(182) => 'o', chr(195) . chr(182) => 'o',
            chr(195) . chr(185) => 'u', chr(195) . chr(186) => 'u',
            chr(195) . chr(187) => 'u', chr(195) . chr(188) => 'u',
            chr(195) . chr(189) => 'y', chr(195) . chr(190) => 'th',
            chr(195) . chr(191) => 'y',
            // Decompositions for Latin Extended-A
            chr(196) . chr(128) => 'A', chr(196) . chr(129) => 'a',
            chr(196) . chr(130) => 'A', chr(196) . chr(131) => 'a',
            chr(196) . chr(132) => 'A', chr(196) . chr(133) => 'a',
            chr(196) . chr(134) => 'C', chr(196) . chr(135) => 'c',
            chr(196) . chr(136) => 'C', chr(196) . chr(137) => 'c',
            chr(196) . chr(138) => 'C', chr(196) . chr(139) => 'c',
            chr(196) . chr(140) => 'C', chr(196) . chr(141) => 'c',
            chr(196) . chr(142) => 'D', chr(196) . chr(143) => 'd',
            chr(196) . chr(144) => 'D', chr(196) . chr(145) => 'd',
            chr(196) . chr(146) => 'E', chr(196) . chr(147) => 'e',
            chr(196) . chr(148) => 'E', chr(196) . chr(149) => 'e',
            chr(196) . chr(150) => 'E', chr(196) . chr(151) => 'e',
            chr(196) . chr(152) => 'E', chr(196) . chr(153) => 'e',
            chr(196) . chr(154) => 'E', chr(196) . chr(155) => 'e',
            chr(196) . chr(156) => 'G', chr(196) . chr(157) => 'g',
            chr(196) . chr(158) => 'G', chr(196) . chr(159) => 'g',
            chr(196) . chr(160) => 'G', chr(196) . chr(161) => 'g',
            chr(196) . chr(162) => 'G', chr(196) . chr(163) => 'g',
            chr(196) . chr(164) => 'H', chr(196) . chr(165) => 'h',
            chr(196) . chr(166) => 'H', chr(196) . chr(167) => 'h',
            chr(196) . chr(168) => 'I', chr(196) . chr(169) => 'i',
            chr(196) . chr(170) => 'I', chr(196) . chr(171) => 'i',
            chr(196) . chr(172) => 'I', chr(196) . chr(173) => 'i',
            chr(196) . chr(174) => 'I', chr(196) . chr(175) => 'i',
            chr(196) . chr(176) => 'I', chr(196) . chr(177) => 'i',
            chr(196) . chr(178) => 'IJ', chr(196) . chr(179) => 'ij',
            chr(196) . chr(180) => 'J', chr(196) . chr(181) => 'j',
            chr(196) . chr(182) => 'K', chr(196) . chr(183) => 'k',
            chr(196) . chr(184) => 'k', chr(196) . chr(185) => 'L',
            chr(196) . chr(186) => 'l', chr(196) . chr(187) => 'L',
            chr(196) . chr(188) => 'l', chr(196) . chr(189) => 'L',
            chr(196) . chr(190) => 'l', chr(196) . chr(191) => 'L',
            chr(197) . chr(128) => 'l', chr(197) . chr(129) => 'L',
            chr(197) . chr(130) => 'l', chr(197) . chr(131) => 'N',
            chr(197) . chr(132) => 'n', chr(197) . chr(133) => 'N',
            chr(197) . chr(134) => 'n', chr(197) . chr(135) => 'N',
            chr(197) . chr(136) => 'n', chr(197) . chr(137) => 'N',
            chr(197) . chr(138) => 'n', chr(197) . chr(139) => 'N',
            chr(197) . chr(140) => 'O', chr(197) . chr(141) => 'o',
            chr(197) . chr(142) => 'O', chr(197) . chr(143) => 'o',
            chr(197) . chr(144) => 'O', chr(197) . chr(145) => 'o',
            chr(197) . chr(146) => 'OE', chr(197) . chr(147) => 'oe',
            chr(197) . chr(148) => 'R', chr(197) . chr(149) => 'r',
            chr(197) . chr(150) => 'R', chr(197) . chr(151) => 'r',
            chr(197) . chr(152) => 'R', chr(197) . chr(153) => 'r',
            chr(197) . chr(154) => 'S', chr(197) . chr(155) => 's',
            chr(197) . chr(156) => 'S', chr(197) . chr(157) => 's',
            chr(197) . chr(158) => 'S', chr(197) . chr(159) => 's',
            chr(197) . chr(160) => 'S', chr(197) . chr(161) => 's',
            chr(197) . chr(162) => 'T', chr(197) . chr(163) => 't',
            chr(197) . chr(164) => 'T', chr(197) . chr(165) => 't',
            chr(197) . chr(166) => 'T', chr(197) . chr(167) => 't',
            chr(197) . chr(168) => 'U', chr(197) . chr(169) => 'u',
            chr(197) . chr(170) => 'U', chr(197) . chr(171) => 'u',
            chr(197) . chr(172) => 'U', chr(197) . chr(173) => 'u',
            chr(197) . chr(174) => 'U', chr(197) . chr(175) => 'u',
            chr(197) . chr(176) => 'U', chr(197) . chr(177) => 'u',
            chr(197) . chr(178) => 'U', chr(197) . chr(179) => 'u',
            chr(197) . chr(180) => 'W', chr(197) . chr(181) => 'w',
            chr(197) . chr(182) => 'Y', chr(197) . chr(183) => 'y',
            chr(197) . chr(184) => 'Y', chr(197) . chr(185) => 'Z',
            chr(197) . chr(186) => 'z', chr(197) . chr(187) => 'Z',
            chr(197) . chr(188) => 'z', chr(197) . chr(189) => 'Z',
            chr(197) . chr(190) => 'z', chr(197) . chr(191) => 's',
            // Decompositions for Latin Extended-B
            chr(200) . chr(152) => 'S', chr(200) . chr(153) => 's',
            chr(200) . chr(154) => 'T', chr(200) . chr(155) => 't',
            // Euro Sign
            chr(226) . chr(130) . chr(172) => 'E',
            // GBP (Pound) Sign
            chr(194) . chr(163) => ''
        );

        $string = strtr($string, $chars);
    } else {
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128) . chr(131) . chr(138) . chr(142) . chr(154) . chr(158)
                . chr(159) . chr(162) . chr(165) . chr(181) . chr(192) . chr(193) . chr(194)
                . chr(195) . chr(196) . chr(197) . chr(199) . chr(200) . chr(201) . chr(202)
                . chr(203) . chr(204) . chr(205) . chr(206) . chr(207) . chr(209) . chr(210)
                . chr(211) . chr(212) . chr(213) . chr(214) . chr(216) . chr(217) . chr(218)
                . chr(219) . chr(220) . chr(221) . chr(224) . chr(225) . chr(226) . chr(227)
                . chr(228) . chr(229) . chr(231) . chr(232) . chr(233) . chr(234) . chr(235)
                . chr(236) . chr(237) . chr(238) . chr(239) . chr(241) . chr(242) . chr(243)
                . chr(244) . chr(245) . chr(246) . chr(248) . chr(249) . chr(250) . chr(251)
                . chr(252) . chr(253) . chr(255);

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
        $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

function seems_utf8($str) {
    $length = strlen($str);
    for ($i = 0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80)
            $n = 0;# 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0)
            $n = 1;# 110bbbbb
        elseif (($c & 0xF0) == 0xE0)
            $n = 2;# 1110bbbb
        elseif (($c & 0xF8) == 0xF0)
            $n = 3;# 11110bbb
        elseif (($c & 0xFC) == 0xF8)
            $n = 4;# 111110bb
        elseif (($c & 0xFE) == 0xFC)
            $n = 5;# 1111110b
        else
            return false;# Does not match any model
        for ($j = 0; $j < $n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}

// remove aspas
function remove_aspas($string) {
    $especiais = ["'", '"'];
    $string = str_replace($especiais, "", trim($string));
    return $string;
}

//Remove pontuação
function remove_especial_char($string) {
    $especiais = array(".", ",", ";", "!", "@", "#", "%", "¨", "*", "(", ")", "+", "-", "=", "§", "$", "|", "\\", ":", "/", "<", ">", "?", "{", "}", "[", "]", "&", "'", '"', "´", "`", "?", '“', '”', '$', "'", "'");
    $string = str_replace($especiais, "", trim($string));
    return $string;
}

/**
 * Registra log de acesso
 */
function logs() {

    global $tables;

    $navegador = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : ''; // navegador do cliente
    $protocolo = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : ''; //protocolo de acesso(http, https, ftp... )
    $site = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''; // site
    $requisicao = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''; // url solicitada ao site
    $url = $protocolo . '://' . $site . $requisicao; // url completa
    $porta = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ''; // porta do serviço web
    $ipv4 = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';  // IP do cliente
    $session = (session_id()) ? session_id() : ''; // sessao do PHP
    $usuarios_id = isset($_SESSION['usuario']) ? $_SESSION['usuario']['id'] : '';  // ID do usuario logado
    $date = _datetime();

    $dados = [
        'logs__navegador' => $navegador,
        'logs__url' => $url,
        'logs__ipv4' => $ipv4,
        'logs__session' => $session,
        'logs__usuarios_id' => $usuarios_id,
        'logs__data' => $date
    ];

    $logs_id = $tables->logs->Save($dados);

    $_SESSION['logs_id'] = $logs_id;
}

function _contain($string, $search) {

    $new = str_replace($search, '', $string);

    if ($new != $string) {
        //echo "\n:::$string contem $search\n";
        return true;
    }

    return false;
}

function javascript_minify($code) {

    // make it into one long line
    $code = str_replace(array("\n", "\r"), '', $code);

    // replace all multiple spaces by one space
    $code = preg_replace('!\s+!', ' ', $code);

    // replace some unneeded spaces, modify as needed
    $code = str_replace(array(' {', ' }', '{ ', '; '), array('{', '}', '{', ';'), $code);

    return $code;
}

/**
 * Calcula o hash 
 * 
 * @param type $hash
 * @param type $data
 * @param type $binary
 * @return type
 */
function _hash($hash = false, $data = '', $binary = false) {


    if ($hash) {
        return hash($hash, $data, $binary);
    } else {
        _print_r('type of hashes');
        _print_r(hash_algos());
    }
}

function _start_whith($fullstring, $part) {

    if ($fullstring) {

        if (strlen($fullstring) < strlen($part)) {
            return false;
        } else {


            if (gettype($fullstring) == 'string') {

                $a = substr($fullstring, 0, strlen($part));
                $b = $part;

                if ($a == $b) {
                    return true;
                }
            }
        }
    }
}

function _end_whith($string, $compare) {

    if ($string) {

        if (strlen($string) < strlen($compare)) {
            return false;
        } else {


            if (gettype($string) == 'string') {


                $ss = strlen($string);
                $sc = strlen($compare);

                $a = substr($string, $sc * -1);
                $b = $compare;

                if ($a == $b) {
                    return true;
                }
            }
        }
    }
}

function _is_dir($directory = false) {

    if ($directory === false) {
        return;
    }

    // sistema operacional
    // implementar variaveis de ambiente em _getOS
    $os = _getOS();

    $ds = '/';

    $directories = explode($ds, $directory);
    $last = $directories[count($directories) - 1];

    if ($last != '.' and $last != '..') {
        if (is_dir($directory)) {
            return true;
        }
    }

    return false;
}

function _scandir($scan, $recursive = false) {

    $directory = [];

    if (_is_dir($scan)) {

        $dir = scandir($scan);

        foreach ($dir as $d) {
            if (_is_dir($scan . DS . $d)) {
                $directory[] = $d;
            }
        }
    }

    return $directory;
}

function _files($scan, $recursive = false) {
    return _scanfiles($scan, $recursive);
}

function _scanfiles($scan, $recursive = false) {

    $file = [];

    if (_is_dir($scan)) {

        $filess = scandir($scan);

        foreach ($filess as $f) {
            if (is_file($scan . DS . $f)) {
                $file[] = $f;
            }
        }
    }

    return $file;
} 

function _getOS() { 

    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    } else {
        $user_agent = '';
    }

    $os_platform = "Unknown OS Platform";

    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}


function isMobile() { 
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function _getNumbers($string) {
    return preg_replace('/[^0-9]/', '', $string);
}

function _array_search($search = '', $content = []) {

    $key = false;
    foreach ($content as $k => $v) {

        if (_contain($v, $search)) {
            $key = $k;
            break;
        }
    }

    return $key;
}

function _getHtmlTags($tag = false) {

    $tags = ['html', 'head', 'body', 'title', 'label', 'div', 'table', 'td', 'tbody', 'tr', 'th', 'input', 'i', 'a', 'b', 'tt', 'font', 'p', 'br', 'img', 'hr'];

    // retorna uma lista das possíveis tags
    if (!$tag) {
        return $tags;
    } else {

        if (in_array($tag, $tags)) {
            return $tag;
        }
    }
}

function _getHtmlElementProperty($property = '', $element = false) {

    if (!$element) {
        return false;
    }

    $re = '/(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|\s*\/?[>"\']))+.)["\']?/m';
    preg_match_all($re, $element, $matches, PREG_SET_ORDER, 0);

    $found = '';
    foreach ($matches as $match) {

        if ($match[1] == $property) {
            $match[2] = $str = str_replace(['\'', '"'], '', $match[2]);
            $found = $match;
        }
    }

    return $found;
}

function _getHtmlElement($tag = false) {

    if ($tag) {
        
    }
}

/**
 * tratamento de imagens
 */
// gerando miniaturas
function _imageCreateThumbnail($source = false, $target = false) {



    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $target_file = $target;
    $target = dirname($target);

    /**
     * $source
     * pode ser o caminho de uma imagem ou um base64
     */
    if ($source and $target) {

        _mkdir($target, 0777, true);

        // se é um base64
        if (@!is_file($source)) {
            $tmp_image = '/tmp/' . md5($source);
            //_print_l('Criando arquivo: ' . $target_file);
            if (!is_file($target_file)) {
                file_put_contents($target_file, $source);
                if (is_file($target_file)) {
                    //_print_l('Arquivo criado com sucesso');
                } else {
                    //_print_l('Arquivo NAO foi criado');
                }
            }
        } else {
            //_print_l('é um arquivo em disco: ' . $soucrce);
        }
    }

    return $target_file;
}

/*
 * @name: nome do cookie
 * @value: valor do cookie
 * @expire: prazo de expiracao do cookie em dias
 */
function _cookieSet($name, $value, $expire = NULL) {

    //    _print_r( "definindo cookie [$name >>  $value] para: $expire" );
    //    _print_r( $_COOKIE );
    //    


    if ($expire == NULL) {
        $expire = 0;
    } else {
        if (gettype($expire) != 'integer' || gettype($expire) != 'float') {
            //$expire = 1; // um dia 
            $expire = time() + (86400 * $expire);
        }
    }
    // definindo o cookie
    setcookie($name, $value, $expire, "/");
}

function _cookieGet($name) {

    if (isset($_COOKIE[$name])) {
        return $_COOKIE[$name];
    }

    return NULL;
}

function _cookieIsSet($name) {

    if (isset($_COOKIE[$name])) {
        return true;
    } else {
        return false;
    }
}

function __($t = false) {

    global $idiomas;

    if ($t) {
        $t = $idiomas->escrever($t);
    }

    return $t;
}

/**
 * calculo de idade
 */
function idadeCalcular($nascimento) {

    //$datadonascimento = '20/11/1977';
    // Separa em dia, mês e ano
    list($ano, $mes, $dia) = explode('-', $nascimento);

    // Descobre que dia é hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    // Descobre a unix timestamp da data de nascimento do fulano
    $diadonascimento = mktime(0, 0, 0, $mes, $dia, $ano);

    // Depois apenas fazemos o cálculo já citado :)
    $idade = floor((((($hoje - $diadonascimento) / 60) / 60) / 24) / 365.25);

    return $idade;
}

/**
 * Remove TAG HTML vazia
 * 
 * @param type $content
 * @return type
 */
function remove_TAG_empty($content) {


    $content = str_replace(["\n", "\r"], "", $content);

    die($content);

    return $content;
}

/**
 * Verifica se o numer é par
 */
function is_par($number = false) {
    if ($number) {
        if ($number % 2 == 0) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * Verifica se o numer é impar
 */
function is_impar($number = false) {
    if ($number) {
        return !is_par($number);
    }
}

/**
 * Remove os espacos de uma string
 * Analisa se o parametro passado é uma string do tipo "string"
 */
function _trim($string = false) {

    if ($string !== false) {
        if (gettype($string) === 'string') {
            $string = trim($string);
        }
    }

    return $string;
}

function _fileCacheGenerate($source, $target = '', $page = 0) {

    global $_fileCacheGenerate_use_cache_PDF, $app, $config, $lang;

    if (!$_fileCacheGenerate_use_cache_PDF) {
        $_fileCacheGenerate_use_cache_PDF = false;
    }


    // quantidade de parametros
    $n = func_num_args();

    $targetExtension = null;

    if ($target) {
        //        echo "Ver se é diretorio: $target";
        //        
        //        if ( is_dir($target) ) {
        //            echo "Diretorio: $target";
        //        } else {
        //            echo 'Not IS dir ';
        //        }
    }

    if ($n == 0) {
        return false;
    } else {

        // parametros passados
        $p = func_get_args();

        $copiado = true;
        $r = false;

        // nova captura de parametros...
        if ($n > 0) {
            // informando somente a origem
            $fn = $p[0];
            $source = $fn;
            $fnExtension = _fileGetExtension($fn);
        }

        if ($n > 1) {
            /* informando o destino
             */
            $target = $p[1];
            $targetExtension = _fileGetExtension($target);

            /**
             * Nao foi passao o nome de arquivo como destino/$target, mas sim a extensao para o mesmo. 
             * Sendo assim utiliza o parametro do nome como extensao.
             */
            if (!$targetExtension && strlen($target) == 3) {
                $targetExtension = $target;
            }
        }

        if ($n > 2) {
            // informando a pagina
            $page = $p[2];
        } else {

            if (is_numeric($target)) {
                $page = $target;
                $target = NULL;
                $targetExtension = NULL;
            }

            /**
             * Alterando o valor da quantidade de parametros recebidos para que abaixo o sistema gere o nome do arquivo de destino/$destino com base no "hash md5"
             */
            $n = 1;
        }

        if (isset($typeTargetDefault)) {
            $targetExtension = $typeTargetDefault;
        }

        if ($targetExtension == '') {
            $targetExtension = 'jpg';
            $targetExtension = _fileGetExtension($source);
        }

        $cache_dir = $app->cache_modulo . DS . $config->libraries['imagens'] . DS;

        if (!is_file($fn)) {
            $fn = $app->path_root . $fn;
        }

        if (is_file($fn)) {

            $origem = $fn;

            if (isset($page)) {

                $diretorio = dirname($fn);
                $file = str_replace($diretorio, '', $fn);
                $f = explode('.', $file);
                $ext = $f[count($f) - 1];

                // se tem extensao
                if ($ext) {
                    // removendo a extensão do nome do arquivo( ultima posição negativo)
                    $file = str_replace('.' . $ext, '', $file);
                }

                $fn = str_replace($diretorio, '', $fn);

                $fn = "{$file} $page.{$ext}";
            }

            if ($n == 2) {
                // foi informado o nome do arquivo de destino
                $destino = $target;
            } else {
                /**
                 * como nao foi informado o nome do arquivo de destino, 
                 * sera gerado o arquivo na pasta de cache.
                 */
                // utilizando o nome do arquivo de origem( sem o numero da pagina)
                // utilizando um hash  
                $file_name_temp = md5($cache_dir . $fn . '.' . $targetExtension);

                // criando diretorio de destino caso não exista
                _mkdir($app->path_root . $cache_dir);

                $destino = $app->path_root . $cache_dir . $file_name_temp . '.' . $targetExtension;
            }

            if ($fnExtension == $targetExtension && !$page) {
                /**
                 * Se as extensoes entre origem e destino sao iguais,
                 * apenas copia o aruqivo
                 */
                //echo $origem . ' ^ ' . $destino;

                $copiado = _copy("$origem", "$destino");
            } else {

                /**
                 * Se as extensoes dos arquivossao diferente, 
                 * será feita a conversao de tipo dos arquivos.
                 */
                $copiado = true;

                if (isset($page)) {
                    $r = _fileConvert($origem, $destino, $page);
                } else {
                    $r = _fileConvert($origem, $destino);
                }

                if (is_file($destino)) {
                    $copiado = true;
                } else {
                    $copiado = false;
                }
            }

            if ($copiado) {

                if (!$targetExtension) {
                    $targetExtension = 'jpg';
                    $destino = "{$destino}.{$targetExtension}";
                }

                $types = [
                    0 => 'UNKNOWN',
                    1 => 'GIF',
                    2 => 'JPEG',
                    3 => 'PNG',
                    4 => 'SWF',
                    5 => 'PSD',
                    6 => 'BMP',
                    7 => 'TIFF_II',
                    8 => 'TIFF_MM',
                    9 => 'JPC',
                    10 => 'JP2',
                    11 => 'JPX',
                    12 => 'JB2',
                    13 => 'SWC',
                    14 => 'IFF',
                    15 => 'WBMP',
                    16 => 'XBM',
                    17 => 'ICO',
                    18 => 'COUNT'
                ];
                $info = getimagesize($destino);

                $r = ['destino' => $destino,
                    'source' => $origem,
                    'width' => $info[0],
                    'height' => $info[1],
                    'type' => $types[ $info[2] ],
                    'mime' => $info['mime'],
                    'bits' => $info['bits'],
                    'channels' => $info['channels'],
                ];
            } else {
                $r = false;
            }
        } else {
            $r = false;
        }

        return $r;
    }
}

function _fileConvert($from, $to = '', $page = 0) {

    global $_fileCacheGenerate_use_cache_PDF;

    /**
     * $page = 0 
     * Converte todas as paginas do documento($from) ou,
     * somente a pagina espeficada
     * 
     * 
     * JPG eh a extensao padrao para arquivos que não tem nome de destino.
     */
    if (!is_file($from)) {
        return false;
    }

    $extrairPagina = '';
    $formatFrom = _fileGetExtension($from);
    if (is_numeric($to)) {
        $page = $to;
        $to = _file_get_name($from) . '.jpg';
    } else {
        /**
         * Somente o nome do arquivo sem caminho
         */
        //$to = _file_get_name($to);
    }


    if ($to) {
        $formatTo = _fileGetExtension(_file_get_name($to));
    } else {
        $to = _file_get_name($from);
    }

    switch ($formatTo) {
        case 'jpg':
        case NULL:
        case '':
            $convertTo = 'jpeg';
            $formatTo = 'jpg';
            break;
    }

    switch ($formatFrom) {
        case 'jpg':
            $convertTo = 'jpeg';
            break;
        case 'pdf':

            /**
             * Extraindo a pagina para que seja convertida
             * no caso de PDF
             */
            if ($page) {

                // ajustando o nome do arquivo de destino
                $to_file_name = $to;
                if (strpos($to, $formatTo)) {
                    $to = str_replace(".{$formatTo}", '', $to);
                }

                // extraindo a pagina definida
                $extrairPagina = "pdftk {$from} cat {$page} output {$to_file_name};";
                $old_from = $from;

                /**
                 *  ajustando o nome do arquivo de origem para que seja feito o procedimento de conversao                 
                 * com base no aruqivo convertido
                 *  - este arquivo apos convertido, devera ser removido
                 */
                $from = $to_file_name;
            }
            break;
    }

    switch ($formatFrom) {
        case 'pdf':
            $_fileCacheGenerate_use_cache_PDF = true;

            if (!isset($convertTo)) {
                $convertTo = $formatFrom;
            }

            if ($_fileCacheGenerate_use_cache_PDF) {

                if (is_file("/usr/bin/pdftoppm")) {

                    // preparando nome do arquivo para fazer o rename
                    $e = _fileGetExtension($to);
                    if (!$e) {
                        $e = $formatFrom;
                    }

                    $d = dirname($to);
                    $fn = _file_get_name($to);
                    if (isset($page)) {
                        $pg = '-' . $page;
                    } else {
                        $pg = '';
                    }
                    $src = "{$d}{$fn}{$pg}.{$e}";

                    // CONVERRTE, RENOMEIA E APAGA O ARQUIVO TEMPORARIO
                    // convertendo a pagina em pdf para o formato definido
                    // tudo deve ser feito com uma unica chamada ao shell atraves do comando "exec"
                    $converte = "/usr/bin/pdftoppm -{$convertTo} {$from} {$to};";
                    //                $converte='';

                    $remove = "rm -f {$from};";
                    $remove = '';

                    if (strpos($to, $formatTo)) {
                        $to = str_replace(".{$formatTo}", '', $to);
                    }

                    $target = $to;
                    $rename = "mv {$to}-1.{$formatTo} {$target}.{$formatTo};";

                    // executando somente se o destino não existir
                    if ($formatTo == $formatFrom && $page) {
                        exec("$extrairPagina");
                    } else {
                        if (!is_file("{$target}.{$formatTo}")) {
                            exec("$extrairPagina $converte $rename $remove ");
                        }
                    }
                }
            }

            break;
    }
}

function _copy($origem, $destino, $opcoes = NULL) {

    // se o destino existir, sera removido
    if (file_exists($destino)) {
        return true;
    }

    $criado = _mkdir(dirname($destino), 0777, RECURSIVE);

    $fail = false;

    if (file_exists($origem)) {
        if (!file_exists($destino)) {
            copy($origem, $destino);
        }
    } else {

        if (is_dir($origem)) {

            _mkdir($destino);
            $files = scandir($origem);

            /**
             * Inicialmente assum-se que todas as copias ocorreram com sucesso
             */
            foreach ($files as $file) {

                if ($file != "." && $file != "..") {

                    copy("{$origem}/{$file}", "{$destino}/{$file}");

                    if (!file_exists("{$destino}/{$file}")) {
                        /**
                         * Ocorreu um erro em uma das copias
                         */
                        $fail = true;
                    }
                }
            }
        }
    }


    if ($criado === true and $fail) {
        _rmdir(dirname($destino));
    }

    return !$fail;
}
