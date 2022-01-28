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

$parse_count_all = 0;

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

date_default_timezone_set("Europe/Moscow");

log_write_echo(dirname(__dir__ ) . '/alibaba.log', 'start parse...');

try {
    $GLOBALS['DRIVER'] = RemoteWebDriver::create('http://localhost:4444',
        DesiredCapabilities::chrome(), 120000, 130000);
}
catch (WebDriverException $e) {
    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
}

foreach (DB::GetAll('SELECT * FROM `' . $db_table_conf_list .
    '` WHERE `parser_id` = ? and `name` = ?', [1 => 'alibaba', 2 => get_config_name
    ()]) as $row) {
    if (!isset($GLOBALS['GLOBALS']['config'][$row['type']])) {
        config($row['type'], is_numeric($row['value']) ? parsefloatstrval($row['value']) :
            $row['value']);
    }
}

$ali_price_info = explode('|', config('ali_price_info'));
config('ali_price_info', trim($ali_price_info[0]));

$offset = intval(config('offset'));
$limit = intval(config('limit'));
$count = null;

if (isset($_SERVER) && isset($_SERVER['argv']) && isset($_SERVER['argv'][1]) &&
    is_numeric($_SERVER['argv'][1])) {
    $offset = intval($_SERVER['argv'][1]);
}

if (isset($_SERVER) && isset($_SERVER['argv']) && isset($_SERVER['argv'][2]) &&
    is_numeric($_SERVER['argv'][2])) {
    $count = intval($_SERVER['argv'][2]);
}

if (isset($_SERVER) && isset($_SERVER['argv']) && isset($_SERVER['argv'][3]) &&
    is_numeric($_SERVER['argv'][3])) {
    $is_resore = intval($_SERVER['argv'][3]);
} else {
    $is_resore == '0';
}

if (isset($_SERVER) && isset($_SERVER['argv']) && isset($_SERVER['argv'][4]) &&
    is_numeric($_SERVER['argv'][4])) {
    $start_comporator = $_SERVER['argv'][3] == '1' ? true : false;
} else {
    $start_comporator = config('ali_checking_image') == '1' ? true : false;
}

config('runkey', $offset);

startRun();

$start_time = time();

$description = '';
DB::Execute("INSERT INTO `$db_table_parser_stat` (`parser_id`, `action`, `description`, `date_start`) VALUES('$parser_id', 'start/end', '$description', NOW())");
$db_table_parser_stat_id = DB::LastInsertId();
log_write_echo(dirname(__dir__ ) . '/' . $parser_id . '.log', "log ID: " . $db_table_parser_stat_id .
    "");

if (config('ali_trademarkia_cmd')) {
    cmdexec(dirname(dirname(__dir__ )) . '/php.x64/php.exe -q ' . dirname(__dir__ ) .
        '/parser.trademarkia.com.multi.php');
}

if ($is_resore == '1') {

} else {
    DB::Execute('UPDATE `parser_china` SET `parse_at` = null WHERE `parse_at` is not null');
    DB::Execute('UPDATE `parser_alibaba_results` SET `parse_at` = null WHERE `parse_at` is not null');
}

if (file_exists(dirname(__dir__ ) . '/data/_session')) {
    $data_session = json_decode(file_get_contents(dirname(__dir__ ) .
        '/data/_session'), true);
}

