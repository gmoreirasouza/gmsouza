<?php

namespace php\Config;

_debug(" &nbsp; &nbsp; Carregando " . __FILE__ . "\n");

/**
 * Constantes 
 */
// separador de diretorio(directory separator)
define('DS', '/');

// separador de caminhos(path separator)
if ( !defined('PS'))
    define('PS', ':');

// quebra de linha/HTML
define('BR', '<br>');

define('AJAX', 1);
define('NOAJAX', 0);

define('CREATE_IF_NOT_EXIST', 201);

class Config { 

    /**
     * Padrao de cores do sistema
     */
    var $colors;

    /**
     * 
     * charset (codificação)
     */
    var $charset;

    
    /**
     * nome da pasta do core
     */
    var $core;
    
    /**
     * E-mails para nofificacoes de acoes do sistema
     */
    var $emailsNotificacoes;

    /**
     * Servidores de email
     */
    var $serverMailConfig;

    /**
     *  raiz da biblioteca
     */
    var $folder_library;

    /**
     * Caminho do nucleo
     */
    var $path_nucleo;

    /**
     *  nome das pastas de bibliotecas 
     */
    var $libraries;

    /**
     * Regioes
     */
    var $locales;

    /**
     * Configuracoes regionais/localizacao
     */
    var $locale;

    /**
     * configuracao regional selecionada/default
     */
    var $locale_default;

    /**
     * Lista de time_zone
     */
    var $time_zone_list;

    /**
     * TimeZones
     * Lista de timezones do Brasil
     */
    var $timezones;

    /**
     * PAIS
     */
    var $country;

    /**
     * Idiomas
     */
    var $languages;

    /**
     * idioma corrente
     */
    var $language;

    /**
     * Lista de estados/UF´s
     */
    var $states;

    /**
     * Estado padrao
     */
    var $state;

    /**
     * mes corrente
     */
    var $mes;
    var $mes_short;
    var $dia_semana;
    var $dia_semana_extend;
    var $dia_mes;
    var $ano;
    var $dias_semana;
    var $dias_semana_extend;
    var $meses;
    var $meses_short;

