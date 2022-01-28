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

$is_parser_alibaba = true;

require_once (__dir__ . "/../config.inc.php");

$GLOBALS['GLOBALS']['config'] = ['ali_base_url' =>
    'https://www.alibaba.com/trade/search', 'ali_base_url_search_by_img' =>
    'https://www.alibaba.com/picture/search.htm?imageType=oss&escapeQp=true&imageAddress=/icbuimgsearch/cbebHr7Gwz1630141671000.jpg&sourceFrom=imageupload&originFrom=https://www.alibaba.com/?__redirected__=1',
    'db' => ['mysql' => ['DRIVER' => 'mysql', 'DB_PERSISTENCY' => true, 'DB_SERVER' =>
    $db_host, 'DB_DATABASE' => $db_name, 'DB_USERNAME' => $db_user, 'DB_PASSWORD' =>
    $db_pasw, 'DB_CHARSET' => 'utf8', ], ], 'default_db' => 'mysql', 'offset' => 0,
    'limit' => 100, ];

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

require_once (__dir__ . "/vendor/autoload.php");
require_once (__dir__ . "/libs.php");
require_once (__dir__ . "/Parser.php");
require_once (__dir__ . "/functions.php");
require_once (__dir__ . "/../lib/function.php7.php");

foreach (DB::GetAll('SELECT * FROM `' . $db_table_conf_list .
    '` WHERE `parser_id` = ? and `name` = ?', [1 => 'alibaba', 2 => get_config_name
    ()]) as $row) {
    if (!isset($GLOBALS['GLOBALS']['config'][$row['type']])) {
        config($row['type'], is_numeric($row['value']) ? floatstrval($row['value']) : $row['value']);
    }
}

config('runkey', 'sync');

startRun();

DB::Execute('DELETE FROM `' . $db_table_results .
    '` WHERE (SELECT COUNT(*) FROM `' . $db_table .
    '` WHERE `parser_alibaba_results`.`asin` = `parser_china`.`asin`) = 0');

$offset = 0;
$limit = 100;
$count = null;