if (isset($data_session[find_ext_submit])) {
    $dataresult[categories] = $datafind[categories] = DB::Quote($data_session[categories]);
    $dataresult[category_name] = $datafind[category_name] = DB::Quote($data_session[category_name]);
    $dataresult[date_start] = $datafind[date_start] = DB::Quote($data_session[date_start]);
    $dataresult[date_end] = $datafind[date_end] = DB::Quote($data_session[date_end]);
    $dataresult[date_start_add] = $datafind[date_start_add] = DB::Quote($data_session[date_start_add]);
    $dataresult[date_end_add] = $datafind[date_end_add] = DB::Quote($data_session[date_end_add]);
    $dataresult[date_start_update] = $datafind[date_start_update] = DB::Quote($data_session[date_start_update]);
    $dataresult[date_end_update] = $datafind[date_end_update] = DB::Quote($data_session[date_end_update]);
    //$dataresult[parser_id] = $datafind[parser_id] = DB::Quote( $data_session[parser_id]);
    $dataresult[list_id] = $datafind[list_id] = DB::Quote($data_session[list_id]);
    $dataresult[min_star_export] = $datafind[min_star_export] = DB::Quote($data_session[min_star_export]);
    $dataresult[title_filter] = $datafind[title_filter] = DB::Quote($data_session[title_filter]);
    $dataresult[developer_filter] = $datafind[developer_filter] = DB::Quote($data_session[developer_filter]);
    $dataresult[asin_filter] = $datafind[asin_filter] = DB::Quote($data_session[asin_filter]);

    $dataresult['fba_filter_from'] = $datafind['fba_filter_from'] = DB::Quote($data_session['fba_filter_from']);
    $dataresult['fbm_filter_from'] = $datafind['fbm_filter_from'] = DB::Quote($data_session['fbm_filter_from']);
    $dataresult['bsr_filter_from'] = $datafind['bsr_filter_from'] = DB::Quote($data_session['bsr_filter_from']);
    $dataresult['fba_filter_to'] = $datafind['fba_filter_to'] = DB::Quote($data_session['fba_filter_to']);
    $dataresult['fbm_filter_to'] = $datafind['fbm_filter_to'] = DB::Quote($data_session['fbm_filter_to']);
    $dataresult['bsr_filter_to'] = $datafind['bsr_filter_to'] = DB::Quote($data_session['bsr_filter_to']);
    $dataresult['category_filter'] = $datafind['category_filter'] = DB::Quote($data_session['category_filter']);
    $dataresult['profile_filter'] = $datafind['profile_filter'] = DB::Quote($data_session['profile_filter']);

    $where = [];

    if ($dataresult[categories] != '')
        $where[] = "`categories` LIKE '%{$dataresult[categories]}%'";
    if ($dataresult[category_name] != '')
        $where[] = "`categories` LIKE '%{$dataresult[category_name]}%'";
    if ($dataresult[date_start] != '')
        $where[] = "`item-add-date` >= '{$dataresult[date_start]} 00:00' ";
    if ($dataresult[date_end] != '')
        $where[] = "`item-add-date` <= '{$dataresult[date_end]} 23:59' ";
    if ($dataresult[date_start_add] != '')
        $where[] = "`date_add` >= '{$dataresult[date_start_add]} 00:00' ";
    if ($dataresult[date_end_add] != '')
        $where[] = "`date_add` <= '{$dataresult[date_end_add]} 23:59' ";
    if ($dataresult[date_start_update] != '')
        $where[] = "`date_update` >= '{$dataresult[date_start_update]} 00:00' ";
    if ($dataresult[date_end_update] != '')
        $where[] = "`date_update` <= '{$dataresult[date_end_update]} 23:59' ";
    //if($dataresult[parser_id] != '') $where[] = "`parser_id` = '{$dataresult[parser_id]}' ";
    if ($dataresult[min_star_export] != '')
        $where[] = "`star` >= '" . intval($dataresult[min_star_export]) . "' ";
    if ($dataresult[title_filter] != '')
        $where[] = "`asin` LIKE '%{$dataresult[title_filter]}%' ";
    if ($dataresult[developer_filter] != '')
        $where[] = "`developer` LIKE '%{$dataresult[developer_filter]}%' ";
    if ($result[asin_filter] != '')
        $where[] = "`asin` LIKE '%{$dataresult[asin_filter]}%' ";

    if (($dataresult['fba_filter_from']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBA\"'),'')), '') >= " .
            parsefloatstrval($dataresult['fba_filter_from']) . "";
    }
    if (($dataresult['fba_filter_to']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBA\"'),'')), '') <= " .
            parsefloatstrval($dataresult['fba_filter_to']) . "";
    }
    if (($dataresult['fbm_filter_from']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBM\"'),'')), '')>= " .
            parsefloatstrval($dataresult['fbm_filter_from']) . "";
    }
    if (($dataresult['fbm_filter_to']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBM\"'),'')), '') <= " .
            parsefloatstrval($dataresult['fbm_filter_to']) . "";
    }
    if (($dataresult['bsr_filter_from']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Sales Rank: Current\"'),'')), '') >= " .
            parsefloatstrval($dataresult['bsr_filter_from']) . "";
    }
    if (($dataresult['bsr_filter_to']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Sales Rank: Current\"'),'')), '') <= " .
            parsefloatstrval($dataresult['bsr_filter_to']) . "";
    }
    if (($dataresult['category_filter']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Categories: Root\"'),'')), '') = '" .
            $dataresult['category_filter'] . "'";
    }
    if (($dataresult['profile_filter']) != '') {
        $where[] = "`profile` like '%" . ($dataresult['profile_filter'] == '-' ? '' : $dataresult['profile_filter']) .
            "%'";
    }

    if (($dataresult['title_filter']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Title\"'),'')), '') like '%" .
            $result['title_filter'] . "%'";
    }
}

if (config('save_to_table_alibaba') == '1') {
    $where = "";

    while ($rows = DB::GetAll('SELECT * FROM `parser_google_results` ' . $where .
        ' ORDER BY `id` LIMIT ' . $offset . ', ' . $limit)) {
        if (isRun()) {
            parseRowsByUrls($rows);
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
} else {
    if (config('save_to_table_alibaba') != '1') {
        $where[] = "`parse_at` is null";
    }

    $where = implode(' and ', $where);

    if ($where != '') {
        $where = " WHERE " . $where;
    }

    while ($rows = DB::GetAll('SELECT * FROM `' . $db_table . '` ' . $where .
        ' ORDER BY `parse_at` LIMIT ' . $offset . ', ' . $limit)) {
        if (isRun()) {
            parseRows($rows);
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
}

function parseRowsByUrls($rows)
{
    $items = [];


    foreach ($rows as $row) {
        if ($row['results']) {
            $result = json_decode($row['results'], true);

            if ($result) {
                if (isset($result['url_all']) && is_array($result['url_all'])) {
                    foreach ($result['url_all'] as $url) {
                        $url = trim($url);

                        if ($url) {
                            if (stripos($url, 'alibaba.com') !== false) {
                                if (!isset($items[$row['asin']])) {
                                    $item = DB::GetRow("SELECT * FROM `parser_google` WHERE `asin` = ? LIMIT 1", [1 =>
                                        $row['asin'], ]);

                                    if ($item) {
                                        $item['info'] = json_decode($item['info'], true);

                                        if (!is_array($item['info'])) {
                                            $item['info'] = [];
                                        }

                                        $items[$row['asin']] = ['asin' => $row['asin'], 'margin' => $row['margin'],
                                            'urls' => [], 'item' => $item];
                                    }
                                }

                                if (isset($items[$row['asin']])) {
                                    if (!in_array($url, $items[$row['asin']]['urls'])) {
                                        $items[$row['asin']]['urls'][] = $url;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    parseItemsByUrls($items);
}

function parseItemsByUrls($items)
{
    ctrlRun();

    $urls = [];

    $categories = array_map(function ($item)
    {
        return str_getcsv($item, ';'); }
    , file(__dir__ . '/../data/categories.csv'));

    foreach ($items as $item) {
        foreach ($item['urls'] as $url) {
            $CatId = '';
            $priceMax = '';
            $priceMin = '';

            $CatId = '';

            if (config('ali_search_by_category') == '1') {
                if (isset($item['item']['info']['Categories: Tree'])) {
                    foreach ($categories as $categoryitem) {
                        if (stripos($item['item']['info']['Categories: Tree'], $categoryitem[0]) !== false) {
                            $CatId = $categoryitem[2];
                        }
                    }
                }
            }

            $PriceMaxFind = round(parsefloatstrval($info['PriceMaxFind']), 2);
            $MOQ_Find = floor(parsefloatstrval($info['MOQ_Find']));

            $priceMax = $PriceMaxFind;
            $priceMin = round((parsefloatstrval(config('ali_price_min_percent')) / 100) * $PriceMaxFind,
                1);

            if (config('ali_pricemax_min') && $PriceMaxFind) {
                if ($PriceMaxFind < config('ali_pricemax_min')) {
                    continue;
                }
            }

            $urls[] = ['QueryFind' => [], 'url' => $url, 'type' => 'googlesearch', 'asin' =>
                $item['asin'], 'rowid' => $item['item']['id'], 'CatId' => $CatId, 'priceMax' =>
                $priceMax, 'priceMin' => $priceMin, 'info' => $item['item']['info'], ];
        }
    }

    parseItemsUrls($urls);
}

function parseItemsUrls($urls)
{
    $rows = [];

    foreach ($urls as $key => $url) {
        parsePageByRowsKey($url, $url['url'], $rows, $key);

        $rows[$key]['Sales30'] = $url['info']['Sales30'];
        $rows[$key]['Sales30,$'] = $url['info']['Sales30,$'];

        $rows[$key]['Shipping'] = $url['info']['Shipping'];
        $rows[$key]['Margin'] = $url['info']['Margin'];
        $rows[$key]['Profit30'] = $url['info']['Profit30'];

        $rows[$key]['checking'] = isSuccessDataAlibaba($rows[$key]) ? 1 : 0;
    }

    //print_r($rows); // delete
    //exit; // delete

    saveRows($rows);
}

function parseRows($rows)
{
    global $parse_count_all;

    $urls = [];

    ctrlRun();

    $categories = array_map(function ($item)
    {
        return str_getcsv($item, ';'); }
    , file(__dir__ . '/../data/categories.csv'));

    foreach ($rows as $row) {
        $info = json_decode($row['info'], true);

        $PriceMaxFind = round(parsefloatstrval($info['PriceMaxFind']), 2);
        $MOQ_Find = floor(parsefloatstrval($info['MOQ_Find']));

        $PriceMinFind = round((parsefloatstrval(config('ali_price_min_percent')) / 100) *
            $PriceMaxFind, 1);

        if (config('ali_pricemax_min') > $PriceMinFind) {
            $PriceMinFind = config('ali_pricemax_min');
        }

        //echo $PriceMinFind;exit;

        $CatId = '';

        if (config('ali_search_by_category') == '1') {
            if (isset($info['Categories: Tree'])) {
                foreach ($categories as $item) {
                    if (stripos($info['Categories: Tree'], $item[0]) !== false) {
                        $CatId = $item[2];
                    }
                }
            }
        }

        if (false && !$CatId && config('ali_search_by_category') == '1') {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', 'Asin: ' . $row['asin'] .
                '. Категория не найдена.');
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                'Категория не найдена.' . "\n", 2 => $row['asin']]);

            continue;
        }

        if (config('ali_search_by_title')) {
            $SearchText = '';

            if (isset($info['Title']) && $info['Title']) {
                $SearchText = $info['Title'];

                $SearchText = explode(' ', $SearchText);

                foreach ($SearchText as $key => $value) {
                    if (mb_strlen($value) > 50 //|| !preg_match("#[a-zA-Zа-яА-Я]#sui", $value)
                        ) {
                        unset($SearchText[$key]);
                    }
                }

                $SearchText = implode(' ', $SearchText);

                if (mb_strlen($SearchText) > 50) {
                    while (mb_strlen($SearchText) > 50) {
                        if (preg_match("# #sui", $SearchText)) {
                            $SearchText = preg_replace("# [^ ]*?$#sui", '', $SearchText);
                        } else {
                            break;
                        }
                    }
                }
            }

            if ($SearchText) {
                $res = [];

                $res['QueryFind'] = [];

                $query = [];

                if (true)
                    $query['IndexArea'] = 'product_en';
                if ($CatId)
                    $query['CatId'] = $CatId; // id категории, можно указать id нижестоящих субкатегорий: CatId=141906 (2-й уровень), CatId=410605 (3-й уровень)
                if ($SearchText)
                    $query['SearchText'] = $SearchText; // поисковая фраза
                if (config('ali_verified_suplier'))
                    $query['assessment_company'] = config('ali_verified_suplier') ? 'ASS' : ''; // в настройках галка: Verified Suplier
                if ($MOQ_Find) {
                    $query['moqt'] = $MOQ_Find; // в настройках поле: минимальное количество (MOQ_Find = 10)
                }
                if (config('ali_trade_ashurance'))
                    $query['ta'] = config('ali_trade_ashurance') ? 'y' : ''; //  в настройках галка: Trade Ashurance
                if (config('ali_ready_to_ship'))
                    $query['productTag'] = config('ali_ready_to_ship') ? '1200000228' : ''; // галка в настройках: Ready to Ship
                if (true)
                    $query['f0'] = 'y';
                if ($PriceMinFind)
                    $query['pricef'] = $PriceMinFind; // диапазон цен - от (в примере: 0-2)
                if ($PriceMaxFind) {
                    $query['pricet'] = $PriceMaxFind; // диапазон цен - до (в примере: 0-2)
                }

                if (config('ali_pricemax_min')) {
                    if ($PriceMaxFind < config('ali_pricemax_min')) {
                        continue;
                    }
                }

                if (config('ali_1h_response_time'))
                    $query['replyAvgTime'] = config('ali_1h_response_time') ? '1' : ''; // галка < 1h response time
                if (config('ali_country'))
                    $query['Country'] = config('ali_country') ? config('ali_country') : 'CN'; // выбор страны поставщика ( в примере Китай и Индия, CN,IN) выбор из стран: Китай (CN),Индия (IN), Индонезия (ID), Гонг Конг (HK), Тайвань (TH), США (US),

                foreach ($query as $k => $v) {
                    $res['QueryFind'][$k] = $v;
                }

                if (true)
                    $res['url'] = config('ali_base_url') . '?' . http_build_query($query);
                if (true)
                    $res['type'] = 'Title';
                if (true)
                    $res['asin'] = $row['asin'];
                if (true)
                    $res['rowid'] = $row['id'];
                if (true)
                    $res['CatId'] = $CatId;
                if (true)
                    $res['priceMax'] = $PriceMaxFind;
                if (true)
                    $res['priceMin'] = $PriceMinFind;
                if (true)
                    $res['info'] = $info;

                $urls[] = $res;
            }
        }
        if (config('ali_search_by_code')) {
            $SearchText = '';

            $type_find = '';

            if (config('ali_type_search_code') == '1') {
                $SearchText = $info['Product Codes: PartNumber'];
                $type_find = 'PN';
            } elseif (config('ali_type_search_code') == '2') {
                $SearchText = $info['Model'];
                $type_find = 'Model';
            } elseif (config('ali_type_search_code') == '3') {
                if ($info['Product Codes: PartNumber'] && $info['Model'] && $info['Product Codes: PartNumber'] !=
                    $info['Model']) {
                    $SearchText = $info['Product Codes: PartNumber'] . ' ' . $info['Model'];
                } elseif ($info['Product Codes: PartNumber']) {
                    $SearchText = $info['Product Codes: PartNumber'];
                } elseif ($info['Model']) {
                    $SearchText = $info['Model'];
                }
                $type_find = 'PN+Model';
            } elseif ($info['Product Codes: PartNumber']) {
                $SearchText = $info['Product Codes: PartNumber'];
                $type_find = 'PN';
            } elseif ($info['Model']) {
                $SearchText = $info['Model'];
                $type_find = 'Model';
            }

            if (config('ali_length_search_min') && config('ali_add_brand_or_manufacturer')) {
                if (mb_strlen($SearchText) < config('ali_length_search_min')) {
                    if (config('ali_type_3_search_code') == 2 && $info['Manufacturer']) {
                        $SearchText .= ' ' . $info['Manufacturer'];
                    } elseif ($info['Brand']) {
                        $SearchText .= ' ' . $info['Brand'];
                    }
                }
            }

            $SearchText = str_limit($SearchText, 50);

            if ($SearchText) {
                $res = [];

                $res['QueryFind'] = [];

                $query = [];

                if (true)
                    $query['IndexArea'] = 'product_en';
                if ($CatId)
                    $query['CatId'] = $CatId; // id категории, можно указать id нижестоящих субкатегорий: CatId=141906 (2-й уровень), CatId=410605 (3-й уровень)
                if ($SearchText)
                    $query['SearchText'] = $SearchText; // поисковая фраза
                if (config('ali_verified_suplier'))
                    $query['assessment_company'] = config('ali_verified_suplier') ? 'ASS' : ''; // в настройках галка: Verified Suplier
                if (config('ali_trade_ashurance'))
                    $query['ta'] = config('ali_trade_ashurance') ? 'y' : ''; //  в настройках галка: Trade Ashurance
                if (config('ali_ready_to_ship'))
                    $query['productTag'] = config('ali_ready_to_ship') ? '1200000228' : ''; // галка в настройках: Ready to Ship
                if (true)
                    $query['f0'] = 'y';
                if ($PriceMinFind)
                    $query['pricef'] = $PriceMinFind; // диапазон цен - от (в примере: 0-2)
                if ($PriceMaxFind) {
                    $query['pricet'] = $PriceMaxFind; // диапазон цен - до (в примере: 0-2)
                }
                if ($MOQ_Find) {
                    $query['moqt'] = $MOQ_Find; // в настройках поле: минимальное количество (MOQ_Find = 10)
                }

                if (config('ali_pricemax_min')) {
                    if ($PriceMaxFind < config('ali_pricemax_min')) {
                        continue;
                    }
                }

                if (config('ali_1h_response_time'))
                    $query['replyAvgTime'] = config('ali_1h_response_time') ? '1' : ''; // галка < 1h response time
                if (config('ali_country'))
                    $query['Country'] = config('ali_country') ? config('ali_country') : 'CN'; // выбор страны поставщика ( в примере Китай и Индия, CN,IN) выбор из стран: Китай (CN),Индия (IN), Индонезия (ID), Гонг Конг (HK), Тайвань (TH), США (US),

                foreach ($query as $k => $v) {
                    $res['QueryFind'][$k] = $v;
                }

                if (true)
                    $res['url'] = config('ali_base_url') . '?' . http_build_query($query);
                if (true)
                    $res['type'] = $type_find;
                if (true)
                    $res['asin'] = $row['asin'];
                if (true)
                    $res['rowid'] = $row['id'];
                if (true)
                    $res['CatId'] = $CatId;
                if (true)
                    $res['priceMax'] = $PriceMaxFind;
                if (true)
                    $res['priceMin'] = $PriceMinFind;
                if (true)
                    $res['info'] = $info;

                $urls[] = $res;
            }
        }
        if (config('ali_is_search_by_upc_ean') && config('ali_search_by_upc_ean')) {
            $SearchText = '';

            $type_find = '';

            if (config('ali_search_by_upc_ean') == '1' && $info['Product Codes: UPC']) {
                $SearchText = $info['Product Codes: UPC'];
                $type_find = 'UPC';
            } elseif (config('ali_search_by_upc_ean') == '2' && $info['Product Codes: EAN']) {
                $SearchText = $info['Product Codes: EAN'];
                $type_find = 'EAN';
            } elseif ($info['Product Codes: UPC']) {
                $SearchText = $info['Product Codes: UPC'];
                $type_find = 'UPC';
            }

            $SearchText = str_limit($SearchText, 50);

            if ($SearchText) {
                $res = [];

                $res['QueryFind'] = [];

                $query = [];

                if (true)
                    $query['IndexArea'] = 'product_en';
                if ($CatId)
                    $query['CatId'] = $CatId; // id категории, можно указать id нижестоящих субкатегорий: CatId=141906 (2-й уровень), CatId=410605 (3-й уровень)
                if ($SearchText)
                    $query['SearchText'] = $SearchText; // поисковая фраза
                if (config('ali_verified_suplier'))
                    $query['assessment_company'] = config('ali_verified_suplier') ? 'ASS' : ''; // в настройках галка: Verified Suplier
                if (config('ali_trade_ashurance'))
                    $query['ta'] = config('ali_trade_ashurance') ? 'y' : ''; //  в настройках галка: Trade Ashurance
                if (config('ali_ready_to_ship'))
                    $query['productTag'] = config('ali_ready_to_ship') ? '1200000228' : ''; // галка в настройках: Ready to Ship
                if (true)
                    $query['f0'] = 'y';
                if ($PriceMinFind)
                    $query['pricef'] = $PriceMinFind; // диапазон цен - от (в примере: 0-2)
                if ($PriceMaxFind) {
                    $query['pricet'] = $PriceMaxFind; // диапазон цен - до (в примере: 0-2)
                }
                if ($MOQ_Find) {
                    $query['moqt'] = $MOQ_Find; // в настройках поле: минимальное количество (MOQ_Find = 10)
                }

                if (config('ali_pricemax_min')) {
                    if ($PriceMaxFind < config('ali_pricemax_min')) {
                        continue;
                    }
                }

                if (config('ali_1h_response_time'))
                    $query['replyAvgTime'] = config('ali_1h_response_time') ? '1' : ''; // галка < 1h response time
                if (config('ali_country'))
                    $query['Country'] = config('ali_country') ? config('ali_country') : 'CN'; // выбор страны поставщика ( в примере Китай и Индия, CN,IN) выбор из стран: Китай (CN),Индия (IN), Индонезия (ID), Гонг Конг (HK), Тайвань (TH), США (US),

                foreach ($query as $k => $v) {
                    $res['QueryFind'][$k] = $v;
                }

                if (true)
                    $res['url'] = config('ali_base_url') . '?' . http_build_query($query);
                if (true)
                    $res['type'] = $type_find;
                if (true)
                    $res['asin'] = $row['asin'];
                if (true)
                    $res['rowid'] = $row['id'];
                if (true)
                    $res['CatId'] = $CatId;
                if (true)
                    $res['priceMax'] = $PriceMaxFind;
                if (true)
                    $res['priceMin'] = $PriceMinFind;
                if (true)
                    $res['info'] = $info;

                $urls[] = $res;
            }
        }
        if (config('ali_search_by_img') && isset($info['Image'])) {
            $images = explode(';', $info['Image']);

            foreach ($images as $key => $image) {
                if ($key + 1 <= intval(config('ali_search_by_img_count'))) {
                    $query = [];

                    $QueryFind = [];

                    if (true)
                        $query['IndexArea'] = 'product_en';
                    if ($CatId)
                        $query['CatId'] = $CatId; // id категории, можно указать id нижестоящих субкатегорий: CatId=141906 (2-й уровень), CatId=410605 (3-й уровень)

                    if (config('ali_verified_suplier'))
                        $query['assessment_company'] = config('ali_verified_suplier') ? 'ASS' : ''; // в настройках галка: Verified Suplier
                    if (config('ali_trade_ashurance'))
                        $query['ta'] = config('ali_trade_ashurance') ? 'y' : ''; //  в настройках галка: Trade Ashurance
                    if (config('ali_ready_to_ship'))
                        $query['productTag'] = config('ali_ready_to_ship') ? '1200000228' : ''; // галка в настройках: Ready to Ship
                    if (true)
                        $query['f0'] = 'y';
                    if ($PriceMinFind)
                        $query['pricef'] = $PriceMinFind; // диапазон цен - от (в примере: 0-2)
                    if ($PriceMaxFind) {
                        $query['pricet'] = $PriceMaxFind; // диапазон цен - до (в примере: 0-2)
                    }
                    if ($MOQ_Find) {
                        $query['moqt'] = $MOQ_Find; // в настройках поле: минимальное количество (MOQ_Find = 10)
                    }

                    if (config('ali_pricemax_min')) {
                        if ($PriceMaxFind < config('ali_pricemax_min')) {
                            continue;
                        }
                    }

                    if (config('ali_1h_response_time'))
                        $query['replyAvgTime'] = config('ali_1h_response_time') ? '1' : ''; // галка < 1h response time
                    if (config('ali_country'))
                        $query['Country'] = config('ali_country') ? config('ali_country') : 'CN'; // выбор страны поставщика ( в примере Китай и Индия, CN,IN) выбор из стран: Китай (CN),Индия (IN), Индонезия (ID), Гонг Конг (HK), Тайвань (TH), США (US),

                    foreach ($query as $k => $v) {
                        $QueryFind[$k] = $v;
                    }

                    $QueryFind['Image'] = $image;

                    $QueryFind = ['Image' => $image, 'CatId' => $CatId, ];

                    $urls[] = array(
                        //'url' => config('ali_base_url') . '?' . http_build_query($query),
                        'query' => http_build_query($query),
                        'url' => config('ali_base_url'),
                        'CatId' => $CatId,
                        'QueryFind' => $QueryFind,
                        'file' => $image,
                        'type' => 'img',
                        'rowid' => $row['id'],
                        'asin' => $row['asin'],
                        'priceMax' => $PriceMaxFind,
                        'priceMin' => $PriceMinFind,
                        'info' => $info,
                        );
                } else {
                    break;
                }
            }
        }
    }

    parseUrls($urls);
}

function parseUrls($urls)
{
    global $parse_count_all;

    foreach ($urls as $url) {
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            "", 2 => $url['asin']]);

        $parse_count_all++;

        ctrlRun();

        log_write_echo(dirname(__dir__ ) . '/alibaba.log', 'Parse Url(' . $url['type'] .
            '): ' . $url['url']);
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            'Parse Url(' . $url['type'] . '): ' . $url['url'] . "\n", 2 => $url['asin']]);

        //$url['url'] = "https://www.alibaba.com/trade/search?IndexArea=product_en&SearchText=OneGrill+4PM05+Stainless+Steel+Grill+Rotisserie&assessment_company=ASS&moqt=22&ta=y&f0=y&pricef=0.1&pricet=31.63&Country=CN"; // delete

        $GLOBALS['DRIVER']->get($url['url']);

        if ($url['type'] == 'img') {
            try {
                $tmp_file = dirname(__dir__ ) . DIRECTORY_SEPARATOR . 'upload' .
                    DIRECTORY_SEPARATOR . config('ali_search_file_img') . '/file.' . pathinfo($url['file'],
                    PATHINFO_EXTENSION);

                file_put_contents($tmp_file, file_get_contents($url['file']));

                $GLOBALS['DRIVER']->wait()->until(WebDriverExpectedCondition::
                    visibilityOfElementLocated(WebDriverBy::cssSelector('.ui-searchbar-imgsearch-icon')));

                $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('.ui-searchbar-imgsearch-icon'))->
                    click();

                $GLOBALS['DRIVER']->wait()->until(WebDriverExpectedCondition::
                    visibilityOfElementLocated(WebDriverBy::cssSelector('.ui-searchbar-img-search-box .upload-btn')));

                $GLOBALS['DRIVER']->wait()->until(WebDriverExpectedCondition::
                    presenceOfElementLocated(WebDriverBy::cssSelector('.ui-searchbar-body form input[type=file]')));

                $fileInput = $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('.ui-searchbar-body form input[type=file]'));

                $fileInput->setFileDetector(new LocalFileDetector());

                $fileInput->sendKeys($tmp_file);

                if (file_exists($tmp_file)) {
                    unlink($tmp_file);
                }
            }
            catch (WebDriverException $e) {
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                    "\n" . $e->getMessage(), 2 => $url['asin']]);
            }
        }

        $response = [];

        $count_page = 1;

        do {
            $count_last = count($response);

            if ($count_page > 1 && config('ali_count_page') && (count($response) < config('ali_count_page'))) {
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');

                usleep(1000000);

                try {
                    $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('.seb-pagination__pages-link.pages-next'))->
                        click();
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                    break;
                }
            }

            $is_next_page = false;

            if ($url['type'] == 'img') {
                try {
                    $GLOBALS['DRIVER']->wait()->until(WebDriverExpectedCondition::
                        visibilityOfElementLocated(WebDriverBy::cssSelector('.iamgesaerch-offer-list-wrapper')));

                    if (config('ali_search_by_category') == '1') {
                        $GLOBALS['DRIVER']->get($GLOBALS['DRIVER']->getCurrentURL() . '&categoryId=' . $url['CatId']);

                        $GLOBALS['DRIVER']->wait()->until(WebDriverExpectedCondition::
                            visibilityOfElementLocated(WebDriverBy::cssSelector('.iamgesaerch-offer-list-wrapper')));
                    }

                    usleep(1000000);

                    $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');

                    usleep(1000000);

                    $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');

                    usleep(1000000);

                    $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');

                    usleep(1000000);

                    $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');

                    usleep(1000000);

                    $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');

                    foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.col-gallery-item')) as
                        $key => $elementContent) {

                        $key = $count_last + $key;

                        try {
                            $response[$key]['img'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.bc-ife-gallery-image-box img'))->getAttribute('src'));
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['url'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.bc-ife-gallery-item-title-link'))->getAttribute('href'));
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['title'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.bc-ife-gallery-item-title-link'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['normal_price'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.bc-ife-gallery-price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['price_normal'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.elements-offer-price-normal'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        if (!isset($response[$key]['price_normal'])) {
                            try {
                                $response[$key]['price_normal'] = trim($elementContent->findElement(WebDriverBy::
                                    cssSelector('.elements-offer-price-normal__promotion'))->getText());
                            }
                            catch (WebDriverException $e) {
                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }
                        if (true) {
                            try {
                                $response[$key]['offer_price'] = trim($elementContent->findElement(WebDriverBy::
                                    cssSelector('span[class*=elements-offer-price]'))->getText());
                            }
                            catch (WebDriverException $e) {
                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }
                        try {
                            $response[$key]['section_price'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.organic-gallery-offer-section__price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['tag_year'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.s-gold-supplier-year-icon'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        $response[$key]['Images'] = [];

                        for ($i = 0; $i < 30; $i++) {
                            try {
                                if ($i > 0) {
                                    try {
                                        $GLOBALS['DRIVER']->action()->moveToElement($elementContent->findElement(WebDriverBy::
                                            cssSelector('.bc-ife-gallery-item-title-link')))->perform();
                                    }
                                    catch (WebDriverException $e) {
                                    }

                                    $elementContent->findElement(WebDriverBy::cssSelector('.img-next'))->click();
                                }

                                $data_image = trim($elementContent->findElement(WebDriverBy::cssSelector('.bc-ife-gallery-image-box img'))->
                                    getAttribute('src'));

                                if (!in_array($data_image, $response[$key]['Images'])) {
                                    $response[$key]['Images'][] = $data_image;
                                } else {
                                    break;
                                }
                            }
                            catch (WebDriverException $e) {
                                break;

                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }

                        try {
                            $response[$key]['minorder'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.bc-ife-gallery-minorder'))->getText());

                            $response[$key]['minorder'] = preg_replace("#\(.*$#", '', $response[$key]['minorder']);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        try {
                            $response[$key]['cp-name'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.bc-ife-gallery-company-title-link'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        try {
                            foreach ($elementContent->findElements(WebDriverBy::cssSelector('.icbu-icon-svg path')) as
                                $icon) {
                                $attr = null;

                                try {
                                    $attr = trim($icon->getAttribute('fill'));
                                }
                                catch (WebDriverException $e) {
                                }

                                if ($attr == '#FFF') {
                                    $response[$key]['trade_ashurance'] = 1;
                                }

                                if ($attr == '#FED340') {
                                    $response[$key]['verified'] = 1;
                                }

                                if ($attr == '#FACE32') {
                                    $response[$key]['verified'] = 1;
                                }
                            }

                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        if (config('ali_count_page') && count($response) >= config('ali_count_page')) {
                            break;
                        }

                        //print_r($response); //delete

                        //break; //delete
                    }
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }
            } else {
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.1);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.2);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.3);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.4);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.5);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.6);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.7);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.8);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.9);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.3);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.4);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.5);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.5);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.6);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.7);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.8);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.9);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.3);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.4);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.5);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.5);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.6);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.7);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.8);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight*0.9);');
                usleep(100000);
                $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight);');
                usleep(100000);

                try {
                    foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.app-organic-search__list .list-no-v2-inner')) as
                        $key => $elementContent) {

                        $key = $count_last + $key;

                        $response[$key] = [];
                        $response[$key]['features'] = [];
                        $response[$key]['pid'] = trim($elementContent->getAttribute('data-pid'));
                        try {
                            $tmpElement = $elementContent->findElement(WebDriverBy::cssSelector('.text-ellipsis.list-no-v2-decisionsup__element'));
                            $response[$key]['decisionsup'] = trim($tmpElement->getAttribute('href'));
                            $elementContent->findElement(WebDriverBy::cssSelector('.seller-tag__country'))->
                                click();
                            $GLOBALS['DRIVER']->wait(10)->until(WebDriverExpectedCondition::
                                visibilityOfElementLocated(WebDriverBy::cssSelector('.supplier-tag-popup__content_href')));
                            $response[$key]['cp-name'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.supplier-tag-popup__content_href'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['img'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.seb-img-switcher__imgs'))->getAttribute('data-image'));
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['promotion_activity'] = $elementContent->findElement(WebDriverBy::
                                cssSelector('.promotion-activity-tag pc')) ? '1' : '0';
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['title'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.elements-title-normal__content'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['parameters_p'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.element-key-parameters-p'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['url'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.elements-title-normal'))->getAttribute('href'));
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['title_chuc'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.tags-below-title__chuc.tag-style-orange"'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['price_normal'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.elements-offer-price-normal'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        if (!isset($response[$key]['price_normal'])) {
                            try {
                                $response[$key]['price_normal'] = trim($elementContent->findElement(WebDriverBy::
                                    cssSelector('.elements-offer-price-normal__promotion'))->getText());
                            }
                            catch (WebDriverException $e) {
                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }
                        if (true) {
                            try {
                                $response[$key]['offer_price'] = trim($elementContent->findElement(WebDriverBy::
                                    cssSelector('span[class*=elements-offer-price]'))->getText());
                            }
                            catch (WebDriverException $e) {
                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }
                        try {
                            $response[$key]['normal_price'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.elements-offer-price-normal__price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['section_price'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.organic-gallery-offer-section__price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['tag_year'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.seller-tag__year.flex-no-shrink'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['score_section'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.seb-supplier-review__score-section'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['review_reviews'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.seb-supplier-review__reviews'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['verified'] = $elementContent->findElement(WebDriverBy::
                                cssSelector('.icbu-certificate-icon.icbu-certificate-icon-verified.supplier-tag-verified')) ?
                                '1' : '0';
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['tag_country'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.seller-tag__country'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['minorder'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.element-offer-minorder-normal__value'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['review_reviews_score'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.seb-supplier-review__reviews has-score'))->getText());
                        }
                        catch (WebDriverException $e) {
                            //log_write_echo(dirname(__dir__) . '/alibaba.log', $e->getMessage());
                        }
                        try {
                            foreach ($elementContent->findElements(WebDriverBy::cssSelector('.gallery-offer-seller-tag i')) as
                                $element) {
                                try {
                                    if ($element->getAttribute('class') ==
                                        'iconfont iconzuanshi seller-star-level__dm dm-orange') {
                                        $response[$key]['level__dm']['orange'][] = 1;
                                    } elseif ($element->getAttribute('class') ==
                                    'iconfont iconzuanshi seller-star-level__dm dm-grey') {
                                        $response[$key]['level__dm']['grey'][] = 1;
                                    }
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }
                            }
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            foreach ($elementContent->findElements(WebDriverBy::cssSelector('.tags-below-title__chuc-container')) as
                                $element) {
                                $string = trim($element->getText());
                                $response[$key]['features'][] = $string;
                                if ($string == 'Ready To Ship') {
                                    $response[$key]['ready_to_ship'] = '1';
                                }

                                if ($string == 'Sample Available') {
                                    $response[$key]['sample_available'] = '1';
                                }

                                if ($string == 'Online customization') {
                                    $response[$key]['online_customization'] = '1';
                                }
                            }
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['shipping_price_price'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.element-promotion-shipping-price__price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            //log_write_echo(dirname(__dir__) . '/alibaba.log', $e->getMessage());
                        }
                        try {
                            $response[$key]['shipping_price_line'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.element-promotion-shipping-price__line'))->getText());
                        }
                        catch (WebDriverException $e) {
                            //log_write_echo(dirname(__dir__) . '/alibaba.log', $e->getMessage());
                        }
                        try {
                            $response[$key]['shipping_price_shipping'] = trim($elementContent->findElement(WebDriverBy::
                                cssSelector('.element-promotion-shipping-price__shipping'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['shipping_price__price_shipping'] = trim($elementContent->
                                findElement(WebDriverBy::cssSelector('.element-promotion-shipping-price__price'))->
                                getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                        try {
                            $response[$key]['tag_below_item'] = $elementContent->findElement(WebDriverBy::
                                cssSelector('img[data-role=product-auth-tag]')) ? '1' : '0';
                        }
                        catch (WebDriverException $e) {
                        }

                        $response[$key]['Images'] = [];
                        for ($i = 0; $i < 30; $i++) {
                            try {
                                if ($i > 0) {
                                    $elementContent->findElement(WebDriverBy::cssSelector('.seb-img-switcher__arrow-right'))->
                                        click();
                                }

                                $data_image = trim($elementContent->findElement(WebDriverBy::cssSelector('.seb-img-switcher__imgs'))->
                                    getAttribute('data-image'));
                                if (!in_array($data_image, $response[$key]['Images'])) {
                                    $response[$key]['Images'][] = $data_image;
                                } else {
                                    break;
                                }
                            }
                            catch (WebDriverException $e) {
                                break;
                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }

                        if (!$response[$key]['cp-name']) {
                            try {
                                $response[$key]['cp-name'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                    cssSelector('.supplier-tag-popup__content_href'))->getText());
                            }
                            catch (WebDriverException $e) {
                            }
                        }

                        if (!$response[$key]['cp-name'] && isset($response[$key]['decisionsup'])) {
                            $htmltmp = file_get_contents(preg_replace("#^//#", 'https://', $response['decisionsup']));
                            if ($htmltmp) {
                                if (preg_match('#<span class="cp-name">(.*?)<#', $htmltmp, $matches)) {
                                    $response[$key]['cp-name'] = $matches[1];
                                }
                            }
                        }

                        if (config('ali_count_page') && count($response) >= config('ali_count_page')) {
                            break;
                        }

                        //break; //delete
                    }
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }
            }

            if (config('ali_count_page') && count($response) >= config('ali_count_page')) {
                break;
            }

            $count_page++;
        } while (config('ali_count_page') && (count($response) < config('ali_count_page')));

        $rows = [];

        foreach ($response as $key => $item) {
            if (isset($response[$key]['Images']) && is_array($response[$key]['Images'])) {
                $rows[$key]['Images_Ali'] = $response[$key]['Images'];
            } else {
                $rows[$key]['Images_Ali'] = [];
            }

            foreach ($rows[$key]['Images_Ali'] as $k => $v) {
                $rows[$key]['Images_Ali'][$k] = str_replace(['.jpg_50x50.jpg',
                    '.jpg_100x100.jpg', '.jpg_220x220.jpg', '.jpg_300x300.jpg'], '.jpg', $rows[$key]['Images_Ali'][$k]);
                $rows[$key]['Images_Ali'][$k] = preg_replace("#^//#", 'https://', $rows[$key]['Images_Ali'][$k]);
            }

            if (!$response[$key]['pid'] && $response[$key]['url']) {
                if (preg_match("#(\d+)\.html#", $response[$key]['url'], $match)) {
                    $response[$key]['pid'] = $item['pid'] = $match[1];
                }
            }

            $PriceMax_Ali = null;
            if (!$PriceMax_Ali && isset($response[$key]['normal_price'])) {
                $expl = explode('-', $response[$key]['normal_price']);
                if (isset($expl[1])) {
                    $PriceMax_Ali = parsefloatstrval($expl[1]);
                } elseif (isset($expl[0])) {
                    $PriceMax_Ali = parsefloatstrval($expl[0]);
                }
            }

            if (!$PriceMax_Ali && isset($response[$key]['price_normal'])) {
                $expl = explode('-', $response[$key]['price_normal']);
                if (isset($expl[1])) {
                    $PriceMax_Ali = parsefloatstrval($expl[1]);
                } elseif (isset($expl[0])) {
                    $PriceMax_Ali = parsefloatstrval($expl[0]);
                }
            }

            if (!$PriceMax_Ali && isset($response[$key]['section_price'])) {
                $expl = explode('-', $response[$key]['section_price']);
                if (isset($expl[1])) {
                    $PriceMax_Ali = parsefloatstrval($expl[1]);
                } elseif (isset($expl[0])) {
                    $PriceMax_Ali = parsefloatstrval($expl[0]);
                }
            }

            if (!$PriceMax_Ali && isset($response[$key]['offer_price'])) {
                $expl = explode('-', $response[$key]['offer_price']);
                if (isset($expl[1])) {
                    $PriceMax_Ali = parsefloatstrval($expl[1]);
                } elseif (isset($expl[0])) {
                    $PriceMax_Ali = parsefloatstrval($expl[0]);
                }
            }

            $count_match = preg_match_all("#,#", $PriceMax_Ali);
            if ($count_match > 1) {
                $PriceMax_Ali = preg_replace('#,#', '', $PriceMax_Ali, $count_match - 1);
            }

            if ($url['type'] != 'img') {
                if (config('ali_trade_ashurance')) {
                    $rows[$key]['Trade$_Ali'] = 'yes';
                }
            }

            $htmlList = $GLOBALS['DRIVER']->getPageSource();
            if (isset($response[$key]['cp-name'])) {
                $rows[$key]['Seller_Ali'] = trim($response[$key]['cp-name']);
            }

            $rows[$key]['asin'] = $url['asin'];
            $rows[$key]['id_Ali'] = $response[$key]['pid'];
            $rows[$key]['URL_Ali'] = $response[$key]['url'];
            $rows[$key]['Title_Ali'] = $response[$key]['title'];
            $rows[$key]['PriceMax_Ali'] = parsefloatstrval($PriceMax_Ali);
            if (isset($response[$key]['trade_ashurance']) && $response[$key]['trade_ashurance'] ==
                '1') {
                $rows[$key]['Trade$_Ali'] = 'yes';
            }
            $rows[$key]['Verified_Ali'] = $response[$key]['verified'] == '1' ? 'yes' : 'no';
            $rows[$key]['Reviews_Ali'] = parsefloatstrval(preg_replace("#^.*?\(#", '', $response[$key]['score_section']));
            $rows[$key]['Image_Ali'] = $response[$key]['img'];
            $rows[$key]['Image_Ali'] = str_replace(['_50x50.jpg', '_220x220.jpg',
                '_100x100.jpg', '_300x300.jpg'], '', $rows[$key]['Image_Ali']);
            $rows[$key]['Image_Ali'] = preg_replace("#^//#", 'https://', $rows[$key]['Image_Ali']);
            $rows[$key]['Rating_Ali'] = floatval(preg_replace("#[^\d.].*?$#", "",
                str_replace(',', '.', $response[$key]['score_section'])));
            $rows[$key]['Yrs_Ali'] = $response[$key]['tag_year'];
            if (isset($response[$key]['tag_country']))
                $rows[$key]['CountryS_Ali'] = $response[$key]['tag_country'];
            $rows[$key]['MOQ_Ali'] = $response[$key]['minorder'];
            if (isset($response[$key]['features']))
                $rows[$key]['Ready To Ship'] = in_array('Ready to Ship', $response[$key]['features']) ?
                    'yes' : 'no';
            if ($response[$key]['shipping_price__price_shipping']) {
                $rows[$key]['ShippingPrice'] = parsefloatstrval($response[$key]['shipping_price__price_shipping']);
            }
            $rows[$key]['Shipping'] = $response[$key]['shipping_price_shipping'] ? 'yes' :
                'no';
            $rows[$key]['Shipping'] = $url['info']['Shipping'];
            //$rows[$key]['Seller_Ali'] = 'orange-' . (isset($response[$key]['level__dm']['orange']) ?
            //    count($response[$key]['level__dm']['orange']) : 0) . '|' . 'grey-' . (isset($response[$key]['level__dm']['grey']) ?
            //    count($response[$key]['level__dm']['grey']) : 0);
            $rows[$key]['Features'] = implode(' ', $response['features']);
            $rows[$key]['Details'] = $response[$key]['parameters_p'];
            $rows[$key]['Category_Ali'] = $url['CatId'];
            $rows[$key]['Url_Search_Ali'] = $url['url'];
            $rows[$key]['Find_Ali'] = $url['type'];
            $rows[$key]['Find_PriceMin_Ali'] = parsefloatstrval($url['priceMin']);
            $rows[$key]['Find_PriceMax_Ali'] = parsefloatstrval($url['priceMax']);
            $rows[$key]['QueryFind'] = $url['QueryFind'];
            $rows[$key]['Quantity_Ali'] = 1;
            $rows[$key]['ROI_Ali'] = 0;
            $rows[$key]['Find_Length_Ali'] = $url['info']['Package: Length (cm)'] ? $url['info']['Package: Length (cm)'] : null;
            $rows[$key]['Find_Width_Ali'] = $url['info']['Package: Width (cm)'] ? $url['info']['Package: Width (cm)'] : null;
            $rows[$key]['Find_Height_Ali'] = $url['info']['Package: Height (cm)'] ? $url['info']['Package: Height (cm)'] : null;
            $rows[$key]['Quantity_Ali'] = intval(parsefloatstrval(preg_replace("#[^\d,.]#",
                '', config('ali_total_max'))) / (parsefloatstrval(preg_replace("#[^\d,.]#", '',
                $rows[$key]['PriceMax_Ali'])) + (config('include_shipping') == '1' ?
                parsefloatstrval(preg_replace("#[^\d,.]#", '', $rows[$key]['Shipping'])) : 0)));
            $is_in = false;
            if (DB::GetOne('SELECT count(*) FROM `parser_alibaba_results` WHERE `parse_at` is not null and `id` = ?', [1 =>
                $rows[$key]['id_Ali']])) {
                $is_in = true;
            }

            //1234
            if ($rows[$key]['PriceMax_Ali'] < $rows[$key]['Find_PriceMin_Ali'] || $rows[$key]['PriceMax_Ali'] >
                $rows[$key]['Find_PriceMax_Ali']) {
                $is_in = false;
            }

            //$is_in_test = true; // deleted

            $rows[$key]['ROI'] = strval(round((parsefloatstrval($url['info'][config('ali_price_info')]) *
                0.85 - (config('ali_fba_fees') ? parsefloatstrval($url['info']['FBA Fees:']) : 0) -
                config('ali_ship_pp') - parsefloatstrval($rows[$key]['Shipping'])) /
                parsefloatstrval($rows[$key]['Quantity_Ali']) - parsefloatstrval($rows[$key]['PriceMax_Ali']) /
                (($url['info']['pack'] > 0 ? parsefloatstrval($url['info']['pack']) : 1) * (parsefloatstrval
                ($rows[$key]['PriceMax_Ali']) + parsefloatstrval($rows[$key]['Shipping'])) * 100),
                2));

            $rows[$key]['Total_Ali'] = round(parsefloatstrval($rows[$key]['Quantity_Ali']) *
                parsefloatstrval($rows[$key]['PriceMax_Ali']));
            $rows[$key]['Shipping_Ali'] = round(parsefloatstrval($rows[$key]['Shipping']) *
                parsefloatstrval($rows[$key]['Quantity_Ali']));

            if ($is_in_test || (!$is_in && isSuccessDataAlibaba($rows[$key]) && config('ali_open_page') ==
                '1' && !($url['type'] == 'img' && config('ali_search_img_more') == '1'))) {
                try {
                    $open_url = strpos($rows[$key]['URL_Ali'], '//') === 0 ? 'https:' . $rows[$key]['URL_Ali'] :
                        $rows[$key]['URL_Ali'];
                    //$open_url = 'https://www.alibaba.com/product-detail/new-and-Original-stock-301-1c_62585272885.html'; //deleted
                    //$open_url = 'https://www.alibaba.com/product-detail/2021-Moon-Lamp-Led-2021-Upgrades_1600365656098.html?s=p'; //deleted


                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', 'Open Url: ' . $open_url);
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        'Open Url: ' . $open_url . "\n", 2 => $url['asin']]);
                    $GLOBALS['DRIVER']->get($open_url);
                    //$GLOBALS['DRIVER']->get('https://www.alibaba.com/product-detail/Wall-Picture-Frames-Frame-Wall-Picture_1600276556690.html?spm=a2700.galleryofferlist.normal_offer.d_title.516d292fiNh3z8&s=p');

                    $response2 = [];
                    $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight/2);');
                    try {
                        if (!isset($rows[$key]['Images_Ali'])) {
                            $rows[$key]['Images_Ali'] = [];
                        }

                        if ($rows[$key]['Image_Ali']) {
                            //$response2['Images_Ali'][] = str_replace(['_50x50.jpg', '_300x300.jpg'], '', strpos($rows[$key]['Image_Ali'], '//') === 0 ? 'https:'. $rows[$key]['Image_Ali'] : $rows[$key]['Image_Ali']);
                        }

                        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.main-image-thumb-item img[alt=image]')) as
                            $key1 => $element) {
                            $rows[$key]['Images_Ali'][] = str_replace(['_50x50.jpg', '_100x100.jpg',
                                '_300x300.jpg'], '', trim($element->getAttribute('src')));
                        }

                        $rows[$key]['Images_Ali'] = array_unique($rows[$key]['Images_Ali']);
                        try {
                            $response2['company-name'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.company-name-container .company-name'))->getAttribute('title'));
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        $htmlPage = $GLOBALS['DRIVER']->getPageSource();
                        if (stripos($htmlPage, '<div class="ta-tip-title">Trade Assurance</div>')) {
                            $response2['Trade$_Ali'] = 'yes';
                        }

                        if (stripos($htmlPage, '<span class="ta-icon"></span>')) {
                            $response2['Trade$_Ali'] = 'yes';
                        }
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }

                    $details = [];

                    try {
                        $GLOBALS['DRIVER']->wait(10)->until(WebDriverExpectedCondition::
                            presenceOfElementLocated(WebDriverBy::cssSelector('#skuWrap .quantity-up')));
                    }
                    catch (WebDriverException $e) {
                    }


                    $response2[$key]['normal_price'] = null;

                    if (!$response2[$key]['normal_price']) {
                        try {
                            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.ma-spec-price'))->getText());
                        }
                        catch (WebDriverException $e) {
                        }
                    }

                    if (!$response2[$key]['normal_price']) {
                        try {
                            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.ma-price-promotion'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }

                    if (!$response2[$key]['normal_price']) {
                        try {
                            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.ma-ref-price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }
                    if (!$response2[$key]['normal_price']) {
                        try {
                            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.pre-inquiry-price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }
                    if (!$response2[$key]['normal_price']) {
                        try {
                            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.ma-price-promotion'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }
                    if (!$response2[$key]['normal_price']) {
                        try {
                            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.ma-spec-price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }
                    if (!$response2[$key]['normal_price']) {
                        try {
                            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.ma-ref-price'))->getText());
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }

                    if ($response2[$key]['normal_price']) {
                        $PriceMax_Ali = null;
                        if (!$PriceMax_Ali && isset($response2[$key]['normal_price'])) {
                            $expl = explode('-', $response2[$key]['normal_price']);
                            if (isset($expl[1])) {
                                $PriceMax_Ali = parsefloatstrval($expl[1]);
                            } elseif (isset($expl[0])) {
                                $PriceMax_Ali = parsefloatstrval($expl[0]);
                            }
                        }

                        if ($PriceMax_Ali) {
                            $rows[$key]['PriceMax_Ali'] = parsefloatstrval($PriceMax_Ali);

                            $rows[$key]['Quantity_Ali'] = intval(parsefloatstrval(preg_replace("#[^\d,.]#",
                                '', config('ali_total_max'))) / (parsefloatstrval(preg_replace("#[^\d,.]#", '',
                                $rows[$key]['PriceMax_Ali'])) + (config('include_shipping') == '1' ?
                                parsefloatstrval(preg_replace("#[^\d,.]#", '', $rows[$key]['Shipping'])) : 0)));

                            $rows[$key]['ROI'] = strval(round((parsefloatstrval($url['info'][config('ali_price_info')]) *
                                0.85 - (config('ali_fba_fees') ? parsefloatstrval($url['info']['FBA Fees:']) : 0) -
                                config('ali_ship_pp') - parsefloatstrval($rows[$key]['Shipping'])) /
                                parsefloatstrval($rows[$key]['Quantity_Ali']) - parsefloatstrval($rows[$key]['PriceMax_Ali']) /
                                (($url['info']['pack'] > 0 ? parsefloatstrval($url['info']['pack']) : 1) * (parsefloatstrval
                                ($rows[$key]['PriceMax_Ali']) + parsefloatstrval($rows[$key]['Shipping'])) * 100),
                                2));

                            $rows[$key]['Total_Ali'] = round(parsefloatstrval($rows[$key]['Quantity_Ali']) *
                                parsefloatstrval($rows[$key]['PriceMax_Ali']));
                        }
                    }

                    if (config('ali_add_to_cart')) {
                        try {
                            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_actions .express-item')) as
                                $key1 => $element) {
                                $item_left = null;
                                try {
                                    $item_left = trim($element->findElement(WebDriverBy::cssSelector('.item-left'))->
                                        getText());
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }

                                try {
                                    $response2['min_business_type_lite'] = trim($element->findElement(WebDriverBy::
                                        cssSelector('.module_companycard .business-type-lite'))->getText());
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }

                                if ($item_left) {
                                    if (stripos($item_left, 'Total') !== false) {
                                        try {
                                            $response2['min_total'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                                getText());
                                            $response2['min_total'] = str_replace(',', '', $response2['min_total']);
                                            $response2['min_total'] = preg_replace("#[\n ].*$#", '', $response2['min_total']);
                                        }
                                        catch (WebDriverException $e) {
                                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                                        }
                                    }
                                    if (stripos($item_left, 'Ship') !== false) {
                                        try {
                                            $response2['min_shipping'] = trim($element->findElement(WebDriverBy::
                                                cssSelector('.price'))->getText());
                                            $response2['min_shipping'] = str_replace(',', '', $response2['min_shipping']);
                                            $response2['min_shipping'] = preg_replace("#[\n ].*$#", '', $response2['min_shipping']);
                                        }
                                        catch (WebDriverException $e) {
                                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                                        }
                                    } else {
                                        $text = trim($element->getText());
                                        if (stripos($text, 'Shipping time') !== false || stripos($text,
                                            'Processing Time') !== false) {
                                            $response2['min_shipping_time'] = trim(str_replace(['Shipping time',
                                                'Processing Time'], '', $text));
                                        }
                                    }
                                } else {
                                    $text = trim($element->getText());
                                    if (stripos($text, 'Shipping time') !== false || stripos($text,
                                        'Processing Time') !== false) {
                                        $response2['min_shipping_time'] = trim(str_replace(['Shipping time',
                                            'Processing Time'], '', $text));
                                    }
                                }
                            }
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        try {
                            //$GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap input'))->sendKeys(999991);

                            $GLOBALS['DRIVER']->executeScript("document.querySelector('#skuWrap input').value = 999999;");
                            $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-up'))->
                                click();
                            $tmpQuantity_Ali = parsefloatstrval($GLOBALS['DRIVER']->findElement(WebDriverBy::
                                cssSelector('.ui2-balloon .sku-all-quantity'))->getText());
                            if ($tmpQuantity_Ali > 0) {
                                $rows[$key]['MaxQuantity_Ali'] = $tmpQuantity_Ali;
                                //$GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap input'))->sendKeys($rows[$key]['Quantity_Ali'] - 1);

                                $GLOBALS['DRIVER']->executeScript("document.querySelector('#skuWrap input').value = " .
                                    ($rows[$key]['MaxQuantity_Ali']) . ";");
                                $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-down'))->
                                    click();
                                $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-up'))->
                                    click();
                                usleep(150000);
                            }
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        try {
                            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_actions .express-item')) as
                                $key1 => $element) {
                                $item_left = null;
                                try {
                                    $item_left = trim($element->findElement(WebDriverBy::cssSelector('.item-left'))->
                                        getText());
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }

                                try {
                                    $response2['business_type_lite'] = trim($element->findElement(WebDriverBy::
                                        cssSelector('.module_companycard .business-type-lite'))->getText());
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }

                                if ($item_left) {
                                    if (stripos($item_left, 'Total') !== false) {
                                        try {
                                            $response2['max_total'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                                getText());
                                            $response2['max_total'] = str_replace(',', '', $response2['max_total']);
                                            $response2['max_total'] = preg_replace("#[\n ].*$#", '', $response2['max_total']);
                                        }
                                        catch (WebDriverException $e) {
                                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                                        }
                                    } elseif (stripos($item_left, 'Ship') !== false) {
                                        try {
                                            $response2['max_shipping'] = trim($element->findElement(WebDriverBy::
                                                cssSelector('.price'))->getText());
                                            $response2['max_shipping'] = str_replace(',', '', $response2['max_shipping']);
                                            $response2['max_shipping'] = preg_replace("#[\n ].*$#", '', $response2['max_shipping']);
                                        }
                                        catch (WebDriverException $e) {
                                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                                        }
                                    } else {
                                        $text = trim($element->getText());
                                        if (stripos($text, 'Shipping time') !== false || stripos($text,
                                            'Processing Time') !== false) {
                                            $response2['shipping_time'] = trim(str_replace(['Shipping time',
                                                'Processing Time'], '', $text));
                                        }
                                    }
                                } else {
                                    $text = trim($element->getText());
                                    if (stripos($text, 'Shipping time') !== false || stripos($text,
                                        'Processing Time') !== false) {
                                        $response2['shipping_time'] = trim(str_replace(['Shipping time',
                                            'Processing Time'], '', $text));
                                    }
                                }
                            }
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        try {
                            $GLOBALS['DRIVER']->executeScript("document.querySelector('#skuWrap input').value = " .
                                ($rows[$key]['Quantity_Ali']) . ";");
                            $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-down'))->
                                click();
                            $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-up'))->
                                click();
                            usleep(150000);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }

                        try {
                            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_actions .express-item')) as
                                $key1 => $element) {
                                $item_left = null;
                                try {
                                    $item_left = trim($element->findElement(WebDriverBy::cssSelector('.item-left'))->
                                        getText());
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }

                                try {
                                    $response2['business_type_lite'] = trim($element->findElement(WebDriverBy::
                                        cssSelector('.module_companycard .business-type-lite'))->getText());
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }

                                $htmlPage = $GLOBALS['DRIVER']->getPageSource();

                                if (!isset($response2['shipping_time'])) {
                                    if (preg_match("#<span>Shipping time(.*?)</span>#", $htmlPage, $matches)) {
                                        $response2['shipping_time'] = trim(str_replace(['Shipping time',
                                            'Processing Time'], '', strip_tags(html_entity_decode($matches[1]))));
                                    }
                                }

                                if ($item_left) {
                                    if (stripos($item_left, 'Total') !== false) {
                                        try {
                                            $response2['total'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                                getText());
                                            $response2['total'] = str_replace(',', '', $response2['total']);
                                            $response2['total'] = preg_replace("#[\n ].*$#", '', $response2['total']);
                                        }
                                        catch (WebDriverException $e) {
                                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                                        }
                                    } elseif (stripos($item_left, 'Ship') !== false) {
                                        try {
                                            $response2['shipping'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                                getText());
                                            $response2['shipping'] = str_replace(',', '', $response2['shipping']);
                                            $response2['shipping'] = preg_replace("#[\n ].*$#", '', $response2['shipping']);
                                        }
                                        catch (WebDriverException $e) {
                                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                                        }
                                    } else {
                                        $text = trim($element->getText());
                                        if (stripos($text, 'Shipping time') !== false || stripos($text,
                                            'Processing Time') !== false) {
                                            $response2['shipping_time'] = trim(str_replace(['Shipping time',
                                                'Processing Time'], '', $text));
                                        }
                                    }
                                } else {
                                    $text = trim($element->getText());
                                    if (stripos($text, 'Shipping time') !== false || stripos($text,
                                        'Processing Time') !== false) {
                                        $response2['shipping_time'] = trim(str_replace(['Shipping time',
                                            'Processing Time'], '', $text));
                                    }
                                }
                            }
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }

                    try {
                        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_product_packaging_and_quick_detail .do-entry-list > .do-entry-item')) as
                            $key1 => $element) {
                            try {
                                $name = trim(trim(trim($element->findElement(WebDriverBy::cssSelector('dt'))->
                                    getText()), ':'));
                                try {
                                    if ($name) {
                                        $value = trim($element->findElement(WebDriverBy::cssSelector('dd'))->getText());
                                        if ($value) {
                                            $details[$name] = $value;
                                        }
                                    }
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }
                            }
                            catch (WebDriverException $e) {
                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }

                    $rows[$key]['details2'] = [];
                    try {
                        $is_tmp = false;
                        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.next-tabs-nav .next-tabs-tab-inner')) as
                            $key1 => $element) {
                            try {
                                if ($key1 > 0 && !$is_tmp) {
                                    $element->click();
                                    $GLOBALS['DRIVER']->wait(5)->until(WebDriverExpectedCondition::
                                        visibilityOfElementLocated(WebDriverBy::cssSelector('.company-basicInfo')));
                                    $GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.company-basicInfo'));
                                    $is_tmp = true;
                                }
                            }
                            catch (WebDriverException $e) {
                                $is_tmp = false;
                                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                    "\n" . $e->getMessage(), 2 => $url['asin']]);
                            }
                        }
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }

                    try {
                        try {
                            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.company-basicInfo > tr')) as
                                $key1 => $element) {

                                try {
                                    try {
                                        $tmp1 = [];
                                        $tmp2 = [];
                                        foreach ($element->findElements(WebDriverBy::cssSelector('.field-title')) as $key2 =>
                                            $element2) {
                                            $tmp1[] = trim($element2->getText());
                                        }
                                        foreach ($element->findElements(WebDriverBy::cssSelector('.field-content-wrap')) as
                                            $key2 => $element2) {
                                            $tmp2[] = trim($element2->getText());
                                        }

                                        if (count($tmp1) == count($tmp2)) {
                                            foreach ($tmp1 as $k => $v) {
                                                $rows[$key]['details2'][$tmp1[$k]] = $tmp2[$k];
                                            }
                                        }
                                    }
                                    catch (WebDriverException $e) {
                                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                                    }
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }
                            }
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }

                    try {
                        $response2['business_type_lite'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                            cssSelector('.business-type-lite'))->getText());
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }

                    if (isset($details['Model Number'])) {
                        $response2['model'] = $details['Model Number'];
                    }

                    if (isset($details['Brand Name'])) {
                        $response2['brand'] = $details['Brand Name'];
                    }

                    if (isset($details['Single package size'])) {
                        $response2['single_package_size'] = $details['Single package size'];
                    }

                    if (isset($details['Color'])) {
                        $response2['color'] = $details['Color'];
                    }

                    if (isset($details['Place of Origin'])) {
                        $response2['place_of_origin'] = $details['Place of Origin'];
                    }

                    $leadtime = [];
                    try {
                        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_product_packaging_and_quick_detail .leadtime-table .supply-ability-table tr')) as
                            $key1 => $element) {
                            foreach ($element->findElements(WebDriverBy::cssSelector('td')) as $key2 => $td) {
                                try {
                                    $leadtime[$key1][$key2] = trim($td->getText());
                                }
                                catch (WebDriverException $e) {
                                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                                }
                            }
                        }
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }

                    foreach ($leadtime as $key1 => $item) {
                        if ($item[0] == 'Est. Time(days)') {
                            $response2['est_time'] = $item[1];
                        }
                    }

                    if (isset($details['Single gross weight'])) {
                        $response2['single_gross_weight'] = $details['Single gross weight'];
                    } elseif (isset($details['Gross Weight'])) {
                        $response2['gross_weight'] = $details['Gross Weight'];
                    }

                    try {
                        $response2['company_name'] = trim($elementContent->findElement(WebDriverBy::
                            cssSelector('#module_companycard .company-name'))->getText());
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }

                    $rows[$key]['details'] = $details;
                    if (isset($details['Business Type'])) {
                        $response2['business_type'] = $details['Business Type'];
                    } elseif (isset($rows[$key]['details2']['Business Type'])) {
                        $response2['business_type'] = $rows[$key]['details2']['Business Type'];
                    }
                    if (isset($response2['Trade$_Ali'])) {
                        $rows[$key]['Trade$_Ali'] = $response2['Trade$_Ali'];
                    }
                    if (isset($response2['company-name'])) {
                        $rows[$key]['Seller_Ali'] = $response2['company-name'];
                    }

                    if (isset($response2['total']))
                        $rows[$key]['Total_Ali*'] = parsefloatstrval($response2['total']);
                    if (isset($response2['shipping']))
                        $rows[$key]['Shipping_Ali*'] = parsefloatstrval($response2['shipping']);

                    if (isset($response2['max_total']))
                        $rows[$key]['Max_Total_Ali'] = $response2['max_total'];
                    if (isset($response2['max_shipping']))
                        $rows[$key]['Max_Shipping_Ali'] = $response2['max_shipping'];
                    if ($response2['shipping_time']) {
                        $rows[$key]['Shipping_Time*'] = $response2['shipping_time'];
                    }

                    $rows[$key]['Model_Ali'] = $response2['model'];
                    $rows[$key]['Brand_Ali'] = $response2['brand'];

                    $rows[$key]['ROI_Ali'] = strval(round((parsefloatstrval($url['info'][config('ali_price_info')]) *
                        0.85 - (config('ali_fba_fees') ? parsefloatstrval($url['info']['FBA Fees:']) : 0) -
                        config('ali_ship_pp') - parsefloatstrval($rows[$key]['Shipping_Ali'])) /
                        parsefloatstrval($rows[$key]['Quantity_Ali']) - parsefloatstrval($rows[$key]['PriceMax_Ali']) /
                        (($url['info']['pack'] > 0 ? parsefloatstrval($url['info']['pack']) : 1) * (parsefloatstrval
                        ($rows[$key]['PriceMax_Ali']) + parsefloatstrval($rows[$key]['Shipping_Ali']) /
                        parsefloatstrval($rows[$key]['Quantity_Ali'])) * 100), 2));

                    if ($response2['business_type']) {
                        $rows[$key]['Profile_Ali'] = $response2['business_type'];
                    }

                    if (isset($response2['business_type_lite']) && $response2['business_type_lite']) {
                        $rows[$key]['Profile_Ali'] = $response2['business_type_lite'];
                    }

                    //print_r($response2); // delete

                    $rows[$key]['Country_Ali'] = $response2['place_of_origin'];
                    if ($response2['single_gross_weight'])
                        $rows[$key]['Weight_Ali'] = trim(str_replace('kg', '', $response2['single_gross_weight']));
                    if ($response2['gross_weight'])
                        $rows[$key]['Weight_Ali'] = trim(str_replace('kg', '', $response2['gross_weight']));
                    $rows[$key]['EstTime_Ali'] = $response2['est_time'];
                    $rows[$key]['Single package size'] = trim(str_replace('cm', '', $response2['single_package_size']));
                    $rows[$key]['Color_Ali'] = $response2['color'];
                    if ($rows[$key]['Weight_Ali'])
                        $rows[$key]['%Weight'] = round((parsefloatstrval($rows[$key]['Weight_Ali']) / ($url['info']['Package: Weight (g)'] /
                            1000 - 1)) * 100, 2);
                    if ($details['Place of Origin']) {
                        $rows[$key]['CountryOrigin_Ali'] = trim(preg_replace("#^.*,#", '', $details['Place of Origin']));
                    }

                    if ($rows[$key]['Find_Length_Ali'] && $rows[$key]['Find_Width_Ali'] && $rows[$key]['Find_Height_Ali']) {
                        $tmp = explode('X', $rows[$key]['Single package size']);
                        if (count($tmp) == 3) {
                            if (parsefloatstrval($rows[$key]['Find_Length_Ali']) > 0 && parsefloatstrval($rows[$key]['Find_Width_Ali']) >
                                0 && parsefloatstrval($rows[$key]['Find_Height_Ali']) > 0 && parsefloatstrval($tmp[0]) >
                                0 && parsefloatstrval($tmp[1]) > 0 && parsefloatstrval($tmp[2]) > 0) {
                                $rows[$key]['%Length'] = round(((parsefloatstrval($tmp[0]) / parsefloatstrval($rows[$key]['Find_Length_Ali'])) *
                                    100) - 100, 2);
                                $rows[$key]['%Width'] = round(((parsefloatstrval($tmp[1]) / parsefloatstrval($rows[$key]['Find_Width_Ali'])) *
                                    100) - 100, 2);
                                $rows[$key]['%Height'] = round(((parsefloatstrval($tmp[2]) / parsefloatstrval($rows[$key]['Find_Height_Ali'])) *
                                    100) - 100, 2);
                                $rows[$key]['%Package'] = round(($rows[$key]['%Length'] + $rows[$key]['%Width'] +
                                    $rows[$key]['%Height']) / 3, 2);
                            }
                        }
                    }

                    if (false && config('ali_checking_length_model') && $rows[$key]['Model_Ali'] &&
                        ($url['info']['Product Codes: PartNumber'] || $url['info']['Model']) &&
                        mb_strlen($rows[$key]['Model_Ali']) >= config('ali_checking_length_model')) {
                        $tmp1 = null;
                        $tmp2 = null;
                        $tmp3 = null;
                        $tmp1 = preg_replace("#[^a-zA-Z0-9]#", '', $rows[$key]['Model_Ali']);
                        if ($url['info']['Product Codes: PartNumber'] && preg_match("#\d#", $url['info']['Product Codes: PartNumber']) &&
                            mb_strlen($url['info']['Product Codes: PartNumber']) >= config('ali_checking_length_model'))
                            $tmp2 = preg_replace("#[^a-zA-Z0-9]#", '', $url['info']['Product Codes: PartNumber']);
                        if ($url['info']['Model'] && preg_match("#\d#", $url['info']['Model']) &&
                            mb_strlen($url['info']['Model']) >= config('ali_checking_length_model'))
                            $tmp3 = preg_replace("#[^a-zA-Z0-9]#", '', $url['info']['Model']);
                        if (stripos($tmp1, $tmp2) !== false || stripos($tmp1, $tmp3) !== false) {
                            $rows[$key]['Model'] = 'yes';
                        } else {
                            $rows[$key]['Model'] = 'no';
                        }
                    }
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }


            }

            $rows[$key]['Sales30'] = $url['info']['Sales30'];
            $rows[$key]['Sales30,$'] = $url['info']['Sales30,$'];
            $rows[$key]['Shipping'] = $url['info']['Shipping'];
            $rows[$key]['Margin'] = $url['info']['Margin'];
            $rows[$key]['Profit30'] = $url['info']['Profit30'];
            $rows[$key]['checking'] = isSuccessDataAlibaba($rows[$key]) ? 1 : 0;
            //print_r($rows[$key]);// delete
            //exit; // delete
        }

        //print_r($rows); // delete
        //exit; // delete

        saveRows($rows);
        DB::execute('UPDATE `parser_china` SET `parse_at` = ? WHERE `id` = ?', [1 =>
            date('Y-m-d H:i:s'), 2 => $url['rowid'], ]);
    }
}

function parsePageByRowsKey($url, $open_url, &$rows, $key)
{
    log_write_echo(dirname(__dir__ ) . '/alibaba.log', 'Open Url: ' . $open_url);
    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
        'Open Url: ' . $open_url . "\n", 2 => $url['asin']]);
    $GLOBALS['DRIVER']->get($open_url);
    //$GLOBALS['DRIVER']->get('https://www.alibaba.com/product-detail/Wall-Picture-Frames-Frame-Wall-Picture_1600276556690.html?spm=a2700.galleryofferlist.normal_offer.d_title.516d292fiNh3z8&s=p');

    $response2 = [];
    try {
        $GLOBALS['DRIVER']->executeScript('window.scrollTo(0,document.body.scrollHeight/2);');


        try {
            $GLOBALS['DRIVER']->wait(10)->until(WebDriverExpectedCondition::
                presenceOfElementLocated(WebDriverBy::cssSelector('#skuWrap .quantity-up')));
        }
        catch (WebDriverException $e) {
        }

        try {
            $response2[$key]['title'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                cssSelector('h1'))->getText());
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
        }

        try {
            $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                cssSelector('.ma-ref-price'))->getText());
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }
        if (!$response2[$key]['normal_price']) {
            try {
                $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                    cssSelector('.ma-price-promotion'))->getText());
            }
            catch (WebDriverException $e) {
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                    "\n" . $e->getMessage(), 2 => $url['asin']]);
            }
        }
        if (!$response2[$key]['normal_price']) {
            try {
                $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                    cssSelector('.ma-spec-price'))->getText());
            }
            catch (WebDriverException $e) {
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                    "\n" . $e->getMessage(), 2 => $url['asin']]);
            }
        }
        if (!$response2[$key]['normal_price']) {
            try {
                $response2[$key]['normal_price'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                    cssSelector('.ma-ref-price'))->getText());
            }
            catch (WebDriverException $e) {
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                    "\n" . $e->getMessage(), 2 => $url['asin']]);
            }
        }

        try {
            $response2[$key]['join_year'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                cssSelector('.join-year'))->getText());
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }
        try {
            $response2[$key]['company_name_country'] = trim($GLOBALS['DRIVER']->findElement
                (WebDriverBy::cssSelector('.company-name-country'))->getText());
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }

        $response2['Images_Ali'] = [];

        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.main-image-thumb-item img[alt=image]')) as
            $key1 => $element) {
            $response2['Images_Ali'][] = str_replace(['_50x50.jpg', '_100x100.jpg',
                '_300x300.jpg'], '', trim($element->getAttribute('src')));
        }

        $response2['Images_Ali'] = array_unique($response2['Images_Ali']);

        if (!empty($response2['Images_Ali'])) {
            $response2['Image_Ali'] = $response2['Images_Ali'][0];
        }

        try {
            $response2['company-name'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
                cssSelector('.company-name-container .company-name'))->getAttribute('title'));
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }

        $htmlPage = $GLOBALS['DRIVER']->getPageSource();
        if (stripos($htmlPage, '<div class="ta-tip-title">Trade Assurance</div>')) {
            $response2['Trade$_Ali'] = 'yes';
        }

        if (stripos($htmlPage, '<span class="ta-icon"></span>')) {
            $response2['Trade$_Ali'] = 'yes';
        }
    }
    catch (WebDriverException $e) {
        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            "\n" . $e->getMessage(), 2 => $url['asin']]);
    }

    $details = [];
    try {
        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_product_packaging_and_quick_detail .do-entry-list > .do-entry-item')) as
            $key1 => $element) {
            try {
                $name = trim(trim(trim($element->findElement(WebDriverBy::cssSelector('dt'))->
                    getText()), ':'));
                try {
                    if ($name) {
                        $value = trim($element->findElement(WebDriverBy::cssSelector('dd'))->getText());
                        if ($value) {
                            $details[$name] = $value;
                        }
                    }
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }
            }
            catch (WebDriverException $e) {
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                    "\n" . $e->getMessage(), 2 => $url['asin']]);
            }
        }
    }
    catch (WebDriverException $e) {
        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            "\n" . $e->getMessage(), 2 => $url['asin']]);
    }

    if (isset($details['Model Number'])) {
        $response2['model'] = $details['Model Number'];
    }

    if (isset($details['Brand Name'])) {
        $response2['brand'] = $details['Brand Name'];
    }

    if (isset($details['Single package size'])) {
        $response2['single_package_size'] = $details['Single package size'];
    }

    if (isset($details['Color'])) {
        $response2['color'] = $details['Color'];
    }

    if (isset($details['Place of Origin'])) {
        $response2['place_of_origin'] = $details['Place of Origin'];
    }

    $leadtime = [];
    try {
        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_product_packaging_and_quick_detail .leadtime-table .supply-ability-table tr')) as
            $key1 => $element) {
            foreach ($element->findElements(WebDriverBy::cssSelector('td')) as $key2 => $td) {
                try {
                    $leadtime[$key1][$key2] = trim($td->getText());
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }
            }
        }
    }
    catch (WebDriverException $e) {
        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            "\n" . $e->getMessage(), 2 => $url['asin']]);
    }

    foreach ($leadtime as $key1 => $item) {
        if ($item[0] == 'Est. Time(days)') {
            $response2['est_time'] = $item[1];
        }
    }

    if (isset($details['Single gross weight'])) {
        $response2['single_gross_weight'] = $details['Single gross weight'];
    } elseif (isset($details['Gross Weight'])) {
        $response2['gross_weight'] = $details['Gross Weight'];
    }

    if (preg_match("#SEMR\.productId = '(.*?)';#", $htmlPage, $matches)) {
        $response2[$key]['pid'] = $matches[1];
    }

    $rows[$key]['asin'] = $url['asin'];
    $rows[$key]['URL_Ali'] = $url['url'];
    $rows[$key]['Find_Length_Ali'] = $url['info']['Package: Length (cm)'] ? $url['info']['Package: Length (cm)'] : null;
    $rows[$key]['Find_Width_Ali'] = $url['info']['Package: Width (cm)'] ? $url['info']['Package: Width (cm)'] : null;
    $rows[$key]['Find_Height_Ali'] = $url['info']['Package: Height (cm)'] ? $url['info']['Package: Height (cm)'] : null;
    $rows[$key]['Category_Ali'] = $url['CatId'];
    $rows[$key]['Url_Search_Ali'] = $url['url'];
    $rows[$key]['Find_Ali'] = $url['type'];
    $rows[$key]['Find_PriceMin_Ali'] = $url['priceMin'];
    $rows[$key]['Find_PriceMax_Ali'] = $url['PriceMax'];
    $rows[$key]['QueryFind'] = implode('|', $url['QueryFind']);
    $rows[$key]['id_Ali'] = $response2[$key]['pid'];
    $rows[$key]['Title_Ali'] = $response2[$key]['title'];

    $PriceMax_Ali = null;

    if (!$PriceMax_Ali && isset($response2[$key]['normal_price'])) {
        $expl = explode('-', $response2[$key]['normal_price']);
        if (isset($expl[1])) {
            $PriceMax_Ali = parsefloatstrval($expl[1]);
        } elseif (isset($expl[0])) {
            $PriceMax_Ali = parsefloatstrval($expl[0]);
        }
    }

    if (!$PriceMax_Ali && isset($response2[$key]['section_price'])) {
        $expl = explode('-', $response2[$key]['section_price']);
        if (isset($expl[1])) {
            $PriceMax_Ali = parsefloatstrval($expl[1]);
        } elseif (isset($expl[0])) {
            $PriceMax_Ali = parsefloatstrval($expl[0]);
        }
    }

    if ($count_match = preg_match_all("#,#", $PriceMax_Ali) > 1) {
        $PriceMax_Ali = preg_replace('#,#', '', $PriceMax_Ali, $count_match - 1);
    }

    $rows[$key]['PriceMax_Ali'] = $PriceMax_Ali;

    $rows[$key]['Quantity_Ali'] = intval(parsefloatstrval(preg_replace("#[^\d,.]#",
        '', config('ali_total_max'))) / (parsefloatstrval(preg_replace("#[^\d,.]#", '',
        $rows[$key]['PriceMax_Ali'])) + (config('include_shipping') == '1' ?
        parsefloatstrval(preg_replace("#[^\d,.]#", '', $rows[$key]['Shipping'])) : 0)));


    if (!($rows[$key]['Quantity_Ali'] > 0)) {
        $rows[$key]['Quantity_Ali'] = 1;
    }

    $rows[$key]['Yrs_Ali'] = $response2[$key]['join_year'];
    $rows[$key]['CountryS_Ali'] = $response2[$key]['company_name_country'];

    if (config('ali_add_to_cart')) {
        try {
            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_actions .express-item')) as
                $key1 => $element) {
                $item_left = null;
                try {
                    $item_left = trim($element->findElement(WebDriverBy::cssSelector('.item-left'))->
                        getText());
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }

                try {
                    $response2['min_business_type_lite'] = trim($element->findElement(WebDriverBy::
                        cssSelector('.module_companycard .business-type-lite'))->getText());
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }

                if ($item_left) {
                    if (stripos($item_left, 'Total') !== false) {
                        try {
                            $response2['min_total'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                getText());
                            $response2['min_total'] = str_replace(',', '', $response2['min_total']);
                            $response2['min_total'] = preg_replace("#[\n ].*$#", '', $response2['min_total']);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    }
                    if (stripos($item_left, 'Ship') !== false) {
                        try {
                            $response2['min_shipping'] = trim($element->findElement(WebDriverBy::
                                cssSelector('.price'))->getText());
                            $response2['min_shipping'] = str_replace(',', '', $response2['min_shipping']);
                            $response2['min_shipping'] = preg_replace("#[\n ].*$#", '', $response2['min_shipping']);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    } else {
                        $text = trim($element->getText());
                        if (stripos($text, 'Shipping time') !== false || stripos($text,
                            'Processing Time') !== false) {
                            $response2['min_shipping_time'] = trim(str_replace(['Shipping time',
                                'Processing Time'], '', $text));
                        }
                    }
                } else {
                    $text = trim($element->getText());
                    if (stripos($text, 'Shipping time') !== false || stripos($text,
                        'Processing Time') !== false) {
                        $response2['min_shipping_time'] = trim(str_replace(['Shipping time',
                            'Processing Time'], '', $text));
                    }
                }
            }
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }

        try {
            //$GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap input'))->sendKeys(999991);

            $GLOBALS['DRIVER']->executeScript("document.querySelector('#skuWrap input').value = 999999;");
            $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-up'))->
                click();
            $tmpQuantity_Ali = parsefloatstrval($GLOBALS['DRIVER']->findElement(WebDriverBy::
                cssSelector('.ui2-balloon .sku-all-quantity'))->getText());
            if ($tmpQuantity_Ali > 0) {
                $rows[$key]['MaxQuantity_Ali'] = $tmpQuantity_Ali;
                //$GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap input'))->sendKeys($rows[$key]['Quantity_Ali'] - 1);

                $GLOBALS['DRIVER']->executeScript("document.querySelector('#skuWrap input').value = " .
                    ($rows[$key]['MaxQuantity_Ali']) . ";");
                $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-down'))->
                    click();
                $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-up'))->
                    click();
                usleep(150000);
            }
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }

        try {
            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_actions .express-item')) as
                $key1 => $element) {
                $item_left = null;
                try {
                    $item_left = trim($element->findElement(WebDriverBy::cssSelector('.item-left'))->
                        getText());
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }

                try {
                    $response2['business_type_lite'] = trim($element->findElement(WebDriverBy::
                        cssSelector('.module_companycard .business-type-lite'))->getText());
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }

                if ($item_left) {
                    if (stripos($item_left, 'Total') !== false) {
                        try {
                            $response2['max_total'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                getText());
                            $response2['max_total'] = str_replace(',', '', $response2['max_total']);
                            $response2['max_total'] = preg_replace("#[\n ].*$#", '', $response2['max_total']);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    } elseif (stripos($item_left, 'Ship') !== false) {
                        try {
                            $response2['max_shipping'] = trim($element->findElement(WebDriverBy::
                                cssSelector('.price'))->getText());
                            $response2['max_shipping'] = str_replace(',', '', $response2['max_shipping']);
                            $response2['max_shipping'] = preg_replace("#[\n ].*$#", '', $response2['max_shipping']);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    } else {
                        $text = trim($element->getText());
                        if (stripos($text, 'Shipping time') !== false || stripos($text,
                            'Processing Time') !== false) {
                            $response2['shipping_time'] = trim(str_replace(['Shipping time',
                                'Processing Time'], '', $text));
                        }
                    }
                } else {
                    $text = trim($element->getText());
                    if (stripos($text, 'Shipping time') !== false || stripos($text,
                        'Processing Time') !== false) {
                        $response2['shipping_time'] = trim(str_replace(['Shipping time',
                            'Processing Time'], '', $text));
                    }
                }
            }
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }

        try {
            $GLOBALS['DRIVER']->executeScript("document.querySelector('#skuWrap input').value = " .
                ($rows[$key]['Quantity_Ali']) . ";");
            $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-down'))->
                click();
            $GLOBALS['DRIVER']->findElement(WebDriverBy::cssSelector('#skuWrap .quantity-up'))->
                click();
            usleep(200000);
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }

        try {
            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('#module_actions .express-item')) as
                $key1 => $element) {
                $item_left = null;
                try {
                    $item_left = trim($element->findElement(WebDriverBy::cssSelector('.item-left'))->
                        getText());
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }

                try {
                    $response2['business_type_lite'] = trim($element->findElement(WebDriverBy::
                        cssSelector('.business-type-lite'))->getText());
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }

                $htmlPage = $GLOBALS['DRIVER']->getPageSource();

                if (!isset($response2['shipping_time'])) {
                    if (preg_match("#<span>Shipping time(.*?)</span>#", $htmlPage, $matches)) {
                        $response2['shipping_time'] = trim(str_replace(['Shipping time',
                            'Processing Time'], '', strip_tags(html_entity_decode($matches[1]))));
                    }
                }

                if ($item_left) {
                    if (stripos($item_left, 'Total') !== false) {
                        try {
                            $response2['total'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                getText());
                            $response2['total'] = str_replace(',', '', $response2['total']);
                            $response2['total'] = preg_replace("#[\n ].*$#", '', $response2['total']);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    } elseif (stripos($item_left, 'Ship') !== false) {
                        try {
                            $response2['shipping'] = trim($element->findElement(WebDriverBy::cssSelector('.price'))->
                                getText());
                            $response2['shipping'] = str_replace(',', '', $response2['shipping']);
                            $response2['shipping'] = preg_replace("#[\n ].*$#", '', $response2['shipping']);
                        }
                        catch (WebDriverException $e) {
                            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                                "\n" . $e->getMessage(), 2 => $url['asin']]);
                        }
                    } else {
                        $text = trim($element->getText());
                        if (stripos($text, 'Shipping time') !== false || stripos($text,
                            'Processing Time') !== false) {
                            $response2['shipping_time'] = trim(str_replace(['Shipping time',
                                'Processing Time'], '', $text));
                        }
                    }
                } else {
                    $text = trim($element->getText());
                    if (stripos($text, 'Shipping time') !== false || stripos($text,
                        'Processing Time') !== false) {
                        $response2['shipping_time'] = trim(str_replace(['Shipping time',
                            'Processing Time'], '', $text));
                    }
                }
            }
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }
    }

    try {
        $is_tmp = false;
        foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.next-tabs-nav .next-tabs-tab-inner')) as
            $key1 => $element) {
            try {
                if ($key1 > 0 && !$is_tmp) {
                    $element->click();
                    $GLOBALS['DRIVER']->wait(5)->until(WebDriverExpectedCondition::
                        visibilityOfElementLocated(WebDriverBy::cssSelector('.company-basicInfo')));
                    $GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.company-basicInfo'));
                    $is_tmp = true;
                }
            }
            catch (WebDriverException $e) {
                $is_tmp = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                    "\n" . $e->getMessage(), 2 => $url['asin']]);
            }
        }
    }
    catch (WebDriverException $e) {
        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            "\n" . $e->getMessage(), 2 => $url['asin']]);
    }

    try {
        try {
            foreach ($GLOBALS['DRIVER']->findElements(WebDriverBy::cssSelector('.company-basicInfo > tr')) as
                $key1 => $element) {

                try {
                    try {
                        $tmp1 = [];
                        $tmp2 = [];
                        foreach ($element->findElements(WebDriverBy::cssSelector('.field-title')) as $key2 =>
                            $element2) {
                            $tmp1[] = trim($element2->getText());
                        }
                        foreach ($element->findElements(WebDriverBy::cssSelector('.field-content-wrap')) as
                            $key2 => $element2) {
                            $tmp2[] = trim($element2->getText());
                        }

                        if (count($tmp1) == count($tmp2)) {
                            foreach ($tmp1 as $k => $v) {
                                $rows[$key]['details2'][$tmp1[$k]] = $tmp2[$k];
                            }
                        }
                    }
                    catch (WebDriverException $e) {
                        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                            "\n" . $e->getMessage(), 2 => $url['asin']]);
                    }
                }
                catch (WebDriverException $e) {
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
                    DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                        "\n" . $e->getMessage(), 2 => $url['asin']]);
                }
            }
        }
        catch (WebDriverException $e) {
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
            DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
                "\n" . $e->getMessage(), 2 => $url['asin']]);
        }
    }
    catch (WebDriverException $e) {
        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            "\n" . $e->getMessage(), 2 => $url['asin']]);
    }

    try {
        $response2['business_type_lite'] = trim($GLOBALS['DRIVER']->findElement(WebDriverBy::
            cssSelector('#block-mainscreen-right .business-type-lite'))->getText());
    }
    catch (WebDriverException $e) {
        log_write_echo(dirname(__dir__ ) . '/alibaba.log', $e->getMessage());
        DB::Execute("UPDATE `parser_china` SET `Ali_log` = SUBSTRING(CONCAT(?, `Ali_log`), 1, 60000) WHERE `asin` = ?", [1 =>
            "\n" . $e->getMessage(), 2 => $url['asin']]);
    }

    if (isset($response2['Trade$_Ali'])) {
        $rows[$key]['Trade$_Ali'] = $response2['Trade$_Ali'];
    }
    if (isset($response2['company-name'])) {
        $rows[$key]['Seller_Ali'] = $response2['company-name'];
    }

    if (isset($details['Business Type'])) {
        $response2['business_type'] = $details['Business Type'];
    } elseif (isset($rows[$key]['details2']['Business Type'])) {
        $response2['business_type'] = $rows[$key]['details2']['Business Type'];
    }
    if (isset($response2['Trade$_Ali'])) {
        $rows[$key]['Trade$_Ali'] = $response2['Trade$_Ali'];
    }
    if (isset($response2['company-name'])) {
        $rows[$key]['Seller_Ali'] = $response2['company-name'];
    }


    if (isset($response2['total']))
        $rows[$key]['Total_Ali*'] = parsefloatstrval($response2['total']);
    if (isset($response2['shipping']))
        $rows[$key]['Shipping_Ali*'] = parsefloatstrval($response2['shipping']);

    if (isset($response2['max_total']))
        $rows[$key]['Max_Total_Ali'] = $response2['max_total'];
    if (isset($response2['max_shipping']))
        $rows[$key]['Max_Shipping_Ali'] = $response2['max_shipping'];
    if ($response2['shipping_time']) {
        $rows[$key]['Shipping_Time*'] = $response2['shipping_time'];
    }

    $rows[$key]['Total_Ali'] = round(parsefloatstrval($rows[$key]['Quantity_Ali']) *
        parsefloatstrval($rows[$key]['PriceMax_Ali']));
    $rows[$key]['Shipping_Ali'] = round(parsefloatstrval($rows[$key]['Shipping']) *
        parsefloatstrval($rows[$key]['Quantity_Ali']));

    $rows[$key]['ROI'] = strval(round((parsefloatstrval($url['info'][config('ali_price_info')]) *
        0.85 - (config('ali_fba_fees') ? parsefloatstrval($url['info']['FBA Fees:']) : 0) -
        config('ali_ship_pp') - parsefloatstrval($rows[$key]['Shipping'])) /
        parsefloatstrval($rows[$key]['Quantity_Ali']) - parsefloatstrval($rows[$key]['PriceMax_Ali']) /
        (($url['info']['pack'] > 0 ? parsefloatstrval($url['info']['pack']) : 1) * (parsefloatstrval
        ($rows[$key]['PriceMax_Ali']) + parsefloatstrval($rows[$key]['Shipping'])) * 100),
        2));

    $rows[$key]['ROI_Ali'] = strval(round((parsefloatstrval($url['info'][config('ali_price_info')]) *
        0.85 - (config('ali_fba_fees') ? parsefloatstrval($url['info']['FBA Fees:']) : 0) -
        config('ali_ship_pp') - parsefloatstrval($rows[$key]['Shipping_Ali'])) /
        parsefloatstrval($rows[$key]['Quantity_Ali']) - parsefloatstrval($rows[$key]['PriceMax_Ali']) /
        (($url['info']['pack'] > 0 ? parsefloatstrval($url['info']['pack']) : 1) * (parsefloatstrval
        ($rows[$key]['PriceMax_Ali']) + parsefloatstrval($rows[$key]['Shipping_Ali']) /
        parsefloatstrval($rows[$key]['Quantity_Ali'])) * 100), 2));


    $rows[$key]['Model_Ali'] = $response2['model'];
    $rows[$key]['Images_Ali'] = is_array($response2['Images_Ali']) ? $response2['Images_Ali'] : [];
    $rows[$key]['Brand_Ali'] = $response2['brand'];
    $rows[$key]['Profile_Ali'] = $response2['business_type'];
    $rows[$key]['Country_Ali'] = $response2['place_of_origin'];
    $rows[$key]['Weight_Ali'] = trim(str_replace('kg', '', $response2['single_gross_weight']));
    $rows[$key]['EstTime_Ali'] = $response2['est_time'];

    if (isset($response2['business_type_lite']) && $response2['business_type_lite']) {
        $rows[$key]['Profile_Ali'] = $response2['business_type_lite'];
    }

    $rows[$key]['Single package size'] = trim(str_replace('cm', '', $response2['single_package_size']));
    $rows[$key]['Color_Ali'] = $response2['color'];
    if ($rows[$key]['Weight_Ali'])
        $rows[$key]['%Weight'] = round((parsefloatstrval($rows[$key]['Weight_Ali']) / ($url['info']['Package: Weight (g)'] /
            1000 - 1)) * 100, 2);
    if ($details['Place of Origin']) {
        $rows[$key]['CountryOrigin_Ali'] = trim(preg_replace("#^.*,#", '', $details['Place of Origin']));
    }

    if ($rows[$key]['Find_Length_Ali'] && $rows[$key]['Find_Width_Ali'] && $rows[$key]['Find_Height_Ali']) {
        $tmp = explode('X', $rows[$key]['Single package size']);
        if (count($tmp) == 3) {
            if (parsefloatstrval($rows[$key]['Find_Length_Ali']) > 0 && parsefloatstrval($rows[$key]['Find_Width_Ali']) >
                0 && parsefloatstrval($rows[$key]['Find_Height_Ali']) > 0 && parsefloatstrval($tmp[0]) >
                0 && parsefloatstrval($tmp[1]) > 0 && parsefloatstrval($tmp[2]) > 0) {
                $rows[$key]['%Length'] = round(((parsefloatstrval($tmp[0]) / parsefloatstrval($rows[$key]['Find_Length_Ali'])) *
                    100) - 100, 2);
                $rows[$key]['%Width'] = round(((parsefloatstrval($tmp[1]) / parsefloatstrval($rows[$key]['Find_Width_Ali'])) *
                    100) - 100, 2);
                $rows[$key]['%Height'] = round(((parsefloatstrval($tmp[2]) / parsefloatstrval($rows[$key]['Find_Height_Ali'])) *
                    100) - 100, 2);
                $rows[$key]['%Package'] = round(($rows[$key]['%Length'] + $rows[$key]['%Width'] +
                    $rows[$key]['%Height']) / 3, 2);
            }
        }
    }
}