    function __construct() {

        $this->core = 'core';
        
        $this->path_nucleo = $this->core . DS;
        
        $this->path_root = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] . DS : '';
        $this->path_core = $this->path_root ? $this->path_root . $this->core . DS : '';
        
        
        $this->charset = 'UTF-8';
        $this->dias_semana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado', 'Domingo'];
        $this->dias_semana_extend = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado', 'Domingo'];
        $this->meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        $this->meses_short = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        $this->mes = $this->meses[date('m') - 1];
        $this->mes_short = $this->meses_short[date('m') - 1];
        $this->dia_semana = $this->dias_semana[date('N') - 1];
        $this->dia_semana_extend = $this->dias_semana_extend[date('N') - 1];
        $this->dia_mes = date('j');
        $this->ano = date('Y');
        $this->colors = '';
        $this->emailsNotificacoes = '';
        $this->folder_library = 'bibliotecas' . DS;
        $this->libraries = [
            'javascript',
            'css',
            'scss',
            'php',
            'java',
            'images',
            'cache',
            'certificates',
            'fonts',
            'languages',
            'flash',
            'videos',
            'Models',
            'plugins',
            'templates' => 'templates',
            'temp' => 'temp',
            'storage' => 'storage'
        ];
        $this->languages = [
            'pt_BR' => 'Portugues/Brasil',
            'pt_PT' => 'Portugues/Portugal',
            'en_US' => 'Ingles (Estados Unidos)',
        ];
        $this->states = [
            'AM' => ['cidade' => 'Amazonas'],
            'BA' => ['cidade' => 'Bahia'],
            'ES' => ['cidade' => 'Espirito Santo'],
            'MA' => ['cidade' => 'Manaus'],
            'MG' => ['cidade' => 'Minas Gerais'],
            'RJ' => ['cidade' => 'Rio de Janeiro'],
            'SP' => ['cidade' => 'São Paulo'],
        ];
        $this->state = 'MG';
        $this->locales = ['aa_DJ' => 'Afar (Djibouti)', 'aa_ER' => 'Afar (Eritrea)', 'aa_ET' => 'Afar (Ethiopia)', 'af_ZA' => 'Afrikaans (South Africa)', 'sq_AL' => 'Albanian (Albania)', 'sq_MK' => 'Albanian (Macedonia)', 'am_ET' => 'Amharic (Ethiopia)', 'ar_DZ' => 'Arabic (Algeria)', 'ar_BH' => 'Arabic (Bahrain)', 'ar_EG' => 'Arabic (Egypt)', 'ar_IN' => 'Arabic (India)', 'ar_IQ' => 'Arabic (Iraq)', 'ar_JO' => 'Arabic (Jordan)', 'ar_KW' => 'Arabic (Kuwait)', 'ar_LB' => 'Arabic (Lebanon)', 'ar_LY' => 'Arabic (Libya)', 'ar_MA' => 'Arabic (Morocco)', 'ar_OM' => 'Arabic (Oman)', 'ar_QA' => 'Arabic (Qatar)', 'ar_SA' => 'Arabic (Saudi Arabia)', 'ar_SD' => 'Arabic (Sudan)', 'ar_SY' => 'Arabic (Syria)', 'ar_TN' => 'Arabic (Tunisia)', 'ar_AE' => 'Arabic (United Arab Emirates)', 'ar_YE' => 'Arabic (Yemen)', 'an_ES' => 'Aragonese (Spain)', 'hy_AM' => 'Armenian (Armenia)', 'as_IN' => 'Assamese (India)', 'ast_ES' => 'Asturian (Spain)', 'az_AZ' => 'Azerbaijani (Azerbaijan)', 'az_TR' => 'Azerbaijani (Turkey)', 'eu_FR' => 'Basque (France)', 'eu_ES' => 'Basque (Spain)', 'be_BY' => 'Belarusian (Belarus)', 'bem_ZM' => 'Bemba (Zambia)', 'bn_BD' => 'Bengali (Bangladesh)', 'bn_IN' => 'Bengali (India)', 'ber_DZ' => 'Berber (Algeria)', 'ber_MA' => 'Berber (Morocco)', 'byn_ER' => 'Blin (Eritrea)', 'bs_BA' => 'Bosnian (Bosnia and Herzegovina)', 'br_FR' => 'Breton (France)', 'bg_BG' => 'Bulgarian (Bulgaria)', 'my_MM' => 'Burmese (Myanmar [Burma])', 'ca_AD' => 'Catalan (Andorra)', 'ca_FR' => 'Catalan (France)', 'ca_IT' => 'Catalan (Italy)', 'ca_ES' => 'Catalan (Spain)', 'zh_CN' => 'Chinese (China)', 'zh_HK' => 'Chinese (Hong Kong SAR China)', 'zh_SG' => 'Chinese (Singapore)', 'zh_TW' => 'Chinese (Taiwan)', 'cv_RU' => 'Chuvash (Russia)', 'kw_GB' => 'Cornish (United Kingdom)', 'crh_UA' => 'Crimean Turkish (Ukraine)', 'hr_HR' => 'Croatian (Croatia)', 'cs_CZ' => 'Czech (Czech Republic)', 'da_DK' => 'Danish (Denmark)', 'dv_MV' => 'Divehi (Maldives)', 'nl_AW' => 'Dutch (Aruba)', 'nl_BE' => 'Dutch (Belgium)', 'nl_NL' => 'Dutch (Netherlands)', 'dz_BT' => 'Dzongkha (Bhutan)', 'en_AG' => 'English (Antigua and Barbuda)', 'en_AU' => 'English (Australia)', 'en_BW' => 'English (Botswana)', 'en_CA' => 'English (Canada)', 'en_DK' => 'English (Denmark)', 'en_HK' => 'English (Hong Kong SAR China)', 'en_IN' => 'English (India)', 'en_IE' => 'English (Ireland)', 'en_NZ' => 'English (New Zealand)', 'en_NG' => 'English (Nigeria)', 'en_PH' => 'English (Philippines)', 'en_SG' => 'English (Singapore)', 'en_ZA' => 'English (South Africa)', 'en_GB' => 'English (United Kingdom)', 'en_US' => 'English (United States)', 'en_ZM' => 'English (Zambia)', 'en_ZW' => 'English (Zimbabwe)', 'eo' => 'Esperanto', 'et_EE' => 'Estonian (Estonia)', 'fo_FO' => 'Faroese (Faroe Islands)', 'fil_PH' => 'Filipino (Philippines)', 'fi_FI' => 'Finnish (Finland)', 'fr_BE' => 'French (Belgium)', 'fr_CA' => 'French (Canada)', 'fr_FR' => 'French (France)', 'fr_LU' => 'French (Luxembourg)', 'fr_CH' => 'French (Switzerland)', 'fur_IT' => 'Friulian (Italy)', 'ff_SN' => 'Fulah (Senegal)', 'gl_ES' => 'Galician (Spain)', 'lg_UG' => 'Ganda (Uganda)', 'gez_ER' => 'Geez (Eritrea)', 'gez_ET' => 'Geez (Ethiopia)', 'ka_GE' => 'Georgian (Georgia)', 'de_AT' => 'German (Austria)', 'de_BE' => 'German (Belgium)', 'de_DE' => 'German (Germany)', 'de_LI' => 'German (Liechtenstein)', 'de_LU' => 'German (Luxembourg)', 'de_CH' => 'German (Switzerland)', 'el_CY' => 'Greek (Cyprus)', 'el_GR' => 'Greek (Greece)', 'gu_IN' => 'Gujarati (India)', 'ht_HT' => 'Haitian (Haiti)', 'ha_NG' => 'Hausa (Nigeria)', 'iw_IL' => 'Hebrew (Israel)', 'he_IL' => 'Hebrew (Israel)', 'hi_IN' => 'Hindi (India)', 'hu_HU' => 'Hungarian (Hungary)', 'is_IS' => 'Icelandic (Iceland)', 'ig_NG' => 'Igbo (Nigeria)', 'id_ID' => 'Indonesian (Indonesia)', 'ia' => 'Interlingua', 'iu_CA' => 'Inuktitut (Canada)', 'ik_CA' => 'Inupiaq (Canada)', 'ga_IE' => 'Irish (Ireland)', 'it_IT' => 'Italian (Italy)', 'it_CH' => 'Italian (Switzerland)', 'ja_JP' => 'Japanese (Japan)', 'kl_GL' => 'Kalaallisut (Greenland)', 'kn_IN' => 'Kannada (India)', 'ks_IN' => 'Kashmiri (India)', 'csb_PL' => 'Kashubian (Poland)', 'kk_KZ' => 'Kazakh (Kazakhstan)', 'km_KH' => 'Khmer (Cambodia)', 'rw_RW' => 'Kinyarwanda (Rwanda)', 'ky_KG' => 'Kirghiz (Kyrgyzstan)', 'kok_IN' => 'Konkani (India)', 'ko_KR' => 'Korean (South Korea)', 'ku_TR' => 'Kurdish (Turkey)', 'lo_LA' => 'Lao (Laos)', 'lv_LV' => 'Latvian (Latvia)', 'li_BE' => 'Limburgish (Belgium)', 'li_NL' => 'Limburgish (Netherlands)', 'lt_LT' => 'Lithuanian (Lithuania)', 'nds_DE' => 'Low German (Germany)', 'nds_NL' => 'Low German (Netherlands)', 'mk_MK' => 'Macedonian (Macedonia)', 'mai_IN' => 'Maithili (India)', 'mg_MG' => 'Malagasy (Madagascar)', 'ms_MY' => 'Malay (Malaysia)', 'ml_IN' => 'Malayalam (India)', 'mt_MT' => 'Maltese (Malta)', 'gv_GB' => 'Manx (United Kingdom)', 'mi_NZ' => 'Maori (New Zealand)', 'mr_IN' => 'Marathi (India)', 'mn_MN' => 'Mongolian (Mongolia)', 'ne_NP' => 'Nepali (Nepal)', 'se_NO' => 'Northern Sami (Norway)', 'nso_ZA' => 'Northern Sotho (South Africa)', 'nb_NO' => 'Norwegian Bokmål (Norway)', 'nn_NO' => 'Norwegian Nynorsk (Norway)', 'oc_FR' => 'Occitan (France)', 'or_IN' => 'Oriya (India)', 'om_ET' => 'Oromo (Ethiopia)', 'om_KE' => 'Oromo (Kenya)', 'os_RU' => 'Ossetic (Russia)', 'pap_AN' => 'Papiamento (Netherlands Antilles)', 'ps_AF' => 'Pashto (Afghanistan)', 'fa_IR' => 'Persian (Iran)', 'pl_PL' => 'Polish (Poland)', 'pt_BR' => 'Portuguese (Brazil)', 'pt_PT' => 'Portuguese (Portugal)', 'pa_IN' => 'Punjabi (India)', 'pa_PK' => 'Punjabi (Pakistan)', 'ro_RO' => 'Romanian (Romania)', 'ru_RU' => 'Russian (Russia)', 'ru_UA' => 'Russian (Ukraine)', 'sa_IN' => 'Sanskrit (India)', 'sc_IT' => 'Sardinian (Italy)', 'gd_GB' => 'Scottish Gaelic (United Kingdom)', 'sr_ME' => 'Serbian (Montenegro)', 'sr_RS' => 'Serbian (Serbia)', 'sid_ET' => 'Sidamo (Ethiopia)', 'sd_IN' => 'Sindhi (India)', 'si_LK' => 'Sinhala (Sri Lanka)', 'sk_SK' => 'Slovak (Slovakia)', 'sl_SI' => 'Slovenian (Slovenia)', 'so_DJ' => 'Somali (Djibouti)', 'so_ET' => 'Somali (Ethiopia)', 'so_KE' => 'Somali (Kenya)', 'so_SO' => 'Somali (Somalia)', 'nr_ZA' => 'South Ndebele (South Africa)', 'st_ZA' => 'Southern Sotho (South Africa)', 'es_AR' => 'Spanish (Argentina)', 'es_BO' => 'Spanish (Bolivia)', 'es_CL' => 'Spanish (Chile)', 'es_CO' => 'Spanish (Colombia)', 'es_CR' => 'Spanish (Costa Rica)', 'es_DO' => 'Spanish (Dominican Republic)', 'es_EC' => 'Spanish (Ecuador)', 'es_SV' => 'Spanish (El Salvador)', 'es_GT' => 'Spanish (Guatemala)', 'es_HN' => 'Spanish (Honduras)', 'es_MX' => 'Spanish (Mexico)', 'es_NI' => 'Spanish (Nicaragua)', 'es_PA' => 'Spanish (Panama)', 'es_PY' => 'Spanish (Paraguay)', 'es_PE' => 'Spanish (Peru)', 'es_ES' => 'Spanish (Spain)', 'es_US' => 'Spanish (United States)', 'es_UY' => 'Spanish (Uruguay)', 'es_VE' => 'Spanish (Venezuela)', 'sw_KE' => 'Swahili (Kenya)', 'sw_TZ' => 'Swahili (Tanzania)', 'ss_ZA' => 'Swati (South Africa)', 'sv_FI' => 'Swedish (Finland)', 'sv_SE' => 'Swedish (Sweden)', 'tl_PH' => 'Tagalog (Philippines)', 'tg_TJ' => 'Tajik (Tajikistan)', 'ta_IN' => 'Tamil (India)', 'tt_RU' => 'Tatar (Russia)', 'te_IN' => 'Telugu (India)', 'th_TH' => 'Thai (Thailand)', 'bo_CN' => 'Tibetan (China)', 'bo_IN' => 'Tibetan (India)', 'tig_ER' => 'Tigre (Eritrea)', 'ti_ER' => 'Tigrinya (Eritrea)', 'ti_ET' => 'Tigrinya (Ethiopia)', 'ts_ZA' => 'Tsonga (South Africa)', 'tn_ZA' => 'Tswana (South Africa)', 'tr_CY' => 'Turkish (Cyprus)', 'tr_TR' => 'Turkish (Turkey)', 'tk_TM' => 'Turkmen (Turkmenistan)', 'ug_CN' => 'Uighur (China)', 'uk_UA' => 'Ukrainian (Ukraine)', 'hsb_DE' => 'Upper Sorbian (Germany)', 'ur_PK' => 'Urdu (Pakistan)', 'uz_UZ' => 'Uzbek (Uzbekistan)', 've_ZA' => 'Venda (South Africa)', 'vi_VN' => 'Vietnamese (Vietnam)', 'wa_BE' => 'Walloon (Belgium)', 'cy_GB' => 'Welsh (United Kingdom)', 'fy_DE' => 'Western Frisian (Germany)', 'fy_NL' => 'Western Frisian (Netherlands)', 'wo_SN' => 'Wolof (Senegal)', 'xh_ZA' => 'Xhosa (South Africa)', 'yi_US' => 'Yiddish (United States)', 'yo_NG' => 'Yoruba (Nigeria)', 'zu_ZA' => 'Zulu (South Africa)'];
        $this->time_zone_list = ['Africa/Asmera', 'Africa/Timbuktu', 'America/Argentina/ComodRivadavia', 'America/Atka', 'America/Buenos_Aires', 'America/Catamarca', 'America/Coral_Harbour', 'America/Cordoba', 'America/Ensenada', 'America/Fort_Wayne', 'America/Indianapolis', 'America/Jujuy', 'America/Knox_IN', 'America/Louisville', 'America/Mendoza', 'America/Montreal', 'America/Porto_Acre', 'America/Rosario', 'America/Santa_Isabel', 'America/Shiprock', 'America/Virgin', 'Antarctica/South_Pole', 'Asia/Ashkhabad', 'Asia/Calcutta', 'Asia/Chongqing', 'Asia/Chungking', 'Asia/Dacca', 'Asia/Harbin', 'Asia/Istanbul', 'Asia/Kashgar', 'Asia/Katmandu', 'Asia/Macao', 'Asia/Rangoon', 'Asia/Saigon', 'Asia/Tel_Aviv', 'Asia/Thimbu', 'Asia/Ujung_Pandang', 'Asia/Ulan_Bator', 'Atlantic/Faeroe', 'Atlantic/Jan_Mayen', 'Australia/ACT', 'Australia/Canberra', 'Australia/LHI', 'Australia/North', 'Australia/NSW', 'Australia/Queensland', 'Australia/South', 'Australia/Tasmania', 'Australia/Victoria', 'Australia/West', 'Australia/Yancowinna', 'Brazil/Acre', 'Brazil/DeNoronha', 'Brazil/East', 'Brazil/West', 'Canada/Atlantic', 'Canada/Central', 'Canada/Eastern', 'Canada/Mountain', 'Canada/Newfoundland', 'Canada/Pacific', 'Canada/Saskatchewan', 'Canada/Yukon', 'CET', 'Chile/Continental', 'Chile/EasterIsland', 'CST6CDT', 'Cuba', 'EET', 'Egypt', 'Eire', 'EST', 'EST5EDT', 'Etc/GMT', 'Etc/GMT+0', 'Etc/GMT+1', 'Etc/GMT+10', 'Etc/GMT+11', 'Etc/GMT+12', 'Etc/GMT+2', 'Etc/GMT+3', 'Etc/GMT+4', 'Etc/GMT+5', 'Etc/GMT+6', 'Etc/GMT+7', 'Etc/GMT+8', 'Etc/GMT+9', 'Etc/GMT-0', 'Etc/GMT-1', 'Etc/GMT-10', 'Etc/GMT-11', 'Etc/GMT-12', 'Etc/GMT-13', 'Etc/GMT-14', 'Etc/GMT-2', 'Etc/GMT-3', 'Etc/GMT-4', 'Etc/GMT-5', 'Etc/GMT-6', 'Etc/GMT-7', 'Etc/GMT-8', 'Etc/GMT-9', 'Etc/GMT0', 'Etc/Greenwich', 'Etc/UCT', 'Etc/Universal', 'Etc/UTC', 'Etc/Zulu', 'Europe/Belfast', 'Europe/Nicosia', 'Europe/Tiraspol', 'Factory', 'GB', 'GB-Eire', 'GMT', 'GMT+0', 'GMT-0', 'GMT0', 'Greenwich', 'Hongkong', 'HST', 'Iceland', 'Iran', 'Israel', 'Jamaica', 'Japan', 'Kwajalein', 'Libya', 'MET', 'Mexico/BajaNorte', 'Mexico/BajaSur', 'Mexico/General', 'MST', 'MST7MDT', 'Navajo', 'NZ', 'NZ-CHAT', 'Pacific/Johnston', 'Pacific/Ponape', 'Pacific/Samoa', 'Pacific/Truk', 'Pacific/Yap', 'Poland', 'Portugal', 'PRC', 'PST8PDT', 'ROC', 'ROK', 'Singapore', 'Turkey', 'UCT', 'Universal', 'US/Alaska', 'US/Aleutian', 'US/Arizona', 'US/Central', 'US/East-Indiana', 'US/Eastern', 'US/Hawaii', 'US/Indiana-Starke', 'US/Michigan', 'US/Mountain', 'US/Pacific', 'US/Pacific-New', 'US/Samoa', 'UTC', 'W-SU', 'WET', 'Zulu'];
        $this->timezones = ['BR' => [
                'AC' => ['America/Rio_branco', -3],
                'MG' => ['America/Sao_Paulo', -3],
                'AL' => ['America/Maceio', -3],
                'AP' => ['America/Belem', -3],
                'AM' => ['America/Manaus', -3],
                'BA' => ['America/Bahia', -3],
                'CE' => ['America/Fortaleza', -3],
                'DF' => ['America/Sao_Paulo', -3],
                'ES' => ['America/Sao_Paulo', -3],
                'GO' => ['America/Sao_Paulo', -3],
                'MA' => ['America/Fortaleza', -3],
                'MT' => ['America/Cuiaba', -3],
                'MS' => ['America/Campo_Grande', -3],
                'SP' => ['America/Sao_Paulo', -3],
                'PR' => ['America/Sao_Paulo', -3],
                'PB' => ['America/Fortaleza', -3],
                'PA' => ['America/Belem', -3],
                'PE' => ['America/Recife', -3],
                'PI' => ['America/Fortaleza', -3],
                'RJ' => ['America/Sao_Paulo', -3],
                'RN' => ['America/Fortaleza', -3],
                'RS' => ['America/Sao_Paulo', -3],
                'RO' => ['America/Porto_Velho', -3],
                'RR' => ['America/Boa_Vista', -3],
                'SC' => ['America/Sao_Paulo', -3],
                'SE' => ['America/Maceio', -3],
                'SP' => ['America/Sao_Paulo', -3],
                'TO' => ['America/Araguaia', -3]
            ]
        ];
        $this->country = 'BR';
        $this->locale['BR'] = [
            'timezone' => $this->timezones[$this->country][$this->state],
            'language' => $this->languages['pt_BR'],
            'decimal_separator' => ',',
            'decimal_point' => '.',
            'decimal_float' => 2,
            'date_format' => 'DD/MM/AAAA',
            'date_format_database' => 'AAAA/MM/DD',
            'date_format_extenso' => '',
            'time_format' => 'H:M:S',
            'time_format_slim' => 'H:M',
            'monetary_symbol' => 'R$',
            'monetary_positive_symbol' => '',
            'monetary_negative_symbol' => '-',
            'monetary_negative_color' => 'red',
            'number_symbol_float' => '.'
        ];
        $this->locale_default = $this->locale[$this->country];
        $this->language = 'pt' . '_' . $this->country;

