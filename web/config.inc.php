<?php

///////////////////////////////////////////////////////////////////////
// установка часового пояса
date_default_timezone_set("Europe/Moscow");
//setlocale (LC_ALL, 'ru_RU.UTF-8');

///////////////////////////////////////////////////////////////////////
$db_host = 'localhost'; // host базы
$db_name = 'china'; // название базы
$db_user = 'root'; // логин
$db_pasw = ''; // пароль

$db_table = 'parser_china';
$db_table_results = 'parser_alibaba_results';
$db_table_reference = 'parser_china_reference';
$db_table_conf = 'parser_china_conf';
$db_table_conf_list = 'parser_china_conf_list';
$db_table_urls = 'parser_china_urls';
$db_table_users = 'parser_china_users';
$db_table_stat = 'parser_china_stat';
$db_table_parser_stat = 'parser_china_parser_stat';
$db_table_deleted = 'parser_china_deleted';

$db_parser_id = 'alibaba';

$globalmain = [];

$reference_export_path = dirname(__file__) . "\\..\\reference.export.xlsx";
///////////////////////////////////////////////////////////////////////
$delimiter = ';';
$ln = "\r\n";
if (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) && strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],
    'Mac') !== false) {
    $delimiter = ',';
    $ln = "\r";
}
///////////////////////////////////////////////////////////////////////
$contents_dir = dirname(__file__) . '/contents/';
$exit_flg = 'exit.flg';
$photo_dir = 'contents/images/';
$photo_small_dir = 'contents/images_small/';
$img_w = 100;
$img_h = 100;
///////////////////////////////////////////////////////////////////////
$admin_level = 1;
$user_level_list[1] = 'admin';
///////////////////////////////////////////////////////////////////////
$pager_count = 2; // кол-во страниц ссылками до и после текущей
$pager_divider = ''; // разделитель между номерами страниц
///////////////////////////////////////////////////////////////////////
$parser_id = 'alibaba';
$result['login'] = isset($_SESSION['authorized']) ? $_SESSION['authorized'] : '';
$result['copyright'] =
    ' © <a href="http://weblands.ru" target="_blank">weblands.ru</a> 2021  ';
$result['title'] = "Система управления документами $parser_id";


$delete_list = array();
$delete_list['brand/famous'] = 'brand/famous';
$delete_list['brand/famous/abandoned'] = 'brand/famous/abandoned';
$delete_list['Не найденные'] = 'Не найденные';

$export_list = array();
$export_list['Найденные'] = 'Найденные';


///////////////////////////////////////////////////////////////////////
include_once (dirname(__file__) . "/lib/function.php7.php");
include_once (dirname(__file__) . "/lib/mysql_base.inc.php7.php");
///////////////////////////////////////////////////////////////////////

if (true || !isset($is_parser_alibaba)) {
    $db = db_connect($db_name, $db_host, $db_user, $db_pasw);
    mysqli_query($db, "SET CHARACTER SET utf8mb4");
    mysqli_query($db, "SET NAMES utf8mb4");
    //mysqli_query($db, "SET CHARACTER SET utf8");
    //mysqli_query($db, "SET NAMES utf8");
    //mysql_query("SET sql_mode=''");
    //mysql_query("SET time_zone = 'MSK'");
    //mysql_query("SET time_zone = '+03:00'");
    //mysql_query("SET TIME ZONE '+03:00'");
    //mysql_query("SET TIME ZONE 'MSK'");

    if (!mysql_table_seek($db, $db_table, $db_name)) {
        $sql = trim(file_get_contents('sql/create.sql'));
        db_sql_query($db, $sql, true);
        exit("Table created ($db_table), reload page!");
    }
    if (file_exists('sql/update.sql')) {
        $sql = trim(file_get_contents('sql/update.sql'));
        db_sql_query($db, $sql, true);
        rename('sql/update.sql', 'sql/update.' . date("Y.m.d.H.i") . '.sql');
        exit("DB update, reload page!");
    }
    ///////////////////////////////////////////////////////////////////////
    if (!isset($_POST['setup_save'])) {
        foreach ($_GET as $key => $val)
            if (!is_array($_GET[$key]))
                $_GET[$key] = strip_tags($_GET[$key]);
        foreach ($_POST as $key => $val)
            if (!is_array($_POST[$key]))
                $_POST[$key] = strip_tags($_POST[$key]);
        foreach ($_REQUEST as $key => $val)
            if (!is_array($_REQUEST[$key]))
                $_REQUEST[$key] = strip_tags($_REQUEST[$key]);
    }

    if (get_magic_quotes_gpc()) {
        $_GET = stripslashes_array($_GET);
        $_POST = stripslashes_array($_POST);
        $_REQUEST = stripslashes_array($_REQUEST);
        $_COOKIE = stripslashes_array($_COOKIE);
    }
    ///////////////////////////////////////////////////////////////////////
    
    mysqli_query($db, "SET SQL_MODE='ALLOW_INVALID_DATES'");
    
    $config_name = '';
        
    if (file_exists(__dir__ . '/data/config_name')) {
        $config_name = trim(file_get_contents(__dir__ . '/data/config_name'));
    }
    
    $resmysqli = mysqli_query($db, 'SELECT * FROM `'.$db_table_conf_list.'` WHERE `type` = \'save_to_table_alibaba\' and `name` = \''.$config_name.'\'');
    
    $rowmysqli = mysqli_fetch_assoc($resmysqli);
    
    if ($rowmysqli) {
        if ($rowmysqli['value'] == '1') {
            $db_table_results = 'parser_google_alibaba_results';
        }
    }
}