while ($rows = DB::GetAll('SELECT * FROM `' . $db_table .
    '` ORDER BY `id` LIMIT ' . $offset . ', ' . $limit)) {
    ctrlRun();

    foreach ($rows as $key => $row) {
        $row['info'] = json_decode($row['info'], true);

        if (is_array($row['info'])) {
            $results = DB::GetAll('SELECT * FROM `' . $db_table_results .
                '` where `asin` = ? ORDER BY `id` LIMIT 2000', [1 => $row['asin'], ]);

            if (config('ali_checking_delete_results')) {
                DB::Execute('DELETE FROM `' . $db_table_results . '` where `asin` = ?', [1 => $row['asin'], ]);
            }

            foreach ($results as $k => $res) {
                $results[$k]['Find_Ali'] = explode('/', $results[$k]['Find_Ali']);
                
                $res['results'] = $results[$k]['results'] = json_decode($results[$k]['results'], true);

                if (is_array($results[$k]['results']) && isSuccessDataAlibaba($res['results'])) {
                    $results[$k]['results']['Find_Ali'] = explode('/', $results[$k]['results']['Find_Ali']);
                } else {
                    DB::Execute('DELETE FROM `parser_china` WHERE `id` = ?', [1 => $results[$k]['id']]);
                    unset($results[$k]);
                }
            }

            if (config('ali_sort_sync')) {
                usort($results, function ($a, $b)
                {
                    if ($a['results'][config('ali_sort_sync')] == $b['results'][config('ali_sort_sync')]) {
                        return 0; }
                    return (($a['results'][config('ali_sort_sync')] < $b['results'][config('ali_sort_sync')]) ?
                        -1 : 1) * (config('ali_sort_sync_') ? -1 : 1); }
                );
            }

            $row['info']['Count_Ali'] = count($results);

            if (true || isset($row['info']['Count_Finds_Ali']) && is_array($row['info']['Count_Finds_Ali'])) {
                $row['info']['Count_Finds_Ali'] = [];
            }

            if (true || isset($row['info']['Finds_Ali']) && is_array($row['info']['Finds_Ali'])) {
                $row['info']['Finds_Ali'] = [];
            }

            if (true || isset($row['info']['URLs_Ali']) && is_array($row['info']['URLs_Ali'])) {
                $row['info']['URLs_Ali'] = [];
            }

            if (true || isset($row['info']['URLs_Ali2']) && is_array($row['info']['URLs_Ali2'])) {
                $row['info']['URLs_Ali2'] = [];
            }

            if (true || isset($row['info']['Urls_Shipping_Ali']) && is_array($row['info']['Urls_Shipping_Ali'])) {
                $row['info']['Urls_Shipping_Ali'] = [];
            }

            if (true || isset($row['info']['Urls_Searchs_Ali']) && is_array($row['info']['Urls_Searchs_Ali'])) {
                $row['info']['Urls_Searchs_Ali'] = [];
            }

            if (true || isset($row['info']['ROIs_Ali']) && is_array($row['info']['ROIs_Ali'])) {
                $row['info']['ROIs_Ali'] = [];
            }

            if (true || isset($row['info']['PricesMaxAli']) && is_array($row['info']['PricesMaxAli'])) {
                $row['info']['PricesMaxAli'] = [];
            }

            if (true || isset($row['info']['Sellers_Ali']) && is_array($row['info']['Sellers_Ali'])) {
                $row['info']['Sellers_Ali'] = [];
            }

            if (true || isset($row['info']['MOQs_Ali']) && is_array($row['info']['MOQs_Ali'])) {
                $row['info']['MOQs_Ali'] = [];
            }

            if (true || isset($row['info']['Images_Ali']) && is_array($row['info']['Images_Ali'])) {
                $row['info']['Images_Ali'] = [];
            }

            if (true || isset($row['info']['%Weights']) && is_array($row['info']['%Weights'])) {
                $row['info']['%Weights'] = [];
            }

            if (true || isset($row['info']['%Packages']) && is_array($row['info']['%Packages'])) {
                $row['info']['%Packages'] = [];
            }

            if (true || isset($row['info']['%Images']) && is_array($row['info']['%Images'])) {
                $row['info']['%Images'] = [];
            }

            if (true || isset($row['info']['Margins']) && is_array($row['info']['Margins'])) {
                $row['info']['Margins'] = [];
            }

            if (true || isset($row['info']['Profits30']) && is_array($row['info']['Profits30'])) {
                $row['info']['Profits30'] = [];
            }

            foreach ($results as $k => $res) {
                foreach ($results[$k]['Find_Ali'] as $Find_Ali) {
                    if (!isset($row['info']['Count_Finds_Ali'][$Find_Ali])) {
                        $row['info']['Count_Finds_Ali'][$Find_Ali] = 0;
                    }
                    $row['info']['Count_Finds_Ali'][$Find_Ali] = $row['info']['Count_Finds_Ali'][$Find_Ali] +
                        1;
                }

                $row['info']['URLs_Ali'][] = strpos($res['results']['URL_Ali'], '//') === 0 ?
                    'https:' . $res['results']['URL_Ali'] : $res['results']['URL_Ali'];

                if (count($results[$k]['Find_Ali']) > 1) {
                    $row['info']['URLs_Ali2'][] = strpos($res['results']['URL_Ali'], '//') === 0 ?
                        'https:' . $res['results']['URL_Ali'] : $res['results']['URL_Ali'];
                }

                if (isset($res['results']['Shipping']) == 'yes') {
                    $row['info']['Urls_Shipping_Ali'][] = strpos($res['results']['URL_Ali'], '//')
                        === 0 ? 'https:' . $res['results']['URL_Ali'] : $res['results']['URL_Ali'];
                }

                if (isset($res['results']['Url_Search_Ali'])) {
                    if (!in_array($res['results']['Url_Search_Ali'], $row['info']['Urls_Searchs_Ali'])) {
                        $row['info']['Urls_Searchs_Ali'][] = $res['results']['Url_Search_Ali'];
                    }
                }

                foreach ($res['Find_Ali'] as $Find_Ali) {
                    if (!isset($row['info']['Finds_Ali'][$Find_Ali])) {
                        $row['info']['Finds_Ali'][$Find_Ali] = $Find_Ali;
                    }
                }

                $row['info']['ROIs_Ali'][] = round($res['ROI']);
                $row['info']['PricesMaxAli'][] = $res['results']['PriceMax_Ali'];
                $row['info']['Sellers_Ali'][] = $res['results']['Seller_Ali'];
                $row['info']['MOQs_Ali'][] = $res['results']['MOQ_Ali'];
                $row['info']['Margins'][] = $res['results']['Margin'];
                $row['info']['Profits30'][] = $res['results']['Profit30'];
                $row['info']['Images_Ali'][] = $res['results']['Images_Ali'];

                $row['info']['%Weights'][] = $res['results']['%Weight'];
                $row['info']['%Packages'][] = $res['results']['%Package'];
                $row['info']['%Images'][] = $res['results']['%Image'];
            }

            foreach ($row['info']['Count_Finds_Ali'] as $n => $v) {
                $row['info']['Count_Finds_Ali'][$n] = $n . ':' . $v;
            }

            rsort($row['info']['ROIs_Ali']);

            $row['info']['Count_Finds_Ali'] = implode('/', $row['info']['Count_Finds_Ali']);
            $row['info']['Finds_Ali'] = implode('/', $row['info']['Finds_Ali']);
            $row['info']['URLs_Ali'] = implode("\n", $row['info']['URLs_Ali']);
            $row['info']['URLs_Ali2'] = implode("\n", $row['info']['URLs_Ali2']);
            $row['info']['Urls_Shipping_Ali'] = implode("\n", $row['info']['Urls_Shipping_Ali']);
            $row['info']['Urls_Searchs_Ali'] = implode("\n", $row['info']['Urls_Searchs_Ali']);
            $row['info']['ROIs_Ali'] = implode("/", $row['info']['ROIs_Ali']);
            $row['info']['PricesMaxAli'] = implode("/", $row['info']['PricesMaxAli']);
            $row['info']['Margins'] = implode("/", $row['info']['Margins']);
            $row['info']['Profits30'] = implode("/", $row['info']['Profits30']);
            $row['info']['Sellers_Ali'] = implode("/", $row['info']['Sellers_Ali']);
            $row['info']['MOQs_Ali'] = implode("/", $row['info']['MOQs_Ali']);
            $row['info']['Images_Ali'] = implode(";", $row['info']['Images_Ali']);
            $row['info']['%Weights'] = implode(";", $row['info']['%Weights']);
            $row['info']['%Packages'] = implode(";", $row['info']['%Packages']);
            $row['info']['%Images'] = implode(";", $row['info']['%Images']);

            DB::Execute("UPDATE `" . $db_table . "` SET `info` = ? WHERE `id` = ?", [1 =>
                json_encode($row['info']), 2 => $row['id']]);
        }
    }

    $offset += $limit;

    if (count($rows) != $limit) {
        break;
    }

    if ($count) {
        $count -= count($rows);

        if ($count <= 0) {
            break;
        }
    }
}

if (config('ali_checking_delete_results')) {
    DB::Execute('DELETE FROM `' . $db_table_results . '` where 1', []);
}

if (config('ali_delete_not_found')) {
   // DB::Execute("DELETE FROM `" . $db_table . "` WHERE IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"URLs_Ali\"'),'')), '') != ''");
    DB::Execute("DELETE FROM `" . $db_table . "` WHERE JSON_EXTRACT(`info`, \"$.URLs_Ali\") != ''");
}

stopRun();