        // caminhos
        $paths = '';
        $libs = [];

        /*
         * Criando a pasta da biblioteca no nucleo
         */
        $library = $this->path_nucleo . $this->folder_library;
        if (!is_dir($library)) {
            @mkdir($library, 0777, true);
            if (!is_dir($library)) {
                die(print_r('falha ao criar a pasta: ' . $library));
            }
        }

        // gambiarra pura 
        if (!defined('lib_root')) {
            define('lib_root', '/var/www/html/nucleo2.0/');
        }

        foreach ($this->libraries as $k => $lib) {

            if (is_numeric($k)) {
                $k = $lib;
            }

            $library = $this->path_nucleo . $this->folder_library . $lib . DS;
            $paths .= $library . PS;
            $libs[$k] = $lib;

            /*
             * Criando a pasta da biblioteca no nucleo
             */
            if (!is_dir($library)) {
                @mkdir($library, 0777, true);
                if (!is_dir($library)) {
                    _log('falha ao criar a pasta: ' . $library);
                }
            }

            if (!defined('lib_' . $lib)) {
                define('lib_' . $lib, $library);
            }
        }

        $this->libraries = $libs;
        unset($libs);

        set_include_path(get_include_path() . PS . $this->path_nucleo);
        set_include_path(get_include_path() . PS . $paths);
    }

    function load($options = []) {


        global $tables, $model, $db;

        /**
         * carregando configuracao
         */
        $cfg = [];

//        $tables = new tables;

        if (isset($tables->configuracoes)) {

            $cfg = $tables->configuracoes->Find(['padrao' => '1']);

            if (isset($cfg[0])) {

                /**
                 * COnfiguracao de cores
                 */
                if (isset($tables->configuracoesCores)) {
                    $colors = $tables->configuracoesCores->Find(['id' => $cfg[0]['configuracoesCores_id']]);
                    if (isset($colors[0])) {
                        $this->colors = $colors;
                        $cfg[0]['Cores'] = $colors;
                    }
                }

                if (isset($tables->configuracoesEmailsNotificacoes)) {
                    $emailsNotificacoes = $tables->configuracoesEmailsNotificacoes->Find(['id' => $cfg[0]['configuracoesEmailsNotificacoes_id']]);
                    if (isset($emailsNotificacoes[0])) {
                        $this->emailsNotificacoes = $emailsNotificacoes;
                        $cfg[0]['emailsNotificacoes'] = $emailsNotificacoes;
                    }
                }

                if (isset($tables->configuracoesServidoresEmail)) {

                    $serverMailConfig = $tables->configuracoesServidoresEmail->Find(['id' => $cfg[0]['configuracoesServidoresEmail_id']]);

                    if (isset($serverMailConfig[0])) {
                        $this->serverMailConfig = $serverMailConfig;
                        $cfg[0]['serverMailConfig'] = $serverMailConfig;
                    }
                }
            }
        }

        return $cfg;
    }

    /**
     * Define o idioma padrao e caracteristicas de localizacao
     */
    function set_language($l = false) {

        if ($l) {
            $this->language = $l;
            return $l;
        }
    }

}