function saveRows($rows)
{
    foreach ($rows as $row) {
        if (isset($row['CheckingFind'])) {
            $row['CheckingFind'] = implode('|', $row['CheckingFind']);
        }

        $row['id_Ali'] = intval($row['id_Ali']);

        if (isset($row['CheckingFind']))
            unset($row['CheckingFind']);
        if (isset($row['CheckingsFinds']))
            unset($row['CheckingsFinds']);
        if (isset($row['QueriesFind']))
            unset($row['QueriesFind']);
        if (isset($row['details']))
            unset($row['details']);
        if (isset($row['details2']))
            unset($row['details2']);
        if (isset($row['MaxQuantity_Ali']))
            unset($row['MaxQuantity_Ali']);
        if (isset($row['MaxQuantity_Ali']))
            unset($row['MaxQuantity_Ali']);
        if (isset($row['Max_Shipping_Ali']))
            unset($row['Max_Shipping_Ali']);
        if (isset($row['CheckingFind']))
            unset($row['CheckingFind']);
        if (isset($row['Max_Total_Ali']))
            unset($row['Max_Total_Ali']);
        if (isset($row['Max_Shipping_Ali']))
            unset($row['Max_Shipping_Ali']);
        if (isset($row['Images_Ali']) && is_array($row['Images_Ali'])) {
            $row['Images_Ali'] = implode(',', $row['Images_Ali']);
        }

        if (isset($row['QueryFind']) && is_array($row['QueryFind'])) {
            $row['QueryFind'] = trim(trim(implode('|', $row['QueryFind']), '|'));
        }

        foreach ($row as $key => $value) {
            if (is_string($row[$key])) {
                $row[$key] = trim($row[$key]);
            }

            if (empty($row[$key]) && $row[$key] != '0') {
                unset($row[$key]);
            }
        }

        //print_r($row);// delete

        if ($row['id_Ali'] && $row['asin'] && $row['checking'] == '1') {
            //print_r($row); // delete

            if (isset($row['checking']))
                unset($row['checking']);

            $item = DB::GetRow('SELECT * FROM `parser_alibaba_results` WHERE `id` = ?', [1 =>
                $row['id_Ali']]);
            if (!$item) {
                if (!($url['type'] == 'img' && config('ali_search_img_more') == '1')) {
                    DB::Execute('INSERT INTO `parser_alibaba_results`(`id`, `asin`, `results`, `margin`, `ROI`, `parse_at`,`Find_Ali`) 
                         VALUES (?,?,?,?,?,NOW(),?)', [1 => $row['id_Ali'], 2 =>
                        $row['asin'], 3 => json_encode($row), 4 => strval($row['Margin']), 5 => strval($row['ROI']),
                        6 => $row['Find_Ali']]);
                    $item = DB::GetRow('SELECT * FROM `parser_alibaba_results` WHERE `id` = ?', [1 =>
                        $row['id_Ali']]);
                }
            } else {
                $results = json_decode($item['results'], true);

                if (!is_array($results)) {
                    $results = [];
                }

                foreach ($row as $key => $value) {
                    if ($key == 'Searсh_Ali') {
                        if (isset($results[$key])) {
                            $expl = explode('/', $results[$key]);
                            if (!in_array($value, $expl)) {
                                $expl[] = $value;
                                $results[$key] = implode('/', $expl);
                                $is_update = true;
                            }
                        } else {
                            $results[$key] = $value;
                            $is_update = true;
                        }
                    } elseif ($key == 'Urls_Searchs_Ali') {
                        if (isset($results[$key])) {
                            if (!in_array($value[0], $results[$key])) {
                                $results[$key][] = $value;
                                $is_update = true;
                            }
                        } else {
                            $results[$key] = $value;
                            $is_update = true;
                        }
                    } elseif (!isset($results[$key]) || $results[$key] != $value) {
                        $results[$key] = $value;
                        $is_update = true;
                    }
                }

                $Find_Ali = explode('/', $item['Find_Ali']);
                $Find_Ali[] = $row['Find_Ali'];
                $Find_Ali = array_unique($Find_Ali);
                $Find_Ali = implode('/', $Find_Ali);

                if ($Find_Ali != $item['Find_Ali']) {
                    $is_update = true;
                }

                $is_update = true;

                if ($is_update) {
                    DB::Execute('UPDATE `parser_alibaba_results` SET `results`=?,`margin`=?,`ROI`=?,`parse_at` = NOW(),`Find_Ali` = ?
                         WHERE `id`=?', [1 => json_encode($results), 2 => $item['Margin'],
                        3 => parsefloatstrval($row['roi']), 4 => $Find_Ali, 5 => $item['id'], ]);
                }
            }
        }
    }
}

$GLOBALS['DRIVER']->quit();
$end_time = time();

$time_diff_sec = ($end_time - $start_time);

$time_diff = round($time_diff_sec / 60, 2);

log_write_echo(dirname(__dir__ ) . '/' . $parser_id . '.log', "total time: $time_diff");
$description = array();
$description['parse_count_all'] = (int)$parse_count_all;
$description = json_encode($description);
DB::Execute("UPDATE `$db_table_parser_stat` SET `description` = ?, `date_end` = NOW(), `duration` = ? WHERE `id` = '$db_table_parser_stat_id'", [1 =>
    $description, 2 => ($time_diff_sec) / $parse_count_all, ]);
if ($start_comporator && config('ali_checking_image') && file_exists(dirname(__dir__ ) .
    DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'comparator_mysql' .
    DIRECTORY_SEPARATOR . 'run.bat')) {
    cmdexec(dirname(__dir__ ) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .
        'comparator_mysql' . DIRECTORY_SEPARATOR . 'run.bat');
}

cmdexec(dirname(dirname(__dir__ )) . '/php.x64/php.exe -q ' . __dir__ .
    '/sync.php');

stopRun();

shell_exec(__dir__ . '/a.vbs');