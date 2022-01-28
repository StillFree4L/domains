<?php

namespace main;

if (!isset($_SESSION)) {
    session_start();
}

mb_internal_encoding("utf-8");

// рандомизировать микросекундами
function make_seed()
{
    list($usec, $sec) = explode(' ', microtime());
    return (float)$sec + ((float)$usec * 100000);
}
srand(make_seed());

date_default_timezone_set("Europe/Moscow");

$GLOBALS['app_options'] =  [
    'forwards' => [
        'default' => [
            'CMD_PARSE' => 'Parse',
        ],
    ],
    'views' => [
        'default' => [
            'CMD_DEFAULT' => 'main',
            'CMD_ERROR' => '500',
            'CMD_ERROR_AUTORIZATION' => 'error_autorization',
            'CMD_ERROR_PRIVILEGES' => 'error_privileges',
            'CMD_LOCATION' => 'Location',
            'CMD_MISSING_ROW' => 'missingrow',
        ],
        'NotFound' => [
            'CMD_DEFAULT' => '500',
        ],
        'Autorization' => [
            'CMD_DEFAULT' => 'autorization',
            'CMD_OK' => 'succes_autorization',
        ],
        'Sources' => [
            'CMD_DEFAULT' => 'Sources',
        ],
        'Cron' => [
            'CMD_DEFAULT' => 'Cron',
        ],
        'SourceUpdate' => [
            'CMD_DEFAULT' => 'SourceUpdate',
        ],
        'SourceUpdateCron' => [
            'CMD_DEFAULT' => 'SourceUpdateCron',
        ],
        'SourceData' => [
            'CMD_DEFAULT' => 'SourceData',
        ],
        'SourceDataCron' => [
            'CMD_DEFAULT' => 'SourceDataCron',
        ],
        'Users' => [
            'CMD_DEFAULT' => 'users',
        ],
        'UserAdd' => [
            'CMD_DEFAULT' => 'useradd',
        ],
        'UserEdit' => [
            'CMD_DEFAULT' => 'useredit',
        ],
    ],
    'classaliases' => [
        'Autorization' => 'Autorization',
    ],
];

namespace workup;

class App
{
    private $config = [];

    private static $app = null;

    private static $path = null;

    private function __construct()
    {
        if (isset($GLOBALS['config'])) {
            $this->config = $GLOBALS['config'];
        } elseif (isset($GLOBALS['GLOBALS']['config'])) {
            $this->config = $GLOBALS['GLOBALS']['config'];
        } elseif (file_exists(__dir__ . "/../config/App.php")) {
            $this->config = require_once (__dir__ . "/../config/App.php");
        }

        self::$path = [
            'workup' => __dir__, 
            'libs' => __dir__ . "/libs", 
            'include' => __dir__ . "/include", 
            'MPDF57' => __dir__ . "/libs/MPDF57", 
        ];

        if (isset($this->config['WORDPRESS'])) {
            AppWordpress::instance();
        }
    }

    public static function config($key)
    {
        $args = func_get_args();

        $cargs = count($args);

        if ($cargs == 1) {
            if (isset(self::$app->config[$args[0]])) {
                return self::$app->config[$args[0]];
            } else {
                return null;
            }
        } elseif ($cargs == 2) {
            if (is_null($args[1])) {
                if (isset($this->config[$args[0]])) {
                    unset($this->config[$args[0]]);
                }
            } else {
                self::$app->config[$args[0]] = $args[1];
            }
        } elseif ($cargs > 2) {
            self::$app->config[$args[0]] = [];

            for ($i = 1; $i < $cargs; $i++) {
                self::$app->config[$args[0]][] = $args[$i];
            }
        }
    }

    public static function instance()
    {
        if (is_null(self::$app)) {
            self::$app = new self();

            self::$app->init();
        }
        return self::$app;
    }

    private function init()
    {
        spl_autoload_register("\workup\App::autoload");

        if (isset($this->config['LOG_ERRORS_FILE']) && file_exists($this->config['LOG_ERRORS_FILE']) && filesize($this->config['LOG_ERRORS_FILE']) > 10485760) {
            unlink($this->config['LOG_ERRORS_FILE']);
        }

        if (isset($this->config['DIR_TMP']) && !is_dir($this->config['DIR_TMP'])) {
            mkdir($this->config['DIR_TMP'], 0777, true);
        }

        \main\ErrorHandler::SetHandler();

    }

    public static function autoload($name)
    {
        $spaces = explode("\\", ltrim($name, "\\"));

        if (isset(self::$path[$spaces[0]])) {
            $spaces[0] = self::$path[$spaces[0]];

            $path = implode(DIRECTORY_SEPARATOR, $spaces);

            $path .= ".php";

            if (is_readable($path)) {
                require_once ($path);

                return true;
            }
        }

        return false;
    }

    public function __get($key)
    {
        if (isset(self::$path[$key])) {
            return self::$path[$key];
        } else {
            return null;
        }
    }
}

class AppWordpress
{
    private static $app = null;

    private $css_frontend = [];
    private $css_admin = [];

    private $script_frontend = [];
    private $script_admin = [];

    private function __construct()
    {
        \register_activation_hook(__dir__ . DIRECTORY_SEPARATOR . 'wp-parser.php', [$this,
            'install']);
        \register_deactivation_hook(__dir__ . DIRECTORY_SEPARATOR . 'wp-parser.php', [$this,
            'uninstall']);

        \add_action('init', [$this, 'init']);
        \add_action('admin_menu', [$this, 'admin_menu']);

        \add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
        \add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    public static function instance()
    {
        if (is_null(self::$app)) {
            self::$app = new self();
        }
        return self::$app;
    }

    public static function addCss($css, $type = 'frontend')
    {
        if ($type = 'admin') {
            self::instance()->css_admin[] = $css;
        } else {
            self::instance()->css_frontend[] = $css;
        }
    }

    public static function addJs($js, $type = 'frontend')
    {
        if ($type = 'admin') {
            self::instance()->script_admin[] = $js;
        } else {
            self::instance()->script_frontend[] = $js;
        }
    }

    public static function unsetTmp()
    {
        \main\delFolder(\workup\App::config('SITE_ROOT') . DIRECTORY_SEPARATOR . 'files');
    }

    public function install()
    {
        \main\DatabasePerform::Execute("CREATE TABLE `_sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `table_name` tinytext,
  `comment` text,
  `urls` text,
  `target_list_element` text,
  `target_list_value` text,
  `target_list_url` text,
  `target_list_next` text,
  `begin_page` int(5) DEFAULT NULL,
  `end_page` int(5) DEFAULT NULL,
  `key_page` tinytext,
  `data_list` text,
  `cookie_list` text,
  `curlopt_list` text,
  `http_method_list` enum('get','post') NOT NULL DEFAULT 'get',
  `page_urls` text,
  `target_page_element` text,
  `target_page_value` text,
  `data_page` text,
  `cookie_page` text,
  `curlopt_page` text,
  `http_method_page` enum('get','post') NOT NULL DEFAULT 'get',
  `result` text,
  `created_at` int(11) DEFAULT NULL,
  `begin_parse_at` int(11) DEFAULT NULL,
  `end_parse_at` int(11) DEFAULT NULL,
  `table_page_urls` text,
  `column_table_page_urls` text,
  `start_table_page_urls` text,
  `length_table_page_urls` text,
  `table_fixing` int(1) DEFAULT NULL,
  `count_all_process` int(11) NOT NULL DEFAULT '0',
  `count_last_process` int(11) NOT NULL DEFAULT '0',
  `count_success_all_process` int(11) NOT NULL DEFAULT '0',
  `count_error_all_process` int(11) NOT NULL DEFAULT '0',
  `count_success_last_process` int(11) NOT NULL DEFAULT '0',
  `count_error_last_process` int(11) NOT NULL DEFAULT '0',
  `count_all_write` int(11) NOT NULL DEFAULT '0',
  `count_last_write` int(11) NOT NULL DEFAULT '0',
  `time_all_process` float NOT NULL DEFAULT '0',
  `time_last_process` float NOT NULL DEFAULT '0',
  `time_all_requests` float NOT NULL DEFAULT '0',
  `time_last_requests` float NOT NULL DEFAULT '0',
  `check_cron` int(1) NOT NULL DEFAULT '0',
  `count_import_all` int(11) NOT NULL DEFAULT '0',
  `count_import_last` int(11) NOT NULL DEFAULT '0',
  `count_import_success_all` int(11) NOT NULL DEFAULT '0',
  `count_import_error_all` int(11) NOT NULL DEFAULT '0',
  `count_import_success_last` int(11) NOT NULL DEFAULT '0',
  `count_import_error_last` int(11) NOT NULL DEFAULT '0',
  `amount_stream` int(11) DEFAULT NULL,
  `microtime_delay` int(11) DEFAULT NULL,
  `cp_all` float NOT NULL DEFAULT '0',
  `cp_last` float NOT NULL DEFAULT '0',
  `memory_all` float NOT NULL DEFAULT '0',
  `memory_last` float NOT NULL DEFAULT '0',
  `count_all_query_to_bd` int(11) NOT NULL DEFAULT '0',
  `count_last_query_to_bd` int(11) NOT NULL DEFAULT '0',
  `func_data_processing_list` tinytext,
  `func_data_processing_page` tinytext,
  `count_process` int(11) NOT NULL DEFAULT '0',
  `status_control_insert` int(2) NOT NULL DEFAULT '1',
  `fields_control_insert` tinytext,
  `proxy` tinytext,
  `func_valid_url_list` tinytext,
  `func_valid_url_page` tinytext,
  `inspect_duplicate_url_list` enum('yes','no') NOT NULL DEFAULT 'yes',
  `inspect_duplicate_url_page` enum('yes','no') NOT NULL DEFAULT 'yes',
  `time_process_life` float NOT NULL DEFAULT '0',
  `inspect_url_table` int(1) NOT NULL DEFAULT '1',
  `insert_type` int(2) NOT NULL DEFAULT '1',
  `import_rate` int(11) NOT NULL DEFAULT '0',
  `last_write_at` int(11) NOT NULL DEFAULT '0',
  `last_write_count` int(11) NOT NULL DEFAULT '0',
  `last_import_at` int(11) NOT NULL DEFAULT '0',
  `last_import_count` int(11) NOT NULL DEFAULT '0',
  `fields_in_table_for_transmission` tinytext,
  `default_values` text,
  `dom_library` int(1) NOT NULL DEFAULT '1',
  `visibility` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        \main\DatabasePerform::Execute('ALTER TABLE `wp_posts` ADD COLUMN `source_id` INT(11)');
        \main\DatabasePerform::Execute('ALTER TABLE `wp_posts` ADD COLUMN `parse_url` TINYTEXT');
    }

    public function uninstall()
    {
        if (\main\DatabasePerform::GetOne("SHOW TABLES LIKE '_sources'")) {
            foreach (\workup\record\SourceRecord::GetRows(0, 1000) as $source) {
                if (stripos($source->table_name, 'tbl_parser_') === 0) {
                    \main\DatabasePerform::Execute("DROP TABLE IF EXISTS `" . $source->table_name .
                        "`");
                }
            }
        }

        \main\DatabasePerform::Execute("DROP TABLE IF EXISTS `_sources`");
        \main\DatabasePerform::Execute('ALTER TABLE `wp_posts` DROP `source_id`');
        \main\DatabasePerform::Execute('ALTER TABLE `wp_posts` DROP `parse_url`');
    }

    public function init()
    {
        if (is_admin() && isset($_GET['cmd']) && preg_match("#^ajax#", $_GET['cmd'])) {
            \workup\controller\Controller::run();
            exit();
        }
    }

    public function admin_menu()
    {
        add_menu_page('Парсер', 'Парсер', 7, 'wp-parser', [$this, 'admin_menu_render'], \plugins_url
            ('wp-parser/assets/img/application.png'));
    }

    public function admin_menu_render()
    {
        \workup\controller\Controller::run();
    }

    public function admin_scripts()
    {
        foreach ($this->css_admin as $css) {
            wp_register_style('wp-parser', \plugins_url('wp-parser/' . $css));

        }
        foreach ($this->script_admin as $script) {
            wp_register_script('wp-parser', \plugins_url('wp-parser/' . $script));
        }

        wp_enqueue_style('wp-parser');
    }

    public function frontend_scripts()
    {
        foreach ($this->css_frontend as $css) {
            wp_register_style('wp-parser', \plugins_url('wp-parser/' . $css));
        }
        foreach ($this->script_frontend as $script) {
            wp_register_script('wp-parser', \plugins_url('wp-parser/' . $script));
        }

        wp_enqueue_style('wp-parser');
    }
}

App::instance();

namespace main;

class ErrorHandler
{
    // Закрытый конструктор, не позволяющий непосредственно
    // создавать объекты класса
    private function __construct()
    {
    }

    /* Выбираем метод ErrorHandler::Handler в качестве метода обработки ошибок */
    public static function SetHandler($errTypes = E_ALL)
    {
        return set_error_handler(array('\main\ErrorHandler', 'Handler'), $errTypes);
    }

    // Метод обработки ошибок
    public static function Handler($errNo, $errStr, $errFile, $errLine)
    {
        /* Первые два элемента массива трассировки :
        - ErrorHandler.GetBacktrace
        - ErrorHandler.Handler */
        $backtrace = ErrorHandler::GetBacktrace(2);

        // Сообщения об ошибках, которые будут выводиться, отправляться по
        // электронной почте или записываться в журнал
        $error_message = "\r\nОшибка: $errNo" . "\r\nТекст ошибки: $errStr" . "\r\nФайл: $errFile, Строка " .
            "$errLine, at " . date('F j, Y, g:i a') . "\r\nСтек:\r\n$backtrace\r\n\r\n";

        // Отправляем сообщения об ошибках, если \workup\App::config('SEND_ERROR_MAIL') равно true
        if (\workup\App::config('SEND_ERROR_MAIL') == true)
            error_log($error_message, 1, \workup\App::config('ADMIN_ERROR_MAIL'), "From: " . \workup\App::config('SENDMAIL_FROM') . "\r\nTo: " .
                \workup\App::config('ADMIN_ERROR_MAIL'));

        // Записываем сообщения в журнал, если \workup\App::config('LOG_ERRORS') равно true
        if (\workup\App::config('LOG_ERRORS') == true)
            error_log($error_message . PHP_EOL, 3, \workup\App::config('LOG_ERRORS_FILE'));

        /* Выполнение не прекращается при предупреждениях,
        если \workup\App::config('IS_WARNING_FATAL') равно false. Ошибки E_NOTICE и E_USER_NOTICE
        тоже не приводят к прекращению выполнения */
        if (($errNo == E_WARNING && \workup\App::config('IS_WARNING_FATAL') == false) || ($errNo == E_NOTICE ||
            $errNo == E_USER_NOTICE)) {
            // Если ошибка не фатальная...
            // Выводим сообщение, только если \workup\App::config('DEBUGGING') равно true
            if (\workup\App::config('DEBUGGING') == true)
                echo '<div class="error_box"><pre>' . $error_message . '</pre></div>';
        } else {
            // Если ошибка фатальная...
            // Выводим сообщение об ошибке
            if (\workup\App::config('DEBUGGING') == true)
                echo '<div class="error_box"><pre>' . $error_message . '</pre></div>';
            else {
                // Очищаем буфер вывода
                if (file_exists(\workup\App::config('SITE_ROOT') . "/workup/view/500.php")) {
                    // Загружаем страницу с сообщением об ошибке 500
                    include \workup\App::config('SITE_ROOT') . "/workup/view/500.php";
                } else {
                    header('HTTP/1.0 500 Internal Server Error');
                    echo '500 Internal Server Error';
                }
                exit();
            }

            // Stop processing the request
            exit();
        }
    }

    // Составляем список вызовов
    public static function GetBacktrace($irrelevantFirstEntries)
    {
        $s = '';
        $MAXSTRLEN = 64;
        $trace_array = debug_backtrace();

        for ($i = 0; $i < $irrelevantFirstEntries; $i++)
            array_shift($trace_array);
        $tabs = sizeof($trace_array) - 1;

        foreach ($trace_array as $arr) {
            $tabs -= 1;
            if (isset($arr['class']))
                $s .= $arr['class'] . '.';
            $args = array();
            if (!empty($arr['args']))
                foreach ($arr['args'] as $v) {
                    if (is_null($v))
                        $args[] = 'null';
                    elseif (is_array($v))
                        $args[] = 'Array[' . sizeof($v) . ']';
                    elseif (is_object($v))
                        $args[] = 'Object: ' . get_class($v);
                    elseif (is_bool($v))
                        $args[] = $v ? 'true' : 'false';
                    else {
                        $v = (string )@$v;
                        $str = htmlspecialchars(substr($v, 0, $MAXSTRLEN), ENT_QUOTES, 'UTF-8');
                        if (strlen($v) > $MAXSTRLEN)
                            $str .= '...';
                        $args[] = '"' . $str . '"';
                    }
                }
            $s .= $arr['function'] . '(' . implode(', ', $args) . ')';
            $line = (isset($arr['line']) ? $arr['line'] : 'unknown');
            $file = (isset($arr['file']) ? $arr['file'] : 'unknown');
            $s .= sprintf(' # line %4d, file: %s', $line, $file);
            $s .= "\r\n";
        }
        return $s;
    }
}

namespace main;

class CurlExec
{
    // Стек ссылок
    public $UrlStack = array();

    private $key_stack = 0;

    // Опции по умочанию
    public $OptionsDefault = array(
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        );

    private $_MaxConnect = 3;
    private $_i = 0;
    private $_active = null;
    private $_status = false;
    private $_mh = null;
    private $_mrc = null;
    private $_ch = null;
    private $_CallbackFunction = null;
    private $_HandleStack = array();
    private $_Handle = null;

    private $_microtime_delay;

    private $proxy = [];
    private $i_proxy = 0;
    private $count_proxy = 0;

    private static $is_php_7 = null;

    private $objParseSource = null;

    public function __construct($options_default = null, $function_default = null, $microtime_delay = null,
        $proxy = null)
    {
        if ($options_default && is_array($options_default)) {
            $this->OptionsDefault = $options_default;
        }

        if ($function_default && ((is_string($function_default) && function_exists($function_default)) || is_array($function_default))) {
            $this->_CallbackFunction = $function_default;
        }

        if (!empty($microtime_delay) && $microtime_delay > 0) {
            $this->_microtime_delay = intval($microtime_delay);
        }

        if ($proxy && is_array($proxy)) {
            foreach ($proxy as $key => $value) {
                $this->addProxy($value);
            }
        }

        $this->count_proxy = count($this->proxy);

        if ($this->count_proxy) {
            shuffle($this->proxy);
        }

        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            self::$is_php_7 = true;
        } else {
            self::$is_php_7 = false;
        }
    }

    public function setObjParseSource($objParseSource)
    {
        if (is_object($objParseSource) || is_null($objParseSource)) {
            $this->objParseSource = $objParseSource;
        }
    }

    public function addProxy($proxy)
    {
        if (is_array($proxy)) {
            if (isset($proxy['proxy'])) {
                if (true || preg_match("#\d{1,3}\.\d{1,3}\.\d{1,3}.\d{1,3}(:\d{1,8})?#", $proxy['proxy'])) {
                    $this->proxy[] = $proxy;
                }
            }
        } else {
            if (true || preg_match("#\d{1,3}\.\d{1,3}\.\d{1,3}.\d{1,3}(:\d{1,8})?#", $proxy)) {
                $this->proxy[] = ['proxy' => trim($proxy)];
            }
        }
    }

    public function AddUrls($urls = array())
    {
        if (is_array($urls) && !isset($urls['url'])) {
            foreach ($urls as $url) {
                self::AddUrl($url);
            }
        } else {
            self::AddUrl($urls);
        }
    }

    // Добавляем url в стек.
    public function AddUrl($url)
    {
        $this->UrlStack[$this->key_stack++] = $this->prepareUrl($url);
    }

    public function prepareUrl($url)
    {
        if (is_array($url)) {
            if (isset($url['url']) && self::is_url($url['url'])) {
                if (!isset($url['options']) || !is_array($url['options'])) {
                    $url['options'] = null;
                }

                if (!(isset($url['function']) && is_string($url['function']) && function_exists
                    ($url['function'])) && !(isset($url['function']) && 
                    is_array($url['function']) && 
                    isset($url['function'][0]) &&
                    isset($url['function'][1]) && 
                    method_exists(($url['function'][0] == '$this' && $this->
                    objParseSource ? $this->objParseSource : $url['function'][0]), $url['function'][1]))) {
                    $url['function'] = null;
                }

                return $url;
            }
        } else {
            if (self::is_url($url)) {
                return array(
                    'url' => $url,
                    'options' => null,
                    'function' => null);
            }
        }
    }

    // Валидация url
    public function is_url($url)
    {
        return true;

        $chars = "a-zA-Z0-9АаБбВвГгҐґДдЕеЄєЭэЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЮюЯяЬьЪъёЫы";

        if (preg_match("#((http|https):\/\/|www\.)([" . $chars . "][" . $chars .
            "_-]*(?:.[" . $chars . "][" . $chars . "@\#$%&*().:;_-]*\/{0,1})+):?(d+)?\/?#Diu",
            $url)) {
            return true;
        } else {
            return false;
        }
    }

    // Запрашиваем все страницы паралельными потоками.
    public function ExecuteMulti($max_connect = null)
    {
        if ($max_connect) {
            $this->_MaxConnect = intval($max_connect);
        }

        if (!$this->_mh && !$this->_active && !$this->_ch && !$this->_status) {
            $this->_status = true;

            // 1. множественный обработчик
            $this->_mh = curl_multi_init();

            // 2. добавляем множество URL
            $this->fillMultiStack();

            // 3. инициализация выполнения
            $this->MultiExec();

            // 4. основной цикл
            while ($this->_active && $this->_mrc == CURLM_OK) {
                curl_multi_select($this->_mh);

                // 5. если всё прошло успешно
                if (true) { // curl_multi_select($mh) != -1 Не работает на некоторых версия php. Всегда возвращает -1

                    // 6. делаем дело
                    $this->MultiExec();

                    // 7. если есть инфа?
                    if ($mhinfo = curl_multi_info_read($this->_mh)) {
                        // это значит, что запрос завершился

                        // 8. извлекаем инфу
                        $chinfo = curl_getinfo($mhinfo['handle']);

                        $chdata = curl_multi_getcontent($mhinfo['handle']); // get results

                        $function = null;

                        $url = array();

                        $keyHandleStack = null;

                        foreach ($this->_HandleStack as $keyHandle => $valueHandle) {
                            if ($valueHandle['ch'] === $mhinfo['handle']) {
                                $keyHandleStack = $keyHandle;
                            }
                        }

                        if (!is_null($keyHandleStack)) {
                            $key = $this->_HandleStack[$keyHandleStack]['i'];

                            if (isset($this->UrlStack[$key])) {
                                $url = $this->UrlStack[$key];
                                if ($this->UrlStack[$key]['function']) {
                                    $function = $this->UrlStack[$key]['function'];
                                }

                                unset($this->UrlStack[$key]);
                            } else {
                                $url = array();
                            }

                            unset($this->_HandleStack[$keyHandleStack]);
                        }

                        if (!$function && $this->_CallbackFunction) {
                            $function = $this->_CallbackFunction;
                        }

                        if ($function && is_string($function) && function_exists($function)) {
                            $function(array_replace($url, array('info' => $chinfo, 'data' => $chdata)));
                        } elseif (is_array($function)) {
                            if ($function[0] == '$this') {
                                $this->objParseSource->{$function[1]}(array_replace($url, array('info' => $chinfo,
                                        'data' => $chdata)));
                            } else {
                                if (self::$is_php_7) {
                                    $callback = $function[0] . '::' . $function[1];
                                    $callback(array_replace($url, array('info' => $chinfo, 'data' => $chdata)));
                                } else {
                                    $function[0]::$function[1](array_replace($url, array('info' => $chinfo, 'data' =>
                                            $chdata)));
                                }
                            }
                        }

                        // 12. чистим за собой
                        curl_multi_remove_handle($this->_mh, $mhinfo['handle']); // в случае зацикливания, закомментируйте данный вызов
                        curl_close($mhinfo['handle']);

                        // 13. добавляем новый url и продолжаем работу
                        if ($this->fillMultiStack() > 0) {
                            $this->MultiExec();
                        }
                    }
                }
            }

            $this->_status = false;

            // 14. завершение
            self::StopMultiCurl();
        }
    }

    private function fillMultiStack()
    {
        $count = 0;

        for ($i = 0; $i < $this->_MaxConnect; $i++) {
            if (count($this->_HandleStack) < $this->_MaxConnect) {
                if ($this->AddUrlToMultiHandle()) {
                    $count++;
                }
            } else {
                break;
            }
        }

        return $count;
    }

    // Запуск дескрипторов стека
    private function MultiExec()
    {
        if ($this->_mh) {
            do {
                $this->_mrc = curl_multi_exec($this->_mh, $this->_active);
            } while ($this->_mrc == CURLM_CALL_MULTI_PERFORM);
        } else {
            self::StopMultiCurl();
        }

    }

    // Добавляем ссылку на выполнение
    private function AddUrlToMultiHandle()
    {
        // если у нас есть ещё url, которые нужно достать
        if ($this->_mh && isset($this->UrlStack[$this->_i]) && isset($this->UrlStack[$this->
            _i]['url'])) {
            // новый curl обработчик
            $ch = curl_init();

            $this->_HandleStack[] = array('i' => $this->_i, 'ch' => $ch);

            $options = null;

            if (isset($this->UrlStack[$this->_i]['options'])) {
                $options = array_replace($this->OptionsDefault, $this->UrlStack[$this->_i]['options']);
            } else {
                $options = $this->OptionsDefault;
            }

            if ($this->count_proxy) {
                if ($this->i_proxy >= $this->count_proxy) {
                    $this->i_proxy = 0;
                }

                if (isset($this->proxy[$this->i_proxy])) {
                    $options[CURLOPT_PROXY] = $this->proxy[$this->i_proxy]['proxy'];

                    if (isset($this->proxy[$this->i_proxy]['userpwd'])) {
                        $options[CURLOPT_PROXYUSERPWD] = $this->proxy[$this->i_proxy]['userpwd'];
                    }

                    if (isset($this->proxy[$this->i_proxy]['useragent'])) {
                        $options[CURLOPT_USERAGENT] = $this->proxy[$this->i_proxy]['useragent'];
                    }

                    if (isset($this->proxy[$this->i_proxy]['type'])) {
                        switch ($this->proxy[$this->i_proxy]['type']) {
                            case 'SOCKS5':
                                $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
                                break;
                            case 'IPv6':
                                break;
                        }
                    }
                }

                $this->i_proxy++;
            }

            $options[CURLOPT_URL] = $this->UrlStack[$this->_i]['url'];
            $options[CURLOPT_RETURNTRANSFER] = 1;

            curl_setopt_array($ch, $options);

            curl_multi_add_handle($this->_mh, $ch);

            // переходим на следующий url
            $this->_i++;

            return true;
        } else {
            // добавление новых URL завершено
            return false;
        }
    }

    // Очищаем стек
    public function ClearStack()
    {
        $this->UrlStack = array();
        $this->key_stack = 0;
        $this->_HandleStack = array();
        $this->_i = 0;
        $this->_active = null;
        $this->_mh = null;
        $this->_mrc;

        $this->_ch = null;
        $this->_Handle = null;
        $this->_status = false;
    }

    // Закрывает набор cURL дескрипторов
    public function StopMultiCurl()
    {
        if ($this->_mh) {
            foreach ($this->_HandleStack as $key => $value) {
                curl_multi_remove_handle($this->_mh, $value['ch']); // в случае зацикливания, закомментируйте данный вызов
                curl_close($value['ch']);
            }

            curl_multi_close($this->_mh);
        }

        $this->_active = false;
        $this->_mh = null;
        $this->_mrc = null;
        $this->ClearStack();
    }

    // Запрашиваем страницы последовательно
    public function Execute($count_connect = 1)
    {
        if ($count_connect > 1) {
            $this->ExecuteMulti($count_connect);
            return;
        }

        if (!$this->_ch && !$this->_status && !$this->_mh && !$this->_active) {
            $this->_status = true;

            while ($this->_status && isset($this->UrlStack[$this->_i]) && isset($this->
                UrlStack[$this->_i]['url'])) {
                if ($this->_status) {
                    if ($this->_microtime_delay) {
                        usleep($this->_microtime_delay);
                    }

                    $this->_ch = curl_init();

                    $this->_Handle = array('i' => $this->_i, 'ch' => $this->_ch);

                    $options = null;

                    if (isset($this->UrlStack[$this->_i]['options'])) {
                        $options = array_replace($this->OptionsDefault, $this->UrlStack[$this->_i]['options']);
                    } else {
                        $options = $this->OptionsDefault;
                    }

                    if ($this->count_proxy) {
                        if ($this->i_proxy >= $this->count_proxy) {
                            $this->i_proxy = 0;
                        }

                        if (isset($this->proxy[$this->i_proxy])) {
                            $options[CURLOPT_PROXY] = $this->proxy[$this->i_proxy]['proxy'];

                            if (isset($this->proxy[$this->i_proxy]['userpwd'])) {
                                $options[CURLOPT_PROXYUSERPWD] = $this->proxy[$this->i_proxy]['userpwd'];
                            }

                            if (isset($this->proxy[$this->i_proxy]['useragent'])) {
                                $options[CURLOPT_USERAGENT] = $this->proxy[$this->i_proxy]['useragent'];
                            }

                            if (isset($this->proxy[$this->i_proxy]['type'])) {
                                switch ($this->proxy[$this->i_proxy]['type']) {
                                    case 'SOCKS5':
                                        $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
                                        break;
                                    case 'IPv6':
                                        break;
                                }
                            }
                        }

                        $this->i_proxy++;
                    }

                    $options[CURLOPT_URL] = $this->UrlStack[$this->_i]['url'];
                    $options[CURLOPT_RETURNTRANSFER] = 1;

                    curl_setopt_array($this->_ch, $options);

                    curl_exec($this->_ch);

                    $chinfo = curl_getinfo($this->_ch);

                    $chdata = curl_multi_getcontent($this->_ch); // get results

                    $function = null;

                    if ($this->UrlStack[$this->_i]['function']) {
                        $function = $this->UrlStack[$this->_i]['function'];
                    }

                    if (!$function && $this->_CallbackFunction) {
                        $function = $this->_CallbackFunction;
                    }

                    if ($function && is_string($function) && function_exists($function)) {
                        $function(array_replace($this->UrlStack[$this->_i], array('info' => $chinfo,
                                'data' => $chdata)));
                    } elseif (is_array($function)) {
                        if ($function[0] == '$this') {
                            $this->objParseSource->{$function[1]}(array_replace($this->UrlStack[$this->_i],
                                array('info' => $chinfo, 'data' => $chdata)));
                        } else {
                            if (self::$is_php_7) {
                                $callback = $function[0] . '::' . $function[1];
                                $callback(array_replace($this->UrlStack[$this->_i], array('info' => $chinfo,
                                        'data' => $chdata)));
                            } else {
                                $function[0]::$function[1](array_replace($this->UrlStack[$this->_i], array('info' =>
                                        $chinfo, 'data' => $chdata)));
                            }
                        }
                    }

                    curl_close($this->_ch);

                    unset($this->UrlStack[$this->_i]);

                    $this->_ch = null;

                    $this->_Handle = null;

                    $this->_i++;
                } else {
                    break;
                }
            }

            self::StopCurl();
        }
    }

    // Останавливаем выполнение curl
    public function StopCurl()
    {
        if ($this->_ch) {
            curl_close($this->_ch);
        }

        $this->_ch = null;
        $this->_Handle = null;
        $this->_status = false;
        $this->ClearStack();
    }

    // Останавливаем выполнение
    public function Stop()
    {
        $this->StopCurl();
        $this->StopMultiCurl();
        $this->ClearStack();
    }

    // Выполняется ли пороцесс
    public function isActive()
    {
        if ($this->_active) {
            return true;
        }

        if ($this->_status) {
            return true;
        }

        return false;
    }

    // Запрашиваем страницу
    public function Exec($url)
    {
        $url = $this->prepareUrl($url);

        if ($this->_microtime_delay) {
            usleep($this->_microtime_delay);
        }

        $ch = curl_init();

        $options = null;

        if (isset($url['options'])) {
            $options = array_replace($this->OptionsDefault, $url['options']);
        } else {
            $options = $this->OptionsDefault;
        }

        if ($this->count_proxy) {
            if ($this->i_proxy >= $this->count_proxy) {
                $this->i_proxy = 0;
            }

            if (isset($this->proxy[$this->i_proxy])) {
                $options[CURLOPT_PROXY] = $this->proxy[$this->i_proxy]['proxy'];

                if (isset($this->proxy[$this->i_proxy]['userpwd'])) {
                    $options[CURLOPT_PROXYUSERPWD] = $this->proxy[$this->i_proxy]['userpwd'];
                }

                if (isset($this->proxy[$this->i_proxy]['useragent'])) {
                    $options[CURLOPT_USERAGENT] = $this->proxy[$this->i_proxy]['useragent'];
                }

                if (isset($this->proxy[$this->i_proxy]['type'])) {
                    switch ($this->proxy[$this->i_proxy]['type']) {
                        case 'SOCKS5':
                            $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
                            break;
                        case 'IPv6':
                            break;
                    }
                }
            }

            $this->i_proxy++;
        }

        $options[CURLOPT_URL] = $url['url'];
        $options[CURLOPT_RETURNTRANSFER] = 1;

        curl_setopt_array($ch, $options);

        curl_exec($ch);

        $chinfo = curl_getinfo($ch);

        $chdata = curl_multi_getcontent($ch); // get results

        curl_close($ch);

        return ['info' => $chinfo, 'data' => $chdata, ];
    }
}

namespace main;

use phpQuery;

class ParseSource
{
    const AMOUNT_DO_SAVE = 50;

    const COUNT_DO_CHECK = 25;

    public $COUNT_DO_INSERT = 25;

    public $SAVE_STATISTICS = true;

    public $DOM_LIBRARY = "simple_html_dom"; // simple_html_dom phpQuery

    private $saveRecord = 'SourceDataRecord';

    private $urls_for_check_status_1 = [];
    private $urls_for_check_status_2 = [];

    private $ids_for_check_status_1 = [];
    private $ids_for_check_status_2 = [];
    
    private $insert_data = [];

    public $begin_time;
    public $time_limit;

    private $cp_all_initial;
    private $cp_last_initial;

    private $memory_all_initial;
    private $memory_last_initial;

    private $count_all_query_to_bd_initial;
    private $count_last_query_to_bd_initial;

    private $iteration_request_do_save_source = 0;

    public $urls_page = [];
    private $count_urls_page = 0;

    private $source = null;
    private $source_id = null;

    private $result = [];

    private $status_target_list = false;
    private $status_target_page = false;

    public $urls_list = [];
    private $count_urls_list = 0;
    private $target_list_element = [];
    private $target_list_value = [];
    private $target_list_url = [];
    private $target_list_next = [];
    private $begin_page = [];
    private $end_page = [];
    private $key_page = [];
    private $data_list = [];
    private $cookie_list = [];
    private $curlopt_list = [];
    private $http_method_list = [];
    private $target_page_element = [];
    private $target_page_value = [];
    private $data_page = [];
    private $cookie_page = [];
    private $curlopt_page = [];
    private $http_method_page = [];

    private $base_href = null;

    private $_curl = null;

    private $processFixRow = false;
    private $inspectUrlTable = null;

    private $options_default = [];
    private $options_for_list = [];
    private $options_for_page = [];

    private $path_files_dir = null;

    public $func_status_url_page = null;

    public $func_before_process_status_url_page = null;

    public $func_data_processing_list = null;

    public $func_data_processing_page = null;

    public $func_valid_url_list = null;

    public $func_valid_url_page = null;

    public $inspect_duplicate_url_list = null;

    public $inspect_duplicate_url_page = null;

    private $proxy = [];

    private $insert_type = 1;

    private $control_url_in_bd = false;

    private $memory = [];

    public function addUrlList($url = null, $key = null)
    {
        if (!isset($url['url'])) {
            return false;
        }

        if ($this->source->status_control_insert == '2') {
            if ($this->isUrlInDb($url['url']) === true) {
                return false;
            }
        }

        if ($this->controlUrlInDbExist($url['url'])) {
            if ($this->func_valid_url_list) {
                $func = $this->func_valid_url_list;

                $prepare_url = $func($url['url']);

                if (is_bool($prepare_url)) {
                    if (!$prepare_url) {
                        return false;
                    }
                } elseif (is_string($prepare_url)) {
                    $url['url'] = $prepare_url;
                } elseif (is_array($prepare_url)) {
                    $url = $prepare_url;
                }
            }

            if (is_array($url) && isset($url['url']) && isset($url['function']) && !empty($url['url']) &&
                !empty($url['function'])) {
                $key_md5 = md5($url['url']);

                $url['status'] = 0;

                if ($key) {
                    if (!isset($this->urls_list[$key])) {
                        if (!$this->inspect_duplicate_url_list) {
                            if ($this->_curl && $this->_curl->isActive()) {
                                $this->_curl->AddUrl($url);
                            } else {
                                $url['key'] = $key;
                                $this->urls_list[$key] = $url;
                            }
                        } else {
                            $this->urls_list[$key] = $url;

                            if ($this->_curl && $this->_curl->isActive()) {
                                $this->_curl->AddUrl($url);
                                $this->urls_list[$key]['status'] = 1;
                            }
                        }

                        $this->count_urls_list++;
                        return true;
                    }
                } elseif (!isset($this->urls_list[$key_md5])) {
                    if (!$this->inspect_duplicate_url_list) {
                        if ($this->_curl && $this->_curl->isActive()) {
                            $this->_curl->AddUrl($url);
                        } else {
                            $url['key'] = $key_md5;
                            $this->urls_list[$key_md5] = $url;
                        }
                    } else {
                        $this->urls_list[$key_md5] = $url;

                        if ($this->_curl && $this->_curl->isActive()) {
                            $this->_curl->AddUrl($url);
                            $this->urls_list[$key_md5]['status'] = 1;
                        }
                    }

                    $this->count_urls_list++;
                    return true;
                }
            }
        }

        return false;
    }

    public function addUrlPage($url = null)
    {
        if (!isset($url['url'])) {
            return false;
        }

        if ($this->source->status_control_insert == '2') {
            if ($this->isUrlInDb($url['url']) === true) {
                return false;
            }
        }

        if ($this->controlUrlInDbExist($url['url'])) {
            if ($this->func_valid_url_page) {
                $func = $this->func_valid_url_page;

                $prepare_url = $func($url['url']);

                if (is_bool($prepare_url)) {
                    if (!$prepare_url) {
                        return false;
                    }
                } elseif (is_string($prepare_url)) {
                    $url['url'] = $prepare_url;
                } elseif (is_array($prepare_url)) {
                    $url = $prepare_url;
                }
            }

            $key_md5 = md5($url['url']);
            $url['status'] = 0;

            if (is_array($url) && isset($url['url']) && isset($url['function']) && !empty($url['url']) &&
                !empty($url['function']) && !isset($this->urls_page[$key_md5])) {
                if (!$this->inspect_duplicate_url_page) {
                    if ($this->_curl && $this->_curl->isActive()) {
                        $this->_curl->AddUrl($url);
                    } else {
                        $url['key'] = $key_md5;
                        $this->urls_page[$key_md5] = $url;
                    }
                } else {
                    $this->urls_page[$key_md5] = $url;

                    if ($this->_curl && $this->_curl->isActive()) {
                        $this->_curl->AddUrl($url);
                        $this->urls_page[$key_md5]['status'] = 1;
                    }
                }

                $this->count_urls_page++;
                return true;
            }
        }

        return false;
    }

    public function addListUrls($urls = [])
    {
        if (is_array($urls)) {
            foreach ($urls as $url) {
                $this->addListUrl($url);
            }
        }
    }

    public function addListUrl($url)
    {
        if (is_array($url)) {
            $url['function'] = ['$this', 'parseList'];
            $url['options'] = $this->options_for_list;
            $this->addUrlList($url);
        } else {
            $this->addUrlList(['url' => $url, 'function' => ['$this', 'parseList'],
                'options' => $this->options_for_list, ]);
        }
    }

    public function addPageUrls($urls = [])
    {
        if (is_array($urls)) {
            foreach ($urls as $url) {
                $this->addPageUrl($url);
            }
        }
    }

    public function addPageUrl($url)
    {
        if (is_array($url)) {
            $url['function'] = ['$this', 'parsePage'];
            $url['options'] = $this->options_for_page;
            $this->addUrlPage($url);
        } else {
            $this->addUrlPage(['url' => $url, 'function' => ['$this', 'parsePage'],
                'options' => $this->options_for_page, ]);
        }
    }

    public function __construct(\workup\model\Source $obj)
    {
        \workup\record\SourceDataRecord::setStatusControl($obj->status_control_insert);
        \workup\record\SourceDataRecord::setFieldsControl($obj->fields_control_insert);

        if (\workup\App::config('WORDPRESS')) {
            if (stripos($obj->table_name, 'tbl_parser_') === 0) {
                $this->saveRecord = 'SourceDataRecord';
            } elseif ($obj->table_name == 'wp_posts') {
                $this->saveRecord = 'SourceDataRecordWordpress';
            } else {
                $this->saveRecord = null;
            }
        }

        $this->begin_time = time();

        $obj->count_process++;

        $obj->cp_last = 0;
        $obj->memory_last = 0;

        $obj->count_last_query_to_bd = 0;

        if ($obj->dom_library == 1) {
            $this->DOM_LIBRARY = 'simple_html_dom';
        } elseif ($obj->dom_library == 2) {
            $this->DOM_LIBRARY = 'phpQuery';
        } else {
            $this->DOM_LIBRARY = 'simple_html_dom';
        }

        if ($obj->insert_type > 1) {
            $this->insert_type = 2;
            $this->COUNT_DO_INSERT = $obj->insert_type;
        } else {
            $this->insert_type = 1;
        }

        if ($obj->control_url_in_bd == '1') {
            $this->controlUrlInDbInit();
            $this->control_url_in_bd = true;
        } else {
            $this->control_url_in_bd = false;
        }

        $this->cp_all_initial = $obj->cp_all;
        $this->cp_last_initial = $obj->cp_last;
        $this->memory_all_initial = $obj->memory_all;
        $this->memory_last_initial = $obj->memory_last;

        $this->count_all_query_to_bd_initial = $obj->count_all_query_to_bd;
        $this->count_last_query_to_bd_initial = $obj->count_last_query_to_bd;

        $this->path_files_dir = \workup\App::config('SITE_ROOT') . '/files';

        $this->options_default = [
            CURLOPT_RETURNTRANSFER => 1, 
            CURLOPT_TIMEOUT => 25,
            CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 YaBrowser/17.6.1.744 Yowser/2.5 Safari/537.36",
            CURLOPT_FOLLOWLOCATION => 1, 
            CURLOPT_MAXREDIRS => 4, 
            CURLOPT_COOKIEJAR => \workup\App::config('DIR_TMP') . "/COOKIES", 
            CURLOPT_COOKIEFILE => \workup\App::config('DIR_TMP') . "/COOKIES", 
        ];

        $this->source = $obj;
        $this->source_id = $obj->id;

        $this->result = $obj->_result;

        $this->result['inf_last_request'] = null;
        $this->result['inf_illegal_request'] = null;

        $this->target_list_element = $obj->_target_list_element;
        $this->target_list_value = $obj->_target_list_value;
        $this->target_list_url = $obj->_target_list_url;
        $this->target_list_next = $obj->_target_list_next;
        $this->begin_page = $obj->begin_page;
        $this->end_page = $obj->end_page;
        $this->key_page = $obj->key_page;
        $this->data_list = $obj->_data_list;
        $this->cookie_list = $obj->_cookie_list;
        $this->curlopt_list = $obj->_curlopt_list;
        $this->http_method_list = $obj->http_method_list;
        $this->target_page_element = $obj->_target_page_element;
        $this->target_page_value = $obj->_target_page_value;
        $this->data_page = $obj->_data_page;
        $this->cookie_page = $obj->_cookie_page;
        $this->curlopt_page = $obj->_curlopt_page;
        $this->http_method_page = $obj->http_method_page;

        if ($this->source->processForTable && ($this->source->table_fixing == 1 || $this->
            source->table_fixing == 2)) {
            $this->processFixRow = $this->source->table_fixing;
        }
        $this->inspectUrlTable = $this->source->inspect_url_table;

        if ($this->source->time_limit) {
            $this->time_limit = $this->source->time_limit;
        }

        if (!$this->status_target_list) {
            foreach ($this->target_list_element as $target) {
                $this->status_target_list = true;
                break;
            }
        }
        if (!$this->status_target_list) {
            foreach ($this->target_list_next as $target) {
                if ($target['attribute'] != 'regulare' && $target['attribute'] !=
                    'regulare_strip_tags') {
                    $this->status_target_list = true;
                }
                break;
            }
        }
        if (!$this->status_target_list) {
            foreach ($this->target_list_url as $target) {
                if ($target['attribute'] != 'regulare' && $target['attribute'] !=
                    'regulare_strip_tags') {
                    $this->status_target_list = true;
                }
                break;
            }
        }
        if (!$this->status_target_list) {
            foreach ($this->target_list_value as $target) {
                if ($target['attribute'] != 'regulare' && $target['attribute'] !=
                    'regulare_strip_tags') {
                    $this->status_target_list = true;
                }
                break;
            }
        }

        if (!$this->status_target_page) {
            foreach ($this->target_page_element as $target) {
                $this->status_target_page = true;
                break;
            }
        }
        if (!$this->status_target_page) {
            foreach ($this->target_page_value as $target) {
                if ($target['attribute'] != 'regulare' && $target['attribute'] !=
                    'regulare_strip_tags') {
                    $this->status_target_page = true;
                }

                break;
            }
        }

        if ($obj->func_data_processing_list && is_callable((stripos($obj->
            func_data_processing_list, '\\') == '0' ? '\main\\' : '') . $obj->
            func_data_processing_list) && function_exists((stripos($obj->
            func_data_processing_list, '\\') == '0' ? '\main\\' : '') . $obj->
            func_data_processing_list)) {
            $this->func_data_processing_list = (stripos($obj->func_data_processing_list, '\\') ==
                '0' ? '\main\\' : '') . $obj->func_data_processing_list;
        }

        if ($obj->func_data_processing_page && is_callable((stripos($obj->
            func_data_processing_page, '\\') == '0' ? '\main\\' : '') . $obj->
            func_data_processing_page) && function_exists((stripos($obj->
            func_data_processing_page, '\\') == '0' ? '\main\\' : '') . $obj->
            func_data_processing_page)) {
            $this->func_data_processing_page = (stripos($obj->func_data_processing_page, '\\') ==
                '0' ? '\main\\' : '') . $obj->func_data_processing_page;
        }
        
        if ($obj->func_valid_url_list && is_callable((stripos($obj->
            func_valid_url_list, '\\') == '0' ? '\main\\' : '') . $obj->
            func_valid_url_list) && function_exists((stripos($obj->
            func_valid_url_list, '\\') == '0' ? '\main\\' : '') . $obj->
            func_valid_url_list)) {
            $this->func_valid_url_list = (stripos($obj->func_valid_url_list, '\\') ==
                '0' ? '\main\\' : '') . $obj->func_valid_url_list;
        }

        if ($obj->func_valid_url_page && is_callable((stripos($obj->
            func_valid_url_page, '\\') == '0' ? '\main\\' : '') . $obj->
            func_valid_url_page) && function_exists((stripos($obj->
            func_valid_url_page, '\\') == '0' ? '\main\\' : '') . $obj->
            func_valid_url_page)) {
            $this->func_valid_url_page = (stripos($obj->func_valid_url_page, '\\') ==
                '0' ? '\main\\' : '') . $obj->func_valid_url_page;
        }

        if ($obj->inspect_duplicate_url_list == 'yes') {
            $this->inspect_duplicate_url_list = true;
        } else {
            $this->inspect_duplicate_url_list = false;
        }

        if ($obj->inspect_duplicate_url_page == 'yes') {
            $this->inspect_duplicate_url_page = true;
        } else {
            $this->inspect_duplicate_url_page = false;
        }

        $options = [];

        if ($this->http_method_list == 'post') {
            $options[CURLOPT_POST] = 1;

            if (!empty($this->data_list)) {
                $params = [];

                foreach ($this->data_list as $var) {
                    $params[$var['key']] = $var['value'];
                }

                $options[CURLOPT_POSTFIELDS] = http_build_query($params);
            }
        }

        if (!empty($this->cookie_list)) {
            $cookies = [];

            foreach ($this->cookie_list as $var) {
                $cookies[] = $var['key'] . '=' . $var['value'];
            }

            $options[CURLOPT_COOKIE] = implode('; ', $cookies);
        }

        if (!empty($this->curlopt_list)) {
            foreach ($this->curlopt_list as $var) {
                if (preg_match("#^CURLOPT_#", $var['key']) && !empty($var['value'])) {
                    if (defined($var['key'])) {
                        $options[constant($var['key'])] = $var['value'];
                    }
                }
            }
        }

        $this->options_for_list = $options;

        $options = [];

        if ($this->http_method_page == 'post') {
            $options[CURLOPT_POST] = 1;

            if (!empty($this->data_page)) {
                $params = [];

                foreach ($this->data_page as $var) {
                    $params[$var['key']] = $var['value'];
                }

                $options[CURLOPT_POSTFIELDS] = http_build_query($params);
            }
        }

        if (!empty($this->cookie_page)) {
            $cookies = [];

            foreach ($this->cookie_page as $var) {
                $cookies[] = $var['key'] . '=' . $var['value'];
            }

            $options[CURLOPT_COOKIE] = implode('; ', $cookies);
        }

        if (!empty($this->curlopt_page)) {
            foreach ($this->curlopt_page as $var) {
                if (preg_match("#^CURLOPT_#", $var['key']) && !empty($var['value'])) {
                    if (defined($var['key'])) {
                        $options[constant($var['key'])] = $var['value'];
                    }
                }
            }
        }

        $this->options_for_page = $options;

        $options = null;

        $this->urls_list = [];
        $this->urls_page = [];

        foreach ($obj->_urls as $url) {
            $this->addUrlList(['url' => $url, 'function' => ['$this', 'parseList'],
                'options' => $this->options_for_list, ]);
        }

        foreach ($this->urls_list as $url) {
            $this->base_href = $url;

            break;
        }

        $obj->_urls = [];

        foreach ($obj->_page_urls as $url) {
            $this->addUrlPage(['url' => $url, 'function' => ['$this', 'parsePage'],
                'options' => $this->options_for_page, ]);
        }

        $obj->_page_urls = [];

        if ($this->begin_page && $this->end_page && $this->key_page) {
            $url_base = $this->base_href;

            if ($url_base) {
                for ($i = intval($this->begin_page); $i <= intval($this->end_page); $i++) {
                    $url = ['url' => $url_base['url'], 'function' => ['$this', 'parseList'],
                        'options' => $this->options_for_list, ];

                    $params = [];

                    if (!empty($this->data_list)) {
                        foreach ($this->data_list as $var) {
                            $params[$var['key']] = $var['value'];
                        }
                    }

                    $params[$this->key_page] = $i;

                    if ($this->http_method_list == 'post') {
                        $url['options'] = array_replace($url['options'], [CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => http_build_query($params), ]);
                    } else {
                        $parse_url = parse_url($url['url']);

                        if (isset($parse_url['query'])) {
                            $url['url'] = $url['url'] . "&" . http_build_query($params);
                        } else {
                            $url['url'] = $url['url'] . "?" . http_build_query($params);
                        }
                    }

                    $this->addUrlList($url, md5($url['url'] . $i));
                }
            }
        }

        $this->result['count_urls_list'] = $this->count_urls_list;
        $this->result['count_urls_page'] = $this->count_urls_page;
        $this->result['inf_illegal_request'] = null;

        $this->source->result = serialize($this->result);

        if ($this->source instanceof \workup\model\SourceParse) {
            $this->SAVE_STATISTICS = false;
        }

        if ($this->SAVE_STATISTICS) {
            $this->source->save();
        }
    }

    public function setFixRow($table_fixing)
    {
        if ($table_fixing == 1 || $table_fixing == 2) {
            $this->processFixRow = $table_fixing;
        }
    }

    public function processLists()
    {
        $proxy = null;

        if ($this->source->proxy && !empty($this->source->proxy)) {
            if (file_exists(\workup\App::config('SITE_ROOT') . '/proxy/' . $this->source->
                proxy)) {
                if (preg_match("#php$#", $this->source->proxy)) {
                    $proxy = include (\workup\App::config('SITE_ROOT') . '/proxy/' . $this->source->
                        proxy);
                } elseif (preg_match("#txt$#", $this->source->proxy)) {
                    $proxy = file(\workup\App::config('SITE_ROOT') . '/proxy/' . $this->source->
                        proxy);
                }

                if (!is_array($proxy)) {
                    $proxy = null;
                }
            }
        }

        $this->_curl = new CurlExec($this->options_default, null, $this->source->
            microtime_delay, $proxy);

        $this->_curl->setObjParseSource($this);

        foreach ($this->urls_list as $key => $url) {
            if ($url['status']) {
                continue;
            }

            $this->_curl->AddUrl($url);
            $this->urls_list[$key]['status'] = 1;
        }

        if (true || !$this->inspect_duplicate_url_list) {
            $this->urls_list = [];
        }

        if ($this->source->amount_stream > 0 && $this->source->amount_stream < 10) {
            if ($this->source->amount_stream == 1) {
                $this->_curl->Execute();
            } else {
                $this->_curl->ExecuteMulti($this->source->amount_stream);
            }
        } else {
            $this->_curl->ExecuteMulti(3);
        }

        $this->complete();
    }

    public function processPages()
    {
        $proxy = null;

        if ($this->source->proxy && !empty($this->source->proxy)) {
            if (file_exists(\workup\App::config('SITE_ROOT') . '/proxy/' . $this->source->
                proxy)) {
                if (preg_match("#php$#", $this->source->proxy)) {
                    $proxy = include (\workup\App::config('SITE_ROOT') . '/proxy/' . $this->source->
                        proxy);
                } elseif (preg_match("#txt$#", $this->source->proxy)) {
                    $proxy = file(\workup\App::config('SITE_ROOT') . '/proxy/' . $this->source->
                        proxy);
                }

                if (!is_array($proxy)) {
                    $proxy = null;
                }
            }
        }

        $this->_curl = new CurlExec($this->options_default, null, $this->source->
            microtime_delay, $proxy);

        $this->_curl->setObjParseSource($this);

        $func_before_process_status_url_page = $this->
            func_before_process_status_url_page;

        if (is_callable($func_before_process_status_url_page)) {
            foreach (array_chunk($this->urls_page, 20, true) as $urls) {
                foreach ($func_before_process_status_url_page($urls) as $key => $url) {
                    if ($url['status']) {
                        continue;
                    }

                    $this->_curl->AddUrl($url);

                    if (isset($this->urls_page[$key])) {
                        $this->urls_page[$key]['status'] = 1;
                    }
                }
            }
        } else {
            foreach ($this->urls_page as $key => $url) {
                if ($url['status']) {
                    continue;
                }

                $this->_curl->AddUrl($url);
                $this->urls_page[$key]['status'] = 1;
            }
        }

        if (true || !$this->inspect_duplicate_url_page) {
            $this->urls_page = [];
        }

        if ($this->source->amount_stream > 0 && $this->source->amount_stream < 10) {
            if ($this->source->amount_stream == 1) {
                $this->_curl->Execute();
            } else {
                $this->_curl->ExecuteMulti($this->source->amount_stream);
            }
        } else {
            $this->_curl->ExecuteMulti(3);
        }

        $this->complete();
    }

    public function parsePage($ans)
    {
        $this->stop();
        
        $tblvid = null;
        
        if (isset($ans['res']) && isset($ans['res']['tblvid'])) {
            $tblvid = $ans['res']['tblvid'];
            unset($ans['res']['tblvid']);
        }        

        if (true || !$this->inspect_duplicate_url_page) {
            if (isset($ans['key']) && isset($this->urls_page[$ans['key']])) {
                unset($this->urls_page[$ans['key']]);
            }
        }

        $this->source->count_all_process++;
        $this->source->count_last_process++;

        $begin_time = microtime(true);

        if (isset($ans['info'])) {
            $this->result['inf_last_request_page'] = $ans['info'];
        }

        if (is_array($ans['info']) && !(isset($ans['info']['http_code']) && $ans['info']['http_code'] ==
            200)) {
            $this->source->count_error_all_process++;
            $this->source->count_error_last_process++;
            $this->result['inf_illegal_request'] = $ans['info'];
        } elseif (is_array($ans['info']) && isset($ans['info']['http_code']) && $ans['info']['http_code'] ==
        200) {
            $this->source->count_success_all_process++;
            $this->source->count_success_last_process++;
            
            $this->source->request_time = $ans['info']['total_time'];
            $this->source->request_time_all = floatval($this->source->request_time_all) + floatval($ans['info']['total_time']);
        } else {
            $this->source->count_error_all_process++;
            $this->source->count_error_last_process++;
        }

        if (is_array($ans['info']) && isset($ans['info']['total_time'])) {
            $this->source->time_all_requests = $this->source->time_all_requests + $ans['info']['total_time'];
            $this->source->time_last_requests = $this->source->time_last_requests + $ans['info']['total_time'];
        }

        $base_url = null;

        if (isset($ans['data']) && !empty($ans['data'])) {
            $ans['data'] = preparation_html($ans['data']);
            if ($ans['info'] && $ans['info']['http_code'] == 200) {
                if ($this->DOM_LIBRARY == 'simple_html_dom' && isset($ans['info']['content_type'])) {
                    if (preg_match('#charset=([a-zA-Z0-9_-]+)#', $ans['info']['content_type'], $matches)) {
                        if (isset($matches[1])) {
                            $charset = trim(strtolower($matches[1]));
                            if ($charset && $charset != 'utf-8' && $charset != 'utf8') {
                                $ans['data'] = iconv($charset, 'utf-8', $ans['data']);
                            }
                        }
                    }
                }

                $data = $this->driverDom('new', $ans['data']);

                if ($data) {
                    foreach ($this->driverDom('find', $data, 'head base') as $obj) {
                        if ($this->driverDom('get', $obj, 'href')) {
                            $base_url = trim($this->driverDom('get', $obj, 'href'));

                            break;
                        }
                    }
                }

                if (empty($this->target_page_element)) {
                    $res = [];

                    foreach ($this->target_page_value as $target) {
                        $target_attribute = $target['attribute'];
                        if ($target_attribute == 'regulare' || $target_attribute ==
                            'regulare_strip_tags') {
                            $matches = null;
                            $html = $ans['data'];

                            //$html = str_replace(["\r", "\n"], '', $html);
                            //$html = preg_replace("#>\s*<#", "><", $html);
                            preg_match_all($target['phrase'], $html, $matches);
                            if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                is_array($matches[1]) && !empty($matches[1])) {
                                if ($target_attribute == 'regulare_strip_tags') {
                                    foreach ($matches[1] as $key => $value) {
                                        $matches[1][$key] = strip_tags($value);
                                    }
                                }

                                if (stripos($target['name'], '_download') > 0) {
                                    foreach ($matches[1] as $match) {
                                        $file = $this->downloadFile($target['name'], $match, $ans['url'], $base_url, $res);

                                        if ($file) {
                                            $res[$file['field']][] = $file['name'];
                                        }
                                    }
                                } else {
                                    $res[$target['name']] = $matches[1];
                                }
                            }
                        } elseif ($data) {
                            foreach ($this->driverDom('find', $data, $target['phrase']) as $obj) {
                                if (stripos($target['name'], '_download') > 0) {
                                    $file = $this->downloadFile($target['name'], $this->driverDom('get', $obj, $target_attribute),
                                        $ans['url'], $base_url, $res);

                                    if ($file) {
                                        $res[$file['field']][] = $file['name'];
                                    }
                                } else {
                                    if (($target_attribute == 'href' && $this->driverDom('tag', $obj) == 'a') || ($target_attribute ==
                                        'src' && $this->driverDom('tag', $obj) == 'img')) {
                                        $res[$target['name']][] = prepare_url($this->driverDom('get', $obj, $target_attribute),
                                            $ans['url'], $base_url);
                                    } else {
                                        $res[$target['name']][] = $this->driverDom('get', $obj, $target_attribute);
                                    }
                                }

                                $this->driverDom('clear', $obj);
                            }
                        }
                    }

                    foreach ($res as $key => $value) {
                        $res[$key] = preparation_data($value);
                    }

                    if (!empty($res)) {
                        if (isset($ans['res']) && is_array($ans['res']) && !empty($ans['res'])) {
                            $res = array_merge($ans['res'], $res);
                        }

                        $res['parse_url'] = $ans['url'];
                        if ($ans['url'] != $ans['info']['url']) {
                            $res['redirect_url'] = $ans['info']['url'];
                        }

                        $this->processingObjPage($res);

                        $this->saveData($res);
                    } elseif (isset($ans['res']) && is_array($ans['res']) && !empty($ans['res'])) {
                        $res = $ans['res'];

                        $res['parse_url'] = $ans['url'];
                        if ($ans['url'] != $ans['info']['url']) {
                            $res['redirect_url'] = $ans['info']['url'];
                        }

                        $this->processingObjPage($res);

                        $this->saveData($res);
                    }
                } elseif (true) {
                    foreach ($this->target_page_element as $target_page_element_attribute) {
                        if (preg_match("#^\##", $target_page_element_attribute) && preg_match("#\#$#", $target_page_element_attribute)) {
                            $matches_list = null;
                            $html = $ans['data'];
                            //$html = str_replace(["\r", "\n"], '', $html);
                            //$html = preg_replace("#>\s*<#", "><", $html);
                            preg_match_all($target_page_element_attribute, $html, $matches_list);
                            if ($matches_list && is_array($matches_list) && !empty($matches_list) && isset($matches_list[1]) &&
                                is_array($matches_list[1]) && !empty($matches_list[1])) {
                                foreach ($matches_list[1] as $match_list) {
                                    if ((trim($match_list))) {
                                        $element = $this->driverDom('new', $match_list);

                                        $res = [];

                                        foreach ($this->target_page_value as $target) {
                                            $target_attribute = $target['attribute'];
                                            if ($target_attribute == 'regulare' || $target_attribute ==
                                                'regulare_strip_tags') {
                                                $matches = null;
                                                $html = $match_list;
                                                //$html = str_replace(["\r", "\n"], '', $html);
                                                //$html = preg_replace("#>\s*<#", "><", $html);
                                                preg_match_all($target['phrase'], $html, $matches);
                                                if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                                    is_array($matches[1]) && !empty($matches[1])) {
                                                    if ($target_attribute == 'regulare_strip_tags') {
                                                        foreach ($matches[1] as $key => $value) {
                                                            $matches[1][$key] = strip_tags($value);
                                                        }
                                                    }
                                                    if (stripos($target['name'], '_download') > 0) {
                                                        foreach ($matches[1] as $match) {
                                                            $file = $this->downloadFile($target['name'], $match, $ans['url'], $base_url, $res);

                                                            if ($file) {
                                                                $res[$file['field']][] = $file['name'];
                                                            }
                                                        }
                                                    } else {
                                                        $res[$target['name']] = $matches[1];
                                                    }
                                                }
                                            } elseif ($element) {
                                                foreach ($this->driverDom('find', $element, $target['phrase']) as $obj) {
                                                    if (stripos($target['name'], '_download') > 0) {
                                                        $file = $this->downloadFile($target['name'], $this->driverDom('get', $obj, $target_attribute),
                                                            $ans['url'], $base_url, $res);

                                                        if ($file) {
                                                            $res[$file['field']][] = $file['name'];
                                                        }
                                                    } else {
                                                        if (($target_attribute == 'href' && $this->driverDom('tag', $obj) == 'a') || ($target_attribute ==
                                                            'src' && $this->driverDom('tag', $obj) == 'img')) {
                                                            $res[$target['name']][] = prepare_url($this->driverDom('get', $obj, $target_attribute),
                                                                $ans['url'], $base_url);
                                                        } else {
                                                            $res[$target['name']][] = $this->driverDom('get', $obj, $target_attribute);
                                                        }
                                                    }

                                                    $this->driverDom('clear', $obj);
                                                }
                                            }
                                        }

                                        foreach ($res as $key => $value) {
                                            $res[$key] = preparation_data($value);
                                        }

                                        if (!empty($res)) {
                                            if (isset($ans['res']) && is_array($ans['res']) && !empty($ans['res'])) {
                                                $res = array_merge($ans['res'], $res);
                                            }

                                            $res['parse_url'] = $ans['url'];
                                            if ($ans['url'] != $ans['info']['url']) {
                                                $res['redirect_url'] = $ans['info']['url'];
                                            }

                                            $this->processingObjPage($res);

                                            $this->saveData($res);
                                        } elseif (isset($ans['res']) && is_array($ans['res']) && !empty($ans['res'])) {
                                            $res = $ans['res'];

                                            $res['parse_url'] = $ans['url'];
                                            if ($ans['url'] != $ans['info']['url']) {
                                                $res['redirect_url'] = $ans['info']['url'];
                                            }

                                            $this->processingObjPage($res);

                                            $this->saveData($res);
                                        }

                                        $this->driverDom('clear', $element);
                                    }
                                }
                            }
                        } elseif ($data) {
                            foreach ($this->driverDom('find', $data, $target_page_element_attribute) as $element) {
                                $res = [];

                                foreach ($this->target_page_value as $target) {
                                    $target_attribute = $target['attribute'];
                                    if ($target_attribute == 'regulare' || $target_attribute ==
                                        'regulare_strip_tags') {
                                        $matches = null;
                                        $html = $this->driverDom('outertext', $element);
                                        //$html = str_replace(["\r", "\n"], '', $html);
                                        //$html = preg_replace("#>\s*<#", "><", $html);
                                        preg_match_all($target['phrase'], $html, $matches);
                                        if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                            is_array($matches[1]) && !empty($matches[1])) {
                                            if ($target_attribute == 'regulare_strip_tags') {
                                                foreach ($matches[1] as $key => $value) {
                                                    $matches[1][$key] = strip_tags($value);
                                                }
                                            }
                                            if (stripos($target['name'], '_download') > 0) {
                                                foreach ($matches[1] as $match) {
                                                    $file = $this->downloadFile($target['name'], $match, $ans['url'], $base_url, $res);

                                                    if ($file) {
                                                        $res[$file['field']][] = $file['name'];
                                                    }
                                                }
                                            } else {
                                                $res[$target['name']] = $matches[1];
                                            }
                                        }
                                    } else {
                                        foreach ($this->driverDom('find', $element, $target['phrase']) as $obj) {
                                            if (stripos($target['name'], '_download') > 0) {
                                                $file = $this->downloadFile($target['name'], $this->driverDom('get', $obj, $target_attribute),
                                                    $ans['url'], $base_url, $res);

                                                if ($file) {
                                                    $res[$file['field']][] = $file['name'];
                                                }
                                            } else {
                                                if (($target_attribute == 'href' && $this->driverDom('tag', $obj) == 'a') || ($target_attribute ==
                                                    'src' && $this->driverDom('tag', $obj) == 'img')) {
                                                    $res[$target['name']][] = prepare_url($this->driverDom('get', $obj, $target_attribute),
                                                        $ans['url'], $base_url);
                                                } else {
                                                    $res[$target['name']][] = $this->driverDom('get', $obj, $target_attribute);
                                                }
                                            }

                                            $this->driverDom('clear', $obj);
                                        }
                                    }
                                }

                                foreach ($res as $key => $value) {
                                    $res[$key] = preparation_data($value);
                                }

                                if (!empty($res)) {
                                    if (isset($ans['res']) && is_array($ans['res']) && !empty($ans['res'])) {
                                        $res = array_merge($ans['res'], $res);
                                    }

                                    $res['parse_url'] = $ans['url'];
                                    if ($ans['url'] != $ans['info']['url']) {
                                        $res['redirect_url'] = $ans['info']['url'];
                                    }

                                    $this->processingObjPage($res);

                                    $this->saveData($res);
                                } elseif (isset($ans['res']) && is_array($ans['res']) && !empty($ans['res'])) {
                                    $res = $ans['res'];

                                    $res['parse_url'] = $ans['url'];
                                    if ($ans['url'] != $ans['info']['url']) {
                                        $res['redirect_url'] = $ans['info']['url'];
                                    }

                                    $this->processingObjPage($res);

                                    $this->saveData($res);
                                }

                                $this->driverDom('clear', $element);
                            }
                        }
                    }
                }

                $this->driverDom('clear', $data);
            }
        } elseif (isset($ans['res']) && is_array($ans['res']) && !empty($ans['res'])) {
            $res = $ans['res'];

            $res['parse_url'] = $ans['url'];
            if ($ans['url'] != $ans['info']['url']) {
                $res['redirect_url'] = $ans['info']['url'];
            }

            $this->processingObjPage($res);

            $this->saveData($res);
        }

        if ($this->processFixRow == 1 && $tblvid) {
            switch ($this->inspectUrlTable) {
                case 1:
                    if (isset($ans['info']) && $ans['info']['http_code'] == 200) {
                        $this->checkId($tblvid, 1);
                    } else {
                        $this->checkId($tblvid, 2);
                    }
                    break;
                case 2:
                    break;
                case 3:
                    if (isset($ans['info']) && $ans['info']['http_code'] == 200) {

                    } else {
                        $this->checkId($tblvid, 2);
                    }
                    break;
            }
        }

        $end_time = microtime(true);

        $this->result['last_total_time'] = $end_time - $begin_time;

        $this->source->time_all_process = $this->source->time_all_process + $end_time -
            $begin_time;
        $this->source->time_last_process = $this->source->time_last_process + $end_time -
            $begin_time;

        $this->iteration_request_do_save_source++;

        $this->controlSave();
    }

    public function parseList($ans)
    {
        $this->stop();

        $tblvid = null;

        if (isset($ans['res']) && isset($ans['res']['tblvid'])) {
            $tblvid = $ans['res']['tblvid'];
            unset($ans['res']['tblvid']);
        }

        if (true || !$this->inspect_duplicate_url_list) {
            if (isset($ans['key']) && isset($this->urls_list[$ans['key']])) {
                unset($this->urls_list[$ans['key']]);
            }
        }

        $this->source->count_all_process++;
        $this->source->count_last_process++;

        $begin_time = microtime(true);

        if (isset($ans['info'])) {
            $this->result['inf_last_request'] = $ans['info'];
        }

        if (is_array($ans['info']) && !(isset($ans['info']['http_code']) && $ans['info']['http_code'] ==
            200)) {
            $this->source->count_error_all_process++;
            $this->source->count_error_last_process++;
            $this->result['inf_illegal_request'] = $ans['info'];
        } elseif (is_array($ans['info']) && isset($ans['info']['http_code']) && $ans['info']['http_code'] ==
        200) {
            $this->source->count_success_all_process++;
            $this->source->count_success_last_process++;
            
            $this->source->request_time = $ans['info']['total_time'];
            $this->source->request_time_all = floatval($this->source->request_time_all) + floatval($ans['info']['total_time']);
        } else {
            $this->source->count_error_all_process++;
            $this->source->count_error_last_process++;
        }

        if (is_array($ans['info']) && isset($ans['info']['total_time'])) {
            $this->source->time_all_requests = $this->source->time_all_requests + $ans['info']['total_time'];
            $this->source->time_last_requests = $this->source->time_last_requests + $ans['info']['total_time'];
        }

        $base_url = null;

        if (isset($ans['data']) && !empty($ans['data'])) {
            $ans['data'] = preparation_html($ans['data']);

            if ($ans['info'] && $ans['info']['http_code'] == 200) {
                if ($this->DOM_LIBRARY == 'simple_html_dom' && isset($ans['info']['content_type'])) {
                    if (preg_match('#charset=([a-zA-Z0-9_-]+)#', $ans['info']['content_type'], $matches)) {
                        if (isset($matches[1])) {
                            $charset = trim(strtolower($matches[1]));
                            if ($charset && $charset != 'utf-8' && $charset != 'utf8') {
                                $ans['data'] = iconv($charset, 'utf-8', $ans['data']);
                            }
                        }
                    }
                }

                $data = $this->driverDom('new', $ans['data']);

                if ($data) {
                    foreach ($this->driverDom('find', $data, 'head base') as $obj) {
                        if ($this->driverDom('get', $obj, 'href')) {
                            $base_url = trim($this->driverDom('get', $obj, 'href'));

                            break;
                        }
                    }
                }

                if (empty($this->target_list_element)) {
                    foreach ($this->target_list_url as $target) {
                        $target_attribute = $target['attribute'];
                        if ($target_attribute == 'regulare' || $target_attribute ==
                            'regulare_strip_tags') {
                            $matches = null;
                            $html = $ans['data'];
                            //$html = str_replace(["\r", "\n"], '', $html);
                            //$html = preg_replace("#>\s*<#", "><", $html);
                            preg_match_all($target['phrase'], $html, $matches);
                            if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                is_array($matches[1]) && !empty($matches[1])) {
                                if ($target_attribute == 'regulare_strip_tags') {
                                    foreach ($matches[1] as $key => $value) {
                                        $matches[1][$key] = strip_tags($value);
                                    }
                                }
                                foreach ($matches[1] as $key => $value) {
                                    if ((trim($value))) {
                                        $this->addUrlPage(['url' => prepare_url(trim($value), $ans['url'], $base_url),
                                            'function' => ['$this', 'parsePage'], 'options' => $this->options_for_page, ]);
                                    }
                                }
                            }
                        } elseif ($data) {
                            foreach ($this->driverDom('find', $data, $target['phrase']) as $obj) {
                                if ((trim($this->driverDom('get', $obj, $target_attribute)))) {
                                    $this->addUrlPage(['url' => prepare_url($this->driverDom('get', $obj, $target_attribute),
                                        $ans['url'], $base_url), 'function' => ['$this', 'parsePage'], 'options' => $this->
                                        options_for_page, ]);
                                }

                                $this->driverDom('clear', $obj);
                            }
                        }
                    }
                } elseif (true) {
                    foreach ($this->target_list_element as $target_page_element_attribute) {
                        if (preg_match("#^\##", $target_page_element_attribute) && preg_match("#\#$#", $target_page_element_attribute)) {
                            $matches_list = null;
                            $html = $ans['data'];
                            //$html = str_replace(["\r", "\n"], '', $html);
                            //$html = preg_replace("#>\s*<#", "><", $html);
                            preg_match_all($target_page_element_attribute, $html, $matches_list);
                            if ($matches_list && is_array($matches_list) && !empty($matches_list) && isset($matches_list[1]) &&
                                is_array($matches_list[1]) && !empty($matches_list[1])) {
                                foreach ($matches_list[1] as $match_list) {
                                    if ((trim($match_list))) {
                                        $element = $this->driverDom('new', $match_list);

                                        $res = [];

                                        foreach ($this->target_list_value as $target) {
                                            $target_attribute = $target['attribute'];
                                            if ($target_attribute == 'regulare' || $target_attribute ==
                                                'regulare_strip_tags') {
                                                $matches = null;
                                                $html = $match_list;
                                                //$html = str_replace(["\r", "\n"], '', $html);
                                                //$html = preg_replace("#>\s*<#", "><", $html);
                                                preg_match_all($target['phrase'], $html, $matches);
                                                if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                                    is_array($matches[1]) && !empty($matches[1])) {
                                                    if ($target_attribute == 'regulare_strip_tags') {
                                                        foreach ($matches[1] as $key => $value) {
                                                            $matches[1][$key] = strip_tags($value);
                                                        }
                                                    }
                                                    if (stripos($target['name'], '_download') > 0) {
                                                        foreach ($matches[1] as $match) {
                                                            $file = $this->downloadFile($target['name'], $match, $ans['url'], $base_url, $res);

                                                            if ($file) {
                                                                $res[$file['field']][] = $file['name'];
                                                            }
                                                        }
                                                    } else {
                                                        $res[$target['name']] = $matches[1];
                                                    }
                                                }
                                            } elseif ($element) {
                                                foreach ($this->driverDom('find', $element, $target['phrase']) as $obj) {
                                                    if (stripos($target['name'], '_download') > 0) {
                                                        $file = $this->downloadFile($target['name'], $this->driverDom('get', $obj, $target_attribute),
                                                            $ans['url'], $base_url, $res);

                                                        if ($file) {
                                                            $res[$file['field']][] = $file['name'];
                                                        }
                                                    } else {
                                                        if (($target_attribute == 'href' && $this->driverDom('tag', $obj) == 'a') || ($target_attribute ==
                                                            'src' && $this->driverDom('tag', $obj) == 'img')) {
                                                            $res[$target['name']][] = prepare_url($this->driverDom('get', $obj, $target_attribute),
                                                                $ans['url'], $base_url);
                                                        } else {
                                                            $res[$target['name']][] = $this->driverDom('get', $obj, $target_attribute);
                                                        }
                                                    }

                                                    $this->driverDom('clear', $obj);
                                                }
                                            }
                                        }

                                        foreach ($res as $key => $value) {
                                            $res[$key] = preparation_data($value);
                                        }

                                        $status_res = false;

                                        foreach ($this->target_list_url as $target) {
                                            $target_attribute = $target['attribute'];
                                            if ($target_attribute == 'regulare' || $target_attribute ==
                                                'regulare_strip_tags') {
                                                $matches = null;
                                                $html = $match_list;
                                                //$html = str_replace(["\r", "\n"], '', $html);
                                                //$html = preg_replace("#>\s*<#", "><", $html);
                                                preg_match_all($target['phrase'], $html, $matches);
                                                if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                                    is_array($matches[1]) && !empty($matches[1])) {
                                                    if ($target_attribute == 'regulare_strip_tags') {
                                                        foreach ($matches[1] as $key => $value) {
                                                            $matches[1][$key] = strip_tags($value);
                                                        }
                                                    }
                                                    foreach ($matches[1] as $key => $value) {
                                                        if ((trim($value))) {
                                                            $this->addUrlPage(['url' => prepare_url(trim($value), $ans['url'], $base_url),
                                                                'function' => ['$this', 'parsePage'], 'res' => $res, 'options' => $this->
                                                                options_for_page, ]);
                                                        }
                                                    }
                                                }
                                            } elseif ($element) {
                                                foreach ($this->driverDom('find', $element, $target['phrase']) as $obj) {
                                                    if ((trim($this->driverDom('get', $obj, $target_attribute)))) {
                                                        $status_res = true;

                                                        $this->addUrlPage(['url' => prepare_url($this->driverDom('get', $obj, $target_attribute),
                                                            $ans['url'], $base_url), 'function' => ['$this', 'parsePage'], 'res' => $res,
                                                            'options' => $this->options_for_page, ]);
                                                    }
                                                    $this->driverDom('clear', $obj);
                                                }
                                            }
                                        }

                                        if (!$status_res && !empty($res)) {
                                            $res['parse_url'] = $ans['url'];
                                            if ($ans['url'] != $ans['info']['url']) {
                                                $res['redirect_url'] = $ans['info']['url'];
                                            }

                                            $this->processingObjList($res);

                                            $this->saveData($res);
                                        }

                                        $this->driverDom('clear', $element);
                                    }
                                }
                            }
                        } elseif ($data) {
                            foreach ($this->driverDom('find', $data, $target_page_element_attribute) as $element) {
                                $res = [];

                                foreach ($this->target_list_value as $target) {
                                    $target_attribute = $target['attribute'];
                                    if ($target_attribute == 'regulare' || $target_attribute ==
                                        'regulare_strip_tags') {
                                        $matches = null;
                                        $html = $this->driverDom('outertext', $element);
                                        //$html = str_replace(["\r", "\n"], '', $html);
                                        //$html = preg_replace("#>\s*<#", "><", $html);
                                        preg_match_all($target['phrase'], $html, $matches);
                                        if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                            is_array($matches[1]) && !empty($matches[1])) {
                                            if ($target_attribute == 'regulare_strip_tags') {
                                                foreach ($matches[1] as $key => $value) {
                                                    $matches[1][$key] = strip_tags($value);
                                                }
                                            }
                                            if (stripos($target['name'], '_download') > 0) {
                                                foreach ($matches[1] as $match) {
                                                    $file = $this->downloadFile($target['name'], $match, $ans['url'], $base_url, $res);

                                                    if ($file) {
                                                        $res[$file['field']][] = $file['name'];
                                                    }
                                                }
                                            } else {
                                                $res[$target['name']] = $matches[1];
                                            }
                                        }
                                    } else {
                                        foreach ($this->driverDom('find', $element, $target['phrase']) as $obj) {
                                            if (stripos($target['name'], '_download') > 0) {
                                                $file = $this->downloadFile($target['name'], $this->driverDom('get', $obj, $target_attribute),
                                                    $ans['url'], $base_url, $res);

                                                if ($file) {
                                                    $res[$file['field']][] = $file['name'];
                                                }
                                            } else {
                                                if (($target_attribute == 'href' && $this->driverDom('tag', $obj) == 'a') || ($target_attribute ==
                                                    'src' && $this->driverDom('tag', $obj) == 'img')) {
                                                    $res[$target['name']][] = prepare_url($this->driverDom('get', $obj, $target_attribute),
                                                        $ans['url'], $base_url);
                                                } else {
                                                    $res[$target['name']][] = $this->driverDom('get', $obj, $target_attribute);
                                                }
                                            }

                                            $this->driverDom('clear', $obj);
                                        }
                                    }
                                }

                                foreach ($res as $key => $value) {
                                    $res[$key] = preparation_data($value);
                                }

                                $status_res = false;

                                foreach ($this->target_list_url as $target) {
                                    $target_attribute = $target['attribute'];
                                    if ($target_attribute == 'regulare' || $target_attribute ==
                                        'regulare_strip_tags') {
                                        $matches = null;
                                        $html = $this->driverDom('outertext', $element);
                                        //$html = str_replace(["\r", "\n"], '', $html);
                                        //$html = preg_replace("#>\s*<#", "><", $html);
                                        preg_match_all($target['phrase'], $html, $matches);
                                        if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                            is_array($matches[1]) && !empty($matches[1])) {
                                            if ($target_attribute == 'regulare_strip_tags') {
                                                foreach ($matches[1] as $key => $value) {
                                                    $matches[1][$key] = strip_tags($value);
                                                }
                                            }
                                            foreach ($matches[1] as $key => $value) {
                                                if ((trim($value))) {
                                                    $status_res = true;
                                                    $this->addUrlPage(['url' => prepare_url(trim($value), $ans['url'], $base_url),
                                                        'function' => ['$this', 'parsePage'], 'res' => $res, 'options' => $this->
                                                        options_for_page, ]);
                                                }
                                            }
                                        }
                                    } else {
                                        foreach ($this->driverDom('find', $element, $target['phrase']) as $obj) {
                                            if ((trim($this->driverDom('get', $obj, $target_attribute)))) {
                                                $status_res = true;

                                                $this->addUrlPage(['url' => prepare_url($this->driverDom('get', $obj, $target_attribute),
                                                    $ans['url'], $base_url), 'function' => ['$this', 'parsePage'], 'res' => $res,
                                                    'options' => $this->options_for_page, ]);
                                            }

                                            $this->driverDom('clear', $obj);
                                        }
                                    }
                                }

                                if (!$status_res && !empty($res)) {
                                    $res['parse_url'] = $ans['url'];
                                    if ($ans['url'] != $ans['info']['url']) {
                                        $res['redirect_url'] = $ans['info']['url'];
                                    }

                                    $this->processingObjList($res);

                                    $this->saveData($res);
                                }

                                $this->driverDom('clear', $element);
                            }
                        }
                    }
                }

                if (!($this->begin_page && $this->end_page && $this->key_page && stripos($this->
                    base_href['url'], $ans['url']) == '0')) {
                    foreach ($this->target_list_next as $target) {
                        $target_attribute = $target['attribute'];
                        if ($target_attribute == 'regulare' || $target_attribute ==
                            'regulare_strip_tags') {
                            $matches = null;
                            $html = $ans['data'];
                            //$html = str_replace(["\r", "\n"], '', $html);
                            //$html = preg_replace("#>\s*<#", "><", $html);
                            preg_match_all($target['phrase'], $html, $matches);
                            if ($matches && is_array($matches) && !empty($matches) && isset($matches[1]) &&
                                is_array($matches[1]) && !empty($matches[1])) {
                                if ($target_attribute == 'regulare_strip_tags') {
                                    foreach ($matches[1] as $key => $value) {
                                        $matches[1][$key] = strip_tags($value);
                                    }
                                }
                                foreach ($matches[1] as $key => $value) {
                                    if ((trim($value))) {
                                        $href = trim($value);

                                        if (!empty($href)) {
                                            $href = prepare_url($href, $ans['url'], $base_url);

                                            $url = ['url' => $href, 'function' => ['$this', 'parseList'], 'options' => $this->
                                                options_for_list, ];

                                            $this->addUrlList($url);
                                        }
                                    }
                                }
                            }
                        } elseif ($data) {
                            foreach ($this->driverDom('find', $data, $target['phrase']) as $obj) {
                                $href = trim($this->driverDom('get', $obj, $target_attribute));

                                if (!empty($href)) {
                                    $href = prepare_url($href, $ans['url'], $base_url);

                                    $url = ['url' => $href, 'function' => ['$this', 'parseList'], 'options' => $this->
                                        options_for_list, ];

                                    $this->addUrlList($url);
                                }
                            }
                        }
                    }
                }

                $this->driverDom('clear', $data);

                $this->result['count_urls_list'] = $this->count_urls_list;
                $this->result['count_urls_page'] = $this->count_urls_page;
            }
        }

        if ($this->processFixRow == 2 && $tblvid) {
            switch ($this->inspectUrlTable) {
                case 1:
                    if (isset($ans['info']) && $ans['info']['http_code'] == 200) {
                        $this->checkId($tblvid, 1);
                    } else {
                        $this->checkId($tblvid, 2);
                    }
                    break;
                case 2:
                    break;
                case 3:
                    if (isset($ans['info']) && $ans['info']['http_code'] == 200) {

                    } else {
                        $this->checkId($tblvid, 2);
                    }
                    break;
            }
        }

        $end_time = microtime(true);

        $this->result['last_total_time'] = $end_time - $begin_time;

        $this->source->time_all_process = $this->source->time_all_process + $end_time -
            $begin_time;
        $this->source->time_last_process = $this->source->time_last_process + $end_time -
            $begin_time;

        $this->iteration_request_do_save_source++;

        $this->controlSave();
    }

    public function downloadFile($field, $url, $url_page, $base_url = null, $data = [])
    {
        $result = null;

        $field = str_replace('_download', '', $field);

        if (!empty($field)) {
            $url = prepare_url($url, $url_page, $base_url);

            if ($url) {
                $pathinfo = pathinfo($url);
                $parse_url = parse_url($url);

                if (isset($pathinfo['basename']) && isset($pathinfo['filename'])) {
                    $filename = str_limit($pathinfo['filename'], 80, '');

                    if (isset($parse_url['path'])) {
                        $filename = str_limit(translit(str_replace($pathinfo['basename'], '', $parse_url['path']),
                            '-'), 70, '') . '.' . $filename;
                    }

                    if (isset($pathinfo['extension'])) {
                        $filename = $filename . '.' . $pathinfo['extension'];
                    }

                    $filename = preg_replace("#[^a-zA-Z0-9АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЮюЯяЬьЪъёЫы_.-]#",
                        '_', $filename);

                    $filename = preg_replace("#_{2,}#", '_', $filename);

                    $filename = strtolower($filename);

                    if ($this->source->table_name == 'festo') {
                        $filename = str_replace('cfp-camoshtml-i.i_sig_', '', $filename);

                        if ($field == 'fs_images_small_foto' || $field == 'fs_images_big_foto') {
                            $filename = str_replace('.png', '-foto.png', $filename);
                            $filename = str_replace('.jpg', '-foto.jpg', $filename);
                        }

                        if ($field == 'fs_images_small_chertezh' || $field == 'fs_images_big_chertezh') {
                            $filename = str_replace('.png', '-chertezh.png', $filename);
                            $filename = str_replace('.jpg', '-chertezh.jpg', $filename);
                        }
                    }

                    if (file_exists($this->path_files_dir . '/' . $this->source->table_name . '/' .
                        $field . '/' . $filename)) {
                        $result = ['field' => $field, 'name' => $filename];
                    } else {
                        $options = $this->options_default;

                        $options[CURLOPT_URL] = $url;
                        $options[CURLOPT_RETURNTRANSFER] = 1;
                        $options[CURLOPT_NOBODY] = 0;
                        $options[CURLOPT_HEADER] = 0;

                        $ch = curl_init();

                        curl_setopt_array($ch, $options);

                        $data = curl_exec($ch);
                        $chinfo = curl_getinfo($ch);

                        curl_close($ch);

                        if ($chinfo && $chinfo['http_code'] == 200) {
                            if (!is_dir($this->path_files_dir . '/' . $this->source->table_name . '/' . $field)) {
                                mkdir($this->path_files_dir . '/' . $this->source->table_name . '/' . $field,
                                    0777, true);
                            }

                            file_put_contents($this->path_files_dir . '/' . $this->source->table_name . '/' .
                                $field . '/' . $filename, $data);

                            $result = ['field' => $field, 'name' => $filename];
                        }
                    }
                }
            }
        }

        return $result;
    }

    private function checkId($tblvid, $status = 1)
    {
        if ($status == 1) {
            $this->ids_for_check_status_1[] = $tblvid;
        } elseif ($status == 2) {
                        $this->ids_for_check_status_2[] = $tblvid;
        }

        if (count($this->ids_for_check_status_1) >= self::COUNT_DO_CHECK) {
            $this->checkIdsInBd(1);
        }

        if (count($this->ids_for_check_status_2) >= self::COUNT_DO_CHECK) {
            $this->checkIdsInBd(2);
        }
    }

    private function checkIdsInBd($status = 1)
    {
        $i = 1;

        $keys = [];
        $values = [];

        if ($status == 1) {
            foreach ($this->ids_for_check_status_1 as $tblvid) {
                $keys[] = ':id_' . $i;
                $values['id_' . $i] = $tblvid;
                $i++;
            }

            if (!empty($keys)) {
                DatabasePerform::Execute("UPDATE `" . $this->source->table_page_urls .
                    "` SET `status_process` = 1 WHERE `" . $this->source->table_column_id .
                    "` IN(" . implode(',', $keys) . ")", $values);
            }
            $this->ids_for_check_status_1 = [];
        } elseif ($status == 2) {
            foreach ($this->ids_for_check_status_2 as $tblvid) {
                $keys[] = ':id_' . $i;
                $values['id_' . $i] = $tblvid;
                $i++;
            }

            if (!empty($keys)) {
                DatabasePerform::Execute("UPDATE `" . $this->source->table_page_urls .
                    "` SET `status_process` = `status_process` + 2 WHERE `" . $this->source->table_column_id .
                    "` IN(" . implode(',', $keys) . ")", $values);
            }

            $this->ids_for_check_status_2 = [];
        }
    }

    private function checkUrl($url, $status = 1)
    {
        if ($status == 1) {
            $this->urls_for_check_status_1[] = $url;
        } else
            if ($status == 2) {
                $this->urls_for_check_status_2[] = $url;
            }

        if (count($this->urls_for_check_status_1) >= self::COUNT_DO_CHECK) {
            $this->checkUrlsInBd(1);
        }

        if (count($this->urls_for_check_status_2) >= self::COUNT_DO_CHECK) {
            $this->checkUrlsInBd(2);
        }
    }

    private function checkUrlsInBd($status = 1)
    {
        $i = 1;

        $keys = [];
        $values = [];

        if ($status == 1) {
            foreach ($this->urls_for_check_status_1 as $url) {
                $keys[] = ':url_' . $i;
                $values['url_' . $i] = $url;
                $i++;
            }

            if (!empty($keys)) {
                DatabasePerform::Execute("UPDATE `" . $this->source->table_page_urls .
                    "` SET `status_process` = 1 WHERE `" . $this->source->column_table_page_urls .
                    "` IN(" . implode(',', $keys) . ")", $values);
            }
            $this->urls_for_check_status_1 = [];
        } elseif ($status == 2) {
            foreach ($this->urls_for_check_status_2 as $url) {
                $keys[] = ':url_' . $i;
                $values['url_' . $i] = $url;
                $i++;
            }

            if (!empty($keys)) {
                DatabasePerform::Execute("UPDATE `" . $this->source->table_page_urls .
                    "` SET `status_process` = `status_process` + 2 WHERE `" . $this->source->column_table_page_urls .
                    "` IN(" . implode(',', $keys) . ")", $values);
            }

            $this->urls_for_check_status_2 = [];
        }
    }

    public function stop()
    {
        if ($this->time_limit) {
            if (time() - $this->begin_time > $this->time_limit) {
                $this->source->end_parse_at = time();
                $this->complete();

                if ($this->_curl) {
                    $this->_curl->Stop();
                }

                exit();
            }
        }

        if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $this->
            source_id) || file_exists(\workup\App::config('DIR_TMP') . "/blocking_all")) {
            $this->source->end_parse_at = time();
            $this->complete();

            if ($this->_curl) {
                $this->_curl->Stop();
            }

            exit();
        }
    }

    private function complete()
    {
        $this->insertData();

        if ($this->SAVE_STATISTICS) {
            $memory = memory_get_usage(true);

            $memory = $memory / 1048576.2;

            $this->memory[] = $memory;

            $cp = 0;

            if (function_exists('getrusage')) {
                $dat = getrusage();
                $cp = (float)($dat["ru_utime.tv_sec"] . '.' . $dat["ru_utime.tv_usec"]);
            }

            $this->source->cp_all = $this->cp_all_initial + $cp;
            $this->source->cp_last = $cp;

            if (count($this->memory) >= 10 || !$this->source->memory_last) {
                $sum = 0;
                $count = count($this->memory);

                foreach ($this->memory as $value) {
                    $sum = $sum + $value;
                }

                if ($this->source->memory_last > 0) {
                    $this->source->memory_last = (($sum / $count) + $this->source->memory_last) / 2;
                } else {
                    $this->source->memory_last = $sum / $count;
                }

                if ($this->source->memory_all > 0) {
                    $this->source->memory_all = ($this->source->memory_all + $this->source->
                        memory_last) / 2;
                } else {
                    $this->source->memory_all = $this->source->memory_last;
                }

                $this->memory = [];
            }

            $this->source->count_all_query_to_bd = $this->count_all_query_to_bd_initial +
                DatabasePerform::getCountQuery();
            $this->source->count_last_query_to_bd = $this->count_last_query_to_bd_initial +
                DatabasePerform::getCountQuery();

            $this->result['count_urls_list'] = $this->count_urls_list;
            $this->result['count_urls_page'] = $this->count_urls_page;

            $this->source->result = serialize($this->result);
            $this->source->save();
        }

        $this->checkUrlsInBd(1);
        $this->checkUrlsInBd(2);
        $this->checkIdsInBd(1);
        $this->checkIdsInBd(2);
    }

    private function controlSave()
    {
        if ($this->iteration_request_do_save_source >= self::
            AMOUNT_DO_SAVE) {
            $memory = memory_get_usage(true);

            $memory = $memory / 1048576.2;

            $this->memory[] = $memory;

            $cp = 0;

            if (function_exists('getrusage')) {
                $dat = getrusage();
                $cp = (float)($dat["ru_utime.tv_sec"] . '.' . $dat["ru_utime.tv_usec"]);
            }

            $this->source->cp_all = $this->cp_all_initial + $cp;
            $this->source->cp_last = $cp;

            if (count($this->memory) >= 50) {
                $sum = 0;
                $count = count($this->memory);

                foreach ($this->memory as $value) {
                    $sum = $sum + $value;
                }

                if ($this->source->memory_last > 0) {
                    $this->source->memory_last = (($sum / $count) + $this->source->memory_last) / 2;
                } else {
                    $this->source->memory_last = $sum / $count;
                }

                if ($this->source->memory_all > 0) {
                    $this->source->memory_all = ($this->source->memory_all + $this->source->
                        memory_last) / 2;
                } else {
                    $this->source->memory_all = $this->source->memory_last;
                }

                $this->memory = [];
            }

            $this->source->count_all_query_to_bd = $this->count_all_query_to_bd_initial +
                DatabasePerform::getCountQuery();
            $this->source->count_last_query_to_bd = $this->count_last_query_to_bd_initial +
                DatabasePerform::getCountQuery();

            $this->result['count_urls_list'] = $this->count_urls_list;
            $this->result['count_urls_page'] = $this->count_urls_page;
            $this->result['request_time'] = $this->source->request_time;
            $this->result['request_average_time'] = $this->count_urls_page;

            $this->source->result = serialize($this->result);
            
            if ($this->SAVE_STATISTICS) {
                $this->source->save();
            } else {
                file_put_contents(
                    \workup\App::config('DIR_TMP').'/stat.txt', 
                    "Количество запросов: ".$this->source->count_last_process."\n".
                    "Количество успешных запросов: ".$this->source->count_success_last_process."\n".
                    "Количество ошибочных запросов: ".$this->source->count_error_last_process."\n".
                    "Время работы: ".round($this->source->time_last_process, 2)." c.\n".
                    "Врямя на запросы: ".round($this->source->time_last_requests, 2)." c.\n".
                    "CP: ".round($this->source->cp_last, 2)."\n".
                    "Память: ".round($this->source->memory_last, 2)."\n".
                    "Количество запросов к БД: ".round(DatabasePerform::getCountQuery(), 2)."\n".
                    "Обрабатывалось: ".round($this->result['last_total_time'], 2)." c.\n".
                    "Время запроса: ".round($this->source->request_time, 2)." c.\n".
                    "Среднее Время запроса: ".round($this->source->request_time_all/$this->source->count_success_last_process, 2)." c.\n"
                );
            }

            $this->iteration_request_do_save_source = 0;
        }
    }

    private function processingObjList(&$res)
    {
        if ($this->func_data_processing_list) {
            $func = $this->func_data_processing_list;

            $data_res = $res;
            
            $data_res['objParse'] = $this;
            $data_res['objCurl'] = $this->_curl;

            $data = $func($data_res, $this);
            
            if (is_null($data)) {
                $res = [];
            } else {
                foreach ($this->prepareDataProcessing($data) as $key => $value) {
                    if (is_null($value)) {
                        if (isset($res[$key])) {
                            unset($res[$key]);
                        }
                    } else {
                        $res[$key] = $value;
                    }
                }
            }
        }
        
        if ($res) {
            $this->prepareDataDefault($res);

            foreach ($this->source->_default_values as $value) {
                if (!isset($res[$value['key']])) {
                    $res[$value['key']] = $value['value'];
                }
            }
        }
    }

    private function processingObjPage(&$res)
    {
        if ($this->func_data_processing_page) {
            $func = $this->func_data_processing_page;

            $data_res = $res;
            
            $data_res['objParse'] = $this;
            $data_res['objCurl'] = $this->_curl;

            $data = $func($data_res, $this);
            
            if (is_null($data)) {
                $res = [];
            } else {
                foreach ($this->prepareDataProcessing($data) as $key => $value) {
                    if (is_null($value)) {
                        if (isset($res[$key])) {
                            unset($res[$key]);
                        }
                    } else {
                        $res[$key] = $value;
                    }
                }
            }
        }

        if ($res) {
            $this->prepareDataDefault($res);

            foreach ($this->source->_default_values as $value) {
                if (!isset($res[$value['key']])) {
                    $res[$value['key']] = $value['value'];
                }
            }
        }
    }

    private function prepareDataProcessing($data)
    {
        $result = [];

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                switch ($key) {
                    case 'url_list':
                        $this->addListUrl($value);

                        break;
                    case 'urls_list':
                        $this->addListUrls($value);

                        break;
                    case 'url_page':
                        $this->addPageUrl($value);

                        break;
                    case 'urls_page':
                        $this->addPageUrls($value);

                        break;
                    default:
                        if (is_array($value)) {
                            $value = serialize($value);
                        }

                        if (is_string($value) || is_numeric($value) || is_null($value)) {
                            $result[$key] = $value;
                        }

                        break;
                }
            }
        }

        return $result;
    }

    private function prepareDataDefault(&$data)
    {
        if (!empty($data)) {
            $data['source_id'] = $this->source->id;
        }
    }

    private function saveData($data)
    {
        if (!empty($data) && $this->saveRecord) {
            if ($this->insert_type == '1') {
                if (true) {
                    switch ($this->saveRecord) {
                        case 'SourceDataRecord':
                            $count = \workup\record\SourceDataRecord::insertRow($data);
                            break;
                        case 'SourceDataRecordWordpress':
                            $count = \workup\record\SourceDataRecordWordpress::insertRow($data);
                            break;
                        default:
                            $count = null;
                            break;
                    }

                    if ($count) {
                        $this->source->count_all_write = $this->source->count_all_write + $count;
                        $this->source->count_last_write = $this->source->count_last_write + $count;
                    }
                    /*
                    $obj = new \workup\model\SourceData($data);

                    if ($obj) {
                    $obj->save();
                    if ($obj->status_insert_new) {
                    $this->source->count_all_write++;
                    $this->source->count_last_write++;
                    }
                    $obj->remove();
                    unset($obj);
                    }
                    */
                }
            } elseif ($this->insert_type == '2') {
                $this->insert_data[] = $data;

                if (count($this->insert_data) >= $this->COUNT_DO_INSERT) {
                    $this->insertData();
                }
            }
        }
    }

    private function insertData()
    {
        if ($this->saveRecord && !empty($this->insert_data)) {
            switch ($this->saveRecord) {
                case 'SourceDataRecord':
                    $count = \workup\record\SourceDataRecord::insertRows($this->insert_data);
                    break;
                case 'SourceDataRecordWordpress':
                    $count = \workup\record\SourceDataRecordWordpress::insertRows($this->
                        insert_data);
                    break;
                default:
                    $count = null;
                    break;
            }

            if ($count) {
                $this->source->count_all_write = $this->source->count_all_write + $count;
                $this->source->count_last_write = $this->source->count_last_write + $count;
            }

            $this->insert_data = [];
        }
    }

    private function controlUrlInDbInit()
    {
        DatabasePerform::Execute("CREATE TABLE IF NOT EXISTS `tbl_control_urls` (url VARCHAR(256));");
    }

    private function controlUrlInDbExist($url)
    {
        if ($this->control_url_in_bd) {
            if ($this->controlUrlInDbIs($url)) {
                return false;
            } else {
                $this->controlUrlInDbAdd($url);
            }
        }

        return true;
    }

    private function controlUrlInDbIs($url)
    {
        return $this->isUrlInDb($url, 'tbl_control_urls', 'url');
    }

    private function controlUrlInDbAdd($url)
    {
        DatabasePerform::Execute("INSERT INTO `tbl_control_urls` (`url`) VALUES (:url)", ['url' =>
            $url]);
    }

    private function isUrlInDb($url, $tbl = null, $field = 'parse_url')
    {
        if (!$tbl) {
            $tbl = $this->source->table_name;
        }

        $count = DatabasePerform::GetOne("SELECT COUNT(*) FROM `" . $tbl . "` WHERE `" .
            $field . "` = :" . $field, [$field => $url]);

        if ($count > '0') {
            if ($this->processFixRow) {
                $this->checkUrl($url, 1);
            }

            return true;
        } elseif ($count == '0') {
            return false;
        } else {
            return null;
        }
    }

    private function driverDom($action, &$data = null, $arg = null)
    {
        switch ($this->DOM_LIBRARY) {
            case 'simple_html_dom':
                switch ($action) {
                    case 'new':
                        return $this->newSimpleHtmlDom($data);
                        break;
                    case 'find':
                        return $this->findSimpleHtmlDom($data, $arg);
                        break;
                    case 'tag':
                        return $this->tagSimpleHtmlDom($data);
                        break;
                    case 'outertext':
                        return $this->outertextSimpleHtmlDom($data);
                        break;
                    case 'innertext':
                        return $this->innertextSimpleHtmlDom($data);
                        break;
                    case 'plaintext':
                        return $this->plaintextSimpleHtmlDom($data);
                        break;
                    case 'attr':
                        return $this->attrSimpleHtmlDom($data, $arg);
                        break;
                    case 'get':
                        if (in_array($arg, ['tag', 'outertext', 'innertext', 'plaintext'])) {
                            return $this->driverDom($arg, $data, $arg);
                        } else {
                            return $this->attrSimpleHtmlDom($data, $arg);
                        }
                        break;
                    case 'clear':
                        return $this->clearSimpleHtmlDom($data);
                        break;
                }
                break;
            case 'phpQuery':
                switch ($action) {
                    case 'new':
                        return $this->newPhpQuery($data);
                        break;
                    case 'find':
                        return $this->findPhpQuery($data, $arg);
                        break;
                    case 'tag':
                        return $this->tagPhpQuery($data);
                        break;
                    case 'outertext':
                        return $this->outertextPhpQuery($data);
                        break;
                    case 'innertext':
                        return $this->innertextPhpQuery($data);
                        break;
                    case 'plaintext':
                        return $this->plaintextPhpQuery($data);
                        break;
                    case 'attr':
                        return $this->attrPhpQuery($data, $arg);
                        break;
                    case 'get':
                        if (in_array($arg, ['tag', 'outertext', 'innertext', 'plaintext'])) {
                            return $this->driverDom($arg, $data, $arg);
                        } else {
                            return $this->attrPhpQuery($data, $arg);
                        }
                        break;
                    case 'clear':
                        return $this->clearPhpQuery($data);
                        break;
                }
                break;
        }
    }

    private function newSimpleHtmlDom(&$html)
    {
        return str_get_html($html);
    }

    private function newPhpQuery(&$html)
    {
        return phpQuery::newDocument($html);
    }

    private function findSimpleHtmlDom(&$dom, $selectors)
    {
        return $dom->find($selectors);
    }

    private function findPhpQuery(&$dom, $selectors)
    {
        if ($dom instanceof \phpQueryObject) {
            return $dom->find($selectors);
        } else {
            return pq($dom)->find($selectors);
        }
    }

    private function tagSimpleHtmlDom(&$obj)
    {
        return $obj->tag;
    }

    private function tagPhpQuery(&$obj)
    {
        return $obj->tagName;
    }

    private function outertextSimpleHtmlDom(&$obj)
    {
        return $obj->outertext;
    }

    private function outertextPhpQuery(&$obj)
    {
        return pq($obj)->htmlOuter();
    }

    private function innertextSimpleHtmlDom(&$obj)
    {
        return $obj->innertext;
    }

    private function innertextPhpQuery(&$obj)
    {
        return pq($obj)->html();
    }

    private function plaintextSimpleHtmlDom(&$obj)
    {
        return $obj->plaintext;
    }

    private function plaintextPhpQuery(&$obj)
    {
        return pq($obj)->text();
    }

    private function attrSimpleHtmlDom(&$obj, $attr)
    {
        return $obj->$attr;
    }

    private function attrPhpQuery(&$obj, $attr)
    {
        return pq($obj)->attr($attr);
    }

    private function clearSimpleHtmlDom(&$obj)
    {
        if (is_object($obj)) {
            $obj->clear();
        }

        unset($obj);
    }

    private function clearPhpQuery(&$obj)
    {
        unset($obj);
    }
}

function prepare_url($url, $url_page, $base_url = null)
{
    $url = trim($url);
    $url = html_entity_decode($url, ENT_QUOTES);
    $url_page = trim($url_page);

    $chars = "a-zA-Z0-9АаБбВвГгҐґДдЕеЄєЭэЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЮюЯяЬьЪъёЫы";

    if (preg_match("#((http|https):\/\/|www\.)([" . $chars . "][" . $chars .
        "_-]*(?:.[" . $chars . "][" . $chars . "@\#$%&*().:;_-]*\/{0,1})+):?(d+)?\/?#Diu",
        $url)) {
        return $url;
    }

    $parse_url = parse_url($url);

    if ($base_url) {
        $parse_url_page = parse_url($base_url);
    } else {
        $parse_url_page = parse_url($url_page);
    }

    $result_url = null;

    if (isset($parse_url['hostname']) || isset($parse_url['path']) || isset($parse_url['query'])) {
        if (!isset($parse_url['scheme']) && !isset($parse_url['host']) && isset($parse_url['path'])) {
            if (preg_match("#^\.\.\/#", $parse_url['path'])) {
                $parse_url['path'] = preg_replace("#^\.\.\/#", "", $parse_url['path']);

                $parse_url['path'] = preg_replace("#$#", "", $parse_url['path']);

                if (isset($parse_url_page['path'])) {
                    $pathinfo = pathinfo($parse_url_page['path']);

                    if (isset($pathinfo['extension'])) {
                        $dirname = trim($pathinfo['dirname'], "/\\");
                    } else {
                        $dirname = trim($parse_url_page['path'], "/\\");
                    }

                    if (isset($parse_url['path']) && !empty($dirname)) {
                        $dirs = explode('/', $dirname);

                        if (count($dirs) > 0) {
                            unset($dirs[count($dirs) - 1]);
                        }

                        $dirname = implode('/', $dirs);

                        $parse_url['path'] = $dirname . '/' . ltrim($parse_url['path'], '/');
                    }
                }
            } elseif (!preg_match("#^\/#", $parse_url['path'])) {
                $parse_url['path'] = preg_replace("#^\.\/#", "", $parse_url['path']);

                if (isset($parse_url_page['path'])) {
                    $pathinfo = pathinfo($parse_url_page['path']);

                    if (isset($pathinfo['extension'])) {
                        $dirname = trim($pathinfo['dirname'], "/\\");
                    } else {
                        if (preg_match("#^[\w]#", $parse_url['path']) && !preg_match("#\/$#", $parse_url_page['path'])) {
                            if (stripos($parse_url_page['path'], '/') !== false) {
                                $dirname = trim(preg_replace("#\/[^\/]*$#", "", $parse_url_page['path']), "/\\");
                            } else {
                                $dirname = '';
                            }
                        } else {
                            $dirname = trim($parse_url_page['path'], "/\\");
                        }
                    }

                    if (isset($parse_url['path']) && !empty($dirname)) {
                        $parse_url['path'] = $dirname . '/' . ltrim($parse_url['path'], '/');
                    }
                }
            }
        }

        $result_url = "";

        if (count($parse_url) == 1 && isset($parse_url['query']) && isset($parse_url_page['path'])) {
            $parse_url['path'] = $parse_url_page['path'];
        }

        if (!isset($parse_url['scheme'])) {
            $parse_url['scheme'] = $parse_url_page['scheme'];
        } else {
            $parse_url['scheme'] = 'http';
        }

        if (!isset($parse_url['host'])) {
            $parse_url['host'] = $parse_url_page['host'];
        }

        if (isset($parse_url['scheme'])) {
            $result_url .= $parse_url['scheme'] . "://";
        }

        if (isset($parse_url['user']) && isset($parse_url['pass'])) {
            $result_url .= $parse_url['user'] . ":" . $parse_url['pass'] . "@";
        }

        if (isset($parse_url['host'])) {
            $result_url .= $parse_url['host'];
        }

        if (isset($parse_url['path'])) {
            $result_url .= "/" . ltrim($parse_url['path'], '/');
        }

        if (isset($parse_url['query'])) {
            $result_url .= "?" . $parse_url['query'];
        }

        if (isset($parse_url['fragment'])) {
            $result_url .= "#" . $parse_url['fragment'];
        }
    }

    return $result_url;
}

function preparation_data($data, $type = 'string', $glue = ';')
{
    $local_data = null;

    if (is_array($data)) {
        $local_data = [];

        $data = array_unique($data);

        foreach ($data as $key => $value) {
            $value = trim($value);
            if (!empty($value)) {
                $local_data[$key] = preparation_text($value);
            }
        }
    } else {
        $local_data = preparation_text($data);
    }

    if ($type == 'serialize') {
        return serialize($local_data);
    } elseif (is_array($local_data)) {
        $result = implode($glue, $local_data);
        $result = str_replace(['.,', ';,', ',,', '!,', '?,'], ['. ', '; ', ', ', '! ',
            '? '], $result);
        $result = trim($result, ', ');
        return $result;
    } elseif (is_string($local_data)) {
        return $local_data;
    } else {
        return null;
    }
}

// Подготавливаем html текст перед парсингом. Передается сразу перед любыми действиямию.
function preparation_html($html)
{
    return $html;
}

// Подготавливаем значения спарсеного массива
function preparation_text($str)
{
    return $str;
    
    $str = html_entity_decode($str, ENT_QUOTES);

    $str = encode_emoji($str);

    //$str = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $str);

    $str = preg_replace("#\s{2,}#", ' ', $str);

    return $str;
}

namespace main;

use \workup\App;

abstract class DatabaseHandler
{
    const TIMEOUT_CONNECT = 20;
    protected static $last_time = 0;

    protected static $connects_pdo = array();

    protected static $count_query = 0;
    protected static $duration_query = 0;
    
    protected static $is_start_debug = false;

    public static function GetHandler($connect = null, $is_count = true)
    {
        
        
        if (!$connect) {
            $connect = App::config('default_db');
        }

        if ($is_count) {
            self::$count_query++;
        }

        if (!isset(self::$connects_pdo[$connect]) || (time() - self::$last_time) > self::
            TIMEOUT_CONNECT) {
            $config = App::config('db');
            $config = $config[$connect];

            // Выполняем код, перехватывая потенциальные исключения
            try {
                // Создаем новый экземпляр класса PDO
                switch ($config['DRIVER']) {
                    case 'sqlsrv':
                        self::$connects_pdo[$connect] = new \PDO('sqlsrv:Server=' . $config['DB_SERVER'] .
                            ';Database=' . $config['DB_DATABASE'], $config['DB_USERNAME'], $config['DB_PASSWORD']);
                        // if ($config['DB_CHARSET'])
                        // self::$connects_pdo[$connect]->exec("SET character_set_database = " . $config['DB_CHARSET']);
                        // if ($config['DB_CHARSET'])
                        // self::$connects_pdo[$connect]->exec("SET NAMES " . $config['DB_CHARSET']);
                        break;
                    case 'sqlite':
                        self::$connects_pdo[$connect] = new \PDO('sqlite:' . $config['DB_DATABASE']);
                        break;
                    case 'mysql':
                        self::$connects_pdo[$connect] = new \PDO('mysql:host=' . $config['DB_SERVER'] .
                            ';dbname=' . $config['DB_DATABASE'] . ($config['DB_CHARSET'] ? ';charset=' . $config['DB_CHARSET'] : ''), $config['DB_USERNAME'],
                            $config['DB_PASSWORD'], array(\PDO::ATTR_PERSISTENT => $config['DB_PERSISTENCY']));
                        if ($config['DB_CHARSET'])
                            self::$connects_pdo[$connect]->exec("SET NAMES '" . $config['DB_CHARSET'] . "'");
                        
                        self::$connects_pdo[$connect]->exec("SET SQL_MODE='ALLOW_INVALID_DATES'");
                        
                        break;
                    default:
                        self::$connects_pdo[$connect] = null;

                }

                // Настраиваем PDO на генерацию исключений
                self::$connects_pdo[$connect]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::
                    ERRMODE_EXCEPTION);
            }
            catch (PDOException $e) {
                // Закрываем дескриптор и генерируем ошибку
                self::Close($connect);
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }

        self::$last_time = time();

        // Возвращаем дескриптор базы данных
        return self::$connects_pdo[$connect];
    }

    // Очищаем экземпляр класса PDO
    public static function Close($connect = null)
    {
        if (!$connect) {
            $connect = App::config('default_db');
        }

        self::$connects_pdo[$connect] = null;
    }

    public static function getCountQuery()
    {
        return self::$count_query;
    }

    public static function getDurationQuery()
    {
        return self::$duration_query;
    }
}

class DatabasePerform extends DatabaseHandler
{
    // Метод-обертка для PDOStatement::execute()
    public static function Execute($sqlQuery, $params = null, $connect = null)
    {
        self::debug($sqlQuery, $params);

        // Пытаемся выполнить SQL-запрос или хранимую процедуру
        try {
            $begin_time = microtime(true);
            // Получаем дескриптор базы данных
            $database_handler = self::GetHandler($connect);
            

            // Подготавливаем запрос к выполнению
            $statement_handler = $database_handler->prepare($sqlQuery);

            // Выполняем запрос
            
            $res = self::PrepareAndExecute($statement_handler, $params);
            
            self::$duration_query = self::$duration_query+(microtime(true) - $begin_time);
            
            return $res;
            //return $statement_handler->execute($params);
        }
        // Генерируем ошибку, если при выполнении SQL-запроса возникло исключение
        catch (PDOException $e) {
            // Закрываем дескриптор базы данных и генерируем ошибку
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    // Метод-обертка для PDOStatement::fetchAll(). Извлекает все строки
    public static function GetAll($sqlQuery, $params = null, $fetchStyle = \PDO::
        FETCH_ASSOC, $connect = null)
    {
        self::debug($sqlQuery, $params);

        $result = array();

        // Пытаемся выполнить SQL-запрос или хранимую процедуру
        try {
            $begin_time = microtime(true);
            // Получаем дескриптор базы данных
            $database_handler = self::GetHandler($connect);

            // Подготавливаем запрос к выполнению
            $statement_handler = $database_handler->prepare($sqlQuery);

            // Выполняем запрос
            //$statement_handler->execute($params);
            self::PrepareAndExecute($statement_handler, $params);

            // Получаем результат
            
            $result = $statement_handler->fetchAll($fetchStyle);
            
            self::$duration_query = self::$duration_query+(microtime(true) - $begin_time);
        }
        // Генерируем ошибку, если при выполнении SQL-запроса возникло исключение
        catch (PDOException $e) {
            // Закрываем дескриптор базы данных и генерируем ошибку
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        // Возвращаем результаты запроса
        return $result;
        //return $this->getCollection( $result );
    }

    // Метод-обертка для PDOStatement::fetch().  Извлечение следующей строки.
    public static function GetRow($sqlQuery, $params = null, $fetchStyle = \PDO::
        FETCH_ASSOC, $connect = null)
    {
        self::debug($sqlQuery, $params);

        // Инициализируем возвращаемое значение
        $result = null;

        // Пытаемся выполнить SQL-запрос или хранимую процедуру
        try {
            $begin_time = microtime(true);
            // Получаем дескриптор базы данных
            $database_handler = self::GetHandler($connect);

            // Готовим запрос к выполнению
            $statement_handler = $database_handler->prepare($sqlQuery);

            // Выполняем запрос
            //$statement_handler->execute($params);
            self::PrepareAndExecute($statement_handler, $params);

            // Получаем результат
            
            $result = $statement_handler->fetch($fetchStyle);
            
            self::$duration_query = self::$duration_query+(microtime(true) - $begin_time);
        }
        // Генерируем ошибку, если при выполнении SQL-запроса возникло исключение
        catch (PDOException $e) {
            // Закрываем дескриптор базы данных и генерируем ошибку
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        // Возвращаем результаты запроса
        return $result;
    }

    // Возвращает значение первого столбца из строки
    public static function GetOne($sqlQuery, $params = null, $connect = null)
    {
        self::debug($sqlQuery, $params);

        // Инициализируем возвращаемое значение
        $result = null;

        // Пытаемся выполнить SQL-запрос или хранимую процедуру
        try {
            $begin_time = microtime(true);
            // Получаем дескриптор базы данных
            $database_handler = self::GetHandler($connect);

            // Готовим запрос к выполнению
            $statement_handler = $database_handler->prepare($sqlQuery);

            // Выполняем запрос
            //$statement_handler->execute($params);
            self::PrepareAndExecute($statement_handler, $params);

            // Получаем результат
            
            $result = $statement_handler->fetch(\PDO::FETCH_NUM);
            
            self::$duration_query = self::$duration_query+(microtime(true) - $begin_time);

            /* Сохраняем первое значение из множества (первый столбец первой строки) в переменной $result */
            $result = $result[0];
        }

        // Генерируем ошибку, если при выполнении SQL-запроса возникло исключение
        catch (PDOException $e) {
            // Закрываем дескриптор базы данных и генерируем ошибку
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        // Возвращаем результаты выполнения запроса
        return $result;
    }
    
    public static function Quote($string, $connect = null)
    {
        return trim(self::GetHandler($connect, false)->quote($string), "'");
    }

    // Возвращает значение первого столбца из строки
    public static function LastInsertId($connect = null)
    {
        self::debug('LAST_INSERT_ID();');
        
        // Инициализируем возвращаемое значение
        $result = null;

        // Пытаемся выполнить SQL-запрос или хранимую процедуру
        try {
            $begin_time = microtime(true);
            // Получаем дескриптор базы данных
            $database_handler = self::GetHandler($connect, false);

            
            $result = $database_handler->lastInsertId();
            
            self::$duration_query = self::$duration_query+(microtime(true) - $begin_time);
        }

        // Генерируем ошибку, если при выполнении SQL-запроса возникло исключение
        catch (PDOException $e) {
            // Закрываем дескриптор базы данных и генерируем ошибку
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        // Возвращаем результаты выполнения запроса
        return $result;
    }

    private static function PrepareAndExecute($sth, &$params)
    {
        if (is_array($params))
            foreach ($params as $key => $value) {
                switch (gettype($value)) {
                    case 'boolean';
                        $sth->bindValue($key, $value, \PDO::PARAM_BOOL);
                        break;
                    case 'integer';
                        $sth->bindValue($key, $value, \PDO::PARAM_INT);
                        break;
                    case 'double';
                        $sth->bindValue($key, $value, \PDO::PARAM_STR);
                        break;
                    case 'string';
                        $sth->bindValue($key, $value, \PDO::PARAM_STR);
                        break;
                    case 'array';
                        break;
                    case 'object';
                        break;
                    case 'resource';
                        break;
                    case 'NULL';
                        $sth->bindValue($key, $value, \PDO::PARAM_NULL);
                        break;
                    case 'unknown type';
                        break;
                }
            }

        return $sth->execute();
    }

    protected static function debug($sqlQuery = null, $params = null)
    {
        //echo $sqlQuery;
    }

    public static function GetAutoIncrement($table, $connect = null)
    {
        $row = self::GetRow("SHOW TABLE STATUS FROM `".config('db.'.($connect ? $connect : config('default_db')).'.DB_DATABASE')."` LIKE ?", [1 => $table]);
        
        if($row && isset($row['Auto_increment'])){
            return $row['Auto_increment'];
        }
    }
}

namespace workup\base;

use main;

class AppException extends \Exception
{
}

class DBException extends \Exception
{
    private $error;
    function __construct(DB_Error $error)
    {
        parent::__construct($error->getMessage(), $error->getCode());
        $this->error = $db_error;
    }

    function getErrorObject()
    {
        return $this->error;
    }

}

namespace main;

class Auth
{
    private static $user = "empty";

    private static function isUserSession()
    {
        if (isset($_SESSION['auth']) && is_array($_SESSION['auth']) && isset($_SESSION['auth']['id']) &&
            isset($_SESSION['auth']['name']) && isset($_SESSION['auth']['login']) && isset($_SESSION['auth']['password']) &&
            isset($_SESSION['auth']['privileges'])) {
            return true;
        }
    }

    public static function statusAutorization()
    {
        if (\workup\App::config('WORDPRESS')) {
            return is_user_logged_in();
        }
        
        if (self::isUserSession() && self::get('id') == $_SESSION['auth']['id'] && self::
            get('login') == $_SESSION['auth']['login'] && self::get('password') == $_SESSION['auth']['password']) {
            return true;
        }

        return false;
    }

    private static function get($key)
    {
        if (self::isUserSession()) {
            if (self::$user == "empty") {
                $user_obj = \workup\model\User::find($_SESSION['auth']['id']);

                self::$user = array(
                    'id' => $user_obj->id,
                    'name' => $user_obj->name,
                    'login' => $user_obj->login,
                    'privileges' => $user_obj->privileges,
                    'password' => $user_obj->password);
            }

            if (self::$user && is_array(self::$user)) {
                if (isset(self::$user[$key])) {
                    return self::$user[$key];
                }
            }
        }
        return null;
    }

    public static function getId()
    {
        if (\workup\App::config('WORDPRESS')) {
            $user = wp_get_current_user();
            return $user ? $user->ID : null;
        }
        
        if (self::statusAutorization()) {
            return self::get('id');
        }
        return null;
    }

    public static function getName()
    {
        if (\workup\App::config('WORDPRESS')) {
            $user = wp_get_current_user();
            return $user ? $user->user_nicename : null;
        }
        
        if (self::statusAutorization()) {
            return self::get('name');
        }
        return null;
    }

    public static function getLogin()
    {
        if (\workup\App::config('WORDPRESS')) {
            $user = wp_get_current_user();
            return $user ? $user->user_login : null;
        }
        
        if (self::statusAutorization()) {
            return self::get('login');
        }
        return null;
    }

    public static function getPrivileges()
    {
        if (\workup\App::config('WORDPRESS')) {
            $role = 'guest';
            
            $user = wp_get_current_user();
            
            $roles = $user ? $user->roles : [];
            
            if (is_array($roles)) {
                
            }
            
            foreach ($roles as $key => $value) {
                $role = $value;
                
                if($role == 'administrator'){
                    $role = 'admin';
                    break;
                }
            }
            
            return $role;
        }
        
        if (self::statusAutorization()) {
            return self::get('privileges');
        }
        return null;
    }
}

namespace main;

class Link
{
    public static $cmd = 'cmd';

    public static function Build($link = '', $type = 'https')
    {
        $base = (($type == 'https' && \workup\App::config('USE_SSL') == 'yes') ? 'https://' : 'http://') .
            getenv('SERVER_NAME');

        // Если константа \workup\App::config('HTTP_SERVER_PORT') определена и значение отличается
        // от используемого по умолчанию...
        if (\workup\App::config('HTTP_SERVER_PORT') != '80' && strpos($base,
            'https') === false) {
            // Добавляем номер порта
            $base .= ':' . \workup\App::config('HTTP_SERVER_PORT');
        }
        
        if (\workup\App::config('WORDPRESS')) {
            $link = 'page=wp-parser&' . $link;
        }

        $link = $base . \workup\App::config('VIRTUAL_LOCATION') . \workup\App::config('FILE_SCRIPT_NAME') . ".php?" . $link;

        return $link;
    }

    public static function ListSources($p = null)
    {
        if (!$p && isset($_REQUEST['p'])) {
            $p = $_REQUEST['p'];
        }

        $link = self::$cmd . "=Sources&p=" . intval($p);

        return self::Build($link);
    }

    public static function ListCron($p = null)
    {
        if (!$p && isset($_REQUEST['p'])) {
            $p = $_REQUEST['p'];
        }

        $link = self::$cmd . "=Cron&p=" . intval($p);

        return self::Build($link);
    }

    public static function SourceUpdate($id = null)
    {
        return self::Build(self::$cmd . "=SourceUpdate" . ($id ? "&id=" . intval($id) :
            '') . (isset($_REQUEST['p']) ? '&p=' . $_REQUEST['p'] : ''));
    }

    public static function SourceUpdateCron($id = null)
    {
        return self::Build(self::$cmd . "=SourceUpdateCron" . ($id ? "&id=" . intval($id) :
            '') . (isset($_REQUEST['p']) ? '&p=' . $_REQUEST['p'] : ''));
    }

    public static function SourceData($id)
    {
        return self::Build(self::$cmd . "=SourceData&id_source_data=" . intval($id));
    }

    public static function SourceDataCron($id)
    {
        return self::Build(self::$cmd . "=SourceDataCron&id_source_data=" . intval($id));
    }

    public static function ClearSourceData($id)
    {
        return self::Build(self::$cmd . "=ClearSourceData&id_source_data=" . intval($id));
    }

    public static function ClearSourceDataCron($id)
    {
        return self::Build(self::$cmd . "=ClearSourceDataCron&id_source_data=" . intval
            ($id));
    }

    public static function DropSourceTable($id)
    {
        return self::Build(self::$cmd . "=DropSourceTable&id_source_data=" . intval($id));
    }

    public static function DropSourceTableCron($id)
    {
        return self::Build(self::$cmd . "=DropSourceTableCron&id_source_data=" . intval($id));
    }

    public static function Autorization()
    {
        return self::Build(self::$cmd . "=Autorization");
    }

    public static function Logout()
    {
        return self::Build(self::$cmd . "=Autorization&logout=1");
    }

    public static function ListUsers($p = null)
    {
        if (!$p && isset($_REQUEST['p'])) {
            $p = $_REQUEST['p'];
        }

        $link = self::$cmd . "=Users&p=" . intval($p);

        return self::Build($link);
    }

    public static function SaveParser($p = null)
    {
        return self::Build(self::$cmd . "=SaveParser");
    }

    public static function UserAdd()
    {
        return self::Build(self::$cmd . "=UserAdd");
    }

    public static function UserEdit($id)
    {
        $p = 1;

        if (!$p && isset($_REQUEST['p'])) {
            $p = $_REQUEST['p'];
        }

        $link = self::$cmd . "=UserEdit&id=" . $id . "&p=" . intval($p);

        return self::Build($link);
    }
}

namespace main;

function clearHtml($html)
{
    $html = str_replace("<br>", '<br />', $html);
    $html = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/sui", '<$1$2>', $html);
    $html = str_replace(["<h3>", '</h3>'], ['<h3>', '</h3>'], $html);
    $html = str_replace(["<h2>", '</h2>'], ['<h3>', '</h3>'], $html);
    $html = str_replace(["<h1>", '</h1>'], ['<h3>', '</h3>'], $html);

    $html = preg_replace("/<(a)[^>]*?(\/?)>(.*?)<\/a>/isu", '$3', $html);

    $html = preg_replace("#<script[^<>/]*?>.*?</script>#sui", '', $html);
    $html = preg_replace("#<svg[^<>/]*?>.*?</svg>#sui", '', $html);
    $html = preg_replace("#<iframe[^<>/]*?>.*?</iframe>#sui", '', $html);
    $html = preg_replace("#<frame[^<>/]*?>.*?</frame>#sui", '', $html);
    $html = preg_replace("#<img[^<>/]*?/?>#sui", '', $html);
    $html = preg_replace("#<head[^<>/]*?>.*?</head>#sui", '', $html);

    $html = preg_replace("#<body[^<>/]*?>#sui", '', $html);
    $html = preg_replace("#<html[^<>/]*?>#sui", '', $html);

    $html = str_replace("<body>", '', $html);
    $html = str_replace("</body>", '', $html);
    $html = str_replace("<html>", '', $html);
    $html = str_replace("</html>", '', $html);

    $html = str_replace('справка | детали | валюта | карта страны', '', $html);

    $html = str_replace("<div>  <br/><br/>
</div>", '', $html);

    $html = close_tags($html);

    return trim($html);
}

function close_tags($content)
{
    $position = 0;
    $open_tags = array();
    //теги для игнорирования
    $ignored_tags = array(
        'br',
        'hr',
        'img');

    while (($position = strpos($content, '<', $position)) !== false) {
        //забираем все теги из контента
        if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match)) {
            $tag = strtolower($match[2]);
            //игнорируем все одиночные теги
            if (in_array($tag, $ignored_tags) == false) {
                //тег открыт
                if (isset($match[1]) and $match[1] == '') {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]++;
                    else
                        $open_tags[$tag] = 1;
                }
                //тег закрыт
                if (isset($match[1]) and $match[1] == '/') {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]--;
                }
            }
            $position += strlen($match[0]);
        } else
            $position++;
    }
    //закрываем все теги
    foreach ($open_tags as $tag => $count_not_closed) {
        if ($count_not_closed > 0)
            $content .= str_repeat("</{$tag}>", $count_not_closed);
    }

    return $content;
}

function html_remove_attributes($text, $allowed = [])
{
    $attributes = implode('|', $allowed);
    $reg = '/(<[\w]+)([^>]*)(>)/i';
    $text = preg_replace_callback($reg, function ($matches)use ($attributes)
    {
        // Если нет разрешенных атрибутов, возвращаем пустой тег
        if (!$attributes) {
            return $matches[1] . $matches[3]; }

        $attr = $matches[2]; $reg = '/(' . $attributes . ')="[^"]*"/i'; preg_match_all($reg,
            $attr, $result); $attr = implode(' ', $result[0]); $attr = ($attr ? ' ' : '') .
            $attr; return $matches[1] . $attr . $matches[3]; }
    , $text);

    return $text;
}

function array_get($array, $key, $default = null)
{
    if (!is_array($array)) {
        return $default;
    }

    if (is_null($key)) {
        return $array;
    }

    if (array_key_exists($key, $array)) {
        return $array[$key];
    }

    foreach (explode('.', $key) as $segment) {
        if (is_array($array) && array_key_exists($segment, $array)) {
            $array = $array[$segment];
        } else {
            return $default;
        }
    }

    return $array;
}

function delFolder($dir)
{
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir($dir . DIRECTORY_SEPARATOR . $file)) ? delFolder($dir .
                DIRECTORY_SEPARATOR . $file) : unlink($dir . DIRECTORY_SEPARATOR . $file);
        }
        return rmdir($dir);
    }
}

function squeeze_values_array($values)
{
    $result = [];

    if (is_array($values)) {
        foreach ($values as $value) {
            if (!empty($value['key']) && !empty($value['value'])) {
                $result[] = $value;
            }
        }
    }

    return serialize($result);
}

function squeeze_curlopt_array($curlopts)
{
    $result = [];

    if (is_array($curlopts)) {
        foreach ($curlopts as $curlopt) {
            if (preg_match("#^CURLOPT_#", $curlopt['key']) && !empty($curlopt['value'])) {
                if (defined($curlopt['key'])) {
                    $result[] = $curlopt;
                }
            }
        }
    }

    return serialize($result);
}

function build_fields_triplet($request, $key, $key_1, $key_2, $key_3)
{
    $result = [];

    $method_key_1 = $key . '_' . $key_1;
    $method_key_2 = $key . '_' . $key_2;
    $method_key_3 = $key . '_' . $key_3;

    if ($request->$method_key_1 && $request->$method_key_2 && $request->$method_key_3 &&
        is_array($request->$method_key_1) && is_array($request->$method_key_2) &&
        is_array($request->$method_key_3)) {
        $req_key_1 = $request->$method_key_1;
        $req_key_2 = $request->$method_key_2;
        $req_key_3 = $request->$method_key_3;

        foreach ($req_key_1 as $key => $value) {
            if (isset($req_key_2[$key]) && isset($req_key_3[$key]) && !empty($req_key_1[$key]) &&
                !empty($req_key_2[$key]) && !empty($req_key_3[$key])) {
                $result[] = [$key_1 => trim($req_key_1[$key]), $key_2 => trim($req_key_2[$key]),
                    $key_3 => trim($req_key_3[$key]), ];
            }
        }
    }

    return $result;
}

function build_fields_couple($request, $key, $suffix_, $_suffix)
{
    $result = [];

    $method_phrase = $key . '_' . $suffix_;
    $method_attribute = $key . '_' . $_suffix;

    if ($request->$method_phrase && $request->$method_attribute && is_array($request->
        $method_attribute) && is_array($request->$method_attribute)) {
        $phrase = $request->$method_phrase;
        $attribute = $request->$method_attribute;

        foreach ($phrase as $key => $value) {
            if (isset($attribute[$key]) && !empty($phrase[$key])) {
                $result[] = [$suffix_ => trim($phrase[$key]), $_suffix => trim($attribute[$key]), ];
            }
        }
    }

    return $result;
}

function squeeze($var, $pattern = null)
{
    $result = array();

    if (!empty($var) && is_array($var)) {
        foreach ($var as $key => $value) {
            if (!empty($value)) {
                if ($pattern) {
                    if (preg_match($pattern, $value)) {
                        $result[$key] = trim($value);
                    }
                } else {
                    $result[$key] = trim($value);
                }
            }
        }
    }

    return serialize($result);
}

function unsqueeze($var)
{
    $result = null;

    if (!empty($var)) {
        $result = unserialize($var);
    }

    if (is_array($result)) {
        return $result;
    } else {
        return array();
    }
}

function getPaginator($count, $limit, $targetpage, $symbol = '?')
{
    $stages = 3;
    $page = (isset($_GET['p']) ? intval($_GET['p']) : 1);

    global $start;
    if ($page) {
        $start = ($page - 1) * $limit;
    } else {
        $start = 0;
    }

    // Инициализируем начальные параметры
    if ($page == 0)
        $page = 1;
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($count / $limit);
    $LastPagem1 = $lastpage - 1; // Предпоследняя страница

    $paginate = ''; // div блок, в котором будет содержаться навигация

    $current = 'style="color: #000080; background-color: #EEE8AA;"';

    if ($lastpage > 1) {
        $paginate .= '<ul class="pagination">';
        // Формирование ссылки "Предыдущая"
        if ($page > 1) {
            $paginate .= "<li><a href='$targetpage" . $symbol . "p=$prev'>&laquo;</a></li>";
        } else {
            $paginate .= "<li><span class='disabled'>&laquo;</span></li>";
        }

        // Страницы
        if ($lastpage < 7 + ($stages * 2))
            // Недостаточно страниц для создания троеточия
            {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page) {
                    $paginate .= "<li $current><span $current class='current'>$counter</span></li>";
                } else {
                    $paginate .= "<li><a href='$targetpage" . $symbol . "p=$counter'>$counter</a></li>";
                }
            }
        } elseif ($lastpage > 5 + ($stages * 2))
        // Достаточно страниц, чтобы скрыть несколько из них
            {
            if ($page < 1 + ($stages * 2)) {
                for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<li $current><span $current class='current'>$counter</span></li>";
                    } else {
                        $paginate .= "<li><a href='$targetpage" . $symbol . "p=$counter'>$counter</a></li>";
                    }
                }
                $paginate .= "<li><span>...</span></li>";
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=$LastPagem1'>$LastPagem1</a></li>";
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=$lastpage'>$lastpage</a></li>";
            } elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=1'>1</a></li>";
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=2'>2</a></li>";
                $paginate .= "<li><span>...</span></li>";
                for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<li $current><span $current class='current'>$counter</span></li>";
                    } else {
                        $paginate .= "<li><a href='$targetpage" . $symbol . "p=$counter'>$counter</a></li>";
                    }
                }
                $paginate .= "<li><span>...</span></li>";
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=$LastPagem1'>$LastPagem1</a></li>";
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=$lastpage'>$lastpage</a></li>";
            } else {
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=1'>1</a></li>";
                $paginate .= "<li><a href='$targetpage" . $symbol . "p=2'>2</a></li>";
                $paginate .= "<li><span>...</span></li>";
                for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<li $current><span $current class='current'>$counter</span></li>";
                    } else {
                        $paginate .= "<li><a href='$targetpage" . $symbol . "p=$counter'>$counter</a></li>";
                    }
                }
            }
        }

        // Формирование ссылки "Следующая"
        if ($page < $counter - 1) {
            $paginate .= "<li><a href='$targetpage" . $symbol . "p=$next'>&raquo;</a></li>";
        } else {
            $paginate .= "<li><span class='disabled'>&raquo;</span></li>";
        }

        $paginate .= '</ul>';
    }
    return $paginate; // Возвращаем текстовую переменную, которая содержит блок со страничной навигацией
}

function download_file($filename)
{
    preg_match('/^.+\/([^\/]+)$/i', $filename, $matches);

    header('Content-Disposition: attachment; filename=' . $matches[1]);
    header('Content-Length: ' . filesize($filename));
    header('Keep-Alive: timeout=5, max=100');
    header('Connection: Keep-Alive');
    header('Content-Type: octet-stream');
    readfile($filename);
}

function str_limit($value, $limit = 100, $end = '...')
{
    if (mb_strwidth($value, 'UTF-8') <= $limit) {
        return $value;
    }

    return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
}

function translit($s, $del = '_')
{
    $s = (string )$s; // преобразуем в строковое значение

    $s = mb_convert_encoding($s, "UTF-8");

    $s = strip_tags($s); // убираем HTML-теги
    $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
    $s = trim($s); // убираем пробелы в начале и конце строки
    $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
    $s = strtr($s, array(
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shch',
        'ы' => 'y',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        'ъ' => '',
        'ь' => '',
        "і" => "i",
        "ї" => "i",
        "є" => "ie"));

    $s = preg_replace("/[^0-9a-z]/i", $del, $s); // очищаем строку от недопустимых символов

    $s = preg_replace("#" . $del . "{2,}#", $del, $s);

    $s = trim($s, $del);

    return $s; // возвращаем результат
}

function encode_emoji($content)
{
    if (function_exists('mb_convert_encoding')) {
        $regex = '/(
		     \x23\xE2\x83\xA3               # Digits
		     [\x30-\x39]\xE2\x83\xA3
		   | \xF0\x9F[\x85-\x88][\xA6-\xBF] # Enclosed characters
		   | \xF0\x9F[\x8C-\x97][\x80-\xBF] # Misc
		   | \xF0\x9F\x98[\x80-\xBF]        # Smilies
		   | \xF0\x9F\x99[\x80-\x8F]
		   | \xF0\x9F\x9A[\x80-\xBF]        # Transport and map symbols
		)/x';

        $matches = array();
        if (preg_match_all($regex, $content, $matches)) {
            if (!empty($matches[1])) {
                foreach ($matches[1] as $emoji) {
                    /*
                    * UTF-32's hex encoding is the same as HTML's hex encoding.
                    * So, by converting the emoji from UTF-8 to UTF-32, we magically
                    * get the correct hex encoding.
                    */
                    $unpacked = unpack('H*', mb_convert_encoding($emoji, 'UTF-32', 'UTF-8'));
                    if (isset($unpacked[1])) {
                        $entity = '&#x' . ltrim($unpacked[1], '0') . ';';
                        $content = str_replace($emoji, $entity, $content);
                    }
                }
            }
        }
    }

    return $content;
}

function getCodeTextDir($dirname)
{
    $texts = [];

    $dir = opendir($dirname);
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            if (is_file($dirname . '/' . $file)) {
                $path_parts = pathinfo($dirname . '/' . $file);
                if ($path_parts && isset($path_parts['extension']) && $path_parts['extension'] ==
                    'php') {
                    if (!in_array($dirname . '/' . $file, ['workup/command/DefaultSources.php',
                        'workup/base/ViewHelper.php', 'workup/base/IncludeFile.php',
                        'workup/base/View.php'])) {
                        $view = getCodeText($dirname . '/' . $file);

                        if (!empty($view))
                            $texts[] = $view;
                    }

                }
            }
        }
    }

    closedir($dir);

    return implode("", $texts);
}

function getCodeTextViewDir($dirname)
{
    $texts = [];

    $dir = opendir($dirname);
    while (($file = readdir($dir)) !== false) {
        if ($file != "." && $file != "..") {
            if (is_file($dirname . '/' . $file)) {
                $path_parts = pathinfo($dirname . '/' . $file);
                if ($path_parts && isset($path_parts['extension']) && $path_parts['extension'] ==
                    'php') {
                    $view = getCodeText($dirname . '/' . $file);

                    $view = str_replace('namespace main;', '', $view);

                    $view = trim($view);

                    $texts[] = '    private function view' . str_replace('.php', '', $file) . " () {\r\n" .
                        $view . "\r\n    }";
                }
            }
        }
    }

    closedir($dir);

    return implode("\r\n\r\n", $texts);
}

function getCodeTextJavascript($path)
{
    $text = getCodeText($path);

    return '    private function javascript' . str_replace(['/', '\\', '.', '-'],
        '_', trim(str_replace('.js', '', $path), './ ')) . " () {\r\n?><script type=\"text/javascript\">\r\n" .
        $text . "\r\n</script><?php\r\n    }\r\n";
}

function getCodeTextCss($path)
{
    $text = getCodeText($path);

    return '    private function css' . str_replace(['/', '\\', '.', '-'], '_', trim
        (str_replace('.css', '', $path), './ ')) . " () {\r\n?><style>\r\n" . $text . "\r\n</style><?php\r\n    }\r\n";
}

function getCodeText($path)
{
    global $paths;

    if (!is_array($paths)) {
        $paths = [];
    }

    if (in_array($path, $paths)) {
        return '';
        echo 'Dublicate File: ' . $path;
        exit();
    }

    if (file_exists(\workup\App::config('SITE_ROOT') . '/' . $path)) {
        $paths[] = $path;
        return prepareCodeText(file_get_contents(\workup\App::config('SITE_ROOT') . '/' .
            $path));
    } else {
        echo 'File does not exist: ' . $path;
        exit();
    }

    return '';
}

function prepareCodeText($text)
{
    $text = trim($text);
    $text = preg_replace("#^<\?php#si", '', $text);
    $text = preg_replace("#\?>$#si", '', $text);
    $text = trim($text);
    $text = "\r\n" . $text . "\r\n";
    return $text;
}

namespace workup\base;

abstract class Registry
{
    abstract protected function get($key);
    abstract protected function set($key, $val);
}

class RequestRegistry extends Registry
{
    private $values = array();
    private static $instance = null;

    private function __construct()
    {
    }

    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
        return null;
    }

    protected function set($key, $val)
    {
        $this->values[$key] = $val;
    }

    static function getRequest()
    {
        $inst = self::instance();
        if (is_null($inst->get("request"))) {
            $inst->set('request', new \workup\controller\Request());
        }
        return $inst->get("request");
    }

}

class SessionRegistry extends Registry
{
    private static $instance = null;
    private function __construct()
    {
        session_start();
    }

    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get($key)
    {
        if (isset($_SESSION[__class__][$key])) {
            return $_SESSION[__class__][$key];
        }
        return null;
    }

    protected function set($key, $val)
    {
        $_SESSION[__class__][$key] = $val;
    }

    function setDSN($dsn)
    {
        self::instance()->set('dsn', $dsn);
    }

    function getDSN()
    {
        return self::instance()->get("dsn");
    }
}

class ApplicationRegistry extends Registry
{
    private static $instance = null;
    private $freezedir = null;
    private $values = array();
    private $mtimes = array();

    private $request = null;

    private function __construct()
    {
        $this->freezedir = \workup\App::config('DIR_DATA');
    }

    static function clean()
    {
        self::$instance = null;
    }

    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        $path = $this->freezedir . DIRECTORY_SEPARATOR . $key;

        if (file_exists($path)) {
            clearstatcache();
            $mtime = filemtime($path);
            if (!isset($this->mtimes[$key])) {
                $this->mtimes[$key] = 0;
            }

            if ($mtime > $this->mtimes[$key]) {
                $data = file_get_contents($path);
                $this->mtimes[$key] = $mtime;
                return ($this->values[$key] = unserialize($data));
            }
        }

        return null;
    }

    protected function set($key, $val, $write = true)
    {
        $this->values[$key] = $val;

        if ($write) {
            $path = $this->freezedir . DIRECTORY_SEPARATOR . $key;
            file_put_contents($path, serialize($val));
            $this->mtimes[$key] = time();

            return filemtime($path);
        }
    }

    static function getDSN()
    {
        return PDO_DSN;
    }

    static function setDSN($dsn)
    {
        return true;
    }

    static function setControllerMap(\workup\controller\ControllerMap $map, $write = true)
    {
        $instance = self::instance();

        $instance->set('cmap', $map, $write);

        if ($write) {
            $path = $instance->freezedir . DIRECTORY_SEPARATOR . 'options.xml';

            if (file_exists($path)) {
                $instance->set('timeUpdateCmap', filemtime($path));
            }
        }
    }

    static function getControllerMap()
    {
        return self::instance()->get('cmap');
    }

    static function isRelevanceControllerMap()
    {
        return self::instance()->relevanceControllerMap();
    }

    protected function relevanceControllerMap()
    {
        $time_last_update = self::instance()->get('timeUpdateCmap');

        if (!is_null($time_last_update)) {
            $path = $this->freezedir . DIRECTORY_SEPARATOR . 'options.xml';

            if (file_exists($path)) {
                $time_now_update = filemtime($path);
                if ($time_now_update && $time_last_update == $time_now_update) {
                    return true;
                }
            }
        }

        return false;
    }

    static function appController()
    {
        $obj = self::instance();
        if (!isset($obj->appController)) {
            $cmap = $obj->getControllerMap();
            $obj->appController = new \workup\controller\AppController($cmap);
        }
        return $obj->appController;
    }

    static function getRequest()
    {
        $inst = self::instance();
        if (is_null($inst->request)) {
            $inst->request = new \workup\controller\Request();
        }
        return $inst->request;
    }
}

class MemApplicationRegistry extends Registry
{
    private static $instance = null;
    private $values = array();
    private $id;

    private function __construct()
    {
    }

    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function get($key)
    {
        return \apc_fetch($key);
    }

    protected function set($key, $val)
    {
        return \apc_store($key, $val);
    }

    static function getDSN()
    {
        return self::instance()->get("dsn");
    }

    static function setDSN($dsn)
    {
        return self::instance()->set("dsn", $dsn);
    }

}

namespace workup\model;

use main\DatabasePerform;

interface Finder
{
    function find($id);
    function findAll();

    function update(ModelObject $obj);
    function insert(ModelObject $obj);
    function delete(ModelObject $obj);
}

interface UserFinder extends Finder
{
}

interface SourceFinder extends Finder
{
}

namespace workup\record;

use main\DatabasePerform;

abstract class Collection
{
    protected $dofact;
    protected $total = 0;
    protected $raw = array();

    private $result;
    private $pointer = 0;
    private $objects = array();

    function __construct(array $raw = null, ModelObjectFactory $dofact = null)
    {
        if (!is_null($raw) && !is_null($dofact)) {
            $this->raw = $raw;
            $this->total = count($raw);
        }
        $this->dofact = $dofact;
    }

    function add(\workup\model\ModelObject $object)
    {
        $class = $this->targetClass();
        if (!($object instanceof $class)) {
            throw new Exception("This is a {$class} collection");
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

    function getGenerator()
    {
        for ($x = 0; $x < $this->total; $x++) {
            yield($this->getRow($x));
        }
    }

    abstract function targetClass();

    protected function notifyAccess()
    {
        // deliberately left blank!
    }

    private function getRow($num)
    {
        $this->notifyAccess();
        if ($num >= $this->total || $num < 0) {
            return null;
        }
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }

        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->dofact->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function current()
    {
        return $this->getRow($this->pointer);
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        $row = $this->getRow($this->pointer);
        if ($row) {
            $this->pointer++;
        }
        return $row;
    }

    public function valid()
    {
        return (!is_null($this->current()));
    }
}

namespace workup\model;

use main\DatabasePerform;

interface UserCollection extends \Iterator
{
    function add(ModelObject $event);
}


interface SourceCollection extends \Iterator
{
    function add(ModelObject $event);
}

namespace workup\record;

class UserCollection extends Collection implements \workup\model\UserCollection
{

    function targetClass()
    {
        return "\workup\model\User";
    }
}

class SourceCollection extends Collection implements \workup\model\SourceCollection
{

    function targetClass()
    {
        return "\workup\model\Source";
    }
}

class CronCollection extends Collection implements \workup\model\SourceCollection
{

    function targetClass()
    {
        return "\workup\model\Cron";
    }
}

class SourceDataCollection extends Collection implements \workup\model\SourceCollection
{

    function targetClass()
    {
        return "\workup\model\SourceData";
    }
}

namespace workup\record;

use main\DatabasePerform;

abstract class PersistenceFactory
{

    abstract function getRecord();
    abstract function getModelObjectFactory();
    abstract function getCollection(array $array);

    static function getFactory($target_class)
    {
        switch ($target_class) {
            case '\workup\model\User';
                return new UserPersistenceFactory();
                break;
            case '\workup\model\Source';
                return new SourcePersistenceFactory();
                break;
            case '\workup\model\Cron';
                return new CronPersistenceFactory();
                break;
            case '\workup\model\SourceData';
                return new SourceDataPersistenceFactory();
                break;
            default;
                break;
        }
    }
}

abstract class ModelObjectFactory
{
    protected abstract function targetClass();

    public function createObject(array $array)
    {
        $class = $this->targetClass();
        $old = $this->getFromMap($class, $array['id']);
        if ($old) {
            return $old;
        }
        $obj = new $class($array);

        $obj->build();

        $this->addToMap($obj);
        $obj->markClean();
        return $obj;
    }

    protected function getFromMap($class, $id)
    {
        return \workup\model\ObjectWatcher::exists($class, $id);
    }

    protected function addToMap(\workup\model\ModelObject $obj)
    {
        return \workup\model\ObjectWatcher::add($obj);
    }

}

class UserObjectFactory extends ModelObjectFactory
{
    function targetClass()
    {
        return "\workup\model\User";
    }
}

class UserPersistenceFactory extends PersistenceFactory
{
    function getRecord()
    {
        return new UserRecord();
    }

    function getModelObjectFactory()
    {
        return new UserObjectFactory();
    }

    function getCollection(array $array)
    {
        return new UserCollection($array, $this->getModelObjectFactory());
    }
}

class SourcePersistenceFactory extends PersistenceFactory
{
    function getRecord()
    {
        return new SourceRecord();
    }

    function getModelObjectFactory()
    {
        return new SourceObjectFactory();
    }

    function getCollection(array $array)
    {
        return new SourceCollection($array, $this->getModelObjectFactory());
    }
}

class CronPersistenceFactory extends PersistenceFactory
{
    function getRecord()
    {
        return new CronRecord();
    }

    function getModelObjectFactory()
    {
        return new CronObjectFactory();
    }

    function getCollection(array $array)
    {
        return new CronCollection($array, $this->getModelObjectFactory());
    }
}

class SourceObjectFactory extends ModelObjectFactory
{
    function targetClass()
    {
        return "\workup\model\Source";
    }
}

class CronObjectFactory extends ModelObjectFactory
{
    function targetClass()
    {
        return "\workup\model\Cron";
    }
}

class SourceDataPersistenceFactory extends PersistenceFactory
{
    function getRecord()
    {
        return new SourceDataRecord();
    }

    function getModelObjectFactory()
    {
        return new SourceDataObjectFactory();
    }

    function getCollection(array $array)
    {
        return new SourceDataCollection($array, $this->getModelObjectFactory());
    }
}

class SourceDataObjectFactory extends ModelObjectFactory
{
    function targetClass()
    {
        return "\workup\model\SourceData";
    }
}

namespace workup\controller;

use main;

class ApplicationHelper
{
    private static $instance = null;
    private $config = null;
    private $options = [];

    private function __construct()
    {
        $this->config = \workup\App::config('SITE_ROOT') . "/config/Options.xml";
        if (\workup\App::config('TYPE_OPTIONS') == 'array') {
            if (isset($GLOBALS['app_options'])) {
                $this->options = $GLOBALS['app_options'];
            } elseif (file_exists(\workup\App::config('SITE_ROOT') . '/config/Options.php')) {
                $this->options = require (\workup\App::config('SITE_ROOT') .
                    '/config/Options.php');
            }
        }
    }

    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function init($configpath = null)
    {
        if (\workup\App::config('TYPE_OPTIONS') == 'array') {
            $map = new ControllerMap();
            foreach ($this->options as $type => $cmds) {
                foreach ($cmds as $cmd => $data) {
                    if ($type == 'forwards') {
                        foreach ($data as $status => $view) {
                            $map->addForward($cmd, \workup\command\Command::statuses($status), $view);
                        }
                    }
                    if ($type == 'views') {
                        foreach ($data as $status => $view) {
                            $map->addView($cmd, \workup\command\Command::statuses($status), $view);
                        }
                    }
                    if ($type == 'classaliases') {
                        $map->addClassroot($cmd, $data);
                    }

                }
            }

            \workup\base\ApplicationRegistry::setControllerMap($map, false);
            return;
        }

        if (\workup\base\ApplicationRegistry::isRelevanceControllerMap()) {
            $map = \workup\base\ApplicationRegistry::getControllerMap();

            if (!is_null($map)) {
                if (!is_null($configpath)) {
                    $this->configpath = $configpath;
                }

                return;
            }
        }

        $this->getOptions();
    }

    private function getOptions()
    {
        $this->ensure(file_exists($this->config), "Could not find options file");

        $options = simplexml_load_file($this->config);

        $this->ensure($options instanceof \SimpleXMLElement,
            "Could not resolve options file");

        $map = new ControllerMap();

        foreach ($options->control->view as $default_view) {
            $stat_str = trim($default_view['status']);
            if (empty($stat_str)) {
                $stat_str = "CMD_DEFAULT";
            }
            $status = \workup\command\Command::statuses($stat_str);
            $map->addView('default', $status, (string )$default_view);
        }

        foreach ($options->control->status as $default_status) {
            $view = trim((string )$default_status->view);
            $forward = trim((string )$default_status->forward);
            $stat_str = trim($default_status['value']);
            $status = \workup\command\Command::statuses($stat_str);
            if ($view) {
                $map->addView('default', $status, $view);
            }
            if ($forward) {
                $map->addForward('default', $status, $forward);
            }
        }

        foreach ($options->control->command as $command_view) {
            $command = trim((string )$command_view['name']);
            if ($command_view->classalias) {
                $classroot = trim((string )$command_view->classalias['name']);
                $map->addClassroot($command, $classroot);
            }
            if ($command_view->view) {
                $view = trim((string )$command_view->view);
                $forward = trim((string )$command_view->forward);
                $map->addView($command, 0, $view);
                if ($forward) {
                    $map->addForward($command, 0, $forward);
                }

            }
            foreach ($command_view->status as $command_view_status) {
                $view = trim((string )$command_view_status->view);
                $forward = trim((string )$command_view_status->forward);
                $stat_str = trim($command_view_status['value']);
                $status = \workup\command\Command::statuses($stat_str);
                if ($view) {
                    $map->addView($command, $status, $view);
                }
                if ($forward) {
                    $map->addForward($command, $status, $forward);
                }
            }
        }

        \workup\base\ApplicationRegistry::setControllerMap($map);
    }

    private function ensure($expr, $message)
    {
        if (!$expr) {
            throw new \workup\base\AppException($message);
        }
    }
}

namespace workup\controller;

use workup\App;

class AppController
{
    private static $base_cmd = null;
    private static $default_cmd = null;
    private $controllerMap;
    private $invoked = array();

    public static $cmd = 'cmd';

    function __construct(ControllerMap $map)
    {
        $this->controllerMap = $map;
        if (is_null(self::$base_cmd)) {
            self::$base_cmd = new \ReflectionClass("\workup\command\Command");
            self::$default_cmd = new \workup\command\Sources();
        }
    }

    function reset()
    {
        $this->invoked = array();
    }

    function getView(Request $req)
    {
        $view = $this->getResource($req, "View");
        return $view;
    }

    private function getForward(Request $req)
    {
        $forward = $this->getResource($req, "Forward");
        if ($forward) {
            $req->setProperty(self::$cmd, $forward);
        }
        return $forward;
    }

    private function getResource(Request $req, $res)
    {
        $cmd_str = $req->getProperty(self::$cmd);
        $previous = $req->getLastCommand();
        $status = $previous->getStatus();
        if (!isset($status) || !is_int($status)) {
            $status = 0;
        }
        $acquire = "get$res";
        $resource = $this->controllerMap->$acquire($cmd_str, $status);
        if (is_null($resource)) {
            $resource = $this->controllerMap->$acquire($cmd_str, 0);
        }
        if (is_null($resource)) {
            $resource = $this->controllerMap->$acquire('Sources', $status);
        }
        if (is_null($resource)) {
            $resource = $this->controllerMap->$acquire('Sources', 0);
        }
        return $resource;
    }

    function getCommand(Request $req)
    {
        $previous = $req->getLastCommand();
        if (is_null($previous)) {
            $cmd = $req->getProperty(self::$cmd);
            if (is_null($cmd)) {
                $req->setProperty(self::$cmd, 'Sources');
                return self::$default_cmd;
            }
        } else {
            $cmd = $this->getForward($req);
            if (is_null($cmd)) {
                return null;
            }
        }

        $cmd_obj = $this->resolveCommand($cmd);
        if (is_null($cmd_obj)) {
            $req->setProperty(self::$cmd, 'NotFound');
            $cmd_obj = new \workup\command\NotFound();
        }

        $cmd_class = get_class($cmd_obj);
        if (isset($this->invoked[$cmd_class])) {
            throw new \workup\base\AppException("circular forwarding");
        }

        $this->invoked[$cmd_class] = 1;
        return $cmd_obj;
    }

    function resolveCommand($cmd)
    {
        $cmd = str_replace(array(
            '.',
            '/',
            '\\'), "", $cmd);

        $cmd = preg_replace("#[^a-zA-Z0-9]#", "", $cmd);

        $classroot = $this->controllerMap->getClassroot($cmd);

        $filepath = "workup/command/$classroot.php";
        $classname = "\\workup\\command\\$classroot";
        if (class_exists($classname) || App::autoload($classname)) {
            if (class_exists($classname)) {
                $cmd_class = new \ReflectionClass($classname);
                if ($cmd_class->isSubClassOf(self::$base_cmd)) {
                    return $cmd_class->newInstance();
                }
            }
        }
        return null;
    }
}

class ControllerMap
{
    private $viewMap = array();
    private $forwardMap = array();
    private $classrootMap = array();

    function addClassroot($command, $classroot)
    {
        $this->classrootMap[$command] = $classroot;
    }

    function getClassroot($command)
    {
        if (isset($this->classrootMap[$command])) {
            return $this->classrootMap[$command];
        }
        return $command;
    }

    function addView($command = 'Sources', $status = 0, $view = '')
    {
        $this->viewMap[$command][$status] = $view;
    }

    function getView($command, $status)
    {
        if (isset($this->viewMap[$command][$status])) {
            return $this->viewMap[$command][$status];
        } elseif (isset($this->viewMap['default'][$status])) {
            return $this->viewMap['default'][$status];
        }
        return null;
    }

    function addForward($command, $status = 0, $newCommand = '')
    {
        $this->forwardMap[$command][$status] = $newCommand;
    }

    function getForward($command, $status)
    {
        if (isset($this->forwardMap[$command][$status])) {
            return $this->forwardMap[$command][$status];
        } elseif (isset($this->forwardMap['default'][$status])) {
            return $this->forwardMap['default'][$status];
        }
        return null;
    }
}

namespace workup\controller;

use main;

class Request
{
    private $appreg;
    private $properties;
    private $objects = array();
    private $feedback = array();
    private $lastCommand;

    function __construct()
    {
        $this->init();
    }

    function init()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            if ($_SERVER['REQUEST_METHOD']) {
                if (isset($_GET['cmd'])) {
                    $_GET['cmd'] = preg_replace("#[^a-zA-Z0-9]#", "", $_GET['cmd']);
                }

                if (isset($_POST['cmd'])) {
                    $_POST['cmd'] = preg_replace("#[^a-zA-Z0-9]#", "", $_POST['cmd']);
                }

                $this->properties = array_merge($_GET, $_POST);
                return;
            }
        } elseif (isset($GLOBALS['_APP_PARAMS']) && is_array($GLOBALS['_APP_PARAMS'])) {
            $this->properties = $GLOBALS['_APP_PARAMS'];
            return;
        }

        foreach ($_SERVER['argv'] as $arg) {
            if (strpos($arg, '=')) {
                list($key, $val) = explode("=", $arg);
                $this->setProperty($key, $val);
            }
        }
    }

    function getProperty($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }
        return null;
    }

    function setProperty($key, $val)
    {
        $this->properties[$key] = $val;
    }

    function __clone()
    {
        $this->properties = array();
    }

    function addFeedback($msg)
    {
        array_push($this->feedback, $msg);
    }

    function getFeedback()
    {
        return $this->feedback;
    }

    function getFeedbackString($separator = "\n")
    {
        return implode($separator, $this->feedback);
    }

    function setObject($name, $object)
    {
        $this->objects[$name] = $object;
    }

    function getObject($name)
    {
        if (isset($this->objects[$name])) {
            return $this->objects[$name];
        }
        return null;
    }

    function clearLastCommand()
    {
        $this->lastCommand = null;
    }

    function setCommand(\workup\command\Command $command)
    {
        $this->lastCommand = $command;
    }

    function getLastCommand()
    {
        return $this->lastCommand;
    }

    function __get($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }
        return null;
    }

    function __set($key, $val)
    {
        $this->properties[$key] = $val;
    }

    function __isset($key)
    {
        if (isset($this->properties[$key])) {
            return true;
        }
        return false;
    }
}

namespace workup\controller;

use main;

class Controller
{
    private $applicationHelper;
    private $view;

    private function __construct()
    {
    }

    static function run()
    {
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }

    function init()
    {
        $applicationHelper = ApplicationHelper::instance();
        $this->view = new \main\View();
        $applicationHelper->init();
    }

    function handleRequest()
    {
        $request = \workup\base\ApplicationRegistry::getRequest();
        $app_c = \workup\base\ApplicationRegistry::appController();

        while ($cmd = $app_c->getCommand($request)) {
            $cmd->execute($request);
        }

        \workup\model\ObjectWatcher::instance()->performOperations();

        if ($request->getLastCommand()->doView()) {
            $this->invokeView($app_c->getView($request));
        }
    }

    function invokeView($target)
    {
        $this->view->render($target);
    }
}

namespace workup\command;

use main\DatabasePerform;

abstract class Command
{

    private static $STATUS_STRINGS = array(
        'CMD_DEFAULT' => 0,
        'CMD_OK' => 1,
        'CMD_ERROR' => 2,
        'CMD_INSUFFICIENT_DATA' => 3,
        'CMD_ERROR_AUTORIZATION' => 4,
        'CMD_ERROR_PRIVILEGES' => 5,
        'CMD_LOCATION' => 6,
        'CMD_MISSING_ROW' => 7,
        'CMD_AJAX' => 8,
        'CMD_NONE_VIEW' => 9,
        'CMD_PARSE' => 10);

    public $res = null;

    private $status = 0;

    final function __construct()
    {
    }

    function execute(\workup\controller\Request $request)
    {
        $this->status = $this->doExecute($request);
        $request->setCommand($this);
    }

    function getStatus()
    {
        return $this->status;
    }

    static function statuses($str = 'CMD_DEFAULT')
    {
        if (empty($str)) {
            $str = 'CMD_DEFAULT';
        }
        return self::$STATUS_STRINGS[$str];
    }

    function doView()
    {
        if ($this->status == self::statuses('CMD_LOCATION')) {
            header("Location: " . $this->res);
            exit();
        } elseif ($this->status == self::statuses('CMD_AJAX')) {
            header('Content-Type: application/json');
            echo $this->res;
            exit();
        } elseif ($this->status == self::statuses('CMD_NONE_VIEW')) {
            return false;
        } else {
            return true;
        }
    }

    abstract function doExecute(\workup\controller\Request $request);
}

namespace workup\base;

use main;

class Mail
{
    public static function send($to, $subject, $message)
    {
        $m = new \libs\Mail('utf-8'); // можно сразу указать кодировку, можно ничего не указывать ($m= new Mail;)
        $m->From(SMTP_MAIL_FROM); // от кого Можно использовать имя, отделяется точкой с запятой
        $m->ReplyTo(SMTP_MAIL_REPLY); // куда ответить, тоже можно указать имя
        if (is_array($to)) {
            foreach ($to as $key => $value) {
                $m->To($value, rand(1111, 9999)); // кому, в этом поле так же разрешено указывать имя
            }
        } else {
            $m->To($to); // кому, в этом поле так же разрешено указывать имя
        }
        $m->Subject($subject);
        $m->Body("<div>" . $message . "</div>", "html");
        $m->Priority(4); // установка приоритета
        if (SMTP_MAIL) {
            $m->smtp_on(SMTP_MAIL_SERVER, SMTP_MAIL_LOGIN, SMTP_MAIL_PASSWORD,
                SMTP_MAIL_PORT, 60); // используя эту команду отправка пойдет через smtp
        }
        $m->log_on(true);
        $m->Send(); // отправка
        if ($m->status_mail['status']) {
            return true;
        }

        return false;
    }
}

namespace workup\command;

use main\DatabasePerform;

class NotFound extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        return self::statuses('CMD_OK');
    }
}

namespace workup\command;

use main\DatabasePerform;

class Sources extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        $page = (isset($_GET['p']) ? intval($_GET['p']) : 1);

        \workup\record\SourceRecord::setLimit(50);

        $limit = \workup\record\SourceRecord::getLimit();

        if ($page) {
            $start = ($page - 1) * $limit;
        } else {
            $start = 0;
        }

        $params_search = [];

        if ($request->search && is_array($request->search)) {
            $search = [];

            foreach ($request->search as $key => $value) {
                if (!empty($value)) {
                    $search[$key] = trim($value);
                }
            }
            $params_search = $search;
        }

        $params_sort = [];

        if ($request->sort) {
            $params_sort['sort'] = $request->sort;
        }

        if ($request->order) {
            $params_sort['order'] = $request->order;
        }

        if (empty($params_sort)) {
            $request->sort = $params_sort['sort'] = 'check_cron DESC, id';
            $request->order = $params_sort['order'] = 'DESC';
        }

        $params = [];

        foreach ($params_search as $key => $value) {
            if ($key == 'search') {
                $expl = explode('->', $value);

                if (count($expl) == 2) {
                    if (count($expl) == 2 && ($expl[0] == 'name' || $expl[0] == 'comment' || $expl[0] ==
                        'table_name' || $expl[0] == 'urls')) {
                        $params[$expl[0]] = $expl[1];
                    } else {
                        $params['name'] = $expl[1];
                    }
                } else {
                    $params['name'] = $value;
                }
            } else {
                $params[$key] = $value;
            }
        }
        
        if (\main\Auth::getPrivileges() != 'admin') {
            $params['visibility'] = '0';
        }

        \workup\record\SourceRecord::setCount($params);

        $request->count_all_sources = \workup\record\SourceRecord::getCount();

        $sources = \workup\record\SourceRecord::GetRows($start, $limit, $params, $params_sort);

        $request->setObject('sources', $sources);

        if (!empty($params_search)) {
            $params_search = ['search' => $params_search];
        }

        $request->params_search = $params_search;

        $request->params_sort = $params_sort;

        $request->params = array_merge($params_search, $params_sort);

        return self::statuses('CMD_DEFAULT');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ProcessParse extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        $sources = [];

        if (isset($GLOBALS['SOURCE_OBJECTS']) && is_array($GLOBALS['SOURCE_OBJECTS'])) {
            foreach ($GLOBALS['SOURCE_OBJECTS'] as $SOURCE_OBJECT) {
                $sources[] = new \workup\model\SourceParse($SOURCE_OBJECT);
            }
        }

        $request->setObject('parse_sources', $sources);
        return self::statuses('CMD_PARSE');
    }
}

namespace workup\command;

use main\DatabasePerform;

class Parse extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        $sources = $request->getObject('parse_sources');

        $message = 'NULL';

        if ($sources && !empty($sources)) {
            $begin_process = time();

            $count_sources = 0;

            if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_all")) {
                unlink(\workup\App::config('DIR_TMP') . "/blocking_all");
            }

            set_time_limit(0);

            foreach ($sources as $source_obj) {
                if ($source_obj && $source_obj instanceof \workup\model\Source && $source_obj->
                    createTableIfNotExist()) {
                    $begin_time = microtime(true);

                    if ($source_obj->begin_parse_at) {
                        if (!($source_obj->end_parse_at >= $source_obj->begin_parse_at)) {
                            file_put_contents(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->
                                id, "blocking");
                            sleep(20);
                        }
                    }

                    \workup\model\SourceData::setTable($source_obj->table_name);

                    if (file_exists(\workup\App::config('DIR_TMP') . "/COOKIES")) {
                        unlink(\workup\App::config('DIR_TMP') . "/COOKIES");
                    }


                    if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id)) {
                        unlink(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id);
                    }

                    $source_obj->begin_parse_at = time();

                    $source_obj->end_parse_at = 0;
                    $source_obj->count_last_process = 0;
                    $source_obj->count_success_last_process = 0;
                    $source_obj->count_error_last_process = 0;
                    $source_obj->count_last_write = 0;
                    $source_obj->time_last_process = 0;
                    $source_obj->time_last_requests = 0;
                    $source_obj->time_process_life = 0;

                    $source_obj->save();

                    $source_obj->time_limit = 3600*24*360;

                    $parser = new \main\ParseSource($source_obj);

                    $parser->processLists();

                    $parser->processPages();

                    if ($source_obj->table_page_urls && $source_obj->column_table_page_urls && $source_obj->
                        table_fixing) {
                        if (\workup\record\Record::isTable($source_obj->table_page_urls)) {
                            $columns = \workup\record\Record::getColumns($source_obj->table_page_urls);

                            if (!in_array('status_process', $columns)) {
                                DatabasePerform::Execute("ALTER TABLE `" . $source_obj->table_page_urls .
                                    "` ADD COLUMN `status_process` INT(1) NOT NULL DEFAULT 0;");
                            }

                            if (is_array($columns) && in_array($source_obj->column_table_page_urls, $columns)) {
                                $inf = DatabasePerform::GetAll("DESCRIBE `" . $source_obj->table_page_urls . "`");
                                
                                $exists_id = false;
                                
                                foreach ($inf as $column) {
                                    if ($column['Field'] == 'id') {
                                        $exists_id = true;
                                    }
                                }

                                if ($exists_id) {
                                    $column_id = 'id';
                                } else {
                                    foreach ($inf as $column) {
                                        $column_id = $column['Field'];
                                        break;
                                    }
                                }

                                $source_obj->table_column_id = $column_id;

                                $offset = 0;
                                $amount = 0;
                                $limit = 1000;

                                if ($source_obj->start_table_page_urls >= 0) {
                                    $offset = intval($source_obj->start_table_page_urls);
                                }

                                if ($source_obj->length_table_page_urls >= 0) {
                                    $amount = intval($source_obj->length_table_page_urls);
                                }

                                $count = DatabasePerform::GetOne("SELECT COUNT(*) FROM `" . $source_obj->
                                    table_page_urls . "`" . ($source_obj->inspect_url_table == '1' || $source_obj->
                                    inspect_url_table == '3' ? "WHERE `status_process` IN (0,2,4) " : ""));

                                if ($count > 0 && $amount > 0) do {
                                    if ($amount < $limit) {
                                        $limit = $amount;
                                    }

                                    $amount = $amount - $limit;

                                    $fields = [];
                                    $fields_in_table_for_transmission = [];

                                    $fields[$column_id] = '`'.$column_id.'`';
                                    $fields[$source_obj->column_table_page_urls] = '`' . $source_obj->
                                        column_table_page_urls . '`';

                                    foreach (explode(' ', $source_obj->fields_in_table_for_transmission) as $field) {
                                        $field = trim($field);
                                        if (!empty($field)) {
                                            $fields[$field] = '`' . $field . '`';
                                            $fields_in_table_for_transmission[$field] = $field;
                                        }
                                    }

                                    $query = "SELECT " . implode(',', $fields) . " FROM `" . $source_obj->
                                        table_page_urls . "` " . ($source_obj->inspect_url_table == '1' || $source_obj->
                                        inspect_url_table == '3' ? "WHERE `status_process` IN (0,2,4) " : "") .
                                        "ORDER BY `".$column_id."` LIMIT " . $offset . ", " . $limit;

                                    $rows = DatabasePerform::GetAll($query);

                                    $first_id = null;
                                    $end_id = null;

                                    if (is_array($rows) && !empty($rows)) {
                                        $start_key = 0;
                                        $end_key = count($rows) - 1;

                                        if (isset($rows[$start_key])) {
                                            $first_id = $rows[$start_key][$source_obj->table_column_id];
                                        }
                                        if (isset($rows[$end_key])) {
                                            $end_id = $rows[$end_key][$source_obj->table_column_id];
                                        }

                                        if ($source_obj->inspect_url_table == '1' || $source_obj->inspect_url_table ==
                                            '3') {
                                            $source_obj->processForTable = true;
                                            $parser->setFixRow($source_obj->table_fixing);
                                        }

                                        $_urls = [];

                                        $chars = "a-zA-Z0-9АаБбВвГгҐґДдЕеЄєЭэЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЮюЯяЬьЪъёЫы";

                                        if (!empty($fields_in_table_for_transmission)) {
                                            foreach ($rows as $row) {
                                                if (true) {
                                                    $url = [
                                                        'url' => $row[$source_obj->column_table_page_urls], 
                                                        'res' => [
                                                            'tblvid' => $row[$source_obj->table_column_id]
                                                        ]
                                                    ];
                                                        
                                                    foreach ($fields_in_table_for_transmission as $field) {
                                                        $url['res'][$field] = $row[$field];
                                                    }

                                                    $_urls[] = $url;
                                                }
                                            }
                                        } else {
                                            foreach ($rows as $row) {
                                                if (true) {
                                                    $_urls[] = [
                                                        'url' => $row[$source_obj->column_table_page_urls],
                                                        'res' => [
                                                            'tblvid' => $row[$source_obj->table_column_id]
                                                        ]
                                                    ];
                                                }
                                            }
                                        }

                                        if ($source_obj->table_fixing == 1) {
                                            $parser->addPageUrls($_urls);
                                        } elseif ($source_obj->table_fixing == 2) {
                                            $parser->addListUrls($_urls);
                                        }

                                        unset($_urls);

                                        $parser->processLists();

                                        $parser->processPages();
                                    }

                                    if ($source_obj->inspect_url_table == '2') {
                                        $offset = $offset + $limit;
                                    }

                                    if ($source_obj->inspect_url_table == '3') {
                                        if ($first_id > 0 && $end_id > 0) {
                                            DatabasePerform::Execute("DELETE FROM `" . $source_obj->table_page_urls .
                                                "` WHERE `status_process` != 2 AND `".$source_obj->table_column_id."` >= :first_id AND `".$source_obj->table_column_id."` <= :end_id", ['first_id' =>
                                                $first_id, 'end_id' => $end_id, ]);
                                        }
                                    }
                                } while(count($rows) > 0 && count($rows) == $limit && $amount > 0);
                            }
                        }
                    }

                    unset($parser);

                    $source_obj->end_parse_at = time();

                    $end_time = microtime(true);

                    $source_obj->time_process_life = $end_time - $begin_time;

                    if ($source_obj->count_last_write > 0) {
                        $source_obj->last_write_at = time();
                        $source_obj->last_write_count = $source_obj->count_last_write;
                    }

                    $source_obj->save();
                }

                $count_sources++;
            }

            $end_process = time();

            $duration = $end_process - $begin_process;

            if ($duration < 60) {
                $duration = $duration . ' сек.';
            } else {
                $minutes = floor($duration / 60);

                $seconds = $duration - $minutes * 60;

                $duration = $minutes . ' мин. ' . $seconds . ' сек.';
            }

            $message = 'Обработано: ' . $count_sources . ' ист.' . '<br />' .
                'Длительность: ' . $duration;
        }
        
        if (\workup\App::config('WORDPRESS')) {
            \workup\AppWordpress::unsetTmp();
        }

        $this->res = json_encode(array('status' => 'complete', 'message' => $message));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\model;

use main\DatabasePerform;

class ObjectWatcher
{
    static $test = false;

    private $all = array();
    private $dirty = array();
    private $new = array();
    private $delete = array();
    private static $instance = null;

    private function __construct()
    {
    }

    static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }

    function globalKey(ModelObject $obj)
    {
        $key = get_class($obj) . "." . $obj->getId();
        return $key;
    }

    static function add(ModelObject $obj)
    {
        if (ObjectWatcher::$test) {
            echo $obj->getName() . "(add of all)<br />";
        }

        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;
    }

    static function exists($classname, $id)
    {
        if (ObjectWatcher::$test) {
            echo $obj->getName() . "(exists)<br />";
        }

        $inst = self::instance();
        $key = "$classname.$id";
        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }
        return null;
    }

    static function addDelete(ModelObject $obj)
    {
        if (ObjectWatcher::$test) {
            echo $obj->getName() . "(add of delete)<br />";
        }

        $self = self::instance();
        $self->delete[$self->globalKey($obj)] = $obj;
    }

    static function addDirty(ModelObject $obj)
    {
        if (ObjectWatcher::$test) {
            echo $obj->getName() . "(add of dirty)<br />";
        }

        $inst = self::instance();
        if (!in_array($obj, $inst->new, true)) {
            $inst->dirty[$inst->globalKey($obj)] = $obj;
        }
    }

    static function addNew(ModelObject $obj)
    {
        if (ObjectWatcher::$test) {
            echo $obj->getName() . "(add of new)<br />";
        }

        $inst = self::instance();
        // we don't yet have an id
        $inst->new[] = $obj;
    }

    static function addClean(ModelObject $obj)
    {
        if (ObjectWatcher::$test) {
            echo $obj->getName() . "(clean)<br />";
        }

        $self = self::instance();
        unset($self->delete[$self->globalKey($obj)]);
        unset($self->dirty[$self->globalKey($obj)]);

        $self->new = array_filter($self->new, function ($a)use ($obj)
        {
            return !($a === $obj); }
        );
    }

    static function saveObject(ModelObject $obj)
    {
        $self = self::instance();

        foreach ($self->new as $key => $value) {
            if ($obj === $value) {
                $obj->finder()->insert($obj);

                unset($self->new[$key]);
            }
        }

        if (isset($self->dirty[$self->globalKey($obj)])) {
            $obj->finder()->update($obj);
            unset($self->dirty[$self->globalKey($obj)]);
        }
    }

    static function deleteObject(ModelObject $obj)
    {
        $obj->finder()->delete($obj);
        self::unsetObject($obj);
    }

    static function unsetObject(ModelObject $obj)
    {
        $self = self::instance();

        $key = $self->globalKey($obj);

        foreach ($self->new as $key => $value) {
            if ($obj === $value) {
                unset($self->new[$key]);
            }
        }

        if (isset($self->dirty[$key])) {
            unset($self->dirty[$key]);
        }

        if (isset($self->delete[$key])) {
            unset($self->delete[$key]);
        }

        if (isset($self->all[$key])) {
            unset($self->all[$key]);
        }
    }

    function performOperations()
    {
        if (ObjectWatcher::$test) {
            echo "(ObjectWatcher::performOperations)<br />";
        }

        foreach ($this->dirty as $key => $obj) {
            $obj->finder()->update($obj);
        }
        foreach ($this->new as $key => $obj) {
            $obj->finder()->insert($obj);
        }
        $this->dirty = array();
        $this->new = array();
    }
}

namespace workup\model;

use main\DatabasePerform;

abstract class ModelObject
{
    const TABLE = null;

    protected $properties = array('id' => null);

    protected $columns = array();

    protected $selects = array();
    protected $updates = array();

    public function __construct($arg = array())
    {
        if (is_array($arg)) {
            if (!isset($arg['id']) || is_null($arg['id'])) {
                $this->markNew();
            }

            foreach ($arg as $key => $value) {
                $this->properties[$key] = $value;

                $this->selects[$key] = gettype($value);
            }
        }
    }

    public function build()
    {
    }

    public function markNew()
    {
        ObjectWatcher::addNew($this);
    }

    public function markDeleted()
    {
        ObjectWatcher::addDelete($this);
    }

    public function markDirty()
    {
        ObjectWatcher::addDirty($this);
    }

    public function markClean()
    {
        ObjectWatcher::addClean($this);
    }

    public function save()
    {
        ObjectWatcher::saveObject($this);
    }

    public function delete()
    {
        ObjectWatcher::deleteObject($this);
    }

    public function remove()
    {
        ObjectWatcher::unsetObject($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return self::getCollection(get_class($this));
    }

    public function finder()
    {
        return self::getFinder(get_class($this));
    }

    public static function getFinder($type = null)
    {
        if (is_null($type)) {
            return HelperFactory::getFinder(get_called_class());
        }
        return HelperFactory::getFinder($type);
    }

    public static function getCollection($type = null)
    {
        if (is_null($type)) {
            return HelperFactory::getCollection(get_called_class());
        }
        return HelperFactory::getCollection($type);
    }

    public static function findAll()
    {
        $finder = self::getFinder();
        return $finder->findAll();
    }

    public static function GetAll($sql, $array)
    {
        $finder = self::getFinder();
        return $finder->GetAll($sql, $array);
    }

    public static function find($id)
    {
        $finder = self::getFinder();
        return $finder->find($id);
    }

    public static function Get($sql, $array)
    {
        $finder = self::getFinder();
        return $finder->get($sql, $array);
    }

    public function __clone()
    {
        $this->id = -1;
    }

    public function __get($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }
        return null;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function __set($key, $val)
    {
        $this->properties[$key] = $val;
        if (isset($this->columns[$key])) {
            $this->updates[$key] = gettype($val);

            $this->markDirty();
        }
    }

    public function __isset($key)
    {
        if (isset($this->properties[$key])) {
            return true;
        }
        return false;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getColumnsInsert()
    {
        $data = array();

        foreach ($this->properties as $key => $value) {
            if (isset($this->columns[$key]) && !is_null($value)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function getColumnsUpdate()
    {
        $data = array();

        foreach ($this->updates as $key => $value) {
            if (isset($this->columns[$key]) && array_key_exists($key, $this->properties)) {
                $data[$key] = array('value' => $this->properties[$key], 'type' => $this->
                        columns[$key]);
            }
        }

        return array('where' => array('id' => array('value' => $this->id, 'type' =>
                        'integer 11')), 'set' => $data);
    }

    public static function table()
    {
        return static::TABLE;
    }
}

namespace workup\model;

use main\DatabasePerform;

class Cron extends \workup\model\Source
{
    protected $columns = [
        'id' => 'integer 11',
        'name' => 'string 255',
        'table_name' => 'string 255',
        'urls' => 'text',
        'comment' => 'text',
        'target_list_element' => 'text',
        'target_list_value' => 'text',
        'target_list_url' => 'text',
        'target_list_next' => 'text',
        'begin_page' => 'integer 5',
        'end_page' => 'integer 5',
        'key_page' => 'string 255',
        'data_list' => 'text',
        'cookie_list' => 'text',
        'curlopt_list' => 'text',
        'http_method_list' => 'enum get,post',
        'page_urls' => 'text',
        'target_page_element' => 'text',
        'target_page_value' => 'text',
        'data_page' => 'text',
        'cookie_page' => 'text',
        'curlopt_page' => 'text',
        'http_method_page' => 'enum get,post',
        'table_page_urls' => 'string 255',
        'column_table_page_urls' => 'string 255',
        'start_table_page_urls' => 'string 255',
        'length_table_page_urls' => 'string 255',
        'table_fixing' => 'integer 1',
        'result' => 'text',
        'created_at' => 'integer 11',
        'begin_parse_at' => 'integer 11',
        'end_parse_at' => 'integer 11',
        'count_all_process' => 'integer 11',
        'count_last_process' => 'integer 11',
        'count_success_all_process' => 'integer 11',
        'count_error_all_process' => 'integer 11',
        'count_success_last_process' => 'integer 11',
        'count_error_last_process' => 'integer 11',
        'count_all_write' => 'integer 11',
        'count_last_write' => 'integer 11',
        'time_all_process' => 'integer 11',
        'time_last_process' => 'integer 11',
        'time_all_requests' => 'integer 11',
        'time_last_requests' => 'integer 11',
        'check_cron' => 'integer 1',
        'amount_stream' => 'integer 11',
        'microtime_delay' => 'integer 11',
        'cp_all' => 'integer 11',
        'cp_last' => 'integer 11',
        'memory_all' => 'integer 11',
        'memory_last' => 'integer 11',
        'count_all_query_to_bd' => 'integer 11',
        'count_last_query_to_bd' => 'integer 11',
        'func_data_processing_list' => 'string 255',
        'func_data_processing_page' => 'string 255',
        'count_process' => 'integer 11',
        'status_control_insert' => 'integer 1',
        'fields_control_insert' => 'string 255',
        'proxy' => 'string 255',
        'func_valid_url_list' => 'string 255',
        'func_valid_url_page' => 'string 255',
        'inspect_duplicate_url_list' => 'enum yes,no',
        'inspect_duplicate_url_page' => 'enum yes,no',
        'time_process_life' => 'float',
        'inspect_url_table' => 'integer 1',
        'insert_type' => 'integer 1',
        'import_rate' => 'integer 11',
        'last_write_at' => 'integer 11',
        'last_write_count' => 'integer 11',
        'last_import_at' => 'integer 11',
        'last_import_count' => 'integer 11',
        'fields_in_table_for_transmission' => 'string 255',
        'default_values' => 'text',
        'dom_library' => 'integer 1',
        'visibility' => 'integer 1',
    ];

    public static function table()
    {
        return \workup\App::config('TABLE_CRON');
    }
}

namespace workup\model;

use main\DatabasePerform;

class HelperFactory
{
    private static $finderObjects = [];

    static function getFinder($type)
    {
        $type = preg_replace('|^.*\\\|', "", $type);
        $record = "\\workup\\record\\{$type}Record";

        if (isset(self::$finderObjects[$record])) {
            return self::$finderObjects[$record];
        }

        if (class_exists($record)) {
            self::$finderObjects[$record] = new $record();
            return self::$finderObjects[$record];
        }

        throw new \workup\base\AppException("Unknown: $record");
    }

    static function getCollection($type)
    {
        $type = preg_replace('|^.*\\\|', "", $type);
        $collection = "\\workup\\record\\{$type}Collection";
        if (class_exists($collection)) {
            return new $collection();
        }
        throw new \workup\base\AppException("Unknown: $collection");
    }
}

namespace workup\model;

use main\DatabasePerform;

class Source extends ModelObject
{
    protected $columns = [
        'id' => 'integer 11',
        'name' => 'string 255',
        'table_name' => 'string 255',
        'urls' => 'text',
        'comment' => 'text',
        'target_list_element' => 'text',
        'target_list_value' => 'text',
        'target_list_url' => 'text',
        'target_list_next' => 'text',
        'begin_page' => 'integer 5',
        'end_page' => 'integer 5',
        'key_page' => 'string 255',
        'data_list' => 'text',
        'cookie_list' => 'text',
        'curlopt_list' => 'text',
        'http_method_list' => 'enum get,post',
        'page_urls' => 'text',
        'target_page_element' => 'text',
        'target_page_value' => 'text',
        'data_page' => 'text',
        'cookie_page' => 'text',
        'curlopt_page' => 'text',
        'http_method_page' => 'enum get,post',
        'table_page_urls' => 'string 255',
        'column_table_page_urls' => 'string 255',
        'start_table_page_urls' => 'string 255',
        'length_table_page_urls' => 'string 255',
        'table_fixing' => 'integer 1',
        'result' => 'text',
        'created_at' => 'integer 11',
        'begin_parse_at' => 'integer 11',
        'end_parse_at' => 'integer 11',
        'count_all_process' => 'integer 11',
        'count_last_process' => 'integer 11',
        'count_success_all_process' => 'integer 11',
        'count_error_all_process' => 'integer 11',
        'count_success_last_process' => 'integer 11',
        'count_error_last_process' => 'integer 11',
        'count_all_write' => 'integer 11',
        'count_last_write' => 'integer 11',
        'time_all_process' => 'float',
        'time_last_process' => 'float',
        'time_all_requests' => 'float',
        'time_last_requests' => 'float',
        'check_cron' => 'integer 1',
        'count_import_all' => 'integer 11',
        'count_import_last' => 'integer 11',
        'count_import_success_all' => 'integer 11',
        'count_import_success_last' => 'integer 11',
        'count_import_error_all' => 'integer 11',
        'count_import_error_last' => 'integer 11',
        'amount_stream' => 'integer 11',
        'microtime_delay' => 'integer 11',
        'cp_all' => 'integer 11',
        'cp_last' => 'integer 11',
        'memory_all' => 'integer 11',
        'memory_last' => 'integer 11',
        'count_all_query_to_bd' => 'integer 11',
        'count_last_query_to_bd' => 'integer 11',
        'func_data_processing_list' => 'string 255',
        'func_data_processing_page' => 'string 255',
        'count_process' => 'integer 11',
        'status_control_insert' => 'integer 1',
        'fields_control_insert' => 'string 255',
        'proxy' => 'string 255',
        'func_valid_url_list' => 'string 255',
        'func_valid_url_page' => 'string 255',
        'inspect_duplicate_url_list' => 'enum yes,no',
        'inspect_duplicate_url_page' => 'enum yes,no',
        'time_process_life' => 'float',
        'inspect_url_table' => 'integer 1',
        'insert_type' => 'integer 1',
        'import_rate' => 'integer 11',
        'last_write_at' => 'integer 11',
        'last_write_count' => 'integer 11',
        'last_import_at' => 'integer 11',
        'last_import_count' => 'integer 11',
        'fields_in_table_for_transmission' => 'string 255',
        'default_values' => 'text',
        'dom_library' => 'integer 1',
        'visibility' => 'integer 1',
    ];

    public static function table()
    {
        return \workup\App::config('TABLE_SOURCES');
    }

    function build()
    {
        if (\workup\App::config('WORDPRESS')) {
            if ($this->table_name != 'wp_posts') {
                if (stripos($this->table_name, 'tbl_parser_') !== 0) {
                    $this->table_name = 'tbl_parser_' . $this->table_name;
                }
            }
        }

        $this->_urls = \main\unsqueeze($this->urls);
        $this->_target_list_element = \main\unsqueeze($this->target_list_element);
        $this->_target_list_value = \main\unsqueeze($this->target_list_value);
        $this->_target_list_url = \main\unsqueeze($this->target_list_url);
        $this->_target_list_next = \main\unsqueeze($this->target_list_next);
        $this->_data_list = \main\unsqueeze($this->data_list);
        $this->_cookie_list = \main\unsqueeze($this->cookie_list);
        $this->_curlopt_list = \main\unsqueeze($this->curlopt_list);
        $this->_page_urls = \main\unsqueeze($this->page_urls);
        $this->_target_page_element = \main\unsqueeze($this->target_page_element);
        $this->_target_page_value = \main\unsqueeze($this->target_page_value);
        $this->_data_page = \main\unsqueeze($this->data_page);
        $this->_cookie_page = \main\unsqueeze($this->cookie_page);
        $this->_curlopt_page = \main\unsqueeze($this->curlopt_page);
        $this->_result = \main\unsqueeze($this->result);
        $this->_default_values = \main\unsqueeze($this->default_values);
    }

    function createTableIfNotExist()
    {
        if (!\workup\App::config('WORDPRESS') || stripos($this->table_name,
            'tbl_parser_') === 0) {
            if (\workup\App::config('DB_TYPE') == 'sqlite') {
                $sql = "CREATE TABLE IF NOT EXISTS `" . $this->table_name .
                    "`(id INTEGER PRIMARY KEY AUTOINCREMENT);";
            } else {
                $sql = "CREATE TABLE IF NOT EXISTS `" . $this->table_name .
                    "`(id SERIAL) ENGINE = MyISAM DEFAULT CHARSET = utf8;";
            }

            if (DatabasePerform::Execute($sql)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}

namespace workup\model;

use main\DatabasePerform;

class SourceData extends ModelObject
{
    protected static $_table = null;

    protected static $_tables_column_for_db = [];

    protected $columns = [];

    private static $type_columns = [
        'id' => 'INT(11)', 
        'parse_url' => 'TINYTEXT',
        'redirect_url' => 'TINYTEXT',
        'status_process' => 'INT(1) NOT NULL DEFAULT 0', 
        'created_at' => 'TIMESTAMP NULL DEFAULT NULL',
        'updated_at' => 'TIMESTAMP NULL DEFAULT NULL', 
        'source_id' => 'INT(11)', 
    ];

    function __construct($arg = array())
    {
        if (self::$_table) {
            if (!isset(self::$_tables_column_for_db[$this->table()])) {
                $this->showColumns();
            }

            $this->columns = array_replace($this->columns, self::$_tables_column_for_db[$this->
                table()]);
        }

        parent::__construct($arg);
    }

    function __set($key, $val)
    {
        $this->properties[$key] = $val;

        $this->updates[$key] = gettype($val);

        $this->markDirty();

    }

    private static function showColumns()
    {
        $result = array();

        if (\workup\App::config('DB_TYPE') == 'sqlite') {
            $sql = "pragma table_info(`" . self::$_table . "`)";

            $inf = DatabasePerform::GetAll($sql);

            if (!is_array($inf)) {
                trigger_error('Ошибка запроса информации о структуре таблицы', E_USER_ERROR);
            }

            foreach ($inf as $column) {
                $result[$column['name']] = $column['type'];
            }
        } else {
            $sql = "DESCRIBE `" . self::$_table . "`";

            $inf = DatabasePerform::GetAll($sql);

            if (!is_array($inf)) {
                trigger_error('Ошибка запроса информации о структуре таблицы', E_USER_ERROR);
            }

            foreach ($inf as $column) {
                $result[$column['Field']] = $column['Type'];
            }
        }

        self::$_tables_column_for_db[self::$_table] = $result;

        return $result;
    }

    public static function getColumnForDb()
    {
        if (isset(self::$_tables_column_for_db[self::$_table])) {
            return self::$_tables_column_for_db[self::$_table];
        } else {
            return self::showColumns();
        }
    }

    public static function syncColumnInDb(&$rows)
    {
        $columns = [];

        if (self::$_table) {
            if (!isset(self::$_tables_column_for_db[self::$_table])) {
                self::showColumns();
            }

            $fields = [];

            foreach ($rows as $row) {
                foreach ($row as $key => $value) {
                    if (!isset($fields[$key])) {
                        $fields[$key] = $key;
                    }
                }
            }
            
            $fields['created_at'] = 'created_at';
            $fields['updated_at'] = 'updated_at';

            foreach ($fields as $value) {
                $type = 'TEXT';
                if (!isset(self::$_tables_column_for_db[self::$_table][$value])) {
                    if (isset(self::$type_columns[$value])) {
                        $type = self::$type_columns[$value];
                    }

                    self::$_tables_column_for_db[self::$_table][$value] = $type;

                    DatabasePerform::Execute("ALTER TABLE `" . self::$_table . "` ADD COLUMN `" . $value .
                        "` " . $type);
                }

                $columns[$value] = $type;
            }
        }

        return $columns;
    }

    public static function table()
    {
        return self::$_table;
    }

    public static function setTable($table)
    {
        self::$_table = $table;
    }

    private function addColumn($column, $params = array('type' => 'TEXT', 'text' =>
            'null'))
    {
        $this->columns[$column] = $params['type'];

        if (!isset(self::$_tables_column_for_db[$this->table()])) {
            $this->columns = array_replace($this->columns, self::showColumns());
        }

        if (!isset(self::$_tables_column_for_db[$this->table()][$column])) {
            self::$_tables_column_for_db[$this->table()][$column] = $params['type'];

            DatabasePerform::Execute("ALTER TABLE `" . $this->table() . "` ADD COLUMN `" . $column .
                "` " . $params['type'] . " " . $params['text']);

            $this->columns = array_replace($this->columns, self::showColumns());
        }
    }

    public function getColumnsInsert()
    {
        $data = array();

        foreach ($this->properties as $key => $value) {
            if (!is_null($value)) {
                if (!isset($this->columns[$key])) {
                    $this->addColumn($key);
                }

                $data[$key] = $value;
            }
        }

        return $data;
    }

    public function getColumnsUpdate()
    {
        $data = array();

        foreach ($this->updates as $key => $value) {
            if (isset($this->properties[$key])) {
                if (!isset($this->columns[$key])) {
                    $this->addColumn($key);
                }

                $data[$key] = array('value' => $this->properties[$key], 'type' => $this->
                        columns[$key]);
            }
        }

        return array('where' => array('id' => array('value' => $this->id, 'type' =>
                        'integer 11')), 'set' => $data);
    }
}

namespace workup\model;

use main\DatabasePerform;

class SourceParse extends Source
{
    function build()
    {
        return null;
    }

    function save()
    {
        return null;
    }
}

namespace workup\model;

use main\DatabasePerform;

class User extends ModelObject
{
    public static function table()
    {
        return \workup\App::config('TABLE_USERS');
    }

    protected $columns = array(
        'id' => 'integer 11',
        'name' => 'string 255',
        'login' => 'string 255',
        'password' => 'string 255',
        'privileges' => 'enum admin,operator',
        'email' => 'string 255');
}

namespace workup\record;

use main\DatabasePerform;
use \workup\App;

abstract class Record extends \main\DatabaseHandler implements \workup\model\Finder
{
    const TARGET_CLASS = null;

    protected $selectStmt = null;

    protected static $count = null;
    protected static $limit = 50;

    function __construct()
    {
        if (!isset(static::$PDO)) {
            $this->GetHandler();
        }
    }

    public static function table()
    {
        $class = static::TARGET_CLASS;

        return $class::table();
    }

    function selectStmt()
    {
        if (!$this->selectStmt) {
            $this->selectStmt = self::GetHandler()->prepare("SELECT * FROM `" . static::table() .
                "` WHERE id=?");
        }

        return $this->selectStmt;
    }

    private function getFromMap($id)
    {
        return \workup\model\ObjectWatcher::exists($this->targetClass(), $id);
    }

    private function addToMap(\workup\model\ModelObject $obj)
    {
        return \workup\model\ObjectWatcher::add($obj);
    }

    function find($id)
    {
        $old = $this->getFromMap($id);
        if ($old) {
            return $old;
        }
        $this->selectstmt()->execute(array($id));
        $array = $this->selectstmt()->fetch(\PDO::FETCH_ASSOC);
        $this->selectstmt()->closeCursor();
        if (!is_array($array)) {
            return null;
        }
        if (!isset($array['id'])) {
            return null;
        }
        $object = $this->createObject($array);
        $object->markClean();
        return $object;
    }

    function get($sqlQuery, $params = null, $fetchStyle = \PDO::FETCH_ASSOC)
    {
        $array = DatabasePerform::GetRow($sqlQuery, $params, $fetchStyle);
        if (!is_array($array)) {
            return null;
        }
        if (!isset($array['id'])) {
            return null;
        }
        $object = $this->createObject($array);
        $object->markClean();
        return $object;
    }

    function findAll()
    {
        $this->selectAllStmt()->execute(array());
        return $this->getCollection($this->selectAllStmt()->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function GetAll($sqlQuery, $params = null, $fetchStyle = \PDO::
        FETCH_ASSOC)
    {
        $result = DatabasePerform::GetAll($sqlQuery, $params, $fetchStyle);
        return $this->getCollection($result);
    }

    function getFactory()
    {
        return PersistenceFactory::getFactory($this->targetClass());
    }

    function createObject($array)
    {
        $objfactory = $this->getFactory()->getModelObjectFactory();
        return $objfactory->createObject($array);
    }

    function getCollection(array $raw)
    {
        return $this->getFactory()->getCollection($raw);
    }

    function isRow($data)
    {
        return null;
    }

    function insert(\workup\model\ModelObject $obj)
    {
        $values = $obj->getColumnsInsert();

        if (is_array($values) && !empty($values)) {
            if ($id = $this->isRow($values)) {
                $obj->setId($id);
                $obj->status_insert_new = false;
            } else {
                $set_query = array();
                $value_query = array();

                foreach ($values as $key => $value) {
                    $set_query[$key] = "`" . $key . "`";
                    $value_query[$key] = ":" . $key;
                }

                $query = "INSERT INTO `" . $obj->table() . "` (" . implode(',', $set_query) .
                    ") VALUES (" . implode(',', $value_query) . ")";

                $sth = self::GetHandler()->prepare($query);

                foreach ($values as $key => $value) {
                    $sth->bindValue(':' . $key, $value, \PDO::PARAM_STR);
                }

                $result = $sth->execute();

                if ($result) {
                    $obj->setId(self::GetHandler(false)->lastInsertId());
                    $obj->status_insert_new = true;
                }
            }

            $this->addToMap($obj);
            $obj->markClean();
        }
    }

    function delete(\workup\model\ModelObject $obj)
    {
        return self::deleteForId($obj->getId());
    }

    function update(\workup\model\ModelObject $obj)
    {
        $fields = $obj->getColumnsUpdate();

        $set = $fields['set'];
        $where = $fields['where'];

        if (is_array($set) && is_array($where) && !empty($set) && !empty($where)) {
            $set_query = array();
            $where_query = array();

            $input_parameters = array();

            foreach ($set as $key => $value) {
                $set_query[] = "`" . $key . "`=:" . $key;
                $input_parameters[$key] = $value;
            }

            foreach ($where as $key => $value) {
                $where_query[] = "`" . $key . "`=:" . $key;
                $input_parameters[$key] = $value;
            }

            $query = "UPDATE `" . $obj->table() . "` SET " . implode(',', $set_query) .
                " WHERE " . implode(' AND ', $where_query) . " LIMIT 1";

            $sth = self::GetHandler()->prepare($query);

            foreach ($input_parameters as $key => $value) {
                $sth->bindValue(':' . $key, $value['value'], \PDO::PARAM_STR);
            }

            return $sth->execute();
        } else {
            return null;
        }
    }

    public static function setCount($search = null)
    {
        $where_query = [];
        $params_query = [];

        if (is_array($search)) {
            foreach ($search as $key => $value) {
                if (empty($value)) {
                    $where_query[] = "`" . $key . "`=''";
                } else {
                    if (preg_match("#%#", $value)) {
                        $where_query[] = "LOWER (`" . $key . "`) LIKE LOWER (:" . $key . ")";
                    } else {
                        $where_query[] = "`" . $key . "`=:" . $key;
                    }
                    $params_query[$key] = $value;
                }
            }
        }

        static::$count = intval(DatabasePerform::GetOne("SELECT COUNT(*) FROM `" .
            static::table() . "` 
             " . (!empty($where_query) ? " WHERE " . implode(' AND ', $where_query) :
            '') . ";", $params_query));
    }

    public static function setLimit($limit)
    {
        static::$limit = $limit;
    }

    public static function getCount()
    {
        return static::$count;
    }

    public static function getLimit()
    {
        return static::$limit;
    }

    public static function deleteForId($id)
    {
        if (DatabasePerform::Execute("DELETE FROM `" . static::table() .
            "` WHERE `id`=:id LIMIT 1", array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }

    public static function GetRows($start, $limit, $search = null, $sort = null)
    {
        $start = intval($start);
        $limit = intval($limit);

        $where_query = [];
        $params_query = [];

        $order_by = '';

        if (is_array($sort)) {
            if (isset($sort['sort'])) {
                $order_by = "ORDER BY " . $sort['sort'];

                if (isset($sort['order']) && $sort['order'] == 'DESC') {
                    $order_by = $order_by . " DESC";
                }
            }
        }

        if (is_array($search)) {
            foreach ($search as $key => $value) {
                if (empty($value)) {
                    $where_query[] = "`" . $key . "`=''";
                } elseif (is_array($value)) {
                    if (preg_match("#%#", $value[0])) {
                        $where_query[] = "LOWER (`" . $key . "`) LIKE LOWER (:" . $key . ")";
                    } else {
                        $where_query[] = "`" . $key . "`" . $value[1] . ":" . $key;
                    }
                    $params_query[$key] = $value[0];
                } else {
                    if (preg_match("#%#", $value)) {
                        $where_query[] = "LOWER (`" . $key . "`) LIKE LOWER (:" . $key . ")";
                    } else {
                        $where_query[] = "`" . $key . "`=:" . $key;
                    }
                    $params_query[$key] = $value;
                }
            }
        }

        if (empty($order_by)) {
            $order_by = "ORDER BY id DESC";
        }

        return (new static)->GetAll("SELECT * FROM `" . static::table() . "` " . (!
            empty($where_query) ? " WHERE " . implode(' AND ', $where_query) : '') . " $order_by LIMIT $start, $limit;",
            $params_query);
    }

    static function isTable($tableName)
    {
        $connect = App::config('default_db');
        $config = App::config('db');
        $config = $config[$connect];
        
        $tables = DatabasePerform::GetAll("SHOW TABLES FROM `" . $config['DB_DATABASE'] . "`");

        if (is_array($tables)) {
            foreach ($tables as $table) {
                if (is_array($table)) {
                    foreach ($table as $tbl) {
                        if ($tbl == $tableName) {
                            return true;
                        }
                    }
                } elseif ($table == $tableName) {
                    return true;
                }
            }
        }

        return false;
    }

    static function getColumns($tableName)
    {
        $result = array();

        $sql = "DESCRIBE `" . $tableName . "`";

        $inf = DatabasePerform::GetAll("DESCRIBE `" . $tableName . "`");

        if (!is_array($inf)) {
            return [];
        }

        foreach ($inf as $column) {
            $result[] = $column['Field'];
        }

        return $result;
    }

    protected function targetClass()
    {
        return static::TARGET_CLASS;
    }

    public static function deleteRows(\workup\model\ModelObject $obj, $params = [])
    {
        if (\workup\App::config('WORDPRESS')) {
            if ($obj->table_name != 'wp_posts' && stripos($obj->table_name, 'tbl_parser_')
                !== 0) {
                return false;
            }

            if ($obj->table_name == 'wp_posts') {
                $params['source_id'] = $obj->id;
            }
        }

        if (empty($params)) {
            self::clearTable($obj);
        } else {
            $where = [];
            foreach ($params as $key => $value) {
                if (preg_match("#%#", $value)) {
                    $where[] = "LOWER (`" . $key . "`) LIKE LOWER (:" . $key . ")";
                } else {
                    $where[] = "`" . $key . "`=:" . $key;
                }
            }
            return DatabasePerform::Execute("DELETE FROM `" . $obj->table_name . "` WHERE " .
                implode(' AND ', $where), $params);
        }
    }

    public static function clearTable(\workup\model\ModelObject $obj)
    {
        if (\workup\App::config('WORDPRESS')) {
            return false;
        } else {
            return DatabasePerform::Execute("TRUNCATE `" . $obj->table_name . "`");
        }
    }

    public static function dropTable(\workup\model\ModelObject $obj)
    {
        return DatabasePerform::Execute("DROP TABLE `" . $obj->table_name . "`");
    }
}

namespace workup\record;

use main\DatabasePerform;

class CronRecord extends Record implements \workup\model\SourceFinder
{
    const TARGET_CLASS = '\workup\model\Cron';

    function isRow($data)
    {
        $where_query = [];
        $params = [];

        foreach ($data as $key => $value) {
            $where_query[] = "`" . $key . "`=:" . $key;
            $params[$key] = $value;
        }

        $query = "SELECT id FROM `" . self::table() . "` WHERE " . implode(' AND ', $where_query) .
            " LIMIT 1";

        $result = DatabasePerform::GetRow($query, $params);

        if (is_array($result) && isset($result['id'])) {
            $id = intval($result['id']);
            if ($id > 0) {
                return $id;
            }
        }

        return false;
    }

    public static function GetRowsProcessCron($limit, $begin_parse_at)
    {
        $limit = intval($limit);

        return (new static)->GetAll("SELECT * FROM `" . static::table() .
            "` WHERE (`begin_parse_at`<:begin_parse_at OR `begin_parse_at` IS NULL) AND `check_cron` = 1 ORDER BY `begin_parse_at` LIMIT " .
            $limit, ['begin_parse_at' => $begin_parse_at]);
    }

    function delete(\workup\model\ModelObject $obj)
    {
        if (!empty($obj->table_name) && $obj->table_name) {
            if (!\workup\App::config('WORDPRESS') || stripos($obj->table_name, 'tbl_parser_')
                === 0) {
                if (!\main\DatabasePerform::GetOne("SELECT `id` FROM `_sources` WHERE `table_name` = :table_name AND `id` != :id LIMIT 1", ['table_name' =>
                    $obj->table_name, 'id' => $obj->id])) {
                    \main\DatabasePerform::Execute("DROP TABLE IF EXISTS `" . $obj->table_name . "`");
                }
            }
        }

        return self::deleteForId($obj->getId());
    }
}

namespace workup\record;

use main\DatabasePerform;

class SourceDataRecord extends Record implements \workup\model\SourceFinder
{
    const TARGET_CLASS = '\workup\model\SourceData';

    protected static $status_control = null;
    protected static $fields_control = [];

    public static function setStatusControl($status)
    {
        $status = intval($status);

        if ($status == '1' || $status == '3' || $status == '4') {
            self::$status_control = $status;
        } else {
            self::$status_control = false;
        }
    }

    public static function setFieldsControl($fields)
    {
        $fields = explode(' ', $fields);

        if (is_array($fields)) {
            foreach ($fields as $field) {
                $field = trim($field);

                if (!empty($field)) {
                    self::$fields_control[] = $field;
                }
            }
        }
    }

    public static function table()
    {
        $class = static::TARGET_CLASS;

        return $class::table();
    }

    public function isRow($data)
    {
        if (self::$status_control === false) {
            return null;
        }

        $where_query = [];
        $params = [];

        if (empty(self::$fields_control)) {
            foreach ($data as $key => $value) {
                $where_query[] = "`" . $key . "`=:" . $key;
                $params[$key] = $value;
            }
        } else {
            foreach ($data as $key => $value) {
                if (in_array($key, self::$fields_control)) {
                    $where_query[] = "`" . $key . "`=:" . $key;
                    $params[$key] = $value;
                }
            }
        }

        if (!empty($where_query)) {
            $query = "SELECT id FROM `" . self::table() . "` WHERE " . implode(' AND ', $where_query) .
                " LIMIT 1";

            $result = DatabasePerform::GetRow($query, $params);

            if (is_array($result) && isset($result['id'])) {
                $id = intval($result['id']);
                if ($id > 0) {
                    return $id;
                }
            }
        }

        return false;
    }

    public static function getRowInField($field, $data)
    {
        $result = [];

        $where_query = [];
        $params = [];

        foreach ($data as $key => $value) {
            $where_query[] = "?";
            $params[] = $value;
        }

        $query = "SELECT `" . $field . "` FROM `" . self::table() . "` WHERE `" . $field .
            "` IN (" . implode(',', $where_query) . ") GROUP BY `" . $field . "` LIMIT 20";

        $data = DatabasePerform::GetAll($query, $params);

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $result[] = $value[$field];
            }
        }

        return $result;
    }

    public static function insertRow($rows)
    {
        return static::insertRows([$rows]);
    }

    public static function insertRows($rows)
    {
        $rows_dublicate = [];

        if (!empty($rows)) {
            $columns = \workup\model\SourceData::syncColumnInDb($rows);

            $control_columns = [];

            if (self::$status_control) {
                $where_query = [];
                $params = [];

                $i = 0;
                foreach ($rows as $row) {
                    if (isset($row['parse_url'])) {
                        foreach ($row as $key => $value) {
                            if (empty(self::$fields_control) || in_array($key, self::$fields_control)) {
                                $where_query[$key][$i] = ':' . $key . '_' . $i;
                                $params[$key . '_' . $i] = $value;
                                $control_columns[$key] = '`' . $key . '`';
                            }
                        }

                        $i++;
                    }
                }

                foreach ($where_query as $key => $values) {
                    if (count($values) == '1') {
                        $where_query[$key] = "`" . $key . "` = " . $values[0];
                    } else {
                        $where_query[$key] = "`" . $key . "` IN (" . implode(',', $values) . ")";
                    }
                }

                if (self::$status_control == '3') {
                    $query = "DELETE FROM `" . self::table() . "` WHERE " . implode(' AND ', $where_query);
                    DatabasePerform::Execute($query, $params);
                }

                if ((self::$status_control == '1' || self::$status_control == '4') && !empty($control_columns) &&
                    !empty($where_query)) {
                    $query = "SELECT " . implode(',', $control_columns) . " FROM `" . self::table() .
                        "` WHERE " . implode(' AND ', $where_query) . ' ' . (count($rows) > 1 ?
                        'GROUP BY ' . reset($control_columns) : '') . ' LIMIT ' . count($rows);

                    $rows_in_db = DatabasePerform::GetAll($query, $params);

                    if (is_array($rows_in_db) && !empty($rows_in_db)) {
                        foreach ($rows as $key => $row) {
                            foreach ($rows_in_db as $row_in_db) {
                                $status_dublicate = null;

                                foreach ($row_in_db as $key_in_db => $value_in_db) {

                                    if (isset($row[$key_in_db]) && $row[$key_in_db] == $value_in_db) {
                                        $status_dublicate = true;
                                    } else {
                                        $status_dublicate = false;
                                        break;
                                    }
                                }

                                if ($status_dublicate) {
                                    break;
                                }
                            }

                            if ($status_dublicate) {
                                $rows_dublicate[$key] = $rows[$key];
                                unset($rows[$key]);
                            }
                        }
                    }
                }
            }

            $set_query = array();
            $value_query = array();
            $params = array();

            foreach ($columns as $column => $type) {
                $set_query[$column] = "`" . $column . "`";
            }

            $set_query['created_at'] = "`created_at`";
            $set_query['updated_at'] = "`updated_at`";

            foreach ($rows as $key => $row) {
                $value_query[$key] = array();

                foreach ($columns as $column => $type) {
                    $value = null;
                    if (isset($row[$column])) {
                        $value = $row[$column];
                    }

                    $value_query[$key][$column] = ":" . $column . "_" . $key;
                    $params[$column . "_" . $key] = $value;
                }

                $value_query[$key]['created_at'] = ":created_at_" . $key;
                $value_query[$key]['updated_at'] = ":updated_at_" . $key;

                $params['created_at_' . $key] = date('Y-m-d H:i:s');
                $params['updated_at_' . $key] = null;

            }

            foreach ($value_query as $key => $value) {
                $value_query[$key] = '(' . implode(',', $value) . ')';
            }

            if (!empty($value_query)) {
                $query = "INSERT INTO `" . self::table() . "` (" . implode(',', $set_query) .
                    ") VALUES " . implode(',', $value_query);
                    
                if (DatabasePerform::Execute($query, $params)) {
                    return count($value_query);
                }
            }

            if (self::$status_control == '4' && !empty($control_columns)) {
                foreach ($rows_dublicate as $key => $row) {
                    $set_query = array();
                    $where_query = array();

                    $params = array();

                    foreach ($row as $key => $value) {
                        if (isset($control_columns[$key])) {
                            $where_query[$key] = "`" . $key . "`=:" . $key;
                        } else {
                            $set_query[$key] = "`" . $key . "`=:" . $key;
                        }

                        $params[$key] = $value;
                    }

                    $set_query['updated_at'] = "`updated_at`=:updated_at";
                    $params['updated_at'] = date('Y-m-d H:i:s');

                    if (!empty($set_query) && !empty($where_query)) {
                        $query = "UPDATE `" . self::table() . "` SET " . implode(',', $set_query) .
                            " WHERE " . implode(' AND ', $where_query) . " LIMIT 1";

                        DatabasePerform::Execute($query, $params);
                    }
                }
            }
        }

        return 0;
    }
}

namespace workup\record;

use main\DatabasePerform;

class SourceParseRecord extends SourceDataRecord
{
    const TARGET_CLASS = '\workup\model\SourceParse';

    function update(\workup\model\ModelObject $obj)
    {
        return null;
    }
}

namespace workup\record;

use main\DatabasePerform;

class SourceRecord extends Record implements \workup\model\SourceFinder
{
    const TARGET_CLASS = '\workup\model\Source';

    public static function GetRowsProcessCron($limit, $begin_parse_at)
    {
        $limit = intval($limit);

        return (new static)->GetAll("SELECT * FROM `" . static::table() .
            "` WHERE `check_cron` = 1 ORDER BY `id` LIMIT " . $limit, []);
    }

    function delete(\workup\model\ModelObject $obj)
    {
        if (!empty($obj->table_name) && $obj->table_name) {
            if (!\workup\App::config('WORDPRESS') || stripos($obj->table_name, 'tbl_parser_')
                === 0) {
                if (!\main\DatabasePerform::GetOne("SELECT `id` FROM `_sources` WHERE `table_name` = :table_name AND `id` != :id LIMIT 1", ['table_name' =>
                    $obj->table_name, 'id' => $obj->id])) {
                    \main\DatabasePerform::Execute("DROP TABLE IF EXISTS `" . $obj->table_name . "`");
                }
            }
        }

        return self::deleteForId($obj->getId());
    }
}

namespace workup\record;

use main\DatabasePerform;

class UserRecord extends Record implements \workup\model\UserFinder
{
    const TARGET_CLASS = '\workup\model\User';

    public static function deleteForId($id)
    {
        if (DatabasePerform::Execute("DELETE FROM `" . self::table() .
            "` WHERE `id`=:id LIMIT 1", array('id' => $id))) {
            return true;
        } else {
            return false;
        }
    }

    public static function GetRowForLoginAndPassword($login, $password)
    {
        return (new self)->get("SELECT * FROM `" . self::table() .
            "` WHERE login=:login AND password=:password LIMIT 1", array('login' => $login,
                'password' => sha1($password)));
    }

    public static function isUserForLoginOrEmailAndNotId($login, $email, $id)
    {
        $id = intval($id);

        if (intval(DatabasePerform::GetOne("SELECT COUNT(*) FROM `" . self::table() .
            "` WHERE (`login`=:login OR `email`=:email) AND `id`!=:id;", array(
            'login' => $login,
            'email' => $email,
            'id' => $id))) > 0) {
            return true;
        }

        return false;
    }

    public static function isUserForLoginOrEmail($login, $email)
    {
        if (intval(DatabasePerform::GetOne("SELECT COUNT(*) FROM `" . self::table() .
            "` WHERE `login`=:login OR `email`=:email;", array('login' => $login, 'email' =>
                $email))) > 0) {
            return true;
        }

        return false;
    }
}

namespace workup\command;

use main\DatabasePerform;

class DropSourceTable extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (\workup\App::config('WORDPRESS')) {
            return self::statuses('CMD_ERROR');
        }
        
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $source_obj = null;

        if ($request->id_source_data) {
            $source_obj = \workup\model\Source::find($request->id_source_data);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }

        \workup\record\Record::dropTable($source_obj);
        
        $this->res = \main\Link::ListSources();
        
        return self::statuses('CMD_LOCATION');

        return self::statuses('CMD_ERROR');
    }
}

namespace workup\command;

use main\DatabasePerform;

class SourceUpdate extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $source_obj = null;

        if ($request->id) {
            $source_obj = \workup\model\Source::find($request->id);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }

        if (isset($source_obj) && !$request->insert && !$request->save) {
            $request->name = $source_obj->name;
            $request->table_name = $source_obj->table_name;
            $request->comment = $source_obj->comment;
            $request->urls = $source_obj->_urls;
            $request->target_list_element = $source_obj->_target_list_element;
            $request->target_list_value = $source_obj->_target_list_value;
            $request->target_list_url = $source_obj->_target_list_url;
            $request->target_list_next = $source_obj->_target_list_next;
            $request->begin_page = $source_obj->begin_page;
            $request->end_page = $source_obj->end_page;
            $request->key_page = $source_obj->key_page;
            $request->data_list = $source_obj->_data_list;
            $request->cookie_list = $source_obj->_cookie_list;
            $request->curlopt_list = $source_obj->_curlopt_list;
            $request->http_method_list = $source_obj->http_method_list;
            $request->page_urls = $source_obj->_page_urls;
            $request->target_page_element = $source_obj->_target_page_element;
            $request->target_page_value = $source_obj->_target_page_value;
            $request->data_page = $source_obj->_data_page;
            $request->cookie_page = $source_obj->_cookie_page;
            $request->curlopt_page = $source_obj->_curlopt_page;
            $request->http_method_page = $source_obj->http_method_page;
            $request->table_page_urls = $source_obj->table_page_urls;
            $request->column_table_page_urls = $source_obj->column_table_page_urls;
            $request->start_table_page_urls = $source_obj->start_table_page_urls;
            $request->length_table_page_urls = $source_obj->length_table_page_urls;
            $request->table_fixing = $source_obj->table_fixing;
            $request->amount_stream = $source_obj->amount_stream;
            $request->microtime_delay = $source_obj->microtime_delay;
            $request->func_data_processing_list = $source_obj->func_data_processing_list;
            $request->func_data_processing_page = $source_obj->func_data_processing_page;
            $request->status_control_insert = $source_obj->status_control_insert;
            $request->fields_control_insert = $source_obj->fields_control_insert;
            $request->proxy = $source_obj->proxy;
            $request->func_valid_url_list = $source_obj->func_valid_url_list;
            $request->func_valid_url_page = $source_obj->func_valid_url_page;
            $request->inspect_duplicate_url_list = $source_obj->inspect_duplicate_url_list;
            $request->inspect_duplicate_url_page = $source_obj->inspect_duplicate_url_page;
            $request->inspect_url_table = $source_obj->inspect_url_table;
            $request->insert_type = $source_obj->insert_type;
            $request->fields_in_table_for_transmission = $source_obj->
                fields_in_table_for_transmission;
            $request->default_values = $source_obj->_default_values;
            $request->dom_library = $source_obj->dom_library;
            $request->visibility = $source_obj->visibility;
        } else {
            if (!is_array($request->urls))
                $request->urls = [];
            if (!is_array($request->target_list_element))
                $request->target_list_element = [];
            $request->target_list_value = \main\build_fields_triplet($request,
                'target_list_value', 'phrase', 'attribute', 'name');
            $request->target_list_url = \main\build_fields_couple($request,
                'target_list_url', 'phrase', 'attribute');
            $request->target_list_next = \main\build_fields_couple($request,
                'target_list_next', 'phrase', 'attribute');
            $request->data_list = \main\build_fields_couple($request, 'data_list', 'key',
                'value');
            $request->cookie_list = \main\build_fields_couple($request, 'cookie_list', 'key',
                'value');
            $request->curlopt_list = \main\build_fields_couple($request, 'curlopt_list',
                'key', 'value');
            if (!is_array($request->page_urls))
                $request->page_urls = [];
            if (!is_array($request->target_page_element))
                $request->target_page_element = [];
            $request->target_page_value = \main\build_fields_triplet($request,
                'target_page_value', 'phrase', 'attribute', 'name');
            $request->data_page = \main\build_fields_couple($request, 'data_page', 'key',
                'value');
            $request->cookie_page = \main\build_fields_couple($request, 'cookie_page', 'key',
                'value');
            $request->curlopt_page = \main\build_fields_couple($request, 'curlopt_page',
                'key', 'value');
            $request->default_values = \main\build_fields_couple($request, 'default_values',
                'key', 'value');

            if (!$request->http_method_list == 'post') {
                $request->http_method_list = 'get';
            }
            if (!$request->http_method_page == 'post') {
                $request->http_method_page = 'get';
            }
            if ($request->inspect_duplicate_url_list != 'no') {
                $request->inspect_duplicate_url_list = 'yes';
            }
            if ($request->inspect_duplicate_url_page != 'no') {
                $request->inspect_duplicate_url_page = 'yes';
            }
        }

        if ($request->insert || $request->save) {
            if (!\main\Auth::statusAutorization()) {
                return self::statuses('CMD_ERROR_AUTORIZATION');
            }

            if (\main\Auth::getPrivileges() != 'admin') {
                return self::statuses('CMD_ERROR_PRIVILEGES');
            }

            $request->name = trim($request->name);
            $request->table_name = trim($request->table_name);
            $request->comment = trim($request->comment);
            $request->begin_page = trim($request->begin_page);
            $request->end_page = trim($request->end_page);
            $request->key_page = trim($request->key_page);
            $request->http_method_list = trim($request->http_method_list);
            $request->table_page_urls = trim($request->table_page_urls);
            $request->column_table_page_urls = trim($request->column_table_page_urls);
            $request->start_table_page_urls = trim($request->start_table_page_urls);
            $request->length_table_page_urls = trim($request->length_table_page_urls);
            $request->table_fixing = intval($request->table_fixing);
            $request->amount_stream = trim($request->amount_stream);
            $request->microtime_delay = trim($request->microtime_delay);
            $request->func_data_processing_list = trim($request->func_data_processing_list);
            $request->func_data_processing_page = trim($request->func_data_processing_page);
            $request->status_control_insert = intval($request->status_control_insert);
            $request->fields_control_insert = trim($request->fields_control_insert);
            $request->proxy = trim($request->proxy);
            $request->func_valid_url_list = trim($request->func_valid_url_list);
            $request->func_valid_url_page = trim($request->func_valid_url_page);
            $request->inspect_duplicate_url_list = trim($request->
                inspect_duplicate_url_list);
            $request->inspect_duplicate_url_page = trim($request->
                inspect_duplicate_url_page);
            $request->inspect_url_table = intval($request->inspect_url_table);
            $request->insert_type = intval($request->insert_type);
            $request->fields_in_table_for_transmission = trim($request->
                fields_in_table_for_transmission);
            $request->dom_library = intval($request->dom_library);
            $request->visibility = intval($request->visibility);

            if (empty($request->name)) {
                $request->error = "Имя источника не заполнено";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!\workup\App::config('WORDPRESS') && empty($request->table_name) ||
                preg_match("#[^a-zA-Z0-9_]#", $request->table_name) || preg_match("#^_#", $request->
                table_name)) {
                $request->error = "Имя таблицы заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if ($request->table_name == '_sources') {
                $request->error = "Запрещенное имя таблицы";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->begin_page) && preg_match("#[^\d]#", $request->begin_page)) {
                $request->error = "Начальная страница заполнена не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->end_page) && preg_match("#[^\d]#", $request->end_page)) {
                $request->error = "Конечная страница заполнена не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!is_object($source_obj)) {
                $source_obj = new \workup\model\Source();
                $source_obj->created_at = time();
            }

            if (!empty($request->table_page_urls) && preg_match("#[^a-zA-Z0-9_]#", $request->
                table_page_urls)) {
                $request->error = "Имя таблицы с ссылками на страницу заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->column_table_page_urls) && preg_match("#[^a-zA-Z0-9_]#", $request->
                column_table_page_urls)) {
                $request->error = "Поле в таблице с ссылкой заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->start_table_page_urls) && preg_match("#[^\d]#", $request->
                start_table_page_urls)) {
                $request->error = "Лимит (начало) заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->length_table_page_urls) && preg_match("#[^\d]#", $request->
                length_table_page_urls)) {
                $request->error = "Лимит (количество строк) заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->fields_in_table_for_transmission) && preg_match("#[^a-zA-Z0-9_ ]#",
                $request->fields_in_table_for_transmission)) {
                $request->error = "Поля для передачи заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (\workup\App::config('WORDPRESS')) {
                if(empty($request->table_name)){
                    $request->table_name = 'wp_posts';
                }
                
                if ($request->table_name != 'wp_posts') {
                    if (stripos($request->table_name, 'tbl_parser_') !== 0) {
                        $request->table_name = 'tbl_parser_' . $request->table_name;
                    }
                }
            }

            if (!empty($source_obj->table_name) && $source_obj->table_name != $request->
                table_name) {
                if (!\workup\App::config('WORDPRESS') || stripos($source_obj->table_name,
                    'tbl_parser_') === 0) {
                    if (!\main\DatabasePerform::GetOne("SELECT `id` FROM `_sources` WHERE `table_name` = :table_name AND `id` != :id LIMIT 1", ['table_name' =>
                        $source_obj->table_name, 'id' => $source_obj->id])) {
                        \main\DatabasePerform::Execute("DROP TABLE IF EXISTS `" . $source_obj->
                            table_name . "`");
                    }
                }
            }

            $source_obj->name = $request->name;
            $source_obj->table_name = $request->table_name;
            $source_obj->comment = $request->comment;
            $source_obj->urls = \main\squeeze($request->urls, "/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?/Diu");
            $source_obj->target_list_element = \main\squeeze($request->target_list_element);
            $source_obj->target_list_value = serialize($request->target_list_value);
            $source_obj->target_list_url = serialize($request->target_list_url);
            $source_obj->target_list_next = serialize($request->target_list_next);
            $source_obj->begin_page = intval($request->begin_page);
            $source_obj->end_page = intval($request->end_page);
            $source_obj->key_page = $request->key_page;
            $source_obj->data_list = serialize($request->data_list);
            $source_obj->cookie_list = serialize($request->cookie_list);
            $source_obj->curlopt_list = \main\squeeze_curlopt_array($request->curlopt_list);
            $source_obj->http_method_list = $request->http_method_list;
            $source_obj->page_urls = \main\squeeze($request->page_urls, "/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?/Diu");
            $source_obj->target_page_element = \main\squeeze($request->target_page_element);
            $source_obj->target_page_value = serialize($request->target_page_value);
            $source_obj->data_page = serialize($request->data_page);
            $source_obj->cookie_page = serialize($request->cookie_page);
            $source_obj->curlopt_page = \main\squeeze_curlopt_array($request->curlopt_page);
            $source_obj->http_method_page = $request->http_method_page;
            $source_obj->table_page_urls = $request->table_page_urls;
            $source_obj->column_table_page_urls = $request->column_table_page_urls;
            $source_obj->start_table_page_urls = $request->start_table_page_urls;
            $source_obj->length_table_page_urls = $request->length_table_page_urls;
            $source_obj->table_fixing = $request->table_fixing;
            if ($request->amount_stream > 0) {
                $source_obj->amount_stream = intval($request->amount_stream);
            } else {
                $source_obj->amount_stream = null;
            }
            if ($request->microtime_delay > 0) {
                $source_obj->microtime_delay = intval($request->microtime_delay);
            } else {
                $source_obj->microtime_delay = null;
            }
            $source_obj->func_data_processing_list = $request->func_data_processing_list;
            $source_obj->func_data_processing_page = $request->func_data_processing_page;
            $source_obj->status_control_insert = intval($request->status_control_insert);
            $source_obj->fields_control_insert = $request->fields_control_insert;
            $source_obj->proxy = $request->proxy;
            $source_obj->func_valid_url_list = $request->func_valid_url_list;
            $source_obj->func_valid_url_page = $request->func_valid_url_page;
            $source_obj->inspect_duplicate_url_list = $request->inspect_duplicate_url_list;
            $source_obj->inspect_duplicate_url_page = $request->inspect_duplicate_url_page;
            $source_obj->inspect_url_table = intval($request->inspect_url_table);
            $source_obj->insert_type = intval($request->insert_type);
            $source_obj->fields_in_table_for_transmission = $request->
                fields_in_table_for_transmission;
            $source_obj->default_values = \main\squeeze_values_array($request->
                default_values);
            $source_obj->dom_library = intval($request->dom_library);
            $source_obj->visibility = intval($request->visibility);

            $source_obj->save();

            if (is_object($source_obj) && $source_obj->id) {
                $this->res = \main\Link::ListSources();

                return self::statuses('CMD_LOCATION');
            } else {
                $request->setProperty("error", "Ошибка");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }
        }

        return self::statuses('CMD_DEFAULT');
    }
}

namespace workup\command;

use main\DatabasePerform;

class DropSourceTableCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (\workup\App::config('WORDPRESS')) {
            return self::statuses('CMD_ERROR');
        }
        
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $source_obj = null;

        if ($request->id_source_data) {
            $source_obj = \workup\model\Cron::find($request->id_source_data);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }

        \workup\record\Record::dropTable($source_obj);
        
        $this->res = \main\Link::ListCron();
        
        return self::statuses('CMD_LOCATION');

        return self::statuses('CMD_ERROR');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxBlockingAll extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        file_put_contents(\workup\App::config('DIR_TMP') . "/blocking_all", "blocking");

        $this->res = json_encode(array('status' => 'success', 'message' => 'done'));

        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxDelete extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        if ($request->delete_user) {
            if (!\main\Auth::statusAutorization()) {
                $this->res = json_encode(array('status' => 'fail', 'message' =>
                        'Пользователь не авторизован'));

                return self::statuses('CMD_AJAX');
            }

            if (\main\Auth::getPrivileges() != 'admin') {
                $this->res = json_encode(array('status' => 'fail', 'message' =>
                        'В доступе отказано'));

                return self::statuses('CMD_AJAX');
            }

            if (\workup\record\UserRecord::deleteForId($id)) {
                $this->res = json_encode(array('status' => 'success', 'message' =>
                        'Удаление прошло успешно'));
            } else {
                $this->res = json_encode(array('status' => 'fail', 'message' =>
                        'Ошибка удаления'));
            }

            return self::statuses('CMD_AJAX');
        } elseif ($request->delete_source) {
            $source_obj = \workup\model\Source::find($request->id);
            if ($source_obj) {
                $source_obj->delete();
                
                $this->res = json_encode(array('status' => 'success', 'message' =>
                        'Удаление прошло успешно'));
            } else {
                $this->res = json_encode(array('status' => 'fail', 'message' =>
                        'Ошибка удаления'));
            }

            return self::statuses('CMD_AJAX');
        } elseif ($request->delete_source_cron) {
            $source_obj = \workup\model\Cron::find($request->id);
            if ($source_obj) {
                $source_obj->delete();
                $this->res = json_encode(array('status' => 'success', 'message' =>
                        'Удаление прошло успешно'));
            } else {
                $this->res = json_encode(array('status' => 'fail', 'message' =>
                        'Ошибка удаления'));
            }

            return self::statuses('CMD_AJAX');
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Команда не определена'));
            return self::statuses('CMD_AJAX');
        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class SourceData extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        $source_obj = null;

        if ($request->id_source_data) {
            $source_obj = \workup\model\Source::find($request->id_source_data);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }

        if (\main\Auth::getPrivileges() != 'admin' && $source_obj->visibility > '0') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        if ($source_obj->createTableIfNotExist()) {
            \workup\model\SourceData::setTable($source_obj->table_name);

            $request->setObject('source', $source_obj);

            $page = (isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 1);

            \workup\record\SourceDataRecord::setLimit(50);

            $limit = \workup\record\SourceDataRecord::getLimit();

            if ($page) {
                $start = ($page - 1) * $limit;
            } else {
                $start = 0;
            }

            $params_search = [];

            if ($request->search && is_array($request->search)) {
                $search = [];

                foreach ($request->search as $key => $value) {
                    if (!empty($value)) {
                        $search[$key] = trim($value);
                    }
                }
                $params_search = $search;
            }

            $params_sort = [];

            if ($request->sort) {
                $params_sort['sort'] = $request->sort;
            }

            if ($request->order) {
                $params_sort['order'] = $request->order;
            }

            if (empty($params_sort)) {
                $request->sort = $params_sort['sort'] = 'id';
                $request->order = $params_sort['order'] = 'DESC';
            }

            $where = $params_search;

            if (\workup\App::config('WORDPRESS') && $source_obj->table_name == 'wp_posts') {
                $where['source_id'] = $source_obj->id;
            }

            \workup\record\SourceDataRecord::setCount($where);

            $source_data = \workup\record\SourceDataRecord::GetRows($start, $limit, $where,
                $params_sort);

            $request->setObject('source_data', $source_data);

            foreach ($source_data as $key => $value) {
                $request->columns = $value->getColumns();

                break;
            }

            $request->columns = \workup\model\SourceData::getColumnForDb();

            if (!is_array($request->columns)) {
                $request->columns = [];
            }

            if (!empty($params_search)) {
                $params_search = ['search' => $params_search];
            }

            $request->params_search = $params_search;

            $request->params_sort = $params_sort;

            $request->params = array_merge($params_search, $params_sort);

            return self::statuses('CMD_DEFAULT');
        }

        return self::statuses('CMD_ERROR');
    }
}

namespace workup\command;

use main\DatabasePerform;

class Users extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $page = (isset($_GET['page']) ? intval($_GET['page']) : 1);

        \workup\record\UserRecord::setLimit(20);

        $limit = \workup\record\UserRecord::getLimit();

        if ($page) {
            $start = ($page - 1) * $limit;
        } else {
            $start = 0;
        }

        \workup\record\UserRecord::setCount();

        $collection = \workup\record\UserRecord::GetRows($start, $limit);
        $request->setObject('users', $collection);

        return self::statuses('CMD_OK');
    }
}

namespace workup\command;

use main\DatabasePerform;

class changeCheckCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;
        $value = $request->value;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = \workup\model\Source::find($id);

        if ($source_obj) {
            $source_obj->check_cron = $value;
            $source_obj->save();
        }

        $this->res = json_encode(array('status' => 'success', 'message' => 'done'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use \main\DatabasePerform;

class SaveParser extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        header('Content-type: text/html; charset=utf-8');
        mb_internal_encoding('UTF-8');

        $libs = "<?php\r\n";
        $parser = "<?php\r\n";

        $libs .= \main\getCodeText('libs/phpQuery.php');
        $libs .= \main\getCodeText('libs/simple_html_dom.php');

        $parser .= \main\getCodeText('workup/base/init.php');
                
        $parser .= str_replace('return', '$GLOBALS[\'app_options\'] = ', \main\getCodeText('config/Options.php'));

        $parser .= preg_replace("#private function init().*?\{.*?\}.*?\}.*?\}#si",
            "private function init()\r\n    {\r\n        spl_autoload_register(\"\workup\App::autoload\");\r\n\r\n        if (file_exists(\$this->config['LOG_ERRORS_FILE']) && filesize(\$this->config['LOG_ERRORS_FILE']) > 10485760) {\r\n            unlink(\$this->config['LOG_ERRORS_FILE']);\r\n        }\r\n\r\n        if (!is_dir(\$this->config['DIR_TMP'])) {\r\n            mkdir(\$this->config['DIR_TMP'], 0777, true);\r\n        }\r\n\r\n        \main\ErrorHandler::SetHandler();\r\n\r\n    }", \main\getCodeText
            ('workup/App.php'));

        $parser .= \main\getCodeText('workup/base/ErrorHandler.php');
        $parser .= \main\getCodeText('include/curl_exec.php');
        $parser .= \main\getCodeText('include/ParseSource.php');
        $parser .= \main\getCodeText('workup/base/DatabaseHandler.php');
        $parser .= \main\getCodeText('workup/base/Exceptions.php');
        $parser .= \main\getCodeText('workup/base/Auth.php');
        $parser .= \main\getCodeText('workup/base/Link.php');
        $parser .= \main\getCodeText('workup/base/Functions.php');
        //$parser .= getCodeText('include/helpers.php');
        $parser .= \main\getCodeText('workup/base/Registry.php');
        $parser .= \main\getCodeText('workup/model/Finder.php');
        $parser .= \main\getCodeText('workup/record/Collection.php');
        $parser .= \main\getCodeText('workup/record/Collections.php');
        $parser .= \main\getCodeText('workup/record/PersistenceFactory.php');
        $parser .= \main\getCodeText('workup/controller/ApplicationHelper.php');
        $parser .= \main\getCodeText('workup/controller/AppController.php');
        $parser .= \main\getCodeText('workup/controller/Request.php');
        $parser .= \main\getCodeText('workup/controller/Controller.php');
        $parser .= \main\getCodeText('workup/command/Command.php');

        $parser .= \main\getCodeText('workup/base/Mail.php');
        $parser .= \main\getCodeText('workup/command/NotFound.php');
        $parser .= \main\getCodeText('workup/command/Sources.php');
        $parser .= \main\getCodeText('workup/command/ProcessParse.php');
        $parser .= \main\getCodeText('workup/command/Parse.php');
        $parser .= \main\getCodeText('workup/model/ObjectWatcher.php');
        $parser .= \main\getCodeText('workup/model/ModelObject.php');
        $parser .= \main\getCodeText('workup/model/Cron.php');
        $parser .= \main\getCodeText('workup/model/HelperFactory.php');
        $parser .= \main\getCodeText('workup/model/Source.php');
        $parser .= \main\getCodeText('workup/model/SourceData.php');
        $parser .= \main\getCodeText('workup/model/SourceParse.php');
        $parser .= \main\getCodeText('workup/model/User.php');
        $parser .= \main\getCodeText('workup/record/Record.php');
        $parser .= \main\getCodeText('workup/record/CronRecord.php');
        $parser .= \main\getCodeText('workup/record/SourceDataRecord.php');
        $parser .= \main\getCodeText('workup/record/SourceParseRecord.php');
        $parser .= \main\getCodeText('workup/record/SourceRecord.php');
        $parser .= \main\getCodeText('workup/record/UserRecord.php');

        $parser .= \main\getCodeTextDir('workup/base');
        $parser .= \main\getCodeTextDir('workup/command');
        $parser .= \main\getCodeTextDir('workup/controller');
        $parser .= \main\getCodeTextDir('workup/model');
        $parser .= \main\getCodeTextDir('workup/record');

        $parser .= \main\getCodeText('workup/base/ViewHelper.php');
        $parser .= preg_replace("#\}$#", 
            //\main\getCodeTextJavascript('bootstrap\js\bootstrap.min.js') . 
            //\main\getCodeTextJavascript('js\sources_script.js') .
            //\main\getCodeTextJavascript('js\source_data.js') .
            //\main\getCodeTextJavascript('js\jquery-ui-update-datepicker-event.js') .
            //\main\getCodeTextJavascript('js\datepicker-ru.js') .
            //\main\getCodeTextJavascript('js\users_script.js') .
            //\main\getCodeTextJavascript('js\jquery.timeago.js') .
            //\main\getCodeTextJavascript('js\source_update.js') .
            //\main\getCodeTextJavascript('js\calendar_ru.js') .
            //\main\getCodeTextJavascript('js\jquery-ui.min.js') .
            //\main\getCodeTextJavascript('js\jquery.maskedinput.js') .
            //\main\getCodeTextJavascript('js\jquery.min.js') .
            //\main\getCodeTextCss('css\style.css') . 
            //\main\getCodeTextCss('css\jquery-ui.min.css') . 
            //\main\getCodeTextCss('css\jquery.autocomplete.css') . 
           // \main\getCodeTextCss('bootstrap\css\bootstrap.min.css') . 
            \main\getCodeTextViewDir('workup/view'),
            rtrim(\main\getCodeText('workup/base/IncludeFile.php'))) . "\r\n}\r\n";
        $parser .= \main\getCodeText('workup/base/View.php');

        file_put_contents(\workup\App::config('SITE_ROOT') . '/parser/libs.php',
            mb_convert_encoding($libs, 'UTF-8', 'UTF-8'));
        file_put_contents(\workup\App::config('SITE_ROOT') . '/parser/Parser.php',
            mb_convert_encoding($parser, 'UTF-8', 'UTF-8'));
            
        //file_put_contents('C:\Server\domains\wordpress\public_html\wp-content\plugins\wp-parser/Parser.php',
        //    mb_convert_encoding($parser, 'UTF-8', 'UTF-8'));

        copy(\workup\App::config('SITE_ROOT') . '/include/helpers.php', \workup\App::
            config('SITE_ROOT') . '/parser/helpers.php');

        $this->res = \main\Link::ListSources();

        return self::statuses('CMD_LOCATION');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxExportExcelCron extends Command
{
    public function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = null;

        if ($request->id) {
            $source_obj = \workup\model\Cron::find($request->id);
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Иденитфикатора не передано'));

            return self::statuses('CMD_AJAX');
        }

        if (!$source_obj) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Объекта таблицы не определен'));

            return self::statuses('CMD_AJAX');
        }

        require_once (\workup\App::config('SITE_ROOT') . "/libs/PHPExcel.php");

        if ($source_obj->createTableIfNotExist()) {
            $phpexcel = new \PHPExcel();
            $phpexcel->setActiveSheetIndex(0);
            $ativeIndex = 0;

            $sheet = $phpexcel->getActiveSheet();
            
            $sheet->setTitle(\main\str_limit(trim(preg_replace("#\s{2,}#", ' ', preg_replace("#[^a-zA-Z0-9АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЮюЯяЬьЪъёЫы_. ]#",
                ' ', $source_obj->name))), 25));

            // $sheet->getColumnDimension('A')->setAutoSize(true);

            \workup\model\SourceData::setTable($source_obj->table_name);

            $columns = \workup\model\SourceData::getColumnForDb();

            $_i = 0;

            if (is_array($columns)) {
                $_i++;
                $_j = 0;
                foreach ($columns as $column => $type) {
                    if ($column == 'id') {
                       // continue;
                    }

                    $sheet->setCellValueByColumnAndRow($_j, $_i, $column);

                    $_j++;

                }
            }

            if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id)) {
                unlink(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id);
            }
            if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_all")) {
                unlink(\workup\App::config('DIR_TMP') . "/blocking_all");
            }

            $offset = intval($request->offset);

            $limit = 200;

            $amount = intval($request->limit);

            $count = DatabasePerform::GetOne("SELECT COUNT(*) FROM `" . $source_obj->
                table_name . "`");

            set_time_limit(10000);

            for ($i = 0; $i < ceil($count / $limit) && $amount > 0; $i++, $offset = $offset + $limit) {
                if($amount < $limit){
                    $limit = $amount;        
                }
                                  
                $rows = DatabasePerform::GetAll("SELECT * FROM `" . $source_obj->table_name .
                    "` LIMIT " . $offset . ", " . $limit, null, \PDO::FETCH_NUM);
                    
                $amount = $amount - $limit;   

                if (is_array($rows)) {
                    foreach ($rows as $row) {
                        $_j = 0;
                        $_i++;

                        foreach ($row as $key => $value) {
                            if ($key == 'id') {
                               // continue;
                            }

                            $sheet->setCellValueByColumnAndRow($_j, $_i, $value);

                            $_j++;
                        }
                    }
                }

                if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id)) {
                    exit();
                }
                if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_all")) {
                    exit();
                }
            }

            $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
            $objWriter->save(\workup\App::config('DIR_TMP') . '/' . $source_obj->table_name . '.xlsx');

            $this->res = json_encode(array('status' => 'success', 'message' => 'Успех'));
            return self::statuses('CMD_AJAX');
        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxExtract extends Command
{
    public function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = \workup\model\Source::find($id);

        if ($source_obj) {
            $properties = $source_obj->getProperties();

            $filename = $source_obj->table_name . '.php';

            $this->saveSourceInFile($filename, $properties);

            $this->res = json_encode(array('status' => 'success', 'message' => 'done'));

            return self::statuses('CMD_AJAX');
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Записи не найдено'));

            return self::statuses('CMD_AJAX');

        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }

    private function saveSourceInFile($filename, $data)
    {
        $content = '';

        $content .= "<?php \n\n";
        $content .= "// Лимит времени выполнения 10000 с.\n";
        $content .= "set_time_limit(10000);\n";
        
        $config = '';
        
        $config .= str_replace('return', '$app_config =',
            \main\getCodeText('config/App.php')) . "\r\n";
            
        $config = "\r\n" . trim(str_replace("'TYPE_OPTIONS' => 'xml'",
            "'TYPE_OPTIONS' => 'array'", str_replace("dirname(dirname(__file__))",
            "dirname(__file__)", str_replace("'GOOGLE_SCRIPT_EXPORT_SHEETS' => 'https://script.google.com/macros/s/AKfycbzQtOqep599IQorzNe_18znI1NR8EjsMStVZG8GFZ1Fno3Nzrg/exec'",
            "'GOOGLE_SCRIPT_EXPORT_SHEETS' => ''", str_replace("'GOOGLE_SHEETS_ID' => '158AbaXyOsbAxyyC7IBk-UFI7BMUtcT8A4jgqJmd0IzA'",
            "'GOOGLE_SHEETS_ID' => ''", str_replace("'GOOGLE_SCRIPT_SECRET_KEY' => 'ASD2xcSx3csA4dSDa5s3sc5SDW13'",
            "'GOOGLE_SCRIPT_SECRET_KEY' => ''", $config)))))) . "\r\n\r\n";

        $content .= $config;            
        
        $content .= "\$_APP_PARAMS = [];\n\n";
        $content .= "\$_APP_PARAMS['cmd'] = \$_GET['cmd'] = 'ProcessParse';\n\n";
        
        $content .= "\$SOURCE_OBJECTS = [];\n\n";

        $content .= '$SOURCE_OBJECTS[] = ' . rtrim($this->format(null, $data, 0), "\n,") .
            ";\n\n";

        $content .= "require_once (__dir__ . \"/libs.php\");\n\n";
        $content .= "require_once (__dir__ . \"/helpers.php\");\n\n";
        $content .= "require_once (__dir__ . \"/parser.php\");\n\n";
        $content .= "\workup\controller\Controller::run();\n";

        file_put_contents(\workup\App::config('DIR_TMP') . '/' . $filename, $content);
    }

    private function format($key = '', $data, $iteration = 0)
    {
        $result = "";

        $left_indent = str_repeat(' ', $iteration * 4);

        if (is_array($data)) {
            if (!empty($key) || $key === 0) {
                $result .= $left_indent . (is_numeric($key) ? intval($key) : "'" . $key . "'") .
                    " => [\n";
            } else {
                $result .= $left_indent . "[\n";
            }

            $iteration++;

            foreach ($data as $key => $value) {
                $result .= $this->format($key, $value, $iteration);
            }

            $result .= $left_indent . "],\n";
        } else {
            if (!empty($key) || $key === 0) {
                $result .= $left_indent . (is_numeric($key) ? intval($key) : "'" . $key . "'") .
                    " => " . (is_numeric($data) ? str_replace(',', '.', round((float)$data, 4)) :
                    "'" . str_replace("'", "\'", $data) . "'") . ",\n";
            } else {
                $result .= $left_indent . "" . (is_numeric($data) ? str_replace(',', '.', round
                    ((float)$data, 4)) : "'" . str_replace("'", "\'", $data) . "'") . ",\n";
            }
        }

        return $result;
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxProcessCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $sources = [];

        $sources[] = \workup\model\Cron::find($id);

        if (!empty($sources)) {
            $request->setObject('parse_sources', $sources);
            return self::statuses('CMD_PARSE');
        }

        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ProcessCron extends Command
{
    private $limit = 10;
    private $offset_time = 18000;

    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $sources = \workup\record\CronRecord::GetRowsProcessCron($this->limit, time() -
            $this->offset_time);

        if (!empty($sources)) {
            $request->setObject('parse_sources', $sources);
            return self::statuses('CMD_PARSE');
        }

        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class Autorization extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if ($request->logout) {
            if (isset($_SESSION['auth'])) {
                unset($_SESSION['auth']);
            }
        } elseif ($request->autorization) {
            $login = $request->login;
            $password = $request->password;

            if (empty($login)) {
                $request->error_autorization = "Логин не заполнен";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (empty($password)) {
                $request->error_autorization = "Пароль не заполнен";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            $user_obj = \workup\record\UserRecord::GetRowForLoginAndPassword($login, $password);

            if ($user_obj && is_object($user_obj)) {
                $_SESSION['auth'] = array(
                    'id' => $user_obj->id,
                    'name' => $user_obj->name,
                    'password' => $user_obj->password,
                    'login' => $user_obj->login,
                    'privileges' => $user_obj->privileges);

                return self::statuses('CMD_OK');
            } else {
                $request->error_autorization = "Логин или пароль введены не верно";

                return self::statuses('CMD_ERROR');
            }
        }

        return self::statuses('CMD_DEFAULT');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxExtractCron extends Command
{
    public function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = \workup\model\Cron::find($id);

        if ($source_obj) {
            $properties = $source_obj->getProperties();

            $filename = $source_obj->table_name . '_cron' . '.php';

            $this->saveSourceInFile($filename, $properties);

            $this->res = json_encode(array('status' => 'success', 'message' => 'done'));

            return self::statuses('CMD_AJAX');
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Записи не найдено'));

            return self::statuses('CMD_AJAX');

        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }

    private function saveSourceInFile($filename, $data)
    {
        $content = '';

        $content .= "<?php \n\n";
        $content .= "// Лимит времени выполнения 10000 с.\n";
        
        $config = '';
        
        $config .= str_replace('return', '$app_config =',
            \main\getCodeText('config/App.php')) . "\r\n";
            
        $config = "\r\n" . trim(str_replace("'TYPE_OPTIONS' => 'xml'",
            "'TYPE_OPTIONS' => 'array'", str_replace("dirname(dirname(__file__))",
            "dirname(__file__)", str_replace("'GOOGLE_SCRIPT_EXPORT_SHEETS' => 'https://script.google.com/macros/s/AKfycbzQtOqep599IQorzNe_18znI1NR8EjsMStVZG8GFZ1Fno3Nzrg/exec'",
            "'GOOGLE_SCRIPT_EXPORT_SHEETS' => ''", str_replace("'GOOGLE_SHEETS_ID' => '158AbaXyOsbAxyyC7IBk-UFI7BMUtcT8A4jgqJmd0IzA'",
            "'GOOGLE_SHEETS_ID' => ''", str_replace("'GOOGLE_SCRIPT_SECRET_KEY' => 'ASD2xcSx3csA4dSDa5s3sc5SDW13'",
            "'GOOGLE_SCRIPT_SECRET_KEY' => ''", $config)))))) . "\r\n\r\n";

        $content .= $config;
                        
        $content .= "set_time_limit(10000);\n\n";
        $content .= "\$_APP_PARAMS = [];\n\n";
        $content .= "\$_APP_PARAMS['cmd'] = \$_GET['cmd'] = 'ProcessParse';\n\n";
        
        $content .= "\$SOURCE_OBJECTS = [];\n\n";

        $content .= '$SOURCE_OBJECTS[] = ' . rtrim($this->format(null, $data, 0), "\n,") .
            ";\n\n";

        $content .= "require_once (__dir__ . \"/libs.php\");\n\n";
        $content .= "require_once (__dir__ . \"/helpers.php\");\n\n";
        $content .= "require_once (__dir__ . \"/parser.php\");\n\n";
        $content .= "\workup\controller\Controller::run();\n";

        file_put_contents(\workup\App::config('DIR_TMP') . '/' . $filename, $content);
    }

    private function format($key = '', $data, $iteration = 0)
    {
        $result = "";

        $left_indent = str_repeat(' ', $iteration * 4);

        if (is_array($data)) {
            if (!empty($key) || $key === 0) {
                $result .= $left_indent . (is_numeric($key) ? intval($key) : "'" . $key . "'") .
                    " => [\n";
            } else {
                $result .= $left_indent . "[\n";
            }

            $iteration++;

            foreach ($data as $key => $value) {
                $result .= $this->format($key, $value, $iteration);
            }

            $result .= $left_indent . "],\n";
        } else {
            if (!empty($key) || $key === 0) {
                $result .= $left_indent . (is_numeric($key) ? intval($key) : "'" . $key . "'") .
                    " => " . (is_numeric($data) ? str_replace(',', '.', round((float)$data, 4)) :
                    "'" . str_replace("'", "\'", $data) . "'") . ",\n";
            } else {
                $result .= $left_indent . "" . (is_numeric($data) ? str_replace(',', '.', round
                    ((float)$data, 4)) : "'" . str_replace("'", "\'", $data) . "'") . ",\n";
            }
        }

        return $result;
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxAddCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = \workup\model\Source::find($id);

        if ($source_obj) {

            $source_cron = new \workup\model\Cron();

            $source_cron->name = $source_obj->name;
            $source_cron->table_name = $source_obj->table_name;
            $source_cron->comment = $source_obj->comment;
            $source_cron->urls = $source_obj->urls;
            $source_cron->target_list_element = $source_obj->target_list_element;
            $source_cron->target_list_value = $source_obj->target_list_value;
            $source_cron->target_list_url = $source_obj->target_list_url;
            $source_cron->target_list_next = $source_obj->target_list_next;
            $source_cron->begin_page = $source_obj->begin_page;
            $source_cron->end_page = $source_obj->end_page;
            $source_cron->key_page = $source_obj->key_page;
            $source_cron->data_list = $source_obj->data_list;
            $source_cron->cookie_list = $source_obj->cookie_list;
            $source_cron->curlopt_list = $source_obj->curlopt_list;
            $source_cron->http_method_list = $source_obj->http_method_list;
            $source_cron->page_urls = $source_obj->page_urls;
            $source_cron->target_page_element = $source_obj->target_page_element;
            $source_cron->target_page_value = $source_obj->target_page_value;
            $source_cron->data_page = $source_obj->data_page;
            $source_cron->cookie_page = $source_obj->cookie_page;
            $source_cron->curlopt_page = $source_obj->curlopt_page;
            $source_cron->http_method_page = $source_obj->http_method_page;
            $source_cron->table_page_urls = $source_obj->table_page_urls;
            $source_cron->column_table_page_urls = $source_obj->column_table_page_urls;
            $source_cron->start_table_page_urls = $source_obj->start_table_page_urls;
            $source_cron->length_table_page_urls = $source_obj->length_table_page_urls;
            $source_cron->table_fixing = $source_obj->table_fixing;
            $source_cron->created_at = $source_obj->created_at;
            $source_cron->func_data_processing_list = $source_obj->
                func_data_processing_list;
            $source_cron->func_data_processing_page = $source_obj->
                func_data_processing_page;
            $source_cron->count_process = $source_obj->count_process;
            $source_cron->status_control_insert = $source_obj->status_control_insert;
            $source_cron->fields_control_insert = $source_obj->fields_control_insert;
            $source_cron->proxy = $source_obj->proxy;
            $source_cron->func_valid_url_list = $source_obj->func_valid_url_list;
            $source_cron->func_valid_url_page = $source_obj->func_valid_url_page;
            $source_cron->inspect_duplicate_url_list = $source_obj->
                inspect_duplicate_url_list;
            $source_cron->inspect_duplicate_url_page = $source_obj->
                inspect_duplicate_url_page;
            $source_cron->time_process_life = $source_obj->time_process_life;
            $source_cron->inspect_url_table = $source_obj->inspect_url_table;
            $source_cron->insert_type = $source_obj->insert_type;
            $source_cron->import_rate = $source_obj->import_rate;
            $source_cron->last_write_at = $source_obj->last_write_at;
            $source_cron->last_write_count = $source_obj->last_write_count;
            $source_cron->last_import_at = $source_obj->last_import_at;
            $source_cron->last_import_count = $source_obj->last_import_count;
            $source_cron->fields_in_table_for_transmission = $source_obj->
                fields_in_table_for_transmission;
            $source_cron->default_values = $source_obj->default_values;

            $source_cron->save();

            $this->res = json_encode(array('status' => 'success', 'message' => 'done'));

            return self::statuses('CMD_AJAX');
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Записи не найдено'));

            return self::statuses('CMD_AJAX');

        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class UserAdd extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $name = $request->getProperty("name");
        $login = $request->getProperty("login");
        $password = $request->getProperty("password");
        $pass_again = $request->getProperty("pass_again");
        $privileges = $request->getProperty("privileges");
        $email = $request->getProperty("email");

        if (empty($name)) {
            $request->setProperty("error_text_add", "Имя не заполнено");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (empty($login)) {
            $request->setProperty("error_text_add", "Логин не заполнен");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (empty($password)) {
            $request->setProperty("error_text_add", "Пароль не заполнен");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (empty($pass_again)) {
            $request->setProperty("error_text_add", "Повтор пароля не заполнен");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if ($password != $pass_again) {
            $request->setProperty("error_text_add", "Пароли не совпадают");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (empty($privileges)) {
            $request->setProperty("error_text_add", "Привилегии не выбраны");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (!($privileges == 'admin' || $privileges == 'operator')) {
            $request->setProperty("error_text_add", "Привилегии переданы не верно");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (empty($email)) {
            $request->setProperty("error_text_add", "Адрес Email не заполнено");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (!preg_match("#^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,6}$#i", $email)) {
            $request->setProperty("error_text_add", "E-mail не корректный");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        if (\workup\record\UserRecord::isUserForLoginOrEmail($login, $email)) {
            $request->setProperty("error_text_add", "Указанный Логин или Email занят");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        $user_obj = new \workup\model\User(array(
            'name' => $name,
            'login' => $login,
            'password' => sha1($password),
            'privileges' => $privileges,
            'email' => $email));

        if ($user_obj) {
            $this->res = \main\Link::ListUsers();

            return self::statuses('CMD_LOCATION');
        } else {
            $request->setProperty("error_text_add", "Ошибка добавления");
            return self::statuses('CMD_INSUFFICIENT_DATA');
        }

        return self::statuses('CMD_OK');
    }
}

namespace workup\command;

use main\DatabasePerform;

class UserEdit extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $id = $request->getProperty("id");

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            return self::statuses('CMD_MISSING_ROW');
        }

        $user_obj = $mail_obj = \workup\model\User::find($id);

        if (!$user_obj) {
            return self::statuses('CMD_MISSING_ROW');
        }

        $request->setObject('user', $user_obj);

        $edit = $request->getProperty("edit");

        $edit_department = $request->getProperty("edit_department");
        $department_id = intval($request->getProperty("id_department"));

        if ($edit_department && $department_id) {
            if (\workup\record\UserRecord::isDepartmentForUser($user_obj->id, $department_id)) {
                $request->setProperty("error_text_add_department",
                    "Выбраный отдел уже принадлежит этому пользователю");
            } else {
                \workup\record\UserRecord::addDepartmentForUser($user_obj->id, $department_id);
            }
        }

        if ($edit) {
            $name = $request->getProperty("name");
            $login = $request->getProperty("login");
            $password = $request->getProperty("password");
            $pass_again = $request->getProperty("pass_again");
            $privileges = $request->getProperty("privileges");
            $email = $request->getProperty("email");

            if (empty($name)) {
                $request->setProperty("error_text_add", "Имя не заполнено");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (empty($login)) {
                $request->setProperty("error_text_add", "Логин не заполнен");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($password) && empty($pass_again)) {
                $request->setProperty("error_text_add", "Повтор пароля не заполнен");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($password) && $password != $pass_again) {
                $request->setProperty("error_text_add", "Пароли не совпадают");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (empty($privileges)) {
                $request->setProperty("error_text_add", "Привилегии не выбраны");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!($privileges == 'admin' || $privileges == 'operator')) {
                $request->setProperty("error_text_add", "Привилегии переданы не верно");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (empty($email)) {
                $request->setProperty("error_text_add", "Адрес Email не заполнено");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!preg_match("#^[-0-9a-z_\.]+@[-0-9a-z^\.]+\.[a-z]{2,6}$#i", $email)) {
                $request->setProperty("error_text_add", "E-mail не корректный");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (\workup\record\UserRecord::isUserForLoginOrEmailAndNotId($login, $email, $user_obj->
                id)) {
                $request->setProperty("error_text_add", "Указанный Логин или Email занят");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            $user_obj->name = $name;
            $user_obj->login = $login;
            $user_obj->privileges = $privileges;
            $user_obj->email = $email;

            if (!empty($password)) {
                $user_obj->password = sha1($password);
            }

            if ($user_obj) {
                $this->res = \main\Link::ListUsers(true);

                return self::statuses('CMD_LOCATION');
            } else {
                $request->setProperty("error_text_add", "Ошибка редактирования");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }
        } else {
            $request->setProperty("name", $user_obj->name);
            $request->setProperty("login", $user_obj->login);
            $request->setProperty("privileges", $user_obj->privileges);
            $request->setProperty("email", $user_obj->email);
        }

        return self::statuses('CMD_OK');
    }
}

namespace workup\command;

use main\DatabasePerform;

class changeCheckCronByCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;
        $value = $request->value;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = \workup\model\Cron::find($id);

        if ($source_obj) {
            $source_obj->check_cron = $value;
            $source_obj->save();
        }

        $this->res = json_encode(array('status' => 'success', 'message' => 'done'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxBlocking extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = \workup\model\Source::find($id);

        if ($source_obj) {
            file_put_contents(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id, "blocking");

            $this->res = json_encode(array('status' => 'success', 'message' => 'done'));

            return self::statuses('CMD_AJAX');
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Записи не найдено'));

            return self::statuses('CMD_AJAX');

        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxExportExcel extends Command
{
    public function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = null;

        if ($request->id) {
            $source_obj = \workup\model\Source::find($request->id);
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Иденитфикатора не передано'));

            return self::statuses('CMD_AJAX');
        }

        if (!$source_obj) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Объекта таблицы не определен'));

            return self::statuses('CMD_AJAX');
        }

        require_once (\workup\App::config('SITE_ROOT') . "/libs/PHPExcel.php");

        if ($source_obj->createTableIfNotExist()) {
            $phpexcel = new \PHPExcel();
            $phpexcel->setActiveSheetIndex(0);
            $ativeIndex = 0;

            $sheet = $phpexcel->getActiveSheet();

            $sheet->setTitle(\main\str_limit(trim(preg_replace("#\s{2,}#", ' ', preg_replace("#[^a-zA-Z0-9АаБбВвГгҐґДдЕеЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЮюЯяЬьЪъёЫы_. ]#",
                ' ', $source_obj->name))), 25));

            // $sheet->getColumnDimension('A')->setAutoSize(true);

            \workup\model\SourceData::setTable($source_obj->table_name);

            $columns = \workup\model\SourceData::getColumnForDb();

            $_i = 0;

            if (is_array($columns)) {
                $_i++;
                $_j = 0;
                foreach ($columns as $column => $type) {
                    if ($column == 'id') {
                       // continue;
                    }

                    $sheet->setCellValueByColumnAndRow($_j, $_i, $column);

                    $_j++;

                }
            }

            if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id)) {
                unlink(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id);
            }
            if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_all")) {
                unlink(\workup\App::config('DIR_TMP') . "/blocking_all");
            }

            $offset = intval($request->offset);

            $limit = 200;

            $amount = intval($request->limit);

            $count = DatabasePerform::GetOne("SELECT COUNT(*) FROM `" . $source_obj->
                table_name . "`");

            set_time_limit(10000);

            for ($i = 0; $i < ceil($count / $limit) && $amount > 0; $i++, $offset = $offset +
                $limit) {
                if ($amount < $limit) {
                    $limit = $amount;
                }

                $rows = DatabasePerform::GetAll("SELECT * FROM `" . $source_obj->table_name .
                    "` LIMIT " . $offset . ", " . $limit, null, \PDO::FETCH_NUM);

                $amount = $amount - $limit;

                if (is_array($rows)) {
                    foreach ($rows as $row) {
                        $_j = 0;
                        $_i++;

                        foreach ($row as $key => $value) {
                            if ($key == 'id') {
                               // continue;
                            }

                            $sheet->setCellValueByColumnAndRow($_j, $_i, $value);

                            $_j++;
                        }
                    }
                }

                if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id)) {
                    exit();
                }
                if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_all")) {
                    exit();
                }
            }

            $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
            $objWriter->save(\workup\App::config('DIR_TMP') . '/' . $source_obj->table_name . '.xlsx');

            $this->res = json_encode(array('status' => 'success', 'message' => 'Успех'));
            return self::statuses('CMD_AJAX');
        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class Cron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        $page = (isset($_GET['p']) ? intval($_GET['p']) : 1);

        \workup\record\CronRecord::setLimit(50);

        $limit = \workup\record\CronRecord::getLimit();

        if ($page) {
            $start = ($page - 1) * $limit;
        } else {
            $start = 0;
        }

        $params_search = [];

        if ($request->search && is_array($request->search)) {
            $search = [];

            foreach ($request->search as $key => $value) {
                if (!empty($value)) {
                    $search[$key] = trim($value);
                }
            }
            $params_search = $search;
        }

        $params_sort = [];

        if ($request->sort) {
            $params_sort['sort'] = $request->sort;
        }

        if ($request->order) {
            $params_sort['order'] = $request->order;
        }

        if (empty($params_sort)) {
            $request->sort = $params_sort['sort'] = 'check_cron DESC, id';
            $request->order = $params_sort['order'] = 'DESC';
        }

        $params = [];

        foreach ($params_search as $key => $value) {
            if ($key == 'search') {
                $expl = explode('->', $value);

                if (count($expl == 2)) {
                    if (count($expl == 2) && ($expl[0] == 'name' || $expl[0] == 'comment' || $expl[0] ==
                        'table_name' || $expl[0] == 'table_name' || $expl[0] == 'urls')) {
                        $params[$expl[0]] = $expl[1];
                    } else {
                        $params['name'] = $expl[1];
                    }
                } else {
                    $params['name'] = $value;
                }
            } else {
                $params[$key] = $value;
            }
        }
        
        if (\main\Auth::getPrivileges() != 'admin') {
            $params['visibility'] = '0';
        }

        \workup\record\CronRecord::setCount($params);

        $request->count_all_sources = \workup\record\CronRecord::getCount();

        $sources = \workup\record\CronRecord::GetRows($start, $limit, $params, $params_sort);

        $request->setObject('sources', $sources);

        if (!empty($params_search)) {
            $params_search = ['search' => $params_search];
        }

        $request->params_search = $params_search;

        $request->params_sort = $params_sort;

        $request->params = array_merge($params_search, $params_sort);

        return self::statuses('CMD_DEFAULT');
    }
}

namespace workup\command;

use main\DatabasePerform;

class SourceUpdateCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $source_obj = null;

        if ($request->id) {
            $source_obj = \workup\model\Cron::find($request->id);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }

        if (isset($source_obj) && !$request->insert && !$request->save) {
            $request->name = $source_obj->name;
            $request->table_name = $source_obj->table_name;
            $request->comment = $source_obj->comment;
            $request->urls = $source_obj->_urls;
            $request->target_list_element = $source_obj->_target_list_element;
            $request->target_list_value = $source_obj->_target_list_value;
            $request->target_list_url = $source_obj->_target_list_url;
            $request->target_list_next = $source_obj->_target_list_next;
            $request->begin_page = $source_obj->begin_page;
            $request->end_page = $source_obj->end_page;
            $request->key_page = $source_obj->key_page;
            $request->data_list = $source_obj->_data_list;
            $request->cookie_list = $source_obj->_cookie_list;
            $request->curlopt_list = $source_obj->_curlopt_list;
            $request->http_method_list = $source_obj->http_method_list;
            $request->page_urls = $source_obj->_page_urls;
            $request->target_page_element = $source_obj->_target_page_element;
            $request->target_page_value = $source_obj->_target_page_value;
            $request->data_page = $source_obj->_data_page;
            $request->cookie_page = $source_obj->_cookie_page;
            $request->curlopt_page = $source_obj->_curlopt_page;
            $request->http_method_page = $source_obj->http_method_page;
            $request->table_page_urls = $source_obj->table_page_urls;
            $request->column_table_page_urls = $source_obj->column_table_page_urls;
            $request->start_table_page_urls = $source_obj->start_table_page_urls;
            $request->length_table_page_urls = $source_obj->length_table_page_urls;
            $request->table_fixing = $source_obj->table_fixing;
            $request->amount_stream = $source_obj->amount_stream;
            $request->microtime_delay = $source_obj->microtime_delay;
            $request->func_data_processing_list = $source_obj->func_data_processing_list;
            $request->func_data_processing_page = $source_obj->func_data_processing_page;
            $request->status_control_insert = $source_obj->status_control_insert;
            $request->fields_control_insert = $source_obj->fields_control_insert;
            $request->proxy = $source_obj->proxy;
            $request->func_valid_url_list = $source_obj->func_valid_url_list;
            $request->func_valid_url_page = $source_obj->func_valid_url_page;
            $request->inspect_duplicate_url_list = $source_obj->inspect_duplicate_url_list;
            $request->inspect_duplicate_url_page = $source_obj->inspect_duplicate_url_page;
            $request->inspect_url_table = $source_obj->inspect_url_table;
            $request->insert_type = $source_obj->insert_type;
            $request->fields_in_table_for_transmission = $source_obj->
                fields_in_table_for_transmission;
            $request->default_values = $source_obj->_default_values;
            $request->dom_library = $source_obj->dom_library;
            $request->visibility = $source_obj->visibility;
        } else {
            if (!is_array($request->urls))
                $request->urls = [];
            if (!is_array($request->target_list_element))
                $request->target_list_element = [];
            $request->target_list_value = \main\build_fields_triplet($request, 'target_list_value',
                'phrase', 'attribute', 'name');
            $request->target_list_url = \main\build_fields_couple($request, 'target_list_url',
                'phrase', 'attribute');
            $request->target_list_next = \main\build_fields_couple($request, 'target_list_next',
                'phrase', 'attribute');
            $request->data_list = \main\build_fields_couple($request, 'data_list', 'key', 'value');
            $request->cookie_list = \main\build_fields_couple($request, 'cookie_list', 'key',
                'value');
            $request->curlopt_list = \main\build_fields_couple($request, 'curlopt_list', 'key',
                'value');
            if (!is_array($request->page_urls))
                $request->page_urls = [];
            if (!is_array($request->target_page_element))
                $request->target_page_element = [];
            $request->target_page_value = \main\build_fields_triplet($request, 'target_page_value',
                'phrase', 'attribute', 'name');
            $request->data_page = \main\build_fields_couple($request, 'data_page', 'key', 'value');
            $request->cookie_page = \main\build_fields_couple($request, 'cookie_page', 'key',
                'value');
            $request->curlopt_page = \main\build_fields_couple($request, 'curlopt_page', 'key',
                'value');
            $request->default_values = \main\build_fields_couple($request, 'default_values', 'key',
                'value');

            if (!$request->http_method_list == 'post') {
                $request->http_method_list = 'get';
            }
            if (!$request->http_method_page == 'post') {
                $request->http_method_page = 'get';
            }
            if ($request->inspect_duplicate_url_list != 'no') {
                $request->inspect_duplicate_url_list = 'yes';
            }
            if ($request->inspect_duplicate_url_page != 'no') {
                $request->inspect_duplicate_url_page = 'yes';
            }
        }

        if ($request->insert || $request->save) {
            if (!\main\Auth::statusAutorization()) {
                return self::statuses('CMD_ERROR_AUTORIZATION');
            }

            if (\main\Auth::getPrivileges() != 'admin') {
                return self::statuses('CMD_ERROR_PRIVILEGES');
            }

            $request->name = trim($request->name);
            $request->table_name = trim($request->table_name);
            $request->comment = trim($request->comment);
            $request->begin_page = trim($request->begin_page);
            $request->end_page = trim($request->end_page);
            $request->key_page = trim($request->key_page);
            $request->http_method_list = trim($request->http_method_list);
            $request->table_page_urls = trim($request->table_page_urls);
            $request->column_table_page_urls = trim($request->column_table_page_urls);
            $request->start_table_page_urls = trim($request->start_table_page_urls);
            $request->length_table_page_urls = trim($request->length_table_page_urls);
            $request->table_fixing = intval($request->table_fixing);
            $request->amount_stream = trim($request->amount_stream);
            $request->microtime_delay = trim($request->microtime_delay);
            $request->func_data_processing_list = trim($request->func_data_processing_list);
            $request->func_data_processing_page = trim($request->func_data_processing_page);
            $request->status_control_insert = intval($request->status_control_insert);
            $request->fields_control_insert = trim($request->fields_control_insert);
            $request->proxy = trim($request->proxy);
            $request->func_valid_url_list = trim($request->func_valid_url_list);
            $request->func_valid_url_page = trim($request->func_valid_url_page);
            $request->inspect_duplicate_url_list = trim($request->
                inspect_duplicate_url_list);
            $request->inspect_duplicate_url_page = trim($request->
                inspect_duplicate_url_page);
            $request->inspect_url_table = intval($request->inspect_url_table);
            $request->insert_type = intval($request->insert_type);
            $request->fields_in_table_for_transmission = trim($request->
                fields_in_table_for_transmission);
            $request->dom_library = intval($request->dom_library);
            $request->visibility = intval($request->visibility);

            if (empty($request->name)) {
                $request->error = "Имя источника не заполнено";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (empty($request->table_name) || preg_match("#[^a-zA-Z0-9_]#", $request->
                table_name) || preg_match("#^_#", $request->table_name)) {
                $request->error = "Имя таблицы заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if ($request->table_name == '_sources') {
                $request->error = "Запрещенное имя таблицы";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->begin_page) && preg_match("#[^\d]#", $request->begin_page)) {
                $request->error = "Начальная страница заполнена не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->end_page) && preg_match("#[^\d]#", $request->end_page)) {
                $request->error = "Конечная страница заполнена не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!is_object($source_obj)) {
                $source_obj = new \workup\model\Source();
                $source_obj->created_at = time();
            }

            if (!empty($request->table_page_urls) && preg_match("#[^a-zA-Z0-9_]#", $request->
                table_page_urls)) {
                $request->error = "Имя таблицы с ссылками на страницу заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->column_table_page_urls) && preg_match("#[^a-zA-Z0-9_]#", $request->
                column_table_page_urls)) {
                $request->error = "Поле в таблице с ссылкой заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->start_table_page_urls) && preg_match("#[^\d]#", $request->
                start_table_page_urls)) {
                $request->error = "Лимит (начало) заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->length_table_page_urls) && preg_match("#[^\d]#", $request->
                length_table_page_urls)) {
                $request->error = "Лимит (количество строк) заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($request->fields_in_table_for_transmission) && preg_match("#[^a-zA-Z0-9_ ]#",
                $request->fields_in_table_for_transmission)) {
                $request->error = "Поля для передачи заполнено не корректно";
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }

            if (!empty($source_obj->table_name) && $source_obj->table_name != $request->
                table_name) {
                if (!\workup\App::config('WORDPRESS') || stripos($source_obj->table_name,
                    'tbl_parser_') === 0) {
                    if (!\main\DatabasePerform::GetOne("SELECT `id` FROM `_sources` WHERE `table_name` = :table_name AND `id` != :id LIMIT 1", ['table_name' =>
                        $source_obj->table_name, 'id' => $source_obj->id])) {
                        \main\DatabasePerform::Execute("DROP TABLE IF EXISTS `" . $source_obj->
                            table_name . "`");
                    }
                }
            }

            $source_obj->name = $request->name;
            $source_obj->table_name = $request->table_name;
            $source_obj->comment = $request->comment;
            $source_obj->urls = \main\squeeze($request->urls, "/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?/Diu");
            $source_obj->target_list_element = \main\squeeze($request->target_list_element);
            $source_obj->target_list_value = serialize($request->target_list_value);
            $source_obj->target_list_url = serialize($request->target_list_url);
            $source_obj->target_list_next = serialize($request->target_list_next);
            $source_obj->begin_page = intval($request->begin_page);
            $source_obj->end_page = intval($request->end_page);
            $source_obj->key_page = $request->key_page;
            $source_obj->data_list = serialize($request->data_list);
            $source_obj->cookie_list = serialize($request->cookie_list);
            $source_obj->curlopt_list = \main\squeeze_curlopt_array($request->curlopt_list);
            $source_obj->http_method_list = $request->http_method_list;
            $source_obj->page_urls = \main\squeeze($request->page_urls, "/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?\/?/Diu");
            $source_obj->target_page_element = \main\squeeze($request->target_page_element);
            $source_obj->target_page_value = serialize($request->target_page_value);
            $source_obj->data_page = serialize($request->data_page);
            $source_obj->cookie_page = serialize($request->cookie_page);
            $source_obj->curlopt_page = \main\squeeze_curlopt_array($request->curlopt_page);
            $source_obj->http_method_page = $request->http_method_page;
            $source_obj->table_page_urls = $request->table_page_urls;
            $source_obj->column_table_page_urls = $request->column_table_page_urls;
            $source_obj->start_table_page_urls = $request->start_table_page_urls;
            $source_obj->length_table_page_urls = $request->length_table_page_urls;
            $source_obj->table_fixing = $request->table_fixing;
            if ($request->amount_stream > 0) {
                $source_obj->amount_stream = intval($request->amount_stream);
            } else {
                $source_obj->amount_stream = null;
            }
            if ($request->microtime_delay > 0) {
                $source_obj->microtime_delay = intval($request->microtime_delay);
            } else {
                $source_obj->microtime_delay = null;
            }
            $source_obj->func_data_processing_list = $request->func_data_processing_list;
            $source_obj->func_data_processing_page = $request->func_data_processing_page;
            $source_obj->status_control_insert = intval($request->status_control_insert);
            $source_obj->fields_control_insert = $request->fields_control_insert;
            $source_obj->proxy = $request->proxy;
            $source_obj->func_valid_url_list = $request->func_valid_url_list;
            $source_obj->func_valid_url_page = $request->func_valid_url_page;
            $source_obj->inspect_duplicate_url_list = $request->inspect_duplicate_url_list;
            $source_obj->inspect_duplicate_url_page = $request->inspect_duplicate_url_page;
            $source_obj->inspect_url_table = intval($request->inspect_url_table);
            $source_obj->insert_type = intval($request->insert_type);
            $source_obj->fields_in_table_for_transmission = $request->
                fields_in_table_for_transmission;
            $source_obj->default_values = \main\squeeze_values_array($request->default_values);
            $source_obj->dom_library = intval($request->dom_library);
            $source_obj->visibility = intval($request->visibility);

            \workup\model\ObjectWatcher::instance()->performOperations();

            if (is_object($source_obj) && $source_obj->id) {
                $this->res = \main\Link::ListCron();

                return self::statuses('CMD_LOCATION');
            } else {
                $request->setProperty("error", "Ошибка");
                return self::statuses('CMD_INSUFFICIENT_DATA');
            }
        }

        return self::statuses('CMD_DEFAULT');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ClearSourceDataCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (\workup\App::config('WORDPRESS')) {
            return self::statuses('CMD_ERROR');
        }
        
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $source_obj = null;

        if ($request->id_source_data) {
            $source_obj = \workup\model\Cron::find($request->id_source_data);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }

        if ($source_obj->createTableIfNotExist()) {
            $search = [];

            if ($request->search && is_array($request->search)) {
                foreach ($request->search as $key => $value) {
                    if (!empty($value)) {
                        $search[$key] = trim($value);
                    }
                }
            }
            
            if (\workup\App::config('WORDPRESS')) {
                $search['source_id'] = $source_obj->id;
            }
            
            \workup\record\Record::deleteRows($source_obj, $search);

            $this->res = \main\Link::SourceDataCron($source_obj->id);

            return self::statuses('CMD_LOCATION');
        }

        return self::statuses('CMD_ERROR');
    }
}

namespace workup\command;

use main\DatabasePerform;

/*
Google Скрипт (script.google.com) добавления массива строк

function doGet(e)
{
    return execute(e);
}

function doPost(e)
{
    return execute(e);
}

function execute(e)
{
    var key = 'ASD2xcSx3csA4dSDa5s3sc5SDW13';

    if (e.parameter.key) {
        if (e.parameter.key == key) {
            if (e.parameter.data) {
                var data = JSON.parse(e.parameter.data);
                if (data) {
                    var Spreadsheet = null;

                    if (e.parameter.id) {
                        Spreadsheet = SpreadsheetApp.openById(e.parameter.id);
                    } else if (e.parameter.create) {
                        Spreadsheet = SpreadsheetApp.create(e.parameter.create);
                    }

                    if (Spreadsheet) {
                        for (var i in data) {
                            Spreadsheet.appendRow(data[i]);
                        }
                      
                        return ContentService.createTextOutput(JSON.stringify({
                            "status": "success", 
                            "id": Spreadsheet.getId() 
                        })).setMimeType(ContentService.MimeType.JAVASCRIPT);
                    }
                }
            }
        }
    }
}
*/

class ajaxExportGoogleSheets extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $source_obj = null;

        if ($request->id_source_data) {
            $source_obj = \workup\model\Source::find($request->id_source_data);
        } else {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Иденитфикатора не передано'));

            return self::statuses('CMD_AJAX');
        }

        if (!$source_obj) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Объекта таблицы не определен'));

            return self::statuses('CMD_AJAX');
        }

        if ($source_obj->createTableIfNotExist()) {
            if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id)) {
                unlink(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id);
            }

            $offset = intval($request->offset);

            $limit = 100;

            $amount = intval($request->limit);

            $count = DatabasePerform::GetOne("SELECT COUNT(*) FROM `" . $source_obj->
                table_name . "`");

            set_time_limit(10000);

            $result = $source_obj->_result;

            $id_sheet = null;

            if (isset($result['id_google_sheet']) && !empty($result['id_google_sheet'])) {
                $id_sheet = $result['id_google_sheet'];
            } elseif ($request->id_sheet) {
                $id_sheet = trim($request->id_sheet);

                if (empty($id_sheet)) {
                    $id_sheet = null;
                }
            }

            for ($i = 0; $i < ceil($count / $limit) && $amount > 0; $i++, $offset = $offset +
                $limit) {
                if ($amount < $limit) {
                    $limit = $amount;
                }

                $rows = DatabasePerform::GetAll("SELECT * FROM `" . $source_obj->table_name .
                    "` LIMIT " . $offset . ", " . $limit, null, \PDO::FETCH_NUM);

                $amount = $amount - $limit;

                if (is_array($rows)) {
                    if ($id_sheet) {
                        $params = ['key' => \workup\App::config('GOOGLE_SCRIPT_SECRET_KEY'), 'id' => $id_sheet, 'data' =>
                            json_encode($rows), ];
                    } else {
                        $params = ['key' => \workup\App::config('GOOGLE_SCRIPT_SECRET_KEY'), 'create' => $source_obj->
                            table_name, 'data' => json_encode($rows), ];
                    }

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                    curl_setopt($ch, CURLOPT_USERAGENT,
                        "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_URL, \workup\App::config('GOOGLE_SCRIPT_EXPORT_SHEETS'));
                    $data = curl_exec($ch);
                    curl_close($ch);

                    $json = json_decode($data);

                    if (is_object($json) && isset($json->status) && $json->status == 'success') {
                        if (isset($json->id) && !empty($json->id)) {
                            $id_sheet = $json->id;
                            $result['id_google_sheet'] = $id_sheet;
                        }
                    } else {
                        $id_sheet = null;
                        $result['id_google_sheet'] = null;
                    }
                }

                if (file_exists(\workup\App::config('DIR_TMP') . "/blocking_" . $source_obj->id)) {
                    exit();
                }
            }

            $source_obj->result = serialize($result);

            $source_obj->save();

            $this->res = json_encode(array('status' => 'success', 'message' => 'Успех'));
            return self::statuses('CMD_AJAX');
        }

        $this->res = json_encode(array('status' => 'fail', 'message' => 'NOT FOUND'));
        return self::statuses('CMD_AJAX');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ProcessSource extends Command
{
    private $limit = 5;
    private $offset_time = 0;

    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $sources = \workup\record\SourceRecord::GetRowsProcessCron($this->limit, time() -
            $this->offset_time);

        $request->setObject('parse_sources', $sources);

        return self::statuses('CMD_PARSE');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ClearSourceData extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            return self::statuses('CMD_ERROR_AUTORIZATION');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        $source_obj = null;

        if ($request->id_source_data) {
            $source_obj = \workup\model\Source::find($request->id_source_data);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }

        if ($source_obj->createTableIfNotExist()) {
            $search = [];

            if ($request->search && is_array($request->search)) {
                foreach ($request->search as $key => $value) {
                    if (!empty($value)) {
                        $search[$key] = trim($value);
                    }
                }
            }
            
            if (\workup\App::config('WORDPRESS') && $source_obj->table_name == 'wp_posts') {
                $search['source_id'] = intval($source_obj->id);
            }
            
            \workup\record\Record::deleteRows($source_obj, $search);
            
            $this->res = \main\Link::SourceData($source_obj->id);

            return self::statuses('CMD_LOCATION');
        }

        return self::statuses('CMD_ERROR');
    }
}

namespace workup\command;

use main\DatabasePerform;

class SourceDataCron extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        $source_obj = null;

        if ($request->id_source_data) {
            $source_obj = \workup\model\Cron::find($request->id_source_data);

            if (!$source_obj) {
                return self::statuses('CMD_MISSING_ROW');
            }
        }
        
        if (\main\Auth::getPrivileges() != 'admin' && $source_obj->visibility > '0'){
            return self::statuses('CMD_ERROR_PRIVILEGES');
        }

        if ($source_obj->createTableIfNotExist()) {
            \workup\model\SourceData::setTable($source_obj->table_name);

            $request->setObject('source', $source_obj);

            $page = (isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 1);

            \workup\record\SourceDataRecord::setLimit(50);

            $limit = \workup\record\SourceDataRecord::getLimit();

            if ($page) {
                $start = ($page - 1) * $limit;
            } else {
                $start = 0;
            }

            $params_search = [];

            if ($request->search && is_array($request->search)) {
                $search = [];

                foreach ($request->search as $key => $value) {
                    if (!empty($value)) {
                        $search[$key] = trim($value);
                    }
                }
                $params_search = $search;
            }

            $params_sort = [];

            if ($request->sort) {
                $params_sort['sort'] = $request->sort;
            }

            if ($request->order) {
                $params_sort['order'] = $request->order;
            }

            if (empty($params_sort)) {
                $request->sort = $params_sort['sort'] = 'id';
                $request->order = $params_sort['order'] = 'DESC';
            }

            \workup\record\SourceDataRecord::setCount($params_search);

            $source_data = \workup\record\SourceDataRecord::GetRows($start, $limit, $params_search,
                $params_sort);

            $request->setObject('source_data', $source_data);

            foreach ($source_data as $key => $value) {
                $request->columns = $value->getColumns();

                break;
            }

            $request->columns = \workup\model\SourceData::getColumnForDb();

            if (!is_array($request->columns)) {
                $request->columns = [];
            }

            if (!empty($params_search)) {
                $params_search = ['search' => $params_search];
            }

            $request->params_search = $params_search;

            $request->params_sort = $params_sort;

            $request->params = array_merge($params_search, $params_sort);

            return self::statuses('CMD_DEFAULT');
        }

        return self::statuses('CMD_ERROR');
    }
}

namespace workup\command;

use main\DatabasePerform;

class ajaxProcess extends Command
{
    function doExecute(\workup\controller\Request $request)
    {
        if (!\main\Auth::statusAutorization()) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        if (\main\Auth::getPrivileges() != 'admin') {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'Запрещенное действие!'));

            return self::statuses('CMD_AJAX');
        }

        $id = $request->id;

        if ($id) {
            $id = intval($id);
        }

        if (!$id) {
            $this->res = json_encode(array('status' => 'fail', 'message' =>
                    'ID записи не передано'));

            return self::statuses('CMD_AJAX');
        }

        $sources = [];

        $sources[] = \workup\model\Source::find($id);

        if (!empty($sources)) {
            $request->setObject('parse_sources', $sources);
            return self::statuses('CMD_PARSE');
        }

        return self::statuses('CMD_AJAX');
    }
}

namespace workup\record;

use main\DatabasePerform;

class SourceDataRecordWordpress extends SourceDataRecord
{
    private static $columns = array(
        'post_author' => 'text',
        'post_date' => 'text',
        'post_date_gmt' => 'text',
        'post_content' => 'text',
        'post_title' => 'text',
        'post_excerpt' => 'text',
        'post_status' => 'text',
        'comment_status' => 'text',
        'ping_status' => 'text',
        'post_password' => 'text',
        'post_name' => 'text',
        'to_ping' => 'text',
        'pinged' => 'text',
        'post_modified' => 'text',
        'post_modified_gmt' => 'text',
        'post_content_filtered' => 'text',
        'post_parent' => 'text',
        'guid' => 'text',
        'menu_order' => 'text',
        'post_type' => 'text',
        'post_mime_type' => 'text',
        'comment_count' => 'text',
        );

    public static function insertRows($rows)
    {
        $count_write = 0;

        $rows_dublicate = [];

        if (!empty($rows)) {
            foreach ($rows as $key_row => $row) {var_dump($row);
                $row = self::filterRow($row);

                if (!self::validRow($row)) {
                    continue;
                }

                $row_in_db = null;
                $files_in_bd = [];

                if (self::$status_control) {
                    $where_query = [];
                    $params = [];

                    foreach ($row as $key => $value) {
                        if (empty(self::$fields_control) || in_array($key, self::$fields_control)) {
                            $where_query[$key] = '`' . $key . '`=:' . $key;
                            $params[$key] = $value;
                        }
                    }

                    $query = "SELECT * FROM `wp_posts` WHERE " . implode(' AND ', $where_query) .
                        ' LIMIT 1';

                    $row_in_db = DatabasePerform::GetRow($query, $params);

                    if ($row_in_db) {
                        if (isset($row_in_db['ID'])) {
                            $row_in_db['id'] = $row_in_db['ID'];
                        }
                        if (isset($row_in_db['Id'])) {
                            $row_in_db['id'] = $row_in_db['Id'];
                        }

                        $files_in_bd = DatabasePerform::GetRow("SELECT * FROM `wp_posts` WHERE `id`=:id AND `post_type`=:attachment LIMLIT 1000", ['id' =>
                            $row_in_db['id']]);

                        if (!is_array($files_in_bd)) {
                            $files_in_bd = [];
                        }
                    }
                }

                if (self::$status_control == '3' && $row_in_db && isset($row_in_db['source_id'])) {
                    DatabasePerform::Execute("DELETE FROM `wp_posts` WHERE `id`=:id AND `source_id`=:source_id", ['id' =>
                        $row_in_db['id'], 'source_id' => $row['source_id']]);
                }

                if (self::$status_control == '1' || self::$status_control == '3' || (self::$status_control ==
                    '4' && !$row_in_db)) {
                    $row_insert = self::prepareRowInsert($row);
                    $row_insert['post_date'] = date('Y-m-d H:i:s');
                    $row_insert['post_modified'] = date('Y-m-d H:i:s');
                    $row_insert['post_date_gmt'] = date('Y-m-d H:i:s');
                    $row_insert['post_modified_gmt'] = date('Y-m-d H:i:s');

                    $set_query = [];
                    $value_query = [];
                    $params = [];

                    foreach ($row_insert as $key => $value) {
                        $set_query[$key] = '`' . $key . '`';
                        $value_query[$key] = ':' . $key;
                        $params[$key] = $value;
                    }

                    DatabasePerform::Execute("INSERT INTO `wp_posts` (" . implode(',', $set_query) .
                        ") VALUES (" . implode(',', $value_query) . ')', $params);

                    $last_insert_id = DatabasePerform::LastInsertId();

                    if ($last_insert_id) {
                        $count_write++;

                        $row_update = ['guid' => \workup\App::config('WEB_URL') . '/?p=' . $last_insert_id, ];

                        $set_query = [];
                        $where_query = [];
                        $params = [];

                        $where_query['id'] = '`id`=:id';
                        $where_query['source_id'] = '`source_id`=:source_id';
                        $params['id'] = $last_insert_id;
                        $params['source_id'] = $row['source_id'];

                        foreach ($row_update as $key => $value) {
                            $set_query[$key] = '`' . $key . '`=:' . $key;

                            $params[$key] = $value;
                        }

                        if (!empty($set_query) && !empty($where_query)) {
                            DatabasePerform::Execute("UPDATE `wp_posts` SET " . implode(',', $set_query) .
                                " WHERE " . implode(' AND ', $where_query) . " LIMIT 1", $params);
                        }
                    }
                }

                if (self::$status_control == '4' && $row_in_db && isset($row['source_id'])) {
                    $row_update = self::prepareRowUpdate($row, $row_in_db);

                    $row_update['post_modified'] = date('Y-m-d H:i:s');
                    $row_update['post_modified_gmt'] = date('Y-m-d H:i:s');

                    $set_query = [];
                    $where_query = [];
                    $params = [];

                    $where_query['id'] = '`id`=:id';
                    $where_query['source_id'] = '`source_id`=:source_id';
                    $params['id'] = $row_in_db['id'];
                    $params['source_id'] = $row['source_id'];

                    foreach ($row_update as $key => $value) {
                        $set_query[$key] = '`' . $key . '`=:' . $key;

                        $params[$key] = $value;
                    }

                    if (!empty($set_query) && !empty($where_query)) {
                        DatabasePerform::Execute("UPDATE `wp_posts` SET " . implode(',', $set_query) .
                            " WHERE " . implode(' AND ', $where_query) . " LIMIT 1", $params);
                    }
                }

                $id_parent = null;

                if ($last_insert_id) {
                    $id_parent = $last_insert_id;
                }

                if ($row_in_db) {
                    $id_parent = $row_in_db['id'];
                }

                if (isset($row['images']) && $id_parent) {
                    $images = explode(',', $row['images']);

                    foreach ($images as $image) {
                        $image = \workup\App::config('SITE_ROOT') . '/files/wp_posts/images/' . $image;

                        if (file_exists($image)) {
                            $pathinfo = pathinfo($image);

                            $is_exists = false;

                            foreach ($files_in_bd as $file) {
                                if ($image == $file['post_title']) {
                                    $is_exists = true;

                                    break;
                                }
                            }

                            if (!$is_exists) {
                                $upload_dir = \workup\App::config('SITE_ROOT') . DIRECTORY_SEPARATOR . '..' .
                                    DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' .
                                    DIRECTORY_SEPARATOR . date("Y") . DIRECTORY_SEPARATOR . date("m");

                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['basename']);
                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['filename'] .
                                    '-150x150.' . $pathinfo['extension'], 150, 150);
                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['filename'] .
                                    '-180x180.' . $pathinfo['extension'], 180, 180);
                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['filename'] .
                                    '-300x188.' . $pathinfo['extension'], 300, 188);
                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['filename'] .
                                    '-300x300.' . $pathinfo['extension'], 300, 300);
                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['filename'] .
                                    '-600x600.' . $pathinfo['extension'], 600, 600);
                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['filename'] .
                                    '-768x480.' . $pathinfo['extension'], 768, 480);
                                self::saveImage($image, $upload_dir . DIRECTORY_SEPARATOR . $pathinfo['filename'] .
                                    '-1024x640.' . $pathinfo['extension'], 1024, 640);

                                $row_insert = [];
                                $row_insert['post_title'] = $pathinfo['filename'];
                                $row_insert['post_parent'] = $id_parent;
                                $row_insert['post_mime_type'] = image_type_to_mime_type($image);

                                $row_insert = self::prepareImageInsert($row_insert);
var_dump($row_insert);
                                $row_insert['post_date'] = date('Y-m-d H:i:s');
                                $row_insert['post_modified'] = date('Y-m-d H:i:s');
                                $row_insert['post_date_gmt'] = date('Y-m-d H:i:s');
                                $row_insert['post_modified_gmt'] = date('Y-m-d H:i:s');

                                $set_query = [];
                                $value_query = [];
                                $params = [];

                                foreach ($row_insert as $key => $value) {
                                    $set_query[$key] = '`' . $key . '`';
                                    $value_query[$key] = ':' . $key;
                                    $params[$key] = $value;
                                }

                                DatabasePerform::Execute("INSERT INTO `wp_posts` (" . implode(',', $set_query) .
                                    ") VALUES (" . implode(',', $value_query) . ')', $params);

                                $last_insert_id = DatabasePerform::LastInsertId();

                                if ($last_insert_id) {
                                    $count_write++;

                                    $row_update = ['guid' => \workup\App::config('WEB_URL') . '/wp-content/uploads/' .
                                        $last_insert_id, ];

                                    $set_query = [];
                                    $where_query = [];
                                    $params = [];

                                    $where_query['id'] = '`id`=:id';
                                    $where_query['source_id'] = '`source_id`=:source_id';
                                    $params['id'] = $last_insert_id;
                                    $params['source_id'] = $row['source_id'];

                                    foreach ($row_update as $key => $value) {
                                        $set_query[$key] = '`' . $key . '`=:' . $key;

                                        $params[$key] = $value;
                                    }

                                    if (!empty($set_query) && !empty($where_query)) {
                                        DatabasePerform::Execute("UPDATE `wp_posts` SET " . implode(',', $set_query) .
                                            " WHERE " . implode(' AND ', $where_query) . " LIMIT 1", $params);
                                    }
                                }
                            }
                        }
                    }
                }
                exit();
            }
        }

        return $count_write;
    }

    private static function filterRow($row)
    {
        $keys = array(
            'post_content',
            'post_title',
            'parse_url',
            'source_id',
            );

        foreach ($row as $key => $value) {
            if (!in_array($key, $keys)) {
                unset($row[$key]);
            }
        }

        return $row;
    }

    private static function validRow($row)
    {
        if (!isset($row['post_content']) || empty($row['post_content'])) {
            return false;
        }
        if (!isset($row['post_title']) || empty($row['post_title'])) {
            return false;
        }

        return true;
    }

    private static function prepareRowInsert($row)
    {
        if (isset($row['images'])) {
            unset($row['images']);
        }

        //if (!isset($row['post_author'])) {
        $row['post_author'] = 1;
        //}
        //if (!isset($row['post_date'])) {
        //$row['post_date'] = null;
        //}
        //if (!isset($row['post_date_gmt'])) {
        //$row['post_date_gmt'] = null;
        //}
        if (!isset($row['post_content'])) {
            $row['post_content'] = '';
        }
        if (!isset($row['post_title'])) {
            $row['post_title'] = '';
        }
        //if(!isset($row['post_excerpt'])){
        $row['post_excerpt'] = '';
        //}
        //if(!isset($row['post_status'])){
        $row['post_status'] = 'publish';
        //}
        //if(!isset($row['comment_status'])){
        $row['comment_status'] = 'open';
        //}
        //if(!isset($row['ping_status'])){
        $row['ping_status'] = 'open';
        //}
        //if (!isset($row['post_password'])) {
        $row['post_password'] = '';
        //}
        //if(!isset($row['post_name'])){
        $translit = \main\translit($row['post_title'], '-');
        $post_name = $translit;
        if (\main\DatabasePerform::GetOne("SELECT `id` FROM `wp_posts` WHERE `post_name` = :post_name LIMIT 1", ['post_name' =>
            $post_name])) {
            for ($i = 1; $i <= 40; $i++) {
                $post_name = $translit . '-' . $i;
                if (!\main\DatabasePerform::GetOne("SELECT `id` FROM `wp_posts` WHERE `post_name` = :post_name LIMIT 1", ['post_name' =>
                    $post_name])) {
                    break;
                }
            }
        }
        $row['post_name'] = $post_name;
        //}
        //if(!isset($row['to_ping'])){
        $row['to_ping'] = '';
        //}
        //if(!isset($row['pinged'])){
        $row['pinged'] = '';
        //}
        //if(!isset($row['post_modified'])){
        //$row['post_modified'] = null;
        //}
        //if (!isset($row['post_modified_gmt'])) {
        //$row['post_modified_gmt'] = null;
        //}
        //if(!isset($row['post_content_filtered'])){
        $row['post_content_filtered'] = '';
        //}
        //if(!isset($row['post_parent'])){
        $row['post_parent'] = 0;
        //}
        //if(!isset($row['guid'])){
        $row['guid'] = '';
        //}
        //if(!isset($row['menu_order'])){
        $row['menu_order'] = 0;
        //}
        //if(!isset($row['post_type'])){
        $row['post_type'] = 'post';
        //}
        //if(!isset($row['post_mime_type'])){
        $row['post_mime_type'] = '';
        //}
        //if(!isset($row['comment_count'])){
        $row['comment_count'] = 0;
        //}

        return $row;
    }

    private static function prepareRowUpdate($row, $row_in_db)
    {
        if (isset($row['parse_url'])) {
            unset($row['parse_url']);
        }
        if (isset($row['source_id'])) {
            unset($row['source_id']);
        }
        if (isset($row['images'])) {
            unset($row['images']);
        }

        if ($row['post_title'] != $row_in_db['post_title']) {
            $translit = \main\translit($row['post_title'], '-');
            $post_name = $translit;
            if (\main\DatabasePerform::GetOne("SELECT `id` FROM `wp_posts` WHERE `post_name` = :post_name AND `id` != :id LIMIT 1", ['post_name' =>
                $post_name, 'id' => $row_in_db['id']])) {
                for ($i = 1; $i <= 40; $i++) {
                    $post_name = $translit . '-' . $i;
                    if (!\main\DatabasePerform::GetOne("SELECT `id` FROM `wp_posts` WHERE `post_name` = :post_name AND `id` != :id LIMIT 1", ['post_name' =>
                        $post_name, 'id' => $row_in_db['id']])) {
                        break;
                    }
                }
            }
            $row['post_name'] = $post_name;
        }

        return $row;
    }

    private static function prepareImageInsert($row)
    {
        if (isset($row['images'])) {
            unset($row['images']);
        }

        //if (!isset($row['post_author'])) {
        $row['post_author'] = 1;
        //}
        //if (!isset($row['post_date'])) {
        //$row['post_date'] = null;
        //}
        //if (!isset($row['post_date_gmt'])) {
        //$row['post_date_gmt'] = null;
        //}
        if (!isset($row['post_content'])) {
            $row['post_content'] = '';
        }
        if (!isset($row['post_title'])) {
            $row['post_title'] = '';
        }
        //if(!isset($row['post_excerpt'])){
        $row['post_excerpt'] = '';
        //}
        //if(!isset($row['post_status'])){
        $row['post_status'] = 'inherit';
        //}
        //if(!isset($row['comment_status'])){
        $row['comment_status'] = 'open';
        //}
        //if(!isset($row['ping_status'])){
        $row['ping_status'] = '	closed';
        //}
        //if (!isset($row['post_password'])) {
        $row['post_password'] = '';
        //}
        //if(!isset($row['post_name'])){
        $translit = \main\translit($row['post_title'], '-');
        $post_name = $translit;
        if (\main\DatabasePerform::GetOne("SELECT `id` FROM `wp_posts` WHERE `post_name` = :post_name LIMIT 1", ['post_name' =>
            $post_name])) {
            for ($i = 1; $i <= 40; $i++) {
                $post_name = $translit . '-' . $i;
                if (!\main\DatabasePerform::GetOne("SELECT `id` FROM `wp_posts` WHERE `post_name` = :post_name LIMIT 1", ['post_name' =>
                    $post_name])) {
                    break;
                }
            }
        }
        $row['post_name'] = $post_name;
        //}
        //if(!isset($row['to_ping'])){
        $row['to_ping'] = '';
        //}
        //if(!isset($row['pinged'])){
        $row['pinged'] = '';
        //}
        //if(!isset($row['post_modified'])){
        //$row['post_modified'] = null;
        //}
        //if (!isset($row['post_modified_gmt'])) {
        //$row['post_modified_gmt'] = null;
        //}
        //if(!isset($row['post_content_filtered'])){
        $row['post_content_filtered'] = '';
        //}
        if (!isset($row['post_parent'])) {
            $row['post_parent'] = 0;
        }
        //if(!isset($row['guid'])){
        $row['guid'] = '';
        //}
        //if(!isset($row['menu_order'])){
        $row['menu_order'] = 0;
        //}
        //if(!isset($row['post_type'])){
        $row['post_type'] = 'post';
        //}
        if (!isset($row['post_mime_type'])) {
            $row['post_mime_type'] = '';
        }
        //if(!isset($row['comment_count'])){
        $row['comment_count'] = 0;
        //}

        return $row;
    }

    private static function saveImage($image_dst, $path_src, $width = null, $height = null)
    {
    }
}

namespace workup\base;

class ViewHelper {
    static function getRequest() {
        return \workup\base\ApplicationRegistry::getRequest();
    }
    
    static function access($cmd, $status){
        $comand = \workup\base\ApplicationRegistry::getRequest()->getLastCommand();
        
        $class = "\workup\command\\".$cmd;
        
        if(class_exists($class)){
            $class = new \ReflectionClass($class);

            if($class->isInstance($comand)){
                if(in_array($comand->getStatus(), $status)){
                    return true;
                }            
            }
        }         
        
        return false;
    }
}

namespace main;

trait IncludeFile {
    private function includeCss($view, $default = '')
    {
        $methodView = 'css' . str_replace(['/', '.', '-'], '_', trim(str_replace('.css', '', $view), './ '));
        
        if (method_exists($this, $methodView)) {
            $this->$methodView();
        } else {
            echo $default;
        }
    }
    private function includeJavascript($view, $default = '')
    {
        $methodView = 'javascript' . str_replace(['/', '\\', '.', '-'], '_', trim(str_replace('.js', '', $view), './ '));

        if (method_exists($this, $methodView)) {
            $this->$methodView();
        } else {
            echo $default;
        }
    }
    
    private function includeView($view)
    {
        $methodView = 'view' . $view;

        if (method_exists($this, $methodView)) {
            $this->$methodView();
        } else {
            if (!empty($view) && file_exists(\workup\App::config('SITE_ROOT') .
                "/workup/view/" . $view . ".php")) {
                require (\workup\App::config('SITE_ROOT') . "/workup/view/" . $view . ".php");
            } else {
                echo '<div style="color: red; font-family: cursive; font-size: 12px;">Not View: ' . $view . '</div>';
            }
        }
    }
    private function viewsucces_autorization () {
header("Location: " . Link::ListSources());
    }

    private function viewuseredit () {
$this->title = "Редактирование пользователя";

$user = $this->request->getObject('user');
$departments = $this->request->getObject('departments');

$id = $this->request->getProperty("id");
$name = $this->request->getProperty("name");
$login = $this->request->getProperty("login");
$password = $this->request->getProperty("password");
$pass_again = $this->request->getProperty("pass_again");
$privileges = $this->request->getProperty("privileges");
$email = $this->request->getProperty("email");

$id_department = $this->request->getProperty("id_department");
$department_user = $this->request->getProperty("department_user");

$error_text_add = $this->request->getProperty("error_text_add");    
$error_text_add_department = $this->request->getProperty("error_text_add_department"); 
  
?>
<?= $this->javascript('assets/js/users_script.js') ?>
<div class="col-sm-9" align='center'>
 <h3>Данные пользователя</h3>
 <form class="form-horizontal" action="<?php echo Link::UserEdit($user->id); ?>" method="post">
  <input type="hidden" class="form-control" name="edit" id="edit" value="ok"/>
  <input type="hidden" class="form-control" name="cmd" id="cmd" value="UserEdit"/>
  <div class="form-group">
   <label for="name" class="col-sm-3 control-label">Имя</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" id="name" placeholder="Имя" />
   </div>
  </div>
  <div class="form-group">
   <label for="login" class="col-sm-3 control-label">Логин</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="login" value="<?php echo $login; ?>" id="login" placeholder="Логин" />
   </div>
  </div>
  <div class="form-group">
   <label for="password" class="col-sm-3 control-label">Пароль</label>
   <div class="col-sm-9">
    <div class="help">Оставьте поле пароль пустым если не хотите его изменять</div>
    <input type="password" class="form-control" name="password" value="<?php echo $password; ?>" id="password" placeholder="Пароль" />
   </div>
  </div>
  <div class="form-group">
   <label for="pass_again" class="col-sm-3 control-label">Повтор пароля</label>
   <div class="col-sm-9">
    <input type="password" class="form-control" name="pass_again" value="<?php echo $pass_again; ?>" id="pass_again" placeholder="Повтор пароля" />
   </div>
  </div>
  <div class="form-group">
   <label for="privileges" class="col-sm-3 control-label">Привилегии</label>
   <div class="col-sm-9">
    <select class="form-control" id="privileges" name="privileges">
     <option <?php echo ($privileges=='operator'?'selected=""':""); ?> value="operator">Оператор</option>
     <option <?php echo ($privileges=='admin'?'selected=""':""); ?> value="admin">Админ</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="email" class="col-sm-3 control-label">Адрес&nbsp;Email</label>
   <div class="col-sm-9">
    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="example@mymail.ru" />
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-12"><span class="error_auth" id="error_text_add"><?php echo $error_text_add; ?></span></div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success">Сохранить</button>
   </div>
  </div>
 </form>
</div><?php
    }

    private function viewSourceUpdate () {
$this->title = "Источник";

?>
<?= $this->javascript('assets/js/source_update.js') ?>
<div id="attr_autocomplete">
 <div class="panel panel-default">
  <div class="panel-body attr_autocomplete_click" title="регулярное выражение (подмаска 1)">regulare</div>
  <div class="panel-body attr_autocomplete_click" title="регулярное выражение (удаление тегов)">regulare_strip_tags</div>
  <div class="panel-body attr_autocomplete_click" title="наименование элемента (магический)">tag</div>
  <div class="panel-body attr_autocomplete_click" title="html элемента (магический)">outertext</div>
  <div class="panel-body attr_autocomplete_click" title="html внутри элемента (магический)">innertext</div>
  <div class="panel-body attr_autocomplete_click" title="текст внутри элемента (магический)">plaintext</div> 
  <div class="panel-body attr_autocomplete_click">id</div>    
  <div class="panel-body attr_autocomplete_click">class</div>       
  <div class="panel-body attr_autocomplete_click">href</div>     
  <div class="panel-body attr_autocomplete_click">src</div>       
 </div>
</div>
<div id="name_autocomplete">
 <div class="panel panel-default">
  <div class="panel-body name_autocomplete_click" title="Адрес">address</div>
  <div class="panel-body name_autocomplete_click" title="Бренды">brands</div>
  <div class="panel-body name_autocomplete_click" title="Город">city</div>
  <div class="panel-body name_autocomplete_click" title="Контакты">contacts</div>
  <div class="panel-body name_autocomplete_click" title="Страна">country</div>
  <div class="panel-body name_autocomplete_click" title="Дата начала">date_begin</div>
  <div class="panel-body name_autocomplete_click" title="Дата окончания">date_end</div>
  <div class="panel-body name_autocomplete_click" title="Даты">dates</div>
  <div class="panel-body name_autocomplete_click" title="Описание">description</div>   
  <div class="panel-body name_autocomplete_click" title="Электронная почта">email</div>
  <div class="panel-body name_autocomplete_click" title="Факс">fax</div>
  <div class="panel-body name_autocomplete_click" title="Изображение">logo</div>  
  <div class="panel-body name_autocomplete_click" title="Цена ОТ">low_price</div>  
  <div class="panel-body name_autocomplete_click" title="Цена ДО">high_price</div>  
  <div class="panel-body name_autocomplete_click" title="Изображения">images</div>  
  <div class="panel-body name_autocomplete_click" title="Наименование">name</div>
  <div class="panel-body name_autocomplete_click" title="Время работы">opening_hour</div>
  <div class="panel-body name_autocomplete_click" title="Организатор">sponsor</div>
  <div class="panel-body name_autocomplete_click" title="Павильон">pavilion</div>
  <div class="panel-body name_autocomplete_click" title="Телефон">phone</div>
  <div class="panel-body name_autocomplete_click" title="Валюта">price_currency</div>
  <div class="panel-body name_autocomplete_click" title="Теги">tags</div>
  <div class="panel-body name_autocomplete_click" title="Наименование места">place_name</div>
  <div class="panel-body name_autocomplete_click" title="Почтовый индекс">postal_code</div>
  <div class="panel-body name_autocomplete_click" title="Цена">price</div>
  <div class="panel-body name_autocomplete_click" title="Стенд">stand</div>
  <div class="panel-body name_autocomplete_click" title="Короткое описание">title</div>
  <div class="panel-body name_autocomplete_click" title="Сссылка на сайт">website</div>
 </div>
</div>

<div class="col-sm-10">
 <form class="form-horizontal" action="<?php echo $this->request->id ? Link::SourceUpdate($this->request->id) : Link::SourceUpdate(); ?>" method="post">
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success"><?php if($this->request->id){ ?>Сохранить<? } else { ?>Добавить<?php }; ?></button>
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9"><span class="error_auth" id="error"><?=$this->request->error?></span></div>
  </div>
  <input type="hidden" name="cmd" id="cmd" value="SourceUpdate"/>
  <?php if($this->request->id){ ?>
  <input type="hidden" name="save" id="save" value="1"/> 
  <div class="form-group">
   <label for="id" class="col-sm-3 control-label">ID</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" value="<?=$this->request->id?>" id="id" placeholder="ID" disabled="" />
   </div>
  </div>
  <?php } else { ?>
  <input type="hidden" name="insert" id="insert" value="1"/> 
  <?php } ?>
  <div class="form-group">
   <label for="visibility" class="col-sm-3 control-label">Видимость</label>
   <div class="col-sm-9">
    <select class="form-control" name="visibility">
     <option <?=$this->request->visibility=='1'?'selected':''?> value="1">Админ</option>
     <option <?=$this->request->visibility=='0'?'selected':''?> value="0">Всем</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="name" class="col-sm-3 control-label">Имя</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="name" value="<?=$this->request->name?>" id="name" placeholder="Имя" />
   </div>
  </div>
  <div class="form-group">
   <label for="table_name" class="col-sm-3 control-label">Таблица</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="table_name" value="<?=$this->request->table_name?>" id="table_name" placeholder="Имя таблицы" />
   </div>
  </div>
  <div class="form-group">
   <label for="comment" class="col-sm-3 control-label">Комментарий</label>
   <div class="col-sm-9">
    <textarea class="form-control" name="comment" id="comment" placeholder=""><?=$this->request->comment?></textarea>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Ссылки</label>
   <div class="col-sm-9" id="dynamic_urls">
    <?php foreach($this->request->urls as $url){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="urls[]"><?=$url?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_urls', 'urls')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Элементы в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_element">
    <?php foreach($this->request->target_list_element as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="target_list_element[]"><?=$target?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-4 col-sm-8" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_target_list_element', 'target_list_element')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Целевые объекты в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_value">
    <?php foreach($this->request->target_list_value as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_triplet_1"><textarea class="form-control" name="target_list_value_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_triplet_2"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_value_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="field_dynamic_triplet_3"><textarea onfocus="this.select();lcs_name(this)" onclick="event.cancelBubble=true;this.select();lcs_name(this)" class="form-control" name="target_list_value_name[]"><?=$target['name']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_triplet('dynamic_target_list_value', 'target_list_value')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Объекты с ссылкой в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_url">
    <?php foreach($this->request->target_list_url as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_one"><textarea class="form-control" name="target_list_url_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_two"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_url_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_couple('dynamic_target_list_url', 'target_list_url')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Объекты с ссылкой навигации</label>
   <div class="col-sm-9" id="dynamic_target_list_next">
    <?php foreach($this->request->target_list_next as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_one"><textarea class="form-control" name="target_list_next_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_two"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_next_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_couple('dynamic_target_list_next', 'target_list_next')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="begin_page" class="col-sm-3 control-label">Начальная страница списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="begin_page" value="<?=$this->request->begin_page?>" id="begin_page" placeholder="Номер" />
   </div>
  </div>
  <div class="form-group">
   <label for="end_page" class="col-sm-3 control-label">Конечная страница списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="end_page" value="<?=$this->request->end_page?>" id="end_page" placeholder="Номер" />
   </div>
  </div>
  <div class="form-group">
   <label for="key_page" class="col-sm-3 control-label">Параметр страницы списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="key_page" value="<?=$this->request->key_page?>" id="key_page" placeholder="Имя параметра" />
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Данные POST запроса списка</label>
   <div class="col-sm-9" id="dynamic_data_list">
    <?php foreach($this->request->data_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="data_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="data_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_data_list', 'data_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Cookie при запросе списка</label>
   <div class="col-sm-9" id="dynamic_cookie_list">
    <?php foreach($this->request->cookie_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="cookie_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="cookie_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_cookie_list', 'cookie_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Curlopt при запросе списка</label>
   <div class="col-sm-9" id="dynamic_curlopt_list">
    <?php foreach($this->request->curlopt_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="curlopt_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="curlopt_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_curlopt_list', 'curlopt_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="http_method_list" class="col-sm-3 control-label">HTTP метод при запросе списка</label>
   <div class="col-sm-9">
    <select class="form-control" name="http_method_list" id="http_method_list">
     <option <?=$this->request->http_method_list=='get'?'selected':''?> value="get">GET</option>
     <option <?=$this->request->http_method_list=='post'?'selected':''?> value="post">POST</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="inspect_duplicate_url_list" class="col-sm-3 control-label">Проверять дубликат ссылки списка</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_duplicate_url_list" id="inspect_duplicate_url_list">
     <option <?=$this->request->inspect_duplicate_url_list=='yes'?'selected':''?> value="yes">Да</option>
     <option <?=$this->request->inspect_duplicate_url_list=='no'?'selected':''?> value="no">Нет</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="func_data_processing_list" class="col-sm-3 control-label">Функция обработки объекта списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_data_processing_list" value="<?=$this->request->func_data_processing_list?>" id="func_data_processing_list" placeholder="Принимает массив для записи в БД. Возвращает массив для слияния. NULL удаляет из массива." />
   </div>
  </div>
  <div class="form-group">
   <label for="func_valid_url_list" class="col-sm-3 control-label">Функция подготовки ссылки списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_valid_url_list" value="<?=$this->request->func_valid_url_list?>" id="func_valid_url_list" placeholder="Принимает ссылку. Возвращает: boolean true/false (принять/отклонить), string замена ссылки, array замена всех значений ссылки." />
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Ссылки на страницу</label>
   <div class="col-sm-9" id="dynamic_page_urls">
    <?php foreach($this->request->page_urls as $url){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="page_urls[]"><?=$url?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_page_urls', 'page_urls')">добавиить</button>
   </div>
  </div>  
  <div class="form-group">
   <label class="col-sm-3 control-label">Элементы на странице</label>
   <div class="col-sm-9" id="dynamic_target_page_element">
    <?php foreach($this->request->target_page_element as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="target_page_element[]"><?=$target?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_target_page_element', 'target_page_element')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Целевые объекты на странице</label>
   <div class="col-sm-9" id="dynamic_target_page_value">
    <?php foreach($this->request->target_page_value as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_triplet_1"><textarea class="form-control" name="target_page_value_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_triplet_2"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_page_value_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="field_dynamic_triplet_3"><textarea onfocus="this.select();lcs_name(this)" onclick="event.cancelBubble=true;this.select();lcs_name(this)" class="form-control" name="target_page_value_name[]"><?=$target['name']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_triplet('dynamic_target_page_value', 'target_page_value')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Данные POST запроса страницы</label>
   <div class="col-sm-9" id="dynamic_data_page">
    <?php foreach($this->request->data_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="data_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="data_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_data_page', 'data_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Cookie при запросе страницы</label>
   <div class="col-sm-9" id="dynamic_cookie_page">
    <?php foreach($this->request->cookie_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="cookie_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="cookie_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_cookie_page', 'cookie_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Curlopt при запросе страницы</label>
   <div class="col-sm-9" id="dynamic_curlopt_page">
    <?php foreach($this->request->curlopt_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="curlopt_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="curlopt_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_curlopt_page', 'curlopt_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="http_method_page" class="col-sm-3 control-label">HTTP метод при запросе страницы</label>
   <div class="col-sm-9">
    <select class="form-control" name="http_method_page" id="http_method_page">
     <option <?=$this->request->http_method_page=='get'?'selected':''?> value="get">GET</option>
     <option <?=$this->request->http_method_page=='post'?'selected':''?> value="post">POST</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label for="inspect_duplicate_url_page" class="col-sm-3 control-label">Проверять дубликат ссылки страницы</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_duplicate_url_page" id="inspect_duplicate_url_page">
     <option <?=$this->request->inspect_duplicate_url_page=='yes'?'selected':''?> value="yes">Да</option>
     <option <?=$this->request->inspect_duplicate_url_page=='no'?'selected':''?> value="no">Нет</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="func_data_processing_page" class="col-sm-3 control-label">Функция обработки объекта страницы</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_data_processing_page" value="<?=$this->request->func_data_processing_page?>" id="func_data_processing_page" placeholder="Принимает массив для записи в БД. Возвращает массив для слияния. NULL удаляет из массива." />
   </div>
  </div>
  <div class="form-group">
   <label for="func_valid_url_page" class="col-sm-3 control-label">Функция подготовки ссылки страницы</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_valid_url_page" value="<?=$this->request->func_valid_url_page?>" id="func_valid_url_page" placeholder="Принимает ссылку. Возвращает: boolean true/false (принять/отклонить), string замена ссылки, array замена всех значений ссылки." />
   </div>
  </div>
  <div class="form-group">
   <label for="table_page_urls" class="col-sm-3 control-label">Таблица с ссылками на страницу</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="table_page_urls" value="<?=$this->request->table_page_urls?>" id="table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="table_fixing" class="col-sm-3 control-label">Назначение таблицы</label>
   <div class="col-sm-9">
    <select class="form-control" name="table_fixing" id="table_fixing">
     <option value="0"></option>
     <option <?=$this->request->table_fixing=='1'?'selected':''?> value="1">Страница</option>
     <option <?=$this->request->table_fixing=='2'?'selected':''?> value="2">Список</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label for="column_table_page_urls" class="col-sm-3 control-label">Поле в таблице с ссылкой</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="column_table_page_urls" value="<?=$this->request->column_table_page_urls?>" id="column_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="start_table_page_urls" class="col-sm-3 control-label">Лимит (начало)</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="start_table_page_urls" value="<?=$this->request->start_table_page_urls?>" id="start_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="length_table_page_urls" class="col-sm-3 control-label">Лимит (количество строк)</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="length_table_page_urls" value="<?=$this->request->length_table_page_urls?>" id="length_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="fields_in_table_for_transmission" class="col-sm-3 control-label">Поля для передачи значений</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="fields_in_table_for_transmission" value="<?=$this->request->fields_in_table_for_transmission?>" id="fields_in_table_for_transmission" placeholder="Через пробел" />
   </div>
  </div>
  <div class="form-group">
   <label for="inspect_url_table" class="col-sm-3 control-label">Обработанные ссылки в таблице</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_url_table" id="inspect_url_table">
     <option <?=$this->request->inspect_url_table=='1'?'selected':''?> value="1">Отмечать</option>
     <option <?=$this->request->inspect_url_table=='2'?'selected':''?> value="2">Ничего</option>
     <option <?=$this->request->inspect_url_table=='3'?'selected':''?> value="3">Удалять</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="amount_stream" class="col-sm-3 control-label">Количество потоков</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="amount_stream" value="<?=$this->request->amount_stream?>" id="amount_stream" />
   </div>
  </div>
  <div class="form-group">
   <label for="microtime_delay" class="col-sm-3 control-label">Задержка очередного запроса. (микросекунд)(1 c = 1000000 мкс).</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="microtime_delay" value="<?=$this->request->microtime_delay?>" id="microtime_delay" />
   </div>
  </div>
  <div class="form-group">
   <label for="status_control_insert" class="col-sm-3 control-label">Дубликаты</label>
   <div class="col-sm-9">
    <select class="form-control" name="status_control_insert" id="status_control_insert">
     <option <?=$this->request->status_control_insert=='1'?'selected':''?> value="1">По значениям</option>
     <option <?=$this->request->status_control_insert=='2'?'selected':''?> value="2">Только ссылку</option>
     <option <?=$this->request->status_control_insert=='3'?'selected':''?> value="3">Перезаписывать</option>
     <option <?=$this->request->status_control_insert=='4'?'selected':''?> value="4">Обновлять</option>
     <option <?=$this->request->status_control_insert=='0'?'selected':''?> value="0">Не проверять</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="fields_control_insert" class="col-sm-3 control-label">Поля для дубликатов</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="fields_control_insert" value="<?=$this->request->fields_control_insert?>" id="fields_control_insert" placeholder="Через пробел. Если поле не заполнено то проверка по всем полям" />
   </div>
  </div>
  <div class="form-group">
   <label for="insert_type" class="col-sm-3 control-label">Добавлять записи в БД</label>
   <div class="col-sm-9">
    <select class="form-control" name="insert_type" id="insert_type">
     <option <?=$this->request->insert_type=='1'?'selected':''?> value="1">Одиночно</option>
     <option <?=$this->request->insert_type=='2'?'selected':''?> value="2">Пакетно - 2</option>
     <option <?=$this->request->insert_type=='3'?'selected':''?> value="3">Пакетно - 3</option>
     <option <?=$this->request->insert_type=='4'?'selected':''?> value="4">Пакетно - 4</option>
     <option <?=$this->request->insert_type=='5'?'selected':''?> value="5">Пакетно - 5</option>
     <option <?=$this->request->insert_type=='7'?'selected':''?> value="7">Пакетно - 7</option>
     <option <?=$this->request->insert_type=='10'?'selected':''?> value="10">Пакетно - 10</option>
     <option <?=$this->request->insert_type=='15'?'selected':''?> value="15">Пакетно - 15</option>
     <option <?=$this->request->insert_type=='20'?'selected':''?> value="20">Пакетно - 20</option>
     <option <?=$this->request->insert_type=='25'?'selected':''?> value="25">Пакетно - 25</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="proxy" class="col-sm-3 control-label">Прокси</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="proxy" value="<?=$this->request->proxy?>" id="proxy" placeholder="Файл у папке proxy. php возвращает массив нужного формата. txt - построчный список." />
   </div>
  </div>
  <div class="form-group">
   <label for="dom_library" class="col-sm-3 control-label">Библиотека DOM</label>
   <div class="col-sm-9">
    <select class="form-control" name="dom_library">
     <option <?=$this->request->dom_library=='2'?'selected':''?> value="2">phpQuery</option>
     <option <?=$this->request->dom_library=='1'?'selected':''?> value="1">SimpleHtmlDom</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Значения по умолчанию</label>
   <div class="col-sm-9" id="dynamic_default_values">
    <?php foreach($this->request->default_values as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="default_values_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="default_values_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_default_values', 'default_values')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success"><?php if($this->request->id){ ?>Сохранить<? } else { ?>Добавить<?php }; ?></button>
   </div>
  </div>
 </form>
</div>
<div class="col-sm-2"></div><?php
    }

    private function viewuseradd () {
$this->title = "Добавить пользователя";

$name = $this->request->getProperty("name");
$login = $this->request->getProperty("login");
$password = $this->request->getProperty("password");
$pass_again = $this->request->getProperty("pass_again");
$privileges = $this->request->getProperty("privileges");
$email = $this->request->getProperty("email");
$error_text_add = $this->request->getProperty("error_text_add");  
  
?>
<div class="col-sm-4"></div>
<div class="col-sm-4" align='center'>
 <form class="form-horizontal" action="<?php echo Link::UserAdd(); ?>" method="post">
  <input type="hidden" class="form-control" name="autorization" id="autorization" value="ok"/>
  <input type="hidden" class="form-control" name="cmd" id="cmd" value="UserAdd"/>
  <div class="form-group">
   <label for="name" class="col-sm-3 control-label">Имя</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" id="name" placeholder="Имя" />
   </div>
  </div>
  <div class="form-group">
   <label for="login" class="col-sm-3 control-label">Логин</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="login" value="<?php echo $login; ?>" id="login" placeholder="Логин" />
   </div>
  </div>
  <div class="form-group">
   <label for="password" class="col-sm-3 control-label">Пароль</label>
   <div class="col-sm-9">
    <input type="password" class="form-control" name="password" value="<?php echo $password; ?>" id="password" placeholder="Пароль" />
   </div>
  </div>
  <div class="form-group">
   <label for="pass_again" class="col-sm-3 control-label">Повтор пароля</label>
   <div class="col-sm-9">
    <input type="password" class="form-control" name="pass_again" value="<?php echo $pass_again; ?>" id="pass_again" placeholder="Повтор пароля" />
   </div>
  </div>
  <div class="form-group">
   <label for="privileges" class="col-sm-3 control-label">Привилегии</label>
   <div class="col-sm-9">
    <select class="form-control" id="privileges" name="privileges">
     <option <?php echo ($privileges=='operator'?'selected=""':""); ?> value="operator">Оператор</option>
     <option <?php echo ($privileges=='admin'?'selected=""':""); ?> value="admin">Админ</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="email" class="col-sm-3 control-label">Адрес&nbsp;Email</label>
   <div class="col-sm-9">
    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="example@mymail.ru" />
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-12"><span class="error_auth" id="error_text_add"><?php echo $error_text_add; ?></span></div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success">Добавить нового пользователя</button>
   </div>
  </div>
 </form>
</div>
<div class="col-sm-4"></div><?php
    }

    private function viewtopWordpress () {
$cmd = $this->request->getProperty('cmd');

?>
  <?= $this->css('assets/bootstrap/css/bootstrap.min.css') ?>
  <?= $this->css('assets/css/jquery-ui.min.css') ?>
  <?= $this->css('assets/css/style.css') ?>
  <?= $this->javascript('assets/js/jquery.min.js') ?>
  <?= $this->javascript('assets/js/jquery-ui-update-datepicker-event.js') ?>
  <?= $this->javascript('assets/js/datepicker-ru.js') ?>
  <?= $this->javascript('assets/bootstrap/js/bootstrap.min.js') ?>
  <?= $this->javascript('assets/js/jquery.timeago.js') ?>
  <script type="text/javascript">
	jQuery(document).ready(function() {
	  jQuery("time.timeago").timeago();
	});
  </script>
    <div class="container-fluid">
      &nbsp;
    </div>
    <div class="container-fluid"><?php
    }

    private function viewerror_privileges () {
$this->title = "Ошибка";

?>
<div class="col-sm-12">
 <h3>В доступе отказано</h3>
</div><?php
    }

    private function viewSourceDataWordpres () {
$this->title = "Источники";

$source = $this->request->getObject('source');
$result = $source->_result;
$sourceData = $this->request->getObject('source_data');

?>
<?= $this->javascript('assets/js/source_data.js') ?>

<div class="row">
 <h2><?=$source->name?></h2>
</div>
<div class="row">
  ID: <strong><?=$source->id?></strong>
  <br />Таблица: <strong><a href="<?=Link::SourceData($source->id)?>"><?=$source->table_name?></a></strong>
  <br />Списков: <strong><?=isset($result['count_urls_list'])?$result['count_urls_list']:'null'?></strong>
  <br />Страниц: <strong><?=isset($result['count_urls_page'])?$result['count_urls_page']:'null'?></strong>
 </div>
 <div class="row"> 
  Создано: <strong><?=date('d.m.Y H:i', $source->created_at)?></strong>
  <br />Запускалось: <strong><?=$source->begin_parse_at?date('d.m.Y H:i:s', $source->begin_parse_at):'-'?></strong>
  <br />Завершено: <strong><?=$source->end_parse_at?date('d.m.Y H:i:s', $source->end_parse_at):'-'?></strong>
  <br /><?=isset($result['last_total_time'])?"Обрабатывалось: <strong>".round($result['last_total_time'], 4)."</strong>":'&nbsp;'?>
</div>

<div class="row">
 <div class="col-sm-1" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_details" class="btn btn-default btn-xs">Отчеты</button>
 </div>
 <?php if(isset($result['inf_last_request']) && is_array($result['inf_last_request'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_last_request" class="btn btn-default btn-xs">Дебаг: О последнем запросе списка</button>
 </div>
 <?php } ?>
 
 <?php if(isset($result['inf_last_request_page']) && is_array($result['inf_last_request_page'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_last_request_page" class="btn btn-default btn-xs">Дебаг: О последнем запросе страницы</button>
 </div>
 <?php } ?>
 
 <?php if(isset($result['inf_illegal_request']) && is_array($result['inf_illegal_request'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_illegal_request" class="btn btn-default btn-xs">Дебаг: Не корректный запрос</button>
 </div>
 <?php } ?>
 <div class="col-sm-2" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <span id="btn_clear_table" class="btn btn-default btn-xs" data-href="<?=Link::ClearSourceData($source->id).($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>">Удалить <?=\workup\record\SourceDataRecord::getCount()?> записи</span>
 </div>
</div>

<div class="row" style="display: none;" id="body_inf_details">
 <div class="col-sm-3"> 
  Кол. всех запросов: <strong><?=$source->count_all_process?></strong>
  <br />Кол. запр. при посл. запуске: <strong><?=$source->count_last_process?></strong>
  <br />Кол. всех записаных в бд: <strong><?=$source->count_all_write?></strong>
  <br />Кол. записаных в бд при посл. зап: <strong><?=$source->count_last_write?></strong>
 </div>
 <div class="col-sm-3"> 
  Кол. всех успешных запросов: <strong><?=$source->count_success_all_process?></strong>
  <br />Кол. всех ошибочных запросов: <strong><?=$source->count_error_all_process?></strong>
  <br />Кол. усп. запр. при посл. запуске: <strong><?=$source->count_success_last_process?></strong>
  <br />Кол. ошиб. запр. при посл. запуске: <strong><?=$source->count_error_last_process?></strong>
 </div>
 <div class="col-sm-3"> 
  Общее время обработки: <strong><?=round($source->time_all_process, 2)?></strong>
  <br />Время обработки посл. запуска: <strong><?=round($source->time_last_process, 2)?></strong>
  <br />Общее время на запросы: <strong><?=round($source->time_all_requests, 2)?></strong>
  <br />Врямя на запр. посл. запуска: <strong><?=round($source->time_last_requests, 2)?></strong>
 </div>
 <div class="col-sm-3"> 
  Общее CP: <strong><?=round($source->cp_all, 2)?></strong>
  <br />CP последнего запуска: <strong><?=round($source->cp_last, 2)?></strong>
  <br />Общая память: <strong><?=round($source->memory_all, 2)?></strong>
  <br />Память последнего запуска: <strong><?=round($source->memory_last, 2)?></strong>
 </div>
</div>

<?php if(isset($result['inf_illegal_request']) && is_array($result['inf_illegal_request'])){ ?>
<div class="col-sm-12" id="body_inf_illegal_request" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_illegal_request'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<?php if(isset($result['inf_last_request_page']) && is_array($result['inf_last_request_page'])){ ?>
<div class="col-sm-12" id="body_inf_last_request_page" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_last_request_page'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<?php if(isset($result['inf_last_request']) && is_array($result['inf_last_request'])){ ?>
<div class="col-sm-12" id="body_inf_last_request" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_last_request'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<div class="row">
<?php
echo getPaginator(\workup\record\SourceDataRecord::getCount(), \workup\record\SourceDataRecord::getLimit(), "?page=wp-parser&cmd=SourceData&id_source_data=".$source->id.($this->request->params?'&'.http_build_query($this->request->params):''), "&");
?>
</div>
<div class="row" style="float: right;">
 Всего&nbsp;<?=\workup\record\SourceDataRecord::getCount()?>
</div>
<div class="row">
  <button class="btn btn-default btn-xs" type="button" onclick="$('#sources_list').submit()">Поиск</button>
</div>
<div class="row">
 <table class="table table-striped list_table_source">
  <thead>
   <form id="sources_list" action="<?=Link::SourceData($source->id).'&page=wp-parser'.($this->request->params_sort?'&'.http_build_query($this->request->params_sort):'')?>" method="post">
    <tr bgcolor='#E6E6FA'>
     <?php
     foreach( $this->request->columns as $key => $value ) { 
     ?>
     <td align='center'>
      <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[<?=$key?>]"><?=$this->request->search?isset($this->request->search[$key])?$this->request->search[$key]:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::SourceData($source->id).($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?=$key?><?=$this->request->sort==$key&&!$this->request->order?'&order=DESC':''?>"><?=$key?></a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort==$key?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
     </td>
     <?php
     }
     ?>
    </tr>
   </form>
  </thead> 
  <tbody>
  <?php
  foreach( $sourceData as $row ) { 
  ?>
   <tr>
    <?php
    foreach( $this->request->columns as $key => $value ) { 
    ?>
    <td align='center'>
     <?=$row->$key?>
    </td>
    <?php
    }
    ?>
   </tr>
  <?php
  }
  ?>
  </tbody>
 </table>
</div>
<div class="row">
<?php
echo getPaginator(\workup\record\SourceDataRecord::getCount(), \workup\record\SourceDataRecord::getLimit(), "?page=wp-parser&cmd=SourceData&id_source_data=".$source->id.($this->request->params?'&'.http_build_query($this->request->params):''), "&");
?>
</div><?php
    }

    private function viewSourceData () {
$this->title = "Источники";

$source = $this->request->getObject('source');
$result = $source->_result;
$sourceData = $this->request->getObject('source_data');

?>
<?= $this->javascript('assets/js/source_data.js') ?>

<div class="col-sm-12">
 <h2><?=$source->name?></h2>
</div>
<div class="col-sm-4">
  ID: <strong><?=$source->id?></strong>
  <br />Таблица: <strong><a href="<?=Link::SourceData($source->id)?>"><?=$source->table_name?></a></strong>
  <br />Списков: <strong><?=isset($result['count_urls_list'])?$result['count_urls_list']:'null'?></strong>
  <br />Страниц: <strong><?=isset($result['count_urls_page'])?$result['count_urls_page']:'null'?></strong>
 </div>
 <div class="col-sm-8"> 
  Создано: <strong><?=date('d.m.Y H:i', $source->created_at)?></strong>
  <br />Запускалось: <strong><?=$source->begin_parse_at?date('d.m.Y H:i:s', $source->begin_parse_at):'-'?></strong>
  <br />Завершено: <strong><?=$source->end_parse_at?date('d.m.Y H:i:s', $source->end_parse_at):'-'?></strong>
  <br /><?=isset($result['last_total_time'])?"Обрабатывалось: <strong>".round($result['last_total_time'], 4)."</strong>":'&nbsp;'?>
</div>

<div class="col-sm-12">
 <div class="col-sm-1" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_details" class="btn btn-default btn-xs">Отчеты</button>
 </div>
 <?php if(isset($result['inf_last_request']) && is_array($result['inf_last_request'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_last_request" class="btn btn-default btn-xs">Дебаг: О последнем запросе списка</button>
 </div>
 <?php } ?>
 
 <?php if(isset($result['inf_last_request_page']) && is_array($result['inf_last_request_page'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_last_request_page" class="btn btn-default btn-xs">Дебаг: О последнем запросе страницы</button>
 </div>
 <?php } ?>
 
 <?php if(isset($result['inf_illegal_request']) && is_array($result['inf_illegal_request'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_illegal_request" class="btn btn-default btn-xs">Дебаг: Не корректный запрос</button>
 </div>
 <?php } ?>
 
 <div class="col-sm-2" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <span id="btn_clear_table" class="btn btn-default btn-xs" data-href="<?=Link::ClearSourceData($source->id).($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>">Удалить <?=\workup\record\SourceDataRecord::getCount()?> записи</span>
 </div>
 <div class="col-sm-2" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <span id="btn_drop_table" class="btn btn-default btn-xs" data-href="<?=Link::DropSourceTable($source->id)?>">Удалить таблицу</span>
 </div>
</div>

<div style="display: none;" id="body_inf_details">
 <div class="col-sm-3"> 
  Кол. всех запросов: <strong><?=$source->count_all_process?></strong>
  <br />Кол. запр. при посл. запуске: <strong><?=$source->count_last_process?></strong>
  <br />Кол. всех записаных в бд: <strong><?=$source->count_all_write?></strong>
  <br />Кол. записаных в бд при посл. зап: <strong><?=$source->count_last_write?></strong>
 </div>
 <div class="col-sm-3"> 
  Кол. всех успешных запросов: <strong><?=$source->count_success_all_process?></strong>
  <br />Кол. всех ошибочных запросов: <strong><?=$source->count_error_all_process?></strong>
  <br />Кол. усп. запр. при посл. запуске: <strong><?=$source->count_success_last_process?></strong>
  <br />Кол. ошиб. запр. при посл. запуске: <strong><?=$source->count_error_last_process?></strong>
 </div>
 <div class="col-sm-3"> 
  Общее время обработки: <strong><?=round($source->time_all_process, 2)?></strong>
  <br />Время обработки посл. запуска: <strong><?=round($source->time_last_process, 2)?></strong>
  <br />Общее время на запросы: <strong><?=round($source->time_all_requests, 2)?></strong>
  <br />Врямя на запр. посл. запуска: <strong><?=round($source->time_last_requests, 2)?></strong>
 </div>
 <div class="col-sm-3"> 
  Общее CP: <strong><?=round($source->cp_all, 2)?></strong>
  <br />CP последнего запуска: <strong><?=round($source->cp_last, 2)?></strong>
  <br />Общая память: <strong><?=round($source->memory_all, 2)?></strong>
  <br />Память последнего запуска: <strong><?=round($source->memory_last, 2)?></strong>
 </div>
</div>

<?php if(isset($result['inf_illegal_request']) && is_array($result['inf_illegal_request'])){ ?>
<div class="col-sm-12" id="body_inf_illegal_request" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_illegal_request'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<?php if(isset($result['inf_last_request_page']) && is_array($result['inf_last_request_page'])){ ?>
<div class="col-sm-12" id="body_inf_last_request_page" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_last_request_page'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<?php if(isset($result['inf_last_request']) && is_array($result['inf_last_request'])){ ?>
<div class="col-sm-12" id="body_inf_last_request" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_last_request'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<div class="col-sm-9">
<?php
echo getPaginator(\workup\record\SourceDataRecord::getCount(), \workup\record\SourceDataRecord::getLimit(), "?cmd=SourceData&id_source_data=".$source->id.($this->request->params?'&'.http_build_query($this->request->params):''), "&");
?>
</div>
<div class="col-sm-3" align='right'>
 Всего&nbsp;<?=\workup\record\SourceDataRecord::getCount()?>
</div>
<div class="col-sm-12">
 <table class="table table-striped list_table_source">
  <thead>
   <form action="<?=Link::SourceData($source->id).($this->request->params_sort?'&'.http_build_query($this->request->params_sort):'')?>" method="post">
    <button class="btn btn-default btn-xs" type="submit">Поиск</button>
    <tr bgcolor='#E6E6FA'>
     <?php
     foreach( $this->request->columns as $key => $value ) { 
     ?>
     <td align='center'>
      <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[<?=$key?>]"><?=$this->request->search?isset($this->request->search[$key])?$this->request->search[$key]:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::SourceData($source->id).($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?=$key?><?=$this->request->sort==$key&&!$this->request->order?'&order=DESC':''?>"><?=$key?></a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort==$key?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
     </td>
     <?php
     }
     ?>
    </tr>
   </form>
  </thead> 
  <tbody>
  <?php
  foreach( $sourceData as $row ) { 
  ?>
   <tr>
    <?php
    foreach( $this->request->columns as $key => $value ) { 
    ?>
    <td align='center'>
     <?=$row->$key?>
    </td>
    <?php
    }
    ?>
   </tr>
  <?php
  }
  ?>
  </tbody>
 </table>
</div>
<div class="col-sm-9">
<?php
echo getPaginator(\workup\record\SourceDataRecord::getCount(), \workup\record\SourceDataRecord::getLimit(), "?cmd=SourceData&id_source_data=".$source->id.($this->request->params?'&'.http_build_query($this->request->params):''), "&");
?>
</div><?php
    }

    private function viewtop () {
$cmd = $this->request->getProperty('cmd');

?><html lang="en">
 <head>
  <title><?= $this->title ?></title>
  <meta charset="utf-8" />
  <?= $this->favicon('assets/img/application.png') ?>
  <?= $this->css('assets/bootstrap/css/bootstrap.min.css') ?>
  <?= $this->css('assets/css/jquery-ui.min.css') ?>
  <?= $this->css('assets/css/style.css') ?>
  <?= $this->javascript('assets/js/jquery.min.js') ?>
  <?= $this->javascript('assets/js/jquery-ui-update-datepicker-event.js') ?>
  <?= $this->javascript('assets/js/datepicker-ru.js') ?>
  <?= $this->javascript('assets/bootstrap/js/bootstrap.min.js') ?>
  <?= $this->javascript('assets/js/jquery.timeago.js') ?>
  <script type="text/javascript">
	jQuery(document).ready(function() {
	  jQuery("time.timeago").timeago();
	});
  </script>
 </head>
 <body>
   <div class="navbar navbar-default navbar-static-top top_navbar" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-toggle pull-left visible-xs top_navbar-xs" style="border-color: #8B795E; margin-left: 10px;" href="<?php echo Link::ListSources(1); ?>"><span class="glyphicon glyphicon-th" style="color: #696969;"></span></a>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#top_navbar" style="border-color: #8B795E;"><span class="icon-bar" style="background-color: #8B795E;"></span><span class="icon-bar" style="background-color: #8B795E;"></span><span class="icon-bar" style="background-color: #8B795E;"></span></button>
        </div>
        <div class="navbar-collapse collapse" id="top_navbar">
          <ul class="nav navbar-nav">
            <li <?php echo ($cmd=='Sources'?"class='active'":""); ?>><a href="<?php echo Link::ListSources(1); ?>">Источники</a></li>
            <li <?php echo ($cmd=='Cron'?"class='active'":""); ?>><a href="<?php echo Link::ListCron(1); ?>">Cron</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if(Auth::getPrivileges() == 'admin'){ ?>
            <li><a id="btn-save-parser" data-href="<?php echo Link::SaveParser(); ?>" class="glyphicon glyphicon-asterisk btn-z-index" style="color: #8B795E; cursor: pointer;"></a></li>
            <?php } ?>
            <?php if(!Auth::statusAutorization()){ ?>
            <li <?php echo ($cmd=='Autorization'?"class='active'":""); ?>><a href="<?php echo Link::Autorization(); ?>">Авторизация</a></li>
            <?php } else { ?>
            <li><a><?php echo Auth::getLogin(); ?></a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-menu-hamburger hidden-xs hidden-md hidden-lg"></span><span class="hidden-sm">Аккаунт <b class="caret"></b></span></a>
              <ul class="dropdown-menu">
                <?php if(Auth::getPrivileges() == 'admin'){ ?>
                <li><a href="<?php echo Link::ListUsers(); ?>" style="color: #8B795E;">Пользователи</a></li>
                <?php } ?>
                <li class="divider hidden-xs"></li>
                <li <?php echo ($cmd=='Autorization'?"class='active'":""); ?>><a href="<?php echo Link::Logout(); ?>">Выйти</a></li>
              </ul>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="container-fluid"><?php
    }

    private function viewusers () {
$this->title = "Пользователи";

$users = $this->request->getObject('users');

?>
<script src="assets/js/users_script.js"></script>
<div class="col-sm-12">
 <a class="btn btn-default" href="<?php echo Link::UserAdd(); ?>">Добавить</a>
</div>
<div class="col-sm-12">
<?php
echo getPaginator(\workup\record\UserRecord::getCount(), \workup\record\UserRecord::getLimit(), "?cmd=Users", "&");
?>
</div>
<div class="col-sm-12">
<div class="table-responsive">
 <table class="table table-striped">
  <thead>
   <tr bgcolor='#E6E6FA'>
    <td>
     &nbsp;
    </td>
    <td>
     ID
    </td>
    <td>
     Имя
    </td>
    <td>
     Логин
    </td>
    <td>
     Привилегии
    </td>
    <td>
     Почта
    </td>
   </tr>
  </thead> 
  <tbody>
<?php
foreach( $users as $user ) {
?>
   <tr>
    <td align='center' valign='middle'>
     <?= $this->img('./assets/img/delete.png', [
         'onclick' => 'delete_user(this, ' . $user->id .')',
         'class' => 'delete_row',
         'width' => "16",
         'title' => "Удалить",
         'alt' => "Удалить"]) ?>
    </td>
    <td>
     <a class="edit_row_cel" href="<?php echo Link::UserEdit($user->id) ?>"><?php echo $user->id; ?></a>
    </td>
    <td>
     <a class="edit_row_cel" href="<?php echo Link::UserEdit($user->id) ?>"><?php echo $user->name; ?></a>
    </td>
    <td>
     <a class="edit_row_cel" href="<?php echo Link::UserEdit($user->id) ?>"><?php echo $user->login; ?></a>
    </td>
    <td>
     <a class="edit_row_cel" href="<?php echo Link::UserEdit($user->id) ?>"><?php echo $user->privileges; ?></a>
    </td>
    <td>
     <a class="edit_row_cel" href="<?php echo Link::UserEdit($user->id) ?>"><?php echo $user->email; ?></a>
    </td>
   </tr>
<?php
}
?></tbody>
 </table>
</div>
</div><?php
    }

    private function view500 () {
header('HTTP/1.0 500 Internal Server Error');
$this->title = "Ошибка";

?>
<div class="col-sm-12">
 <h3>Запрашиваемая страница не существует!</h3>
</div><?php
    }

    private function viewerror_autorization () {
$this->title = "Ошибка";

?>
<div class="col-sm-12">
 <h3>Нужно авторизоваться</h3>
</div><?php
    }

    private function viewbottomWordpress () {
?></div><?php
    }

    private function viewSourceUpdateWordpress () {
$this->title = "Источник";

?>
<?= $this->javascript('assets/js/source_update.js') ?>
<div id="attr_autocomplete">
 <div class="panel panel-default">
  <div class="panel-body attr_autocomplete_click" title="регулярное выражение (подмаска 1)">regulare</div>
  <div class="panel-body attr_autocomplete_click" title="регулярное выражение (удаление тегов)">regulare_strip_tags</div>
  <div class="panel-body attr_autocomplete_click" title="наименование элемента (магический)">tag</div>
  <div class="panel-body attr_autocomplete_click" title="html элемента (магический)">outertext</div>
  <div class="panel-body attr_autocomplete_click" title="html внутри элемента (магический)">innertext</div>
  <div class="panel-body attr_autocomplete_click" title="текст внутри элемента (магический)">plaintext</div> 
  <div class="panel-body attr_autocomplete_click">id</div>    
  <div class="panel-body attr_autocomplete_click">class</div>       
  <div class="panel-body attr_autocomplete_click">href</div>     
  <div class="panel-body attr_autocomplete_click">src</div>       
 </div>
</div>
<div id="name_autocomplete">
 <div class="panel panel-default">
  <div class="panel-body name_autocomplete_click" title="Адрес">address</div>
  <div class="panel-body name_autocomplete_click" title="Бренды">brands</div>
  <div class="panel-body name_autocomplete_click" title="Город">city</div>
  <div class="panel-body name_autocomplete_click" title="Контакты">contacts</div>
  <div class="panel-body name_autocomplete_click" title="Страна">country</div>
  <div class="panel-body name_autocomplete_click" title="Дата начала">date_begin</div>
  <div class="panel-body name_autocomplete_click" title="Дата окончания">date_end</div>
  <div class="panel-body name_autocomplete_click" title="Даты">dates</div>
  <div class="panel-body name_autocomplete_click" title="Описание">description</div>   
  <div class="panel-body name_autocomplete_click" title="Электронная почта">email</div>
  <div class="panel-body name_autocomplete_click" title="Факс">fax</div>
  <div class="panel-body name_autocomplete_click" title="Изображение">logo</div>  
  <div class="panel-body name_autocomplete_click" title="Цена ОТ">low_price</div>  
  <div class="panel-body name_autocomplete_click" title="Цена ДО">high_price</div>  
  <div class="panel-body name_autocomplete_click" title="Изображения">images</div>  
  <div class="panel-body name_autocomplete_click" title="Наименование">name</div>
  <div class="panel-body name_autocomplete_click" title="Время работы">opening_hour</div>
  <div class="panel-body name_autocomplete_click" title="Организатор">sponsor</div>
  <div class="panel-body name_autocomplete_click" title="Павильон">pavilion</div>
  <div class="panel-body name_autocomplete_click" title="Телефон">phone</div>
  <div class="panel-body name_autocomplete_click" title="Валюта">price_currency</div>
  <div class="panel-body name_autocomplete_click" title="Теги">tags</div>
  <div class="panel-body name_autocomplete_click" title="Наименование места">place_name</div>
  <div class="panel-body name_autocomplete_click" title="Почтовый индекс">postal_code</div>
  <div class="panel-body name_autocomplete_click" title="Цена">price</div>
  <div class="panel-body name_autocomplete_click" title="Стенд">stand</div>
  <div class="panel-body name_autocomplete_click" title="Короткое описание">title</div>
  <div class="panel-body name_autocomplete_click" title="Сссылка на сайт">website</div>
 </div>
</div>

<div class="col-sm-10">
 <form class="form-horizontal" action="<?php echo $this->request->id ? Link::SourceUpdate($this->request->id) : Link::SourceUpdate(); ?>" method="post">
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success"><?php if($this->request->id){ ?>Сохранить<? } else { ?>Добавить<?php }; ?></button>
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9"><span class="error_auth" id="error"><?=$this->request->error?></span></div>
  </div>
  <input type="hidden" name="cmd" id="cmd" value="SourceUpdate"/>
  <?php if($this->request->id){ ?>
  <input type="hidden" name="save" id="save" value="1"/> 
  <div class="form-group">
   <label for="id" class="col-sm-3 control-label">ID</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" value="<?=$this->request->id?>" id="id" placeholder="ID" disabled="" />
   </div>
  </div>
  <?php } else { ?>
  <input type="hidden" name="insert" id="insert" value="1"/> 
  <?php } ?>
  <div class="form-group">
   <label for="name" class="col-sm-3 control-label">Имя</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="name" value="<?=$this->request->name?>" id="name" placeholder="Имя" />
   </div>
  </div>
  <div class="form-group">
   <label for="table_name" class="col-sm-3 control-label">Таблица</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="table_name" value="<?=$this->request->table_name?>" id="table_name" placeholder="Имя таблицы" />
   </div>
  </div>
  <div class="form-group">
   <label for="comment" class="col-sm-3 control-label">Комментарий</label>
   <div class="col-sm-9">
    <textarea class="form-control" name="comment" id="comment" placeholder=""><?=$this->request->comment?></textarea>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Ссылки</label>
   <div class="col-sm-9" id="dynamic_urls">
    <?php foreach($this->request->urls as $url){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="urls[]"><?=$url?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_urls', 'urls')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Элементы в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_element">
    <?php foreach($this->request->target_list_element as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="target_list_element[]"><?=$target?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-4 col-sm-8" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_target_list_element', 'target_list_element')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Целевые объекты в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_value">
    <?php foreach($this->request->target_list_value as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_triplet_1"><textarea class="form-control" name="target_list_value_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_triplet_2"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_value_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="field_dynamic_triplet_3"><textarea onfocus="this.select();lcs_name(this)" onclick="event.cancelBubble=true;this.select();lcs_name(this)" class="form-control" name="target_list_value_name[]"><?=$target['name']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_triplet('dynamic_target_list_value', 'target_list_value')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Объекты с ссылкой в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_url">
    <?php foreach($this->request->target_list_url as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_one"><textarea class="form-control" name="target_list_url_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_two"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_url_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_couple('dynamic_target_list_url', 'target_list_url')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Объекты с ссылкой навигации</label>
   <div class="col-sm-9" id="dynamic_target_list_next">
    <?php foreach($this->request->target_list_next as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_one"><textarea class="form-control" name="target_list_next_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_two"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_next_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_couple('dynamic_target_list_next', 'target_list_next')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="begin_page" class="col-sm-3 control-label">Начальная страница списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="begin_page" value="<?=$this->request->begin_page?>" id="begin_page" placeholder="Номер" />
   </div>
  </div>
  <div class="form-group">
   <label for="end_page" class="col-sm-3 control-label">Конечная страница списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="end_page" value="<?=$this->request->end_page?>" id="end_page" placeholder="Номер" />
   </div>
  </div>
  <div class="form-group">
   <label for="key_page" class="col-sm-3 control-label">Параметр страницы списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="key_page" value="<?=$this->request->key_page?>" id="key_page" placeholder="Имя параметра" />
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Данные POST запроса списка</label>
   <div class="col-sm-9" id="dynamic_data_list">
    <?php foreach($this->request->data_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="data_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="data_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_data_list', 'data_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Cookie при запросе списка</label>
   <div class="col-sm-9" id="dynamic_cookie_list">
    <?php foreach($this->request->cookie_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="cookie_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="cookie_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_cookie_list', 'cookie_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Curlopt при запросе списка</label>
   <div class="col-sm-9" id="dynamic_curlopt_list">
    <?php foreach($this->request->curlopt_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="curlopt_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="curlopt_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_curlopt_list', 'curlopt_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="http_method_list" class="col-sm-3 control-label">HTTP метод при запросе списка</label>
   <div class="col-sm-9">
    <select class="form-control" name="http_method_list" id="http_method_list">
     <option <?=$this->request->http_method_list=='get'?'selected':''?> value="get">GET</option>
     <option <?=$this->request->http_method_list=='post'?'selected':''?> value="post">POST</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="inspect_duplicate_url_list" class="col-sm-3 control-label">Проверять дубликат ссылки списка</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_duplicate_url_list" id="inspect_duplicate_url_list">
     <option <?=$this->request->inspect_duplicate_url_list=='yes'?'selected':''?> value="yes">Да</option>
     <option <?=$this->request->inspect_duplicate_url_list=='no'?'selected':''?> value="no">Нет</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="func_data_processing_list" class="col-sm-3 control-label">Функция обработки объекта списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_data_processing_list" value="<?=$this->request->func_data_processing_list?>" id="func_data_processing_list" placeholder="Принимает массив для записи в БД. Возвращает массив для слияния. NULL удаляет из массива." />
   </div>
  </div>
  <div class="form-group">
   <label for="func_valid_url_list" class="col-sm-3 control-label">Функция подготовки ссылки списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_valid_url_list" value="<?=$this->request->func_valid_url_list?>" id="func_valid_url_list" placeholder="Принимает ссылку. Возвращает: boolean true/false (принять/отклонить), string замена ссылки, array замена всех значений ссылки." />
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Ссылки на страницу</label>
   <div class="col-sm-9" id="dynamic_page_urls">
    <?php foreach($this->request->page_urls as $url){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="page_urls[]"><?=$url?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_page_urls', 'page_urls')">добавиить</button>
   </div>
  </div>  
  <div class="form-group">
   <label class="col-sm-3 control-label">Элементы на странице</label>
   <div class="col-sm-9" id="dynamic_target_page_element">
    <?php foreach($this->request->target_page_element as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="target_page_element[]"><?=$target?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_target_page_element', 'target_page_element')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Целевые объекты на странице</label>
   <div class="col-sm-9" id="dynamic_target_page_value">
    <?php foreach($this->request->target_page_value as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_triplet_1"><textarea class="form-control" name="target_page_value_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_triplet_2"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_page_value_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="field_dynamic_triplet_3"><textarea onfocus="this.select();lcs_name(this)" onclick="event.cancelBubble=true;this.select();lcs_name(this)" class="form-control" name="target_page_value_name[]"><?=$target['name']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_triplet('dynamic_target_page_value', 'target_page_value')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Данные POST запроса страницы</label>
   <div class="col-sm-9" id="dynamic_data_page">
    <?php foreach($this->request->data_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="data_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="data_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_data_page', 'data_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Cookie при запросе страницы</label>
   <div class="col-sm-9" id="dynamic_cookie_page">
    <?php foreach($this->request->cookie_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="cookie_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="cookie_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_cookie_page', 'cookie_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Curlopt при запросе страницы</label>
   <div class="col-sm-9" id="dynamic_curlopt_page">
    <?php foreach($this->request->curlopt_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="curlopt_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="curlopt_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_curlopt_page', 'curlopt_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="http_method_page" class="col-sm-3 control-label">HTTP метод при запросе страницы</label>
   <div class="col-sm-9">
    <select class="form-control" name="http_method_page" id="http_method_page">
     <option <?=$this->request->http_method_page=='get'?'selected':''?> value="get">GET</option>
     <option <?=$this->request->http_method_page=='post'?'selected':''?> value="post">POST</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label for="inspect_duplicate_url_page" class="col-sm-3 control-label">Проверять дубликат ссылки страницы</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_duplicate_url_page" id="inspect_duplicate_url_page">
     <option <?=$this->request->inspect_duplicate_url_page=='yes'?'selected':''?> value="yes">Да</option>
     <option <?=$this->request->inspect_duplicate_url_page=='no'?'selected':''?> value="no">Нет</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="func_data_processing_page" class="col-sm-3 control-label">Функция обработки объекта страницы</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_data_processing_page" value="<?=$this->request->func_data_processing_page?>" id="func_data_processing_page" placeholder="Принимает массив для записи в БД. Возвращает массив для слияния. NULL удаляет из массива." />
   </div>
  </div>
  <div class="form-group">
   <label for="func_valid_url_page" class="col-sm-3 control-label">Функция подготовки ссылки страницы</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_valid_url_page" value="<?=$this->request->func_valid_url_page?>" id="func_valid_url_page" placeholder="Принимает ссылку. Возвращает: boolean true/false (принять/отклонить), string замена ссылки, array замена всех значений ссылки." />
   </div>
  </div>
  <div class="form-group">
   <label for="table_page_urls" class="col-sm-3 control-label">Таблица с ссылками на страницу</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="table_page_urls" value="<?=$this->request->table_page_urls?>" id="table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="table_fixing" class="col-sm-3 control-label">Назначение таблицы</label>
   <div class="col-sm-9">
    <select class="form-control" name="table_fixing" id="table_fixing">
     <option value="0"></option>
     <option <?=$this->request->table_fixing=='1'?'selected':''?> value="1">Страница</option>
     <option <?=$this->request->table_fixing=='2'?'selected':''?> value="2">Список</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label for="column_table_page_urls" class="col-sm-3 control-label">Поле в таблице с ссылкой</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="column_table_page_urls" value="<?=$this->request->column_table_page_urls?>" id="column_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="start_table_page_urls" class="col-sm-3 control-label">Лимит (начало)</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="start_table_page_urls" value="<?=$this->request->start_table_page_urls?>" id="start_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="length_table_page_urls" class="col-sm-3 control-label">Лимит (количество строк)</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="length_table_page_urls" value="<?=$this->request->length_table_page_urls?>" id="length_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="fields_in_table_for_transmission" class="col-sm-3 control-label">Поля для передачи значений</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="fields_in_table_for_transmission" value="<?=$this->request->fields_in_table_for_transmission?>" id="fields_in_table_for_transmission" placeholder="Через пробел" />
   </div>
  </div>
  <div class="form-group">
   <label for="inspect_url_table" class="col-sm-3 control-label">Обработанные ссылки в таблице</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_url_table" id="inspect_url_table">
     <option <?=$this->request->inspect_url_table=='1'?'selected':''?> value="1">Отмечать</option>
     <option <?=$this->request->inspect_url_table=='2'?'selected':''?> value="2">Ничего</option>
     <option <?=$this->request->inspect_url_table=='3'?'selected':''?> value="3">Удалять</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="amount_stream" class="col-sm-3 control-label">Количество потоков</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="amount_stream" value="<?=$this->request->amount_stream?>" id="amount_stream" />
   </div>
  </div>
  <div class="form-group">
   <label for="microtime_delay" class="col-sm-3 control-label">Задержка очередного запроса. (микросекунд)(1 c = 1000000 мкс).</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="microtime_delay" value="<?=$this->request->microtime_delay?>" id="microtime_delay" />
   </div>
  </div>
  <div class="form-group">
   <label for="status_control_insert" class="col-sm-3 control-label">Дубликаты</label>
   <div class="col-sm-9">
    <select class="form-control" name="status_control_insert" id="status_control_insert">
     <option <?=$this->request->status_control_insert=='1'?'selected':''?> value="1">По значениям</option>
     <option <?=$this->request->status_control_insert=='2'?'selected':''?> value="2">Только ссылку</option>
     <option <?=$this->request->status_control_insert=='3'?'selected':''?> value="3">Перезаписывать</option>
     <option <?=$this->request->status_control_insert=='4'?'selected':''?> value="4">Обновлять</option>
     <option <?=$this->request->status_control_insert=='0'?'selected':''?> value="0">Не проверять</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="fields_control_insert" class="col-sm-3 control-label">Поля для дубликатов</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="fields_control_insert" value="<?=$this->request->fields_control_insert?>" id="fields_control_insert" placeholder="Через пробел. Если поле не заполнено то проверка по всем полям" />
   </div>
  </div>
  <div class="form-group">
   <label for="insert_type" class="col-sm-3 control-label">Добавлять записи в БД</label>
   <div class="col-sm-9">
    <select class="form-control" name="insert_type" id="insert_type">
     <option <?=$this->request->insert_type=='1'?'selected':''?> value="1">Одиночно</option>
     <option <?=$this->request->insert_type=='2'?'selected':''?> value="2">Пакетно - 2</option>
     <option <?=$this->request->insert_type=='3'?'selected':''?> value="3">Пакетно - 3</option>
     <option <?=$this->request->insert_type=='4'?'selected':''?> value="4">Пакетно - 4</option>
     <option <?=$this->request->insert_type=='5'?'selected':''?> value="5">Пакетно - 5</option>
     <option <?=$this->request->insert_type=='7'?'selected':''?> value="7">Пакетно - 7</option>
     <option <?=$this->request->insert_type=='10'?'selected':''?> value="10">Пакетно - 10</option>
     <option <?=$this->request->insert_type=='15'?'selected':''?> value="15">Пакетно - 15</option>
     <option <?=$this->request->insert_type=='20'?'selected':''?> value="20">Пакетно - 20</option>
     <option <?=$this->request->insert_type=='25'?'selected':''?> value="25">Пакетно - 25</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="proxy" class="col-sm-3 control-label">Прокси</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="proxy" value="<?=$this->request->proxy?>" id="proxy" placeholder="Файл у папке proxy. php возвращает массив нужного формата. txt - построчный список." />
   </div>
  </div>
  <div class="form-group">
   <label for="dom_library" class="col-sm-3 control-label">Библиотека DOM</label>
   <div class="col-sm-9">
    <select class="form-control" name="dom_library">
     <option <?=$this->request->dom_library=='2'?'selected':''?> value="2">phpQuery</option>
     <option <?=$this->request->dom_library=='1'?'selected':''?> value="1">SimpleHtmlDom</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Значения по умолчанию</label>
   <div class="col-sm-9" id="dynamic_default_values">
    <?php foreach($this->request->default_values as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="default_values_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="default_values_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_default_values', 'default_values')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success"><?php if($this->request->id){ ?>Сохранить<? } else { ?>Добавить<?php }; ?></button>
   </div>
  </div>
 </form>
</div>
<div class="col-sm-2"></div><?php
    }

    private function viewSourcesWordpress () {
$this->title = "Источники";

$sources = $this->request->getObject('sources');

?>
<?= $this->javascript('assets/js/sources_script.js') ?>
<?= $this->img('assets/img/full_preloader.gif', ['height' => '1', 'style' => 'display: none;']) ?>
<?= $this->img('assets/img/check.png', ['height' => '1', 'style' => 'display: none;']) ?>
<?= $this->img('assets/img/cancel.png', ['height' => '1', 'style' => 'display: none;']) ?>
<div class="row">
  <a class="btn btn-default" href="<?=Link::SourceUpdate()?>">Добавить</a>
  <span><button type="button" onclick="block_all(this)" class="btn btn-default">Блокировать Все</button></span>
  <button class="btn btn-default" type="button" onclick="$('#sources_list').submit()">Поиск</button>
</div>
<div class="row">
  <?php
    echo getPaginator(\workup\record\SourceRecord::getCount(), \workup\record\SourceRecord::getLimit(), "?page=wp-parser&cmd=Sources".($this->request->params?'&'.http_build_query($this->request->params):''), "&");
    ?>
</div>
<div style="float: right;">
  Всего&nbsp;<?=$this->request->count_all_sources?>
</div>
<div class="row">
  <table class="table table-striped list_table_source">
    <thead>
      <form id="sources_list" action="<?=Link::ListSources().($this->request->params_sort?'&page=wp-parser&'.http_build_query($this->request->params_sort):'')?>" method="post">
        
        <tr bgcolor='#E6E6FA'>
          <td align='center' valign='middle'>
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" style="height: 35px;" name="search[check_cron]"><?=$this->request->search?isset($this->request->search['check_cron'])?$this->request->search['check_cron']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='check_cron'?><?=$this->request->sort=='check_cron'&&!$this->request->order?'&order=DESC':''?>">cron</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='check_cron'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td align='center' valign='middle'>
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" style="height: 35px;" name="search[id]"><?=$this->request->search?isset($this->request->search['id'])?$this->request->search['id']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='id'?><?=$this->request->sort=='id'&&!$this->request->order?'&order=DESC':''?>">ID</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='id'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td valign='middle' title="По умолчанию поск по полю `name`. Для поиска по другому полю введите 'field_name->text_search'. Возможные значения `field_name`: name, comment, table_name, urls">
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" style="height: 35px;" name="search[search]"><?=$this->request->search?isset($this->request->search['search'])?$this->request->search['search']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='name'?><?=$this->request->sort=='name'&&!$this->request->order?'&order=DESC':''?>">Источник</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='name'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td align='center'>
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" id="created_at" style="height: 35px;" name="search[created_at]"><?=$this->request->search?isset($this->request->search['created_at'])?$this->request->search['created_at']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='created_at'?><?=$this->request->sort=='created_at'&&!$this->request->order?'&order=DESC':''?>">Создано</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='created_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" id="begin_parse_at" style="height: 35px;" name="search[begin_parse_at]"><?=$this->request->search?isset($this->request->search['begin_parse_at'])?$this->request->search['begin_parse_at']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='begin_parse_at'?><?=$this->request->sort=='begin_parse_at'&&!$this->request->order?'&order=DESC':''?>">Запуск</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='begin_parse_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td align='center'>
            <table class="table table-hover" style="margin: 0px; background-color: inherit; font-size: 12px;">
              <tr>
                <td title="Количество запусков" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_process'?><?=$this->request->sort=='count_process'&&!$this->request->order?'&order=DESC':''?>">c_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех запросов" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_process'?><?=$this->request->sort=='count_all_process'&&!$this->request->order?'&order=DESC':''?>">c_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех записаных в бд" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_write'?><?=$this->request->sort=='count_all_write'&&!$this->request->order?'&order=DESC':''?>">c_a_w</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_all_write'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех успешных запросов" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_success_all_process'?><?=$this->request->sort=='count_success_all_process'&&!$this->request->order?'&order=DESC':''?>">c_s_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_success_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех ошибочных запросов" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_error_all_process'?><?=$this->request->sort=='count_error_all_process'&&!$this->request->order?'&order=DESC':''?>">c_e_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_error_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее время на запросы" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_all_requests'?><?=$this->request->sort=='time_all_requests'&&!$this->request->order?'&order=DESC':''?>">t_a_r</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_all_requests'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее время обработки" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_all_process'?><?=$this->request->sort=='time_all_process'&&!$this->request->order?'&order=DESC':''?>">t_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее CP" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='cp_all'?><?=$this->request->sort=='cp_all'&&!$this->request->order?'&order=DESC':''?>">cp_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='cp_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общая память MB" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='memory_all'?><?=$this->request->sort=='memory_all'&&!$this->request->order?'&order=DESC':''?>">m_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='memory_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее количество обращений к Базе Данных" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_query_to_bd'?><?=$this->request->sort=='count_all_query_to_bd'&&!$this->request->order?'&order=DESC':''?>">c_a_q_t_b</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_all_query_to_bd'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td title="Общее время выполнения" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_process_life'?><?=$this->request->sort=='time_process_life'&&!$this->request->order?'&order=DESC':''?>">t_p_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_process_life'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество запросов при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_process'?><?=$this->request->sort=='count_last_process'&&!$this->request->order?'&order=DESC':''?>">c_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество записаных в бд при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_write'?><?=$this->request->sort=='count_last_write'&&!$this->request->order?'&order=DESC':''?>">c_l_w</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_last_write'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество успешных запросов при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_success_last_process'?><?=$this->request->sort=='count_success_last_process'&&!$this->request->order?'&order=DESC':''?>">c_s_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_success_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество ошибочных запросов при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_error_last_process'?><?=$this->request->sort=='count_error_last_process'&&!$this->request->order?'&order=DESC':''?>">c_e_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_error_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Врямя на запросы последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_last_requests'?><?=$this->request->sort=='time_last_requests'&&!$this->request->order?'&order=DESC':''?>">t_l_r</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_last_requests'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Время обработки последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_last_process'?><?=$this->request->sort=='time_last_process'&&!$this->request->order?'&order=DESC':''?>">t_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="CP последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='cp_last'?><?=$this->request->sort=='cp_last'&&!$this->request->order?'&order=DESC':''?>">cp_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='cp_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Память последего запуска MB" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='memory_last'?><?=$this->request->sort=='memory_last'&&!$this->request->order?'&order=DESC':''?>">m_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='memory_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество обращений к Базе данных Последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_query_to_bd'?><?=$this->request->sort=='count_last_query_to_bd'&&!$this->request->order?'&order=DESC':''?>">c_l_q_t_b</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_last_query_to_bd'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td title="Количество всех прочитанных при импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_all'?><?=$this->request->sort=='count_import_all'&&!$this->request->order?'&order=DESC':''?>">c_i_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество прочитанных при последнем импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_last'?><?=$this->request->sort=='count_import_last'&&!$this->request->order?'&order=DESC':''?>">c_i_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех успешных при импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_success_all'?><?=$this->request->sort=='count_import_success_all'&&!$this->request->order?'&order=DESC':''?>">c_i_s_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_success_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех ошибочных при импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_error_all'?><?=$this->request->sort=='count_import_error_all'&&!$this->request->order?'&order=DESC':''?>">c_i_e_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_error_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество успешных при последнем импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_success_last'?><?=$this->request->sort=='count_import_success_last'&&!$this->request->order?'&order=DESC':''?>">c_i_s_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_success_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество успешных при последнем импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_error_last'?><?=$this->request->sort=='count_import_error_last'&&!$this->request->order?'&order=DESC':''?>">c_i_e_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_error_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Средний бал" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='import_rate'?><?=$this->request->sort=='import_rate'&&!$this->request->order?'&order=DESC':''?>">i_rate</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='import_rate'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество при последнем успешном импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='last_import_count'?><?=$this->request->sort=='last_import_count'&&!$this->request->order?'&order=DESC':''?>">l_i_c</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='last_import_count'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество при последнем успешном парсинге" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='last_write_count'?><?=$this->request->sort=='last_write_count'&&!$this->request->order?'&order=DESC':''?>">l_w_c</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='last_write_count'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
          <td align='center' colspan="8">
            Действия
          </td>
        </tr>
      </form>
    </thead>
    <tbody>
      <?php
        foreach($sources as $source) { 
        ?>
      <tr>
        <td align='center' valign='middle'>
          <input onchange="changeCheckCron(this)" type="checkbox" data-id="<?=$source->id?>" <?=$source->check_cron?'checked=""':''?> />
        </td>
        <td align='center' valign='middle'>
          <?=$source->id?>
        </td>
        <td valign='middle'>
          <a class="edit_row_cel" href="<?=Link::SourceData($source->id)?>"><strong><?=$source->name?></strong></a> <? if (!empty($source->comment)) echo "<span class='glyphicon glyphicon-comment btn-z-index' title='" . $source->comment . "'></span>"; ?>
          <div style='font-size: 80%; margin-top: 10px'>
            <span>БД: <?=$source->table_name?></span>
            <br />
            <span><?=implode('<br />', array_map(function($value){ return str_limit($value, 50); }, array_slice($source->_urls, 0, 5)))?></span>
          </div>
        </td>
        <td align='center' style="font-size: 12px;" valign='middle'>
          <abbr class="timeago" title="<?=date('d.m.Y H:i', $source->created_at)?>" ><?=date('d.m.Y', $source->created_at)?></abbr>
          <abbr class="timeago" title="<?=date('d.m.Y H:i', $source->begin_parse_at)?>" ><?=date('d.m.Y', $source->begin_parse_at)?></abbr>
        </td>
        <td valign='middle'>
          <table class="table table-hover" style="margin: 0px; background-color: inherit; font-size: 12px;">
            <tr>
              <td title="Количество запусков" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_process?></span></td>
              <td title="Количество всех запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_all_process?></span></td>
              <td title="Количество всех записаных в бд" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_all_write?></span></td>
              <td title="Количество всех успешных запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_success_all_process?></span></td>
              <td title="Количество всех ошибочных запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_error_all_process?></span></td>
              <td title="Общее время на запросы" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->time_all_requests, 2)?></span></td>
              <td title="Общее время обработки" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->time_all_process, 2)?></span></td>
              <td title="Общее CP" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->cp_all, 2)?></span></td>
              <td title="Общая память" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->memory_all, 2)?></span></td>
              <td title="Общее количество обращений к Базе Данных" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->count_all_query_to_bd, 2)?></span></td>
            </tr>
            <tr>
              <td title="Общее время выполнения" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_process_life, 2)?></span></td>
              <td title="Количество запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_last_process?></span></td>
              <td title="Количество записаных в бд при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_last_write?></span></td>
              <td title="Количество успешных запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_success_last_process?></span></td>
              <td title="Количество ошибочных запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_error_last_process?></span></td>
              <td title="Врямя на запросы последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_last_requests, 2)?></span></td>
              <td title="Время обработки последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_last_process, 2)?></span></td>
              <td title="CP последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->cp_last, 2)?></span></td>
              <td title="Память последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->memory_last, 2)?></span></td>
              <td title="Количество обращений к Базе данных Последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_last_query_to_bd, 2)?></span></td>
            </tr>
            <tr>
              <td title="Количество всех прочитанных при импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_all, 2)?></span></td>
              <td title="Количество прочитанных при последнем импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_last, 2)?></span></td>
              <td title="Количество всех успешных при импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_success_all, 2)?></span></td>
              <td title="Количество всех ошибочных при импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_error_all, 2)?></span></td>
              <td title="Количество всех успешных при последнем импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_success_last, 2)?></span></td>
              <td title="Количество всех ошибочных при последнем импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_error_last, 2)?></span></td>
              <td title="Средний бал" style="text-align: center;"><span style="cursor: default;"><?=round($source->import_rate, 2)?></span></td>
              <td title="Количество при последнем успешном импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->last_import_count, 2)?></span></td>
              <td title="Количество при последнем успешном парсинге" style="text-align: center;"><span style="cursor: default;"><?=round($source->last_write_count, 2)?></span></td>
            </tr>
          </table>
        </td>
        <td>
          <table class="tbl-control-buttons">
            <tr>
              <td align='center' valign='middle'>
                <button onclick="parse_source(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-play btn-z-index" title='Запустить'></button>
              </td>
              <td align='center' valign='middle'>
                <a class="btn btn-default btn-sm glyphicon glyphicon-cog btn-z-index" href="<?=Link::SourceUpdate($source->id)?>" title='Настройки'></a>
              </td>
              <td align='center' valign='middle'>
                <button onclick="parse_blocking(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-remove btn-z-index" title="Остановить Процесс"></button>
              </td>
              <td align='center' valign='middle'>
                <button onclick="delete_source(this, <?=$source->id?>)" class='btn btn-default btn-sm glyphicon glyphicon-trash btn-z-index' title="Удалить"></button>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <?php
        }
        ?>
    </tbody>
  </table>
</div>
<div class="modal fade" id="ModalExportGoogleSheets" tabindex="-1" role="dialog" aria-labelledby="ModalExportGoogleSheetsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Смещение</h4>
        <p>
          <input type="text" class="form-control" name="offset" value="0" />
        </p>
        <h4>Лимит</h4>
        <p>
          <input type="text" class="form-control" name="limit" value="10000" />
        </p>
        <h4>ID Таблицы</h4>
        <p>
          <input type="text" class="form-control" name="id_sheet" value="" placeholder="Если не указано то будет создана новая таблица" />
        </p>
        <p>
          <input onclick="export_google_sheets(this)" type="submit" class="btn btn-primary" value="Выполнить" /> 
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="ModalExportExcel" tabindex="-1" role="dialog" aria-labelledby="ModalExportExcelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Смещение</h4>
        <p>
          <input type="text" class="form-control" name="offset" value="0" />
        </p>
        <h4>Лимит</h4>
        <p>
          <input type="text" class="form-control" name="limit" value="10000" />
        </p>
        <p>
          <input onclick="export_excel(this)" type="submit" class="btn btn-primary" value="Выполнить" /> 
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </p>
      </div>
    </div>
  </div>
</div><?php
    }

    private function viewbottom () {
?></div>
 </body>
</html><?php
    }

    private function viewmissingrow () {
$this->title = "Ошибка";

?>
<div class="col-sm-12">
 <h3>Запрашиваемой записи не найдено!</h3>
</div><?php
    }

    private function viewautorization () {
$this->title = "Авторизация";

$login = $this->request->getProperty("login");
$password = $this->request->getProperty("password");
$error_autorization = $this->request->getProperty("error_autorization");

?>
<div class="col-sm-4"></div>
<div class="col-sm-4" align='center'>
 <form class="form-horizontal" action="<?php echo Link::Autorization(); ?>" method="post">
  <input type="hidden" class="form-control" name="autorization" id="autorization" value="ok"/>
  <input type="hidden" class="form-control" name="cmd" id="cmd" value="Autorization"/>
  <div class="form-group">
   <label for="login" class="col-sm-3 control-label">Логин</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="login" value="<?php echo $login; ?>" id="login" placeholder="Логин" />
   </div>
  </div>
  <div class="form-group">
   <label for="password" class="col-sm-3 control-label">Пароль</label>
   <div class="col-sm-9">
    <input type="password" class="form-control" name="password" value="<?php echo $password; ?>" id="pass" placeholder="Пароль" />
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-12"><span class="error_auth" id="error_autorization"><?php echo $error_autorization; ?></span></div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success">войти</button>
   </div>
  </div>
 </form>
</div>
<div class="col-sm-4"></div><?php
    }

    private function viewCron () {
$this->title = "Крон";

$sources = $this->request->getObject('sources');
?>
<?= $this->javascript('assets/js/sources_script.js') ?>
<?= $this->img('assets/img/full_preloader.gif', ['height' => '1', 'style' => 'display: none;']) ?>
<?= $this->img('assets/img/check.png', ['height' => '1', 'style' => 'display: none;']) ?>
<?= $this->img('assets/img/cancel.png', ['height' => '1', 'style' => 'display: none;']) ?>
<div class="text">
<?php
echo getPaginator(\workup\record\SourceRecord::getCount(), \workup\record\SourceRecord::getLimit(), "?cmd=Sources", "&");
?>
</div>
<div style="float: right;">
 Всего&nbsp;<?=$this->request->count_all_sources?>
</div>
<div class="text">
 <form class="form-horizontal" id="process_form_all">
  <div class="form-group">
   <div class="col-sm-3" align="center">
    <button type="button" onclick="process_cron()" class="btn btn-block btn-default"><span id="status_proccess_cron">Запустить</span></button>
   </div>
   <div class="col-sm-3" align="center">
    <button type="button" onclick="block_all(this)" class="btn btn-block btn-default">Блокировать</button>
   </div>
  <button class="btn btn-default" type="button" onclick="$('#sources_list').submit()">Поиск</button>
  </div>
  <div class="form-group">
   <div class="col-sm-3" align="center" id="text_proccess_cron">
   </div>
  </div>
 </form>
</div>
<div class="row">
 <table class="table table-striped list_table_source">
  <thead>
   <form id="sources_list" action="<?=Link::ListCron().($this->request->params_sort?'&'.http_build_query($this->request->params_sort):'')?>" method="post">
   
   <tr bgcolor='#E6E6FA'>
    <td>  
      <a href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='visibility'?><?=$this->request->sort=='visibility'&&!$this->request->order?'&order=DESC':''?>"><span class="glyphicon glyphicon-star-empty btn-z-index" style="font-size: 14px;"></span></a>
    </td>
    <td align='center' valign='middle'>
     <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[check_cron]"><?=$this->request->search?isset($this->request->search['check_cron'])?$this->request->search['check_cron']:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='check_cron'?><?=$this->request->sort=='check_cron'&&!$this->request->order?'&order=DESC':''?>">cron</a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort=='check_cron'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
    </td>
    <td align='center' valign='middle'>
     <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[id]"><?=$this->request->search?isset($this->request->search['id'])?$this->request->search['id']:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='id'?><?=$this->request->sort=='id'&&!$this->request->order?'&order=DESC':''?>">ID</a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort=='id'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
    </td>
    <td valign='middle'>
     <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[search]"><?=$this->request->search?isset($this->request->search['search'])?$this->request->search['search']:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='name'?><?=$this->request->sort=='name'&&!$this->request->order?'&order=DESC':''?>">Источник</a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort=='name'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
    </td>
    <td align='center'>
     <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[created_at]"><?=$this->request->search?isset($this->request->search['created_at'])?$this->request->search['created_at']:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='created_at'?><?=$this->request->sort=='created_at'&&!$this->request->order?'&order=DESC':''?>">Создано</a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort=='created_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
    </td>
    <td align='center'>
     <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[begin_parse_at]"><?=$this->request->search?isset($this->request->search['begin_parse_at'])?$this->request->search['begin_parse_at']:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='begin_parse_at'?><?=$this->request->sort=='begin_parse_at'&&!$this->request->order?'&order=DESC':''?>">Запуск</a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort=='begin_parse_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
    </td>
    <td align='center'>
     <table class="table table-hover" style="margin: 0px; background-color: inherit; font-size: 12px;">
      <tr>
       <td title="Количество взапусков" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_process'?><?=$this->request->sort=='count_process'&&!$this->request->order?'&order=DESC':''?>">c_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество всех запросов" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_process'?><?=$this->request->sort=='count_all_process'&&!$this->request->order?'&order=DESC':''?>">c_a_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество всех записаных в бд" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_write'?><?=$this->request->sort=='count_all_write'&&!$this->request->order?'&order=DESC':''?>">c_a_w</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_all_write'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество всех успешных запросов" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_success_all_process'?><?=$this->request->sort=='count_success_all_process'&&!$this->request->order?'&order=DESC':''?>">c_s_a_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_success_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество всех ошибочных запросов" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_error_all_process'?><?=$this->request->sort=='count_error_all_process'&&!$this->request->order?'&order=DESC':''?>">c_e_a_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_error_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Общее время обработки" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_all_process'?><?=$this->request->sort=='time_all_process'&&!$this->request->order?'&order=DESC':''?>">t_a_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='time_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Общее время на запросы" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_all_requests'?><?=$this->request->sort=='time_all_requests'&&!$this->request->order?'&order=DESC':''?>">t_a_r</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='time_all_requests'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Общее CP" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='cp_all'?><?=$this->request->sort=='cp_all'&&!$this->request->order?'&order=DESC':''?>">cp_a</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='cp_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Общая память" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='memory_all'?><?=$this->request->sort=='memory_all'&&!$this->request->order?'&order=DESC':''?>">m_a</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='memory_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Общее количество обращений к Базе Данных" style="border-top: none; text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_query_to_bd'?><?=$this->request->sort=='count_all_query_to_bd'&&!$this->request->order?'&order=DESC':''?>">c_a_q_t_b</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_all_query_to_bd'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
      </tr>
      <tr>
       <td></td>
       <td title="Количество запросов при последнем запуске" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_process'?><?=$this->request->sort=='count_last_process'&&!$this->request->order?'&order=DESC':''?>">c_l_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество записаных в бд при последнем запуске" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_write'?><?=$this->request->sort=='count_last_write'&&!$this->request->order?'&order=DESC':''?>">c_l_w</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_last_write'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество успешных запросов при последнем запуске" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_success_last_process'?><?=$this->request->sort=='count_success_last_process'&&!$this->request->order?'&order=DESC':''?>">c_s_l_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_success_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество ошибочных запросов при последнем запуске" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_error_last_process'?><?=$this->request->sort=='count_error_last_process'&&!$this->request->order?'&order=DESC':''?>">c_e_l_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_error_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Время обработки последнего запуска" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_last_process'?><?=$this->request->sort=='time_last_process'&&!$this->request->order?'&order=DESC':''?>">t_l_p</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='time_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Врямя на запросы последнего запуска" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListCron().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_last_requests'?><?=$this->request->sort=='time_last_requests'&&!$this->request->order?'&order=DESC':''?>">t_l_r</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='time_last_requests'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="CP последнего запуска" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='cp_last'?><?=$this->request->sort=='cp_last'&&!$this->request->order?'&order=DESC':''?>">cp_l</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='cp_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Память последего запуска" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='memory_last'?><?=$this->request->sort=='memory_last'&&!$this->request->order?'&order=DESC':''?>">m_l</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='memory_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
       <td title="Количество обращений к Базе данных Последнего запуска" style="text-align: center;">
        <table>
         <tr>
          <td align='center'>
           <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_query_to_bd'?><?=$this->request->sort=='count_last_query_to_bd'&&!$this->request->order?'&order=DESC':''?>">c_l_q_t_b</a>
          </td>
          <td align='center'>
           <div><?=$this->request->sort=='count_last_query_to_bd'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
          </td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
    </td>
    <td align='center' colspan="5">
     Действия
    </td>
   </tr>
   </form>
  </thead> 
  <tbody>
<?php
foreach( $sources as $source ) { 
?>
   <tr>
    <td>
      <?=$source->visibility=='0'?'<span class="glyphicon glyphicon-star-empty btn-z-index" style="font-size: 14px;"></span>':''?>
    </td>
    <td align='center' valign='middle'>
     <input onchange="changeCheckCronByCron(this)" type="checkbox" data-id="<?=$source->id?>" <?=$source->check_cron?'checked=""':''?> />
    </td>
    <td align='center' valign='middle'>
     <?=$source->id?>
    </td>
    <td valign='middle'>
     <a class="edit_row_cel" href="<?=Link::SourceDataCron($source->id)?>"><strong><?=$source->name?></strong></a> <? if (!empty($source->comment)) echo "<span class='glyphicon glyphicon-comment' title='" . $source->comment . "'></span>"; ?>
	 <div style='font-size: 80%; margin-top: 10px'>
      <span>БД: <?=$source->table_name?></span>
      <br />
      <span><?=implode('<br />', array_map(function($value){ return str_limit($value, 50); }, array_slice($source->_urls, 0, 5)))?></span>
	 </div>
    </td>
    <td align='center' style="font-size: 12px;" valign='middle'>
     <abbr class="timeago" title="<?=date('d.m.Y H:i', $source->created_at)?>" ><?=date('d.m.Y H:i', $source->created_at)?></abbr>
    </td>
    <td align='center' style="font-size: 12px;" valign='middle'>
     <?=$source->begin_parse_at?date('d.m.Y H:i', $source->begin_parse_at):'-'?>
    </td>
    <td valign='middle'>
     <table class="table table-hover" style="margin: 0px; background-color: inherit; font-size: 12px;">
      <tr>
       <td title="Количество запусков" style="border-top: none; text-align: center;"><?=$source->count_process?></td>
       <td title="Количество всех запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_all_process?></span></td>
       <td title="Количество всех записаных в бд" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_all_write?></span></td>
       <td title="Количество всех успешных запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_success_all_process?></span></td>
       <td title="Количество всех ошибочных запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_error_all_process?></span></td>
       <td title="Общее время обработки" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->time_all_process, 2)?></span></td>
       <td title="Общее время на запросы" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->time_all_requests, 2)?></span></td>
       <td title="Общее CP" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->cp_all, 2)?></span></td>
       <td title="Общая память" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->memory_all, 2)?></span></td>
       <td title="Общее количество обращений к Базе Данных" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->count_all_query_to_bd, 2)?></span></td>
      </tr>
      <tr>
       <td></td>
       <td title="Количество запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_last_process?></span></td>
       <td title="Количество записаных в бд при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_last_write?></span></td>
       <td title="Количество успешных запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_success_last_process?></span></td>
       <td title="Количество ошибочных запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_error_last_process?></span></td>
       <td title="Время обработки последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_last_process, 2)?></span></td>
       <td title="Врямя на запросы последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_last_requests, 2)?></span></td>
       <td title="CP последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->cp_last, 2)?></span></td>
       <td title="Память последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->memory_last, 2)?></span></td>
       <td title="Количество обращений к Базе данных Последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_last_query_to_bd, 2)?></span></td>
      </tr>
     </table>
    </td>
    <td align='center' valign='middle'>
     <button onclick="parse_cron(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-play btn-z-index" title='Запустить'></button>
    </td>
    <td align='center' valign='middle'>
     <a class="btn btn-default btn-sm glyphicon glyphicon-cog btn-z-index" href="<?=Link::SourceUpdateCron($source->id)?>" title='Настройки'></a>
    </td>
    <td align='center' valign='middle'>
     <button id="btn_export_excel_cron_<?=$source->id?>" onclick="click_btn(<?=$source->id?>)" data-id="<?=$source->id?>" data-toggle="modal" data-target="#ModalExportExcel" title="Экспорт в Microsoft Excel" class='btn btn-default btn-sm glyphicon glyphicon-list-alt btn-z-index'></button>
    </td>
    <td align='center' valign='middle'>
     <button onclick="extract_cron(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-asterisk btn-z-index" title="Извлеч и сохранить"></button>
    </td>
    <td align='center' valign='middle'>
     <button onclick="delete_cron(this, <?=$source->id?>)" class='btn btn-default btn-sm glyphicon glyphicon-trash btn-z-index' title="Удалить"></button>
    </td>
   </tr>
<?php
}
?>
  </tbody>
 </table>
</div>
<div class="modal fade" id="ModalExportExcel" tabindex="-1" role="dialog" aria-labelledby="ModalExportExcelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Смещение</h4>
        <p>
          <input type="text" class="form-control" name="offset" value="0" />
        </p>
        <h4>Лимит</h4>
        <p>
          <input type="text" class="form-control" name="limit" value="10000" />
        </p>
        <p>
          <input onclick="export_excel_cron(this)" type="submit" class="btn btn-primary" value="Выполнить" /> 
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </p>
      </div>
    </div>
  </div>
</div><?php
    }

    private function viewmain () {
$this->title = "Ошибка";

?>
<div class="col-sm-12">
 <h3>Страница не определена</h3>
</div><?php
    }

    private function viewSourceUpdateCron () {
$this->title = "Источник";

?>
<?= $this->javascript('assets/js/source_update.js') ?>
<div id="attr_autocomplete">
 <div class="panel panel-default">
  <div class="panel-body attr_autocomplete_click" title="регулярное выражение (подмаска 1)">regulare</div>
  <div class="panel-body attr_autocomplete_click" title="регулярное выражение (удаление тегов)">regulare_strip_tags</div>
  <div class="panel-body attr_autocomplete_click" title="наименование элемента (магический)">tag</div>
  <div class="panel-body attr_autocomplete_click" title="html элемента (магический)">outertext</div>
  <div class="panel-body attr_autocomplete_click" title="html внутри элемента (магический)">innertext</div>
  <div class="panel-body attr_autocomplete_click" title="текст внутри элемента (магический)">plaintext</div> 
  <div class="panel-body attr_autocomplete_click">id</div>    
  <div class="panel-body attr_autocomplete_click">class</div>       
  <div class="panel-body attr_autocomplete_click">href</div>     
  <div class="panel-body attr_autocomplete_click">src</div>       
 </div>
</div>
<div id="name_autocomplete">
 <div class="panel panel-default">
  <div class="panel-body name_autocomplete_click" title="Адрес">address</div>
  <div class="panel-body name_autocomplete_click" title="Бренды">brands</div>
  <div class="panel-body name_autocomplete_click" title="Город">city</div>
  <div class="panel-body name_autocomplete_click" title="Контакты">contacts</div>
  <div class="panel-body name_autocomplete_click" title="Страна">country</div>
  <div class="panel-body name_autocomplete_click" title="Дата начала">date_begin</div>
  <div class="panel-body name_autocomplete_click" title="Дата окончания">date_end</div>
  <div class="panel-body name_autocomplete_click" title="Даты">dates</div>
  <div class="panel-body name_autocomplete_click" title="Описание">description</div>   
  <div class="panel-body name_autocomplete_click" title="Электронная почта">email</div>
  <div class="panel-body name_autocomplete_click" title="Факс">fax</div>
  <div class="panel-body name_autocomplete_click" title="Изображение">logo</div>  
  <div class="panel-body name_autocomplete_click" title="Цена ОТ">low_price</div>  
  <div class="panel-body name_autocomplete_click" title="Цена ДО">high_price</div>  
  <div class="panel-body name_autocomplete_click" title="Изображения">images</div>  
  <div class="panel-body name_autocomplete_click" title="Наименование">name</div>
  <div class="panel-body name_autocomplete_click" title="Время работы">opening_hour</div>
  <div class="panel-body name_autocomplete_click" title="Организатор">sponsor</div>
  <div class="panel-body name_autocomplete_click" title="Павильон">pavilion</div>
  <div class="panel-body name_autocomplete_click" title="Телефон">phone</div>
  <div class="panel-body name_autocomplete_click" title="Валюта">price_currency</div>
  <div class="panel-body name_autocomplete_click" title="Теги">tags</div>
  <div class="panel-body name_autocomplete_click" title="Наименование места">place_name</div>
  <div class="panel-body name_autocomplete_click" title="Почтовый индекс">postal_code</div>
  <div class="panel-body name_autocomplete_click" title="Цена">price</div>
  <div class="panel-body name_autocomplete_click" title="Стенд">stand</div>
  <div class="panel-body name_autocomplete_click" title="Короткое описание">title</div>
  <div class="panel-body name_autocomplete_click" title="Сссылка на сайт">website</div>
 </div>
</div>

<div class="col-sm-10">
 <form class="form-horizontal" action="<?php echo $this->request->id ? Link::SourceUpdateCron($this->request->id) : Link::SourceUpdateCron(); ?>" method="post">
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success"><?php if($this->request->id){ ?>Сохранить<? } else { ?>Добавить<?php }; ?></button>
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9"><span class="error_auth" id="error"><?=$this->request->error?></span></div>
  </div>
  <input type="hidden" name="cmd" id="cmd" value="SourceUpdateCron"/>
  <?php if($this->request->id){ ?>
  <input type="hidden" name="save" id="save" value="1"/> 
  <div class="form-group">
   <label for="id" class="col-sm-3 control-label">ID</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" value="<?=$this->request->id?>" id="id" placeholder="ID" disabled="" />
   </div>
  </div>
  <?php } else { ?>
  <input type="hidden" name="insert" id="insert" value="1"/> 
  <?php } ?>
  <div class="form-group">
   <label for="name" class="col-sm-3 control-label">Имя</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="name" value="<?=$this->request->name?>" id="name" placeholder="Имя" />
   </div>
  </div>
  <div class="form-group">
   <label for="visibility" class="col-sm-3 control-label">Видимость</label>
   <div class="col-sm-9">
    <select class="form-control" name="visibility">
     <option <?=$this->request->visibility=='1'?'selected':''?> value="1">Админ</option>
     <option <?=$this->request->visibility=='0'?'selected':''?> value="0">Всем</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="table_name" class="col-sm-3 control-label">Таблица</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="table_name" value="<?=$this->request->table_name?>" id="table_name" placeholder="Имя таблицы" />
   </div>
  </div>
  <div class="form-group">
   <label for="comment" class="col-sm-3 control-label">Комментарий</label>
   <div class="col-sm-9">
    <textarea class="form-control" name="comment" id="comment" placeholder=""><?=$this->request->comment?></textarea>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Ссылки</label>
   <div class="col-sm-9" id="dynamic_urls">
    <?php foreach($this->request->urls as $url){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="urls[]"><?=$url?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_urls', 'urls')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Элементы в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_element">
    <?php foreach($this->request->target_list_element as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="target_list_element[]"><?=$target?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-4 col-sm-8" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_target_list_element', 'target_list_element')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Целевые объекты в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_value">
    <?php foreach($this->request->target_list_value as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_triplet_1"><textarea class="form-control" name="target_list_value_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_triplet_2"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_value_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="field_dynamic_triplet_3"><textarea onfocus="this.select();lcs_name(this)" onclick="event.cancelBubble=true;this.select();lcs_name(this)" class="form-control" name="target_list_value_name[]"><?=$target['name']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_triplet('dynamic_target_list_value', 'target_list_value')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Объекты с ссылкой в списке</label>
   <div class="col-sm-9" id="dynamic_target_list_url">
    <?php foreach($this->request->target_list_url as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_one"><textarea class="form-control" name="target_list_url_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_two"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_url_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_couple('dynamic_target_list_url', 'target_list_url')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Объекты с ссылкой навигации</label>
   <div class="col-sm-9" id="dynamic_target_list_next">
    <?php foreach($this->request->target_list_next as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_one"><textarea class="form-control" name="target_list_next_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_two"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_list_next_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_couple('dynamic_target_list_next', 'target_list_next')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="begin_page" class="col-sm-3 control-label">Начальная страница списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="begin_page" value="<?=$this->request->begin_page?>" id="begin_page" placeholder="Номер" />
   </div>
  </div>
  <div class="form-group">
   <label for="end_page" class="col-sm-3 control-label">Конечная страница списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="end_page" value="<?=$this->request->end_page?>" id="end_page" placeholder="Номер" />
   </div>
  </div>
  <div class="form-group">
   <label for="key_page" class="col-sm-3 control-label">Параметр страницы списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="key_page" value="<?=$this->request->key_page?>" id="key_page" placeholder="Имя параметра" />
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Данные POST запроса списка</label>
   <div class="col-sm-9" id="dynamic_data_list">
    <?php foreach($this->request->data_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="data_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="data_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_data_list', 'data_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Cookie при запросе списка</label>
   <div class="col-sm-9" id="dynamic_cookie_list">
    <?php foreach($this->request->cookie_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="cookie_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="cookie_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_cookie_list', 'cookie_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Curlopt при запросе списка</label>
   <div class="col-sm-9" id="dynamic_curlopt_list">
    <?php foreach($this->request->curlopt_list as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="curlopt_list_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="curlopt_list_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_curlopt_list', 'curlopt_list')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="http_method_list" class="col-sm-3 control-label">HTTP метод при запросе списка</label>
   <div class="col-sm-9">
    <select class="form-control" name="http_method_list">
     <option <?=$this->request->http_method_list=='get'?'selected':''?> value="get">GET</option>
     <option <?=$this->request->http_method_list=='post'?'selected':''?> value="post">POST</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="inspect_duplicate_url_list" class="col-sm-3 control-label">Проверять дубликат ссылки списка</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_duplicate_url_list" id="inspect_duplicate_url_list">
     <option <?=$this->request->inspect_duplicate_url_list=='yes'?'selected':''?> value="get">Да</option>
     <option <?=$this->request->inspect_duplicate_url_list=='no'?'selected':''?> value="post">Нет</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="func_data_processing_list" class="col-sm-3 control-label">Функция обработки объекта списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_data_processing_list" value="<?=$this->request->func_data_processing_list?>" id="func_data_processing_list" />
   </div>
  </div>
  <div class="form-group">
   <label for="func_valid_url_list" class="col-sm-3 control-label">Функция подготовки ссылки списка</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_valid_url_list" value="<?=$this->request->func_valid_url_list?>" id="func_valid_url_list" placeholder="Принимает ссылку. Возвращает: boolean true/false (принять/отклонить), string замена ссылки, array замена всех значений ссылки." />
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Ссылки на страницу</label>
   <div class="col-sm-9" id="dynamic_page_urls">
    <?php foreach($this->request->page_urls as $url){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="page_urls[]"><?=$url?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_page_urls', 'page_urls')">добавиить</button>
   </div>
  </div>  
  <div class="form-group">
   <label class="col-sm-3 control-label">Элементы на странице</label>
   <div class="col-sm-9" id="dynamic_target_page_element">
    <?php foreach($this->request->target_page_element as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic"><textarea class="form-control" name="target_page_element[]"><?=$target?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field('dynamic_target_page_element', 'target_page_element')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Целевые объекты на странице</label>
   <div class="col-sm-9" id="dynamic_target_page_value">
    <?php foreach($this->request->target_page_value as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_triplet_1"><textarea class="form-control" name="target_page_value_phrase[]"><?=$target['phrase']?></textarea></td>
       <td class="field_dynamic_triplet_2"><textarea onfocus="this.select();lcs_attr(this)" onclick="event.cancelBubble=true;this.select();lcs_attr(this)" class="form-control" name="target_page_value_attribute[]"><?=$target['attribute']?></textarea></td>
       <td class="field_dynamic_triplet_3"><textarea onfocus="this.select();lcs_name(this)" onclick="event.cancelBubble=true;this.select();lcs_name(this)" class="form-control" name="target_page_value_name[]"><?=$target['name']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_triplet('dynamic_target_page_value', 'target_page_value')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Данные POST запроса страницы</label>
   <div class="col-sm-9" id="dynamic_data_page">
    <?php foreach($this->request->data_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="data_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="data_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_data_page', 'data_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Cookie при запросе страницы</label>
   <div class="col-sm-9" id="dynamic_cookie_page">
    <?php foreach($this->request->cookie_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="cookie_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="cookie_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_cookie_page', 'cookie_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label class="col-sm-3 control-label">Curlopt при запросе страницы</label>
   <div class="col-sm-9" id="dynamic_curlopt_page">
    <?php foreach($this->request->curlopt_page as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="curlopt_page_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="curlopt_page_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_curlopt_page', 'curlopt_page')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <label for="http_method_page" class="col-sm-3 control-label">HTTP метод при запросе страницы</label>
   <div class="col-sm-9">
    <select class="form-control" name="http_method_page">
     <option <?=$this->request->http_method_page=='get'?'selected':''?> value="get">GET</option>
     <option <?=$this->request->http_method_page=='post'?'selected':''?> value="post">POST</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label for="inspect_duplicate_url_page" class="col-sm-3 control-label">Проверять дубликат ссылки страницы</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_duplicate_url_page" id="inspect_duplicate_url_page">
     <option <?=$this->request->inspect_duplicate_url_page=='yes'?'selected':''?> value="get">Да</option>
     <option <?=$this->request->inspect_duplicate_url_page=='no'?'selected':''?> value="post">Нет</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="func_data_processing_page" class="col-sm-3 control-label">Функция обработки объекта страницы</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_data_processing_page" value="<?=$this->request->func_data_processing_page?>" id="func_data_processing_page" />
   </div>
  </div>
  <div class="form-group">
   <label for="func_valid_url_page" class="col-sm-3 control-label">Функция подготовки ссылки страницы</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="func_valid_url_page" value="<?=$this->request->func_valid_url_page?>" id="func_valid_url_page" placeholder="Принимает ссылку. Возвращает: boolean true/false (принять/отклонить), string замена ссылки, array замена всех значений ссылки." />
   </div>
  </div>
  <div class="form-group">
   <label for="table_page_urls" class="col-sm-3 control-label">Таблица с ссылками на страницу</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="table_page_urls" value="<?=$this->request->table_page_urls?>" id="table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="table_fixing" class="col-sm-3 control-label">Назначение таблицы</label>
   <div class="col-sm-9">
    <select class="form-control" name="table_fixing">
     <option value="0"></option>
     <option <?=$this->request->table_fixing=='1'?'selected':''?> value="1">Страница</option>
     <option <?=$this->request->table_fixing=='2'?'selected':''?> value="2">Список</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label for="column_table_page_urls" class="col-sm-3 control-label">Поле в таблице с ссылкой</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="column_table_page_urls" value="<?=$this->request->column_table_page_urls?>" id="column_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="start_table_page_urls" class="col-sm-3 control-label">Лимит (начало)</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="start_table_page_urls" value="<?=$this->request->start_table_page_urls?>" id="start_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="length_table_page_urls" class="col-sm-3 control-label">Лимит (количество строк)</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="length_table_page_urls" value="<?=$this->request->length_table_page_urls?>" id="length_table_page_urls" />
   </div>
  </div>
  <div class="form-group">
   <label for="fields_in_table_for_transmission" class="col-sm-3 control-label">Поля для передачи значений</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="fields_in_table_for_transmission" value="<?=$this->request->fields_in_table_for_transmission?>" id="fields_in_table_for_transmission" placeholder="Через пробел" />
   </div>
  </div>
  <div class="form-group">
   <label for="inspect_url_table" class="col-sm-3 control-label">Обработанные ссылки в таблице</label>
   <div class="col-sm-9">
    <select class="form-control" name="inspect_url_table" id="inspect_url_table">
     <option <?=$this->request->inspect_url_table=='1'?'selected':''?> value="1">Отмечать</option>
     <option <?=$this->request->inspect_url_table=='2'?'selected':''?> value="2">Ничего</option>
     <option <?=$this->request->inspect_url_table=='3'?'selected':''?> value="3">Удалять</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="amount_stream" class="col-sm-3 control-label">Количество потоков</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="amount_stream" value="<?=$this->request->amount_stream?>" id="amount_stream" />
   </div>
  </div>
  <div class="form-group">
   <label for="microtime_delay" class="col-sm-3 control-label">Задержка очередного запроса. (микросекунд)(1 c = 1000000 мкс)</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="microtime_delay" value="<?=$this->request->microtime_delay?>" id="microtime_delay" />
   </div>
  </div>
  <div class="form-group">
   <label for="status_control_insert" class="col-sm-3 control-label">Дубликаты</label>
   <div class="col-sm-9">
    <select class="form-control" name="status_control_insert">
     <option <?=$this->request->status_control_insert=='1'?'selected':''?> value="1">По значениям</option>
     <option <?=$this->request->status_control_insert=='2'?'selected':''?> value="2">Только ссылку</option>
     <option <?=$this->request->status_control_insert=='3'?'selected':''?> value="3">Перезаписывать</option>
     <option <?=$this->request->status_control_insert=='4'?'selected':''?> value="4">Обновлять</option>
     <option <?=$this->request->status_control_insert=='0'?'selected':''?> value="0">Не проверять</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label for="fields_control_insert" class="col-sm-3 control-label">Поля для дубликатов</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="fields_control_insert" value="<?=$this->request->fields_control_insert?>" id="fields_control_insert" placeholder="Если поле не заполнено то проверка по всем полям" />
   </div>
  </div>
  <div class="form-group">
   <label for="insert_type" class="col-sm-3 control-label">Добавлять записи в БД</label>
   <div class="col-sm-9">
    <select class="form-control" name="insert_type" id="insert_type">
     <option <?=$this->request->insert_type=='1'?'selected':''?> value="1">Одиночно</option>
     <option <?=$this->request->insert_type=='2'?'selected':''?> value="2">Пакетно - 2</option>
     <option <?=$this->request->insert_type=='3'?'selected':''?> value="3">Пакетно - 3</option>
     <option <?=$this->request->insert_type=='4'?'selected':''?> value="4">Пакетно - 4</option>
     <option <?=$this->request->insert_type=='5'?'selected':''?> value="5">Пакетно - 5</option>
     <option <?=$this->request->insert_type=='7'?'selected':''?> value="7">Пакетно - 7</option>
     <option <?=$this->request->insert_type=='10'?'selected':''?> value="10">Пакетно - 10</option>
     <option <?=$this->request->insert_type=='15'?'selected':''?> value="15">Пакетно - 15</option>
     <option <?=$this->request->insert_type=='20'?'selected':''?> value="20">Пакетно - 20</option>
     <option <?=$this->request->insert_type=='25'?'selected':''?> value="25">Пакетно - 25</option>
    </select>
   </div>
  </div>
  <div class="form-group">
   <label for="proxy" class="col-sm-3 control-label">Прокси</label>
   <div class="col-sm-9">
    <input type="text" class="form-control" name="proxy" value="<?=$this->request->proxy?>" id="proxy" placeholder="Файл у папке proxy. php возвращает массив нужного формата. txt - построчный список." />
   </div>
  </div>
  <div class="form-group">
   <label for="dom_library" class="col-sm-3 control-label">Библиотека DOM</label>
   <div class="col-sm-9">
    <select class="form-control" name="dom_library">
     <option <?=$this->request->dom_library=='2'?'selected':''?> value="2">phpQuery</option>
     <option <?=$this->request->dom_library=='1'?'selected':''?> value="1">SimpleHtmlDom</option>
    </select>
   </div>
  </div> 
  <div class="form-group">
   <label class="col-sm-3 control-label">Значения по умолчанию</label>
   <div class="col-sm-9" id="dynamic_default_values">
    <?php foreach($this->request->default_values as $target){ ?>
    <div class="div_dynamic">
     <table>
      <tr>
       <td class="field_dynamic_key"><textarea class="form-control" name="default_values_key[]"><?=$target['key']?></textarea></td>
       <td class="field_dynamic_value"><textarea class="form-control" name="default_values_value[]"><?=$target['value']?></textarea></td>
       <td class="action_dynamic"><?= $this->img('assets/img/delete.png',['width'=>'16', 'onclick' => 'delete_field(this)']) ?></td>
      </tr>
     </table>
    </div>
    <?php } ?>
   </div>
   <div class="col-sm-offset-3 col-sm-9" align='right'>
    <button type="button" class="btn btn-default btn-xs" onclick="add_field_var('dynamic_default_values', 'default_values')">добавиить</button>
   </div>
  </div>
  <div class="form-group">
   <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" class="btn btn-success"><?php if($this->request->id){ ?>Сохранить<? } else { ?>Добавить<?php }; ?></button>
   </div>
  </div>
 </form>
</div>
<div class="col-sm-2"></div><?php
    }

    private function viewSources () {
$this->title = "Источники";

$sources = $this->request->getObject('sources');

?>
<?= $this->javascript('assets/js/sources_script.js') ?>
<?= $this->img('assets/img/full_preloader.gif', ['height' => '1', 'style' => 'display: none;']) ?>
<?= $this->img('assets/img/check.png', ['height' => '1', 'style' => 'display: none;']) ?>
<?= $this->img('assets/img/cancel.png', ['height' => '1', 'style' => 'display: none;']) ?>
<div class="text">
  <a class="btn btn-default" href="<?=Link::SourceUpdate()?>">Добавить</a>
  <span><button type="button" onclick="block_all(this)" class="btn btn-default">Блокировать Все</button></span>
  <button class="btn btn-default" type="button" onclick="$('#sources_list').submit()">Поиск</button>
</div>
<div class="text">
  <?php
    echo getPaginator(\workup\record\SourceRecord::getCount(), \workup\record\SourceRecord::getLimit(), "?cmd=Sources".($this->request->params?'&'.http_build_query($this->request->params):''), "&");
    ?>
</div>
<div style="float: right;">
  Всего&nbsp;<?=$this->request->count_all_sources?>
</div>
<div class="row">
  <table class="table table-striped list_table_source">
    <thead>
      <form id="sources_list" action="<?=Link::ListSources().($this->request->params_sort?'&'.http_build_query($this->request->params_sort):'')?>" method="post">
        
        <tr bgcolor='#E6E6FA'>
          <td align='center' valign='middle'style="min-width: 70px;">
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" style="height: 35px;" name="search[check_cron]"><?=$this->request->search?isset($this->request->search['check_cron'])?$this->request->search['check_cron']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='check_cron'?><?=$this->request->sort=='check_cron'&&!$this->request->order?'&order=DESC':''?>">cron</a>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='visibility'?><?=$this->request->sort=='visibility'&&!$this->request->order?'&order=DESC':''?>"><span class="glyphicon glyphicon-star-empty btn-z-index" style="font-size: 14px;"></span></a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='check_cron'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td align='center' valign='middle'>
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" style="height: 35px;" name="search[id]"><?=$this->request->search?isset($this->request->search['id'])?$this->request->search['id']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='id'?><?=$this->request->sort=='id'&&!$this->request->order?'&order=DESC':''?>">ID</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='id'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td valign='middle' title="По умолчанию поск по полю `name`. Для поиска по другому полю введите 'field_name->text_search'. Возможные значения `field_name`: name, comment, table_name, urls">
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" style="height: 35px;" name="search[search]"><?=$this->request->search?isset($this->request->search['search'])?$this->request->search['search']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='name'?><?=$this->request->sort=='name'&&!$this->request->order?'&order=DESC':''?>">Источник</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='name'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td align='center'>
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" id="created_at" style="height: 35px;" name="search[created_at]"><?=$this->request->search?isset($this->request->search['created_at'])?$this->request->search['created_at']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='created_at'?><?=$this->request->sort=='created_at'&&!$this->request->order?'&order=DESC':''?>">Создано</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='created_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" id="begin_parse_at" style="height: 35px;" name="search[begin_parse_at]"><?=$this->request->search?isset($this->request->search['begin_parse_at'])?$this->request->search['begin_parse_at']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='begin_parse_at'?><?=$this->request->sort=='begin_parse_at'&&!$this->request->order?'&order=DESC':''?>">Запуск</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='begin_parse_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td align='center'>
            <table>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" id="last_write_at" style="height: 35px;" name="search[last_write_at]"><?=$this->request->search?isset($this->request->search['last_write_at'])?$this->request->search['last_write_at']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='last_write_at'?><?=$this->request->sort=='last_write_at'&&!$this->request->order?'&order=DESC':''?>">Парсинг</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='last_write_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
              <tr>
                <td colspan="2" align='center'>
                  <textarea class="form-control" id="last_import_at" style="height: 35px;" name="search[last_import_at]"><?=$this->request->search?isset($this->request->search['last_import_at'])?$this->request->search['last_import_at']:'':''?></textarea>
                </td>
              </tr>
              <tr>
                <td align='center'>
                  <a href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='last_import_at'?><?=$this->request->sort=='last_import_at'&&!$this->request->order?'&order=DESC':''?>">Импорт</a>
                </td>
                <td align='center'>
                  <div><?=$this->request->sort=='last_import_at'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
                </td>
              </tr>
            </table>
          </td>
          <td align='center'>
            <table class="table table-hover" style="margin: 0px; background-color: inherit; font-size: 12px;">
              <tr>
                <td title="Количество запусков" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_process'?><?=$this->request->sort=='count_process'&&!$this->request->order?'&order=DESC':''?>">c_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех запросов" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_process'?><?=$this->request->sort=='count_all_process'&&!$this->request->order?'&order=DESC':''?>">c_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех записаных в бд" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_write'?><?=$this->request->sort=='count_all_write'&&!$this->request->order?'&order=DESC':''?>">c_a_w</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_all_write'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех успешных запросов" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_success_all_process'?><?=$this->request->sort=='count_success_all_process'&&!$this->request->order?'&order=DESC':''?>">c_s_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_success_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех ошибочных запросов" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_error_all_process'?><?=$this->request->sort=='count_error_all_process'&&!$this->request->order?'&order=DESC':''?>">c_e_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_error_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее время на запросы" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_all_requests'?><?=$this->request->sort=='time_all_requests'&&!$this->request->order?'&order=DESC':''?>">t_a_r</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_all_requests'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее время обработки" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_all_process'?><?=$this->request->sort=='time_all_process'&&!$this->request->order?'&order=DESC':''?>">t_a_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_all_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее CP" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='cp_all'?><?=$this->request->sort=='cp_all'&&!$this->request->order?'&order=DESC':''?>">cp_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='cp_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общая память MB" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='memory_all'?><?=$this->request->sort=='memory_all'&&!$this->request->order?'&order=DESC':''?>">m_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='memory_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Общее количество обращений к Базе Данных" style="border-top: none; text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_all_query_to_bd'?><?=$this->request->sort=='count_all_query_to_bd'&&!$this->request->order?'&order=DESC':''?>">c_a_q_t_b</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_all_query_to_bd'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td title="Общее время выполнения" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_process_life'?><?=$this->request->sort=='time_process_life'&&!$this->request->order?'&order=DESC':''?>">t_p_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_process_life'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество запросов при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_process'?><?=$this->request->sort=='count_last_process'&&!$this->request->order?'&order=DESC':''?>">c_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество записаных в бд при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_write'?><?=$this->request->sort=='count_last_write'&&!$this->request->order?'&order=DESC':''?>">c_l_w</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_last_write'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество успешных запросов при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_success_last_process'?><?=$this->request->sort=='count_success_last_process'&&!$this->request->order?'&order=DESC':''?>">c_s_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_success_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество ошибочных запросов при последнем запуске" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_error_last_process'?><?=$this->request->sort=='count_error_last_process'&&!$this->request->order?'&order=DESC':''?>">c_e_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_error_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Врямя на запросы последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_last_requests'?><?=$this->request->sort=='time_last_requests'&&!$this->request->order?'&order=DESC':''?>">t_l_r</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_last_requests'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Время обработки последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='time_last_process'?><?=$this->request->sort=='time_last_process'&&!$this->request->order?'&order=DESC':''?>">t_l_p</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='time_last_process'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="CP последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='cp_last'?><?=$this->request->sort=='cp_last'&&!$this->request->order?'&order=DESC':''?>">cp_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='cp_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Память последего запуска MB" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='memory_last'?><?=$this->request->sort=='memory_last'&&!$this->request->order?'&order=DESC':''?>">m_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='memory_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество обращений к Базе данных Последнего запуска" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_last_query_to_bd'?><?=$this->request->sort=='count_last_query_to_bd'&&!$this->request->order?'&order=DESC':''?>">c_l_q_t_b</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_last_query_to_bd'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td title="Количество всех прочитанных при импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_all'?><?=$this->request->sort=='count_import_all'&&!$this->request->order?'&order=DESC':''?>">c_i_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество прочитанных при последнем импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_last'?><?=$this->request->sort=='count_import_last'&&!$this->request->order?'&order=DESC':''?>">c_i_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех успешных при импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_success_all'?><?=$this->request->sort=='count_import_success_all'&&!$this->request->order?'&order=DESC':''?>">c_i_s_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_success_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество всех ошибочных при импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_error_all'?><?=$this->request->sort=='count_import_error_all'&&!$this->request->order?'&order=DESC':''?>">c_i_e_a</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_error_all'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество успешных при последнем импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_success_last'?><?=$this->request->sort=='count_import_success_last'&&!$this->request->order?'&order=DESC':''?>">c_i_s_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_success_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество успешных при последнем импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='count_import_error_last'?><?=$this->request->sort=='count_import_error_last'&&!$this->request->order?'&order=DESC':''?>">c_i_e_l</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='count_import_error_last'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Средний бал" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='import_rate'?><?=$this->request->sort=='import_rate'&&!$this->request->order?'&order=DESC':''?>">i_rate</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='import_rate'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество при последнем успешном импорте" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='last_import_count'?><?=$this->request->sort=='last_import_count'&&!$this->request->order?'&order=DESC':''?>">l_i_c</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='last_import_count'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td title="Количество при последнем успешном парсинге" style="text-align: center;">
                  <table>
                    <tr>
                      <td align='center'>
                        <a style="font-size: 12px;" href="<?=Link::ListSources().($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?='last_write_count'?><?=$this->request->sort=='last_write_count'&&!$this->request->order?'&order=DESC':''?>">l_w_c</a>
                      </td>
                      <td align='center'>
                        <div><?=$this->request->sort=='last_write_count'?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'12']):$this->img('assets/img/down.png',['width'=>'12']):''?></div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
          <td align='center' colspan="8">
            Действия
          </td>
        </tr>
      </form>
    </thead>
    <tbody>
      <?php
        foreach($sources as $source) { 
        ?>
      <tr>
        <td align='center' valign='middle'>
          <input onchange="changeCheckCron(this)" type="checkbox" data-id="<?=$source->id?>" <?=$source->check_cron?'checked=""':''?> />
          <?=$source->visibility=='0'?'<span class="glyphicon glyphicon-star-empty btn-z-index" style="font-size: 14px;"></span>':''?>
        </td>
        <td align='center' valign='middle'>
          <?=$source->id?>
        </td>
        <td valign='middle'>
          <a class="edit_row_cel" href="<?=Link::SourceData($source->id)?>"><strong><?=$source->name?></strong></a> <? if (!empty($source->comment)) echo "<span class='glyphicon glyphicon-comment btn-z-index' title='" . $source->comment . "'></span>"; ?>
          <div style='font-size: 80%; margin-top: 10px'>
            <span>БД: <?=$source->table_name?></span>
            <br />
            <span><?=implode('<br />', array_map(function($value){ return str_limit($value, 50); }, array_slice($source->_urls, 0, 5)))?></span>
          </div>
        </td>
        <td align='center' style="font-size: 12px;" valign='middle'>
          <abbr class="timeago" title="<?=date('d.m.Y H:i', $source->created_at)?>" ><?=date('d.m.Y', $source->created_at)?></abbr>
          <abbr class="timeago" title="<?=date('d.m.Y H:i', $source->begin_parse_at)?>" ><?=date('d.m.Y', $source->begin_parse_at)?></abbr>
        </td>
        <td align='center' style="font-size: 12px;" valign='middle'>
          <abbr class="timeago" title="<?=$source->last_write_at ? date('d.m.Y H:i', $source->last_write_at) : '00:00:0000'?>" ><?=$source->last_write_at ? date('d.m.Y', $source->last_write_at) : '00:00:0000'?></abbr>
          <abbr class="timeago" title="<?=$source->last_import_at ? date('d.m.Y H:i', $source->last_import_at) : '00:00:0000'?>" ><?=$source->last_import_at ? date('d.m.Y', $source->last_import_at) : '00:00:0000'?></abbr>
        </td>
        <td valign='middle'>
          <table class="table table-hover" style="margin: 0px; background-color: inherit; font-size: 12px;">
            <tr>
              <td title="Количество запусков" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_process?></span></td>
              <td title="Количество всех запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_all_process?></span></td>
              <td title="Количество всех записаных в бд" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_all_write?></span></td>
              <td title="Количество всех успешных запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_success_all_process?></span></td>
              <td title="Количество всех ошибочных запросов" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=$source->count_error_all_process?></span></td>
              <td title="Общее время на запросы" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->time_all_requests, 2)?></span></td>
              <td title="Общее время обработки" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->time_all_process, 2)?></span></td>
              <td title="Общее CP" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->cp_all, 2)?></span></td>
              <td title="Общая память" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->memory_all, 2)?></span></td>
              <td title="Общее количество обращений к Базе Данных" style="border-top: none; text-align: center;"><span style="cursor: default;"><?=round($source->count_all_query_to_bd, 2)?></span></td>
            </tr>
            <tr>
              <td title="Общее время выполнения" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_process_life, 2)?></span></td>
              <td title="Количество запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_last_process?></span></td>
              <td title="Количество записаных в бд при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_last_write?></span></td>
              <td title="Количество успешных запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_success_last_process?></span></td>
              <td title="Количество ошибочных запросов при последнем запуске" style="text-align: center;"><span style="cursor: default;"><?=$source->count_error_last_process?></span></td>
              <td title="Врямя на запросы последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_last_requests, 2)?></span></td>
              <td title="Время обработки последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->time_last_process, 2)?></span></td>
              <td title="CP последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->cp_last, 2)?></span></td>
              <td title="Память последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->memory_last, 2)?></span></td>
              <td title="Количество обращений к Базе данных Последнего запуска" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_last_query_to_bd, 2)?></span></td>
            </tr>
            <tr>
              <td title="Количество всех прочитанных при импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_all, 2)?></span></td>
              <td title="Количество прочитанных при последнем импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_last, 2)?></span></td>
              <td title="Количество всех успешных при импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_success_all, 2)?></span></td>
              <td title="Количество всех ошибочных при импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_error_all, 2)?></span></td>
              <td title="Количество всех успешных при последнем импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_success_last, 2)?></span></td>
              <td title="Количество всех ошибочных при последнем импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->count_import_error_last, 2)?></span></td>
              <td title="Средний бал" style="text-align: center;"><span style="cursor: default;"><?=round($source->import_rate, 2)?></span></td>
              <td title="Количество при последнем успешном импорте" style="text-align: center;"><span style="cursor: default;"><?=round($source->last_import_count, 2)?></span></td>
              <td title="Количество при последнем успешном парсинге" style="text-align: center;"><span style="cursor: default;"><?=round($source->last_write_count, 2)?></span></td>
            </tr>
          </table>
        </td>
        <td>
          <table class="tbl-control-buttons">
            <tr>
              <td align='center' valign='middle'>
                <button onclick="parse_source(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-play btn-z-index" title='Запустить'></button>
              </td>
              <td align='center' valign='middle'>
                <a class="btn btn-default btn-sm glyphicon glyphicon-cog btn-z-index" href="<?=Link::SourceUpdate($source->id)?>" title='Настройки'></a>
              </td>
              <td align='center' valign='middle'>
                <button onclick="add_cron(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-plus btn-z-index" title="Cron"></button>
              </td>
              <td align='center' valign='middle'>
                <button onclick="extract(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-asterisk btn-z-index" title="Извлеч и сохранить"></button>
              </td>
            </tr>
            <tr>
              <td align='center' valign='middle'>
                <button onclick="parse_blocking(this, <?=$source->id?>)" class="btn btn-default btn-sm glyphicon glyphicon-remove btn-z-index" title="Остановить Процесс"></button>
              </td>
              <td align='center' valign='middle'>
                <button id="btn_export_excel_<?=$source->id?>" onclick="click_btn(<?=$source->id?>)" data-id="<?=$source->id?>" data-toggle="modal" data-target="#ModalExportExcel" title="Экспорт в Microsoft Excel" class='btn btn-default btn-sm glyphicon glyphicon-list-alt btn-z-index'></button>
              </td>
              <td align='center' valign='middle'>
                <button id="btn_export_google_sheets_<?=$source->id?>" onclick="click_btn(<?=$source->id?>)" data-id="<?=$source->id?>" data-toggle="modal" data-target="#ModalExportGoogleSheets" title="Экспорт в Google Sheets" class='btn btn-default btn-sm glyphicon glyphicon-th-list btn-z-index'></button>
              </td>
              <td align='center' valign='middle'>
                <button onclick="delete_source(this, <?=$source->id?>)" class='btn btn-default btn-sm glyphicon glyphicon-trash btn-z-index' title="Удалить"></button>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <?php
        }
        ?>
    </tbody>
  </table>
</div>
<div class="modal fade" id="ModalExportGoogleSheets" tabindex="-1" role="dialog" aria-labelledby="ModalExportGoogleSheetsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Смещение</h4>
        <p>
          <input type="text" class="form-control" name="offset" value="0" />
        </p>
        <h4>Лимит</h4>
        <p>
          <input type="text" class="form-control" name="limit" value="10000" />
        </p>
        <h4>ID Таблицы</h4>
        <p>
          <input type="text" class="form-control" name="id_sheet" value="" placeholder="Если не указано то будет создана новая таблица" />
        </p>
        <p>
          <input onclick="export_google_sheets(this)" type="submit" class="btn btn-primary" value="Выполнить" /> 
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="ModalExportExcel" tabindex="-1" role="dialog" aria-labelledby="ModalExportExcelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Смещение</h4>
        <p>
          <input type="text" class="form-control" name="offset" value="0" />
        </p>
        <h4>Лимит</h4>
        <p>
          <input type="text" class="form-control" name="limit" value="10000" />
        </p>
        <p>
          <input onclick="export_excel(this)" type="submit" class="btn btn-primary" value="Выполнить" /> 
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </p>
      </div>
    </div>
  </div>
</div><?php
    }

    private function viewSourceDataCron () {
$this->title = "Источники";

$source = $this->request->getObject('source');
$result = $source->_result;
$sourceData = $this->request->getObject('source_data');

?>
<?= $this->javascript('assets/js/source_data.js') ?>

<div class="col-sm-12">
 <h2><?=$source->name?></h2>
</div>

<div class="col-sm-4">
  ID: <strong><?=$source->id?></strong>
  <br />Таблица: <strong><a href="<?=Link::SourceDataCron($source->id)?>"><?=$source->table_name?></a></strong>
  <br />Списков: <strong><?=isset($result['count_urls_list'])?$result['count_urls_list']:'null'?></strong>
  <br />Страниц: <strong><?=isset($result['count_urls_page'])?$result['count_urls_page']:'null'?></strong>
</div>
<div class="col-sm-8">
  Создано: <strong><?=date('d.m.Y H:i', $source->created_at)?></strong>
  <br />Запускалось: <strong><?=$source->begin_parse_at?date('d.m.Y H:i:s', $source->begin_parse_at):'-'?></strong>
  <br />Завершено: <strong><?=$source->end_parse_at?date('d.m.Y H:i:s', $source->end_parse_at):'-'?></strong>
  <br /><?=isset($result['last_total_time'])?"Обрабатывалось: <strong>".round($result['last_total_time'], 4)."</strong>":'&nbsp;'?>
</div>

<div class="col-sm-12">
 <div class="col-sm-1" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_details" class="btn btn-default btn-xs">Отчеты</button>
 </div>
 
 <?php if(isset($result['inf_last_request']) && is_array($result['inf_last_request'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_last_request" class="btn btn-default btn-xs">Дебаг: О последнем запросе списка</button>
 </div>
 <?php } ?>
 
 <?php if(isset($result['inf_last_request_page']) && is_array($result['inf_last_request_page'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_last_request_page" class="btn btn-default btn-xs">Дебаг: О последнем запросе страницы</button>
 </div>
 <?php } ?>
 
 <?php if(isset($result['inf_illegal_request']) && is_array($result['inf_illegal_request'])){ ?>
 <div class="col-sm-3" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <button id="btn_inf_illegal_request" class="btn btn-default btn-xs">Дебаг: Не корректный запрос</button>
 </div>
 <?php } ?>
 
 <div class="col-sm-2" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <span id="btn_clear_table" class="btn btn-default btn-xs" href="<?=Link::ClearSourceDataCron($source->id).($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>">Удалить <?=\workup\record\SourceDataRecord::getCount()?> записи</span>
 </div>
 <div class="col-sm-2" style="padding: 0px; padding-top: 5px; padding-bottom: 5px; padding-right: 15px;">
  <span id="btn_drop_table" class="btn btn-default btn-xs" data-href="<?=Link::DropSourceTableCron($source->id)?>">Удалить таблицу</span>
 </div>
</div>

<div style="display: none;" id="body_inf_details">
 <div class="col-sm-3"> 
  Кол. всех запросов: <strong><?=$source->count_all_process?></strong>
  <br />Кол. запросов при посл. запуске: <strong><?=$source->count_last_process?></strong>
  <br />Кол. всех записаных в бд: <strong><?=$source->count_all_write?></strong>
  <br />Кол. записаных в бд при посл. запуске: <strong><?=$source->count_last_write?></strong>
 </div>
 <div class="col-sm-3"> 
  Кол. всех успешных запросов: <strong><?=$source->count_success_all_process?></strong>
  <br />Кол. всех ошибочных запросов: <strong><?=$source->count_error_all_process?></strong>
  <br />Кол. успешных запросов при посл. запуске: <strong><?=$source->count_success_last_process?></strong>
  <br />Кол. ошибочных запросов при посл. запуске: <strong><?=$source->count_error_last_process?></strong>
 </div>
 <div class="col-sm-3"> 
  Общее время обработки: <strong><?=round($source->time_all_process, 2)?></strong>
  <br />Время обработки посл. запуска: <strong><?=round($source->time_last_process, 2)?></strong>
  <br />Общее время на запросы: <strong><?=round($source->time_all_requests, 2)?></strong>
  <br />Врямя на запросы посл. запуска: <strong><?=round($source->time_last_requests, 2)?></strong>
 </div>
 <div class="col-sm-3"> 
  Общее CP: <strong><?=round($source->cp_all, 2)?></strong>
  <br />CP последнего запуска: <strong><?=round($source->cp_last, 2)?></strong>
  <br />Общая память: <strong><?=round($source->memory_all, 2)?></strong>
  <br />Память последнего запуска: <strong><?=round($source->memory_last, 2)?></strong>
 </div>
</div>

<?php if(isset($result['inf_illegal_request']) && is_array($result['inf_illegal_request'])){ ?>
<div class="col-sm-12" id="body_inf_illegal_request" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_illegal_request'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<?php if(isset($result['inf_last_request_page']) && is_array($result['inf_last_request_page'])){ ?>
<div class="col-sm-12" id="body_inf_last_request_page" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_last_request_page'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<?php if(isset($result['inf_last_request']) && is_array($result['inf_last_request'])){ ?>
<div class="col-sm-12" id="body_inf_last_request" style="display: none; float: left;">
 <table class="table table-striped list_table_source">
  <?php foreach($result['inf_last_request'] as $key => $value){ ?> 
  <tr>
   <th><?=$key?></th>
   <td>&nbsp;</td>
   <td>
    <?php if(is_array($value)){ ?>
     <div class="col-sm-12">
      <table class="table table-striped list_table_source">
       <?php foreach($value as $k => $v){ if(is_string($value)){ ?> 
        <th><?=$k?></th>
        <td>&nbsp;</td>
        <th><?=$v?></th>
       <?php } } ?>
      </table>
     </div>
    <?php } else { ?>
    <?=$value?>
    <?php } ?>
   </td>
  </tr>
  <?php } ?>
 </table>
</div>
<?php } ?>

<div class="col-sm-9">
<?php
echo getPaginator(\workup\record\SourceDataRecord::getCount(), \workup\record\SourceDataRecord::getLimit(), "?cmd=SourceData&id_source_data=".$source->id.($this->request->params?'&'.http_build_query($this->request->params):''), "&");
?>
</div>
<div class="col-sm-3" align='right'>
 Всего&nbsp;<?=\workup\record\SourceDataRecord::getCount()?>
</div>
<div class="col-sm-12">
 <table class="table table-striped list_table_source">
  <thead>
   <form action="<?=Link::SourceDataCron($source->id).($this->request->params_sort?'&'.http_build_query($this->request->params_sort):'')?>" method="post">
   <button class="btn btn-default btn-xs" type="submit">Поиск</button>
    <tr bgcolor='#E6E6FA'>
     <?php
     foreach( $this->request->columns as $key => $value ) { 
     ?>
     <td align='center'>
      <table>
       <tr>
        <td colspan="2" align='center'>
         <textarea class="form-control" style="height: 35px;" name="search[<?=$key?>]"><?=$this->request->search?isset($this->request->search[$key])?$this->request->search[$key]:'':''?></textarea>
        </td>
       </tr>
       <tr>
        <td align='center'>
         <a href="<?=Link::SourceDataCron($source->id).($this->request->params_search?'&'.http_build_query($this->request->params_search):'')?>&sort=<?=$key?><?=$this->request->sort==$key&&!$this->request->order?'&order=DESC':''?>"><?=$key?></a>
        </td>
        <td align='center'>
         <div><?=$this->request->sort==$key?$this->request->order=='DESC'?$this->img('assets/img/up.png',['width'=>'16']):$this->img('assets/img/down.png',['width'=>'16']):''?></div>
        </td>
       </tr>
      </table>
     </td>
     <?php
     }
     ?>
    </tr>
   </form>
  </thead> 
  <tbody>
  <?php
  foreach( $sourceData as $row ) { 
  ?>
   <tr>
    <?php
    foreach( $this->request->columns as $key => $value ) { 
    ?>
    <td align='center'>
     <?=$row->$key?>
    </td>
    <?php
    }
    ?>
   </tr>
  <?php
  }
  ?>
  </tbody>
 </table>
</div>
<div class="col-sm-9">
<?php
echo getPaginator(\workup\record\SourceDataRecord::getCount(), \workup\record\SourceDataRecord::getLimit(), "?cmd=SourceData&id_source_data=".$source->id.($this->request->params?'&'.http_build_query($this->request->params):''), "&");
?>
</div><?php
    }
}

namespace main;

class View
{
    private $request;

    public $title = 'Парсер';

    use IncludeFile;

    public function __construct()
    {
        $this->request = \workup\base\ViewHelper::getRequest();
    }

    public function render($view)
    {
        ob_start();

        $this->view($view);

        $out_body = ob_get_clean();

        ob_start();

        $this->view('top');

        $out_top = ob_get_clean();

        ob_start();

        $this->view('bottom');

        $out_bottom = ob_get_clean();

        echo $out_top;
        echo $out_body;
        echo $out_bottom;
    }

    private function view($view)
    {
        if (\workup\App::config('WORDPRESS')) {
            switch ($view) {
                case 'top':
                    $view = 'topWordpress';
                    break;
                case 'bottom':
                    $view = 'bottomWordpress';
                    break;
                case 'Sources':
                    $view = 'SourcesWordpress';
                    break;
                case 'SourceUpdate':
                    $view = 'SourceUpdateWordpress';
                    break;
                case 'SourceData':
                    $view = 'SourceDataWordpres';
                    break;
            }
        }

        $this->includeView($view);
    }

    private function favicon($file)
    {
        $file = $this->fileUrl($file);
        if ($file) {
            echo '<link rel="icon" type="image/png" href="' . $file . '" />';
        }
    }

    private function css($file)
    {
        $file = $this->fileUrl($file);
        if ($file) {
            $this->includeCss($file, '<link rel="stylesheet" href="' . $file . '" />');
        }

    }

    private function javascript($file)
    {
        $file = $this->fileUrl($file);
        if ($file) {
            $this->includeJavascript($file, '<script type="text/javascript" src="' . $file .
                '"></script>');
        }
    }

    private function img($file, $options = [])
    {
        $file = $this->fileUrl($file);
        if ($file) {
            $attrs = [];

            foreach ($options as $key => $value) {
                $attrs[] = str_replace(["'", '"'], '', $key) . '="' . str_replace(["'", '"'], '',
                    $value) . '"';
            }

            echo '<img src="' . $file . '" ' . implode(' ', $attrs) . '" />';
        }
    }

    private function fileUrl($file)
    {
        if (\workup\App::config('WORDPRESS')) {
            $file = ltrim($file, './\\ ');
            $file = \plugins_url('wp-parser/' . $file);
        }

        return $file;
    }
}
