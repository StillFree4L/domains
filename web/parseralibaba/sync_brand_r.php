<?php

set_time_limit(0); // Максимальное время выполнения скрипта. В секундах.

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

ini_set('log_errors', 'on');
ini_set('error_log', __dir__ . '/error_log.txt');
ini_set('max_execution_time', '864000');
ini_set('memory_limit', '4048M');
ini_set("pcre.backtrack_limit", 1000000000);

ignore_user_abort(1);

session_start();

$is_parser_script = true;

require_once (__dir__ . "/../config.inc.php");

$GLOBALS['GLOBALS']['config'] = ['db' => ['mysql' => ['DRIVER' => 'mysql',
    'DB_PERSISTENCY' => true, 'DB_SERVER' => $db_host, 'DB_DATABASE' => $db_name,
    'DB_USERNAME' => $db_user, 'DB_PASSWORD' => $db_pasw, 'DB_CHARSET' => 'utf8', ], ],
    'default_db' => 'mysql', ];

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\FileDetector;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverActions;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Cookie;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\LocalFileDetector;

use \main\DatabasePerform as DB;

if (file_exists(__dir__ . "/config.php")) {
    require_once (__dir__ . "/config.php");
}
require_once (__dir__ . "/vendor/autoload.php");
require_once (__dir__ . "/Parser.php");
require_once (__dir__ . "/functions.php");
require_once (__dir__ . "/../lib/function.php7.php");

foreach (DB::GetAll('SELECT * FROM `' . $db_table_conf_list .
    '` WHERE `parser_id` = ? and `name` = ?', [1 => 'alibaba', 2 => get_config_name()]) as
    $row) {
    if (!isset($GLOBALS['GLOBALS']['config'][$row['type']])) {
        config($row['type'], is_numeric($row['value']) ? floatstrval($row['value']) : $row['value']);
    }
}

config('runkey', 'sync');

startRun();

$parse_update_all = 0;
$parse_count_all = 0;
$parse_delete_all = 0;

$start_time = time();

$description = '';
DB::Execute("INSERT INTO `$db_table_parser_stat` (`parser_id`, `action`, `description`, `date_start`) VALUES('brand_r', 'start/end', '$description', NOW())");
$db_table_parser_stat_id = DB::LastInsertId();

if (config('import_filter_brand_r') > '0') {
    if (file_exists(dirname(__dir__) . '/parserbrand/script.bat')) {
        shell_exec(dirname(__dir__) . '/parserbrand/script.bat');
    }
}

echo("start BrandR...\n");

//DB::Execute('DELETE FROM `'.$db_table_results.'` WHERE (SELECT COUNT(*) FROM `'.$db_table.'` WHERE `'.$db_table_results.'`.`asin` = `'.$db_table.'`.`asin`) = 0');

$offset = 0;
$limit = 500;
$count = null;

$BrandCsv = array_map(function ($item)
{
    return str_getcsv($item, ';'); }
, file(__dir__ . '/../data/Brand.csv'));

$countBrandCsv = 0; 

while ($rows = DB::GetAll('SELECT * FROM `' . $db_table .
    '` ORDER BY `id` LIMIT ' . $offset . ', ' . $limit)) {
    ctrlRun();
    
    $cnt_delete = 0;

    foreach ($rows as $key => $row) {
        $row['info'] = json_decode($row['info'], true);

        if (is_array($row['info'])) {
            if ($row['info']['Brand']) {
                $BrandR = null;

                foreach ($BrandCsv as $Brand) {
                    $Brand[0] = trim($Brand[0]);
                    
                    if ($Brand[0]) {
                        if (config('import_filter_brand_r') == '1') {
                            if ($Brand[0] == $row['info']['Brand']) {
                                $BrandR = $Brand[0];
                                break;
                            }
                        } elseif (mb_strlen($Brand[0]) > mb_strlen($row['info']['Brand'])) {
                            if (stripos($Brand[0], $row['info']['Brand']) !== false) {
                                $BrandR = $Brand[0];
                                break;
                            }
                        } else {
                            if (stripos($row['info']['Brand'], $Brand[0]) !== false) {
                                $BrandR = $Brand[0];
                                break;
                            }
                        }
                    }
                }

                if ($BrandR) {
                    $parse_count_all++;
                    
                    $countBrandCsv++;
                    
                    $row['info']['Brand_R'] = $BrandR;

                    if (config('import_filter_brand_r') == '1') {
                        $parse_delete_all++;
                        
                        DB::Execute("DELETE FROM `" . $db_table . "` WHERE `id` = ?", [1 => $row['id']]);
                    }

                    if (config('import_filter_brand_r') == '2') {
                        $parse_update_all++;
                        
                        DB::Execute("UPDATE `" . $db_table . "` SET `info` = ? WHERE `id` = ?", [1 =>
                            json_encode($row['info']), 2 => $row['id']]);
                    }

                    if (config('import_filter_brand_r') == '3') {
                        $parse_delete_all++;
                        
                        $cnt_delete++;
                        
                        DB::Execute("DELETE FROM `" . $db_table . "` WHERE `id` = ?", [1 => $row['id']]);
                    }
                }
            }
        }
    }

    $offset += ($limit - $cnt_delete);

    if (count($rows) != $limit) {
        break;
    }

    if ($count) {
        $count -= count($rows);

        if ($count <= 0) {
            break;
        }
    }
    
    echo("now BrandR: ".$countBrandCsv."\n");
}

echo("complete BrandR: ".$countBrandCsv."\n");

$end_time = time();
$time_diff_sec = ($end_time - $start_time);

$time_diff = ($time_diff_sec) / 60;
$time_diff = number_format($time_diff, 0, '.');

$description = array();
$description['parse_count_all'] = (int)$parse_count_all;
if (config('import_filter_brand_r') == '1' || config('import_filter_brand_r') == '1') {
    $description['parse_update_all'] = (int)$parse_update_all;
} else {
    $description['parse_delete_all'] = (int)$parse_delete_all;
}
$description = json_encode($description);
DB::Execute("UPDATE `$db_table_parser_stat` SET `description` = ?, `date_end` = NOW(), `duration` = ? WHERE `id` = '$db_table_parser_stat_id'", [
    1 => $description,
    2 => ($time_diff_sec)/$parse_count_all,
]);

stopRun();
