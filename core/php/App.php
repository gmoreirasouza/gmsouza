<?php

namespace php;

use Config;



_debug("Carregando " . __FILE__ . "\n");


class App {

    /**
     * Aplicacao
     */
    var $app;

    /**
     * Navegadores
     */
    var $navegadoresHomologados;

    /**
     * Modo de operacao
     * - Development
     * - Production
     */
    var $mode;

    /**
     * Chave (para validacao  de uso de $mode)
     */
    var $key;

    /**
     * String de consulta via URL/Query
     */
    var $query;

    /**
     * Identifica se é um dispositivo movel
     */
    var $isMobile;

    /**
     * Parametros recebidos via URL
     * POST / GET
     */
    var $parameters;

    /**
     * raiz do sistema
     */
    //var $path_root;

    /**
     * Raiz do nucleo
     */
    var $path_nucleo;

    /**
     * Configuracoes do sistema
     */
    var $config;

    /**
     * Configuracao de banco de dados
     */
    var $db;

    /**
     * Modulo corrente
     * www.site.com.br/modulo
     */
    var $modulo;

    /**
     * Funcao/propriedade 
     * que será executada
     * www.site.com.br/modulo/aplicacao/funcao
     */
    var $funcao;

    /**
     * Aplicacao corrente
     * www.site.com.br/modulo/aplicacao
     */
    var $aplicacao;

    /**
     * URL
     * url corrente com a query string
     */
    var $url;

    /**
     * Site
     * endereco padrao do site sem a query string
     */
    var $site;

    /**
     * parametros passados via url
     * www.site.com.br/modulo/aplicacao/funcao/p1/p2/p3/pn....
     */
    var $parametros;

    /**
     * Aplicacao atual
     */
    var $aplicacao_corrente;

    /**
     * Pasta de modulos
     */
    var $path_modules;

    /**
     * Pasta do modulo
     */
    var $path_app;

    /**
     * Parametros recebidos via url
     * http://domain.com/a/b/c/1?parametro=2
     */
    var $url_parametros = '';

    /**
     * Arquivo javascript temporario/flutuante
     */
    var $javascript_file = '';

    /**
     * pasta de cache do modulo
     */
    var $cache_modulo;

    /**
     * Mensagens do sistema
     */
    var $message;

    function __construct() {

        $this->app = '';

        $this->navegadoresHomologados = [
            ' Mozila Firefox' => ['url' => 'https://www.mozilla.org/pt-BR/firefox/new/'],
            'Chrome' => ['url' => 'https://www.google.pt/intl/pt-PT/chrome/?brand=CHBD&amp;gclid=EAIaIQobChMItvHZnYfy3gIVhAWRCh2zNgdsEAAYASAAEgJpG_D_BwE&amp;gclsrc=aw.ds'],
            'EDGE' => ['url' => 'https://www.microsoft.com/pt-br/edge'],
            'Internet Explorer 11'=>['url'=>'https://www.microsoft.com/pt-br/download/confirmation.aspx?id=40901', "enable"=>false] 
        ];

        /**
         * Identifica se é um dispositivo movel
         */
        //$this->isMobile = false;

        /**
         * Em producao
         */
        $this->mode = 'Production';
        $this->key = false;
        $this->parameters = [];
        $this->query = false;
        //$this->path_root = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] . DS : '';
        //$this->path_nucleo = $this->path_root ? $this->path_root . 'nucleo' . DS : '';

        if (isset($_SERVER['REQUEST_SCHEME'])) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
        } else {
            $scheme = '';
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            $host = '';
        }


        // padrao
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->site = $_SERVER['HTTP_REFERER'];
        } else {
            $this->site = "{$scheme}://{$host}";
        }

        // com a querystring


        $this->url = "{$scheme}://{$host}";

        if (isset($_GET['q'])) {
            $p = explode(DS, $_GET['q']);

            if (isset($p[0])) {
                $this->modulo = $p[0];
            } else {
                $this->modulo = 'Home';
            }
            if (isset($p[1])) {
                if (trim($p[1])) {
                    $this->aplicacao = $p[1];
                } else {
                    $this->aplicacao = 'dashBoard';
                }
            } else {
                $this->aplicacao = 'dashBoard';
            }

            $this->funcao = '';
            if (isset($p[2])) {
                if (trim($p[2])) {
                    $this->funcao = $p[2];
                }
            }
        } else {
            $this->modulo = 'Home';
            $this->aplicacao = 'dashBoard';
        }

        $this->aplicacao_corrente = $this->modulo . DS . $this->aplicacao . DS;

        $this->path_app = '';
        $this->url_parametros = '';
        $this->path_modules = 'modulos' . DS;
        $this->javascript_file = '';
        $this->cache_modulo = '';

        /**
         * Definindo o modulo baseando-se na URL
         */
        $this->parametros = [];

        /**
         * Mensagem
         */
        $this->message = '';
    }

    function debug($level = 1) {

        /**
         *  Valor   Constante
         * =======  ===============
         *  1       E_ERROR
         *  2       E_WARNING
         *  4       E_PARSE
         *  8       E_NOTICE
         *  16      E_CORE_ERROR
         *  32      E_CORE_WARNING
         *  64      E_COMPILE_ERROR
         *  128     E_COMPILE_WARNING
         *  256     E_USER_ERROR
         *  512     E_USER_WARNING
         *  1024    E_USER_NOTICE
         *  6143    E_ALL
         *  2048    E_STRICT
         *  4096    E_RECOVERABLE_ERROR
         */
        if ($level) {
            $level = 1;
        }

        if ($level == 0) {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(E_ERROR);
        } elseif ($level == 1) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }

    /**
     * Define conexao padao para banco de dados
     * 
     * @param type $source
     * @param type $array
     */
    function conection($source, $array) {

        $this->db[$source] = $array;

        if (isset($_SERVER['HTTP_HOST'])) {
            $http_host = $_SERVER['HTTP_HOST'];
        } else {
            $http_host = '';
        }


        if ($source === $http_host) {
            unset($this->db);
            $this->db['default'] = $array;
            unset($this->db[$source]);
            $this->db[$source] = $array;
        } else {
            if ($source === 'default') {
                unset($this->db);
                $this->db[$source] = $array;
            }
        }
    }

    function message($message = NULL) {
        $this->message = $message;
    }

    function set($var = false) {
        global $app;

        if ($var) {
            if (isset($app->parameters[$var])) {
                return $app->parameters[$var];
            } else {
                return '';
            }
        }
    }

}
