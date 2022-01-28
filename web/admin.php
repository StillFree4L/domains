<?php

session_start();

set_time_limit(0); // Максимальное время выполнения скрипта. В секундах.

header("Cache-Control: no-cache");
header("Content-Type: text/html; charset=utf-8");

ini_set('log_errors', 'on');
ini_set('error_log', dirname(__file__) . '/data/error_log.txt');
ini_set('max_execution_time', '60000');

error_reporting(1);

require_once (dirname(__file__) . "/config.inc.php");
// ------------------------------------------------------------------------
if ($_SERVER[HTTP_X_REAL_IP] != '') {
    $this_user_ip = mysqli_real_escape_string($db, $_SERVER[HTTP_X_REAL_IP]);
} else {
    $this_user_ip = mysqli_real_escape_string($db, $_SERVER[REMOTE_ADDR]);
}
// ------------------------------------------------------------------------
$index_tpl = 'tpl/admin.html';
// ------------------------------------------------------------------------
$parser_id = 'alibaba.com';
$parser_id_main = 'alibaba';
// ------------------------------------------------------------------------
$export_limit = 0;
if ($demo)
    $export_limit = 50;

$conf = array_merge((array )get_config($db, $db_table_conf), (array )
    get_config_id($db, $db_table_conf_list, 'parser_id', $parser_id_main));
$save_deleted_asin_days = intval($conf[save_deleted_asin_days]);
$delete_SalesRankCurrent = $conf[delete_SalesRankCurrent];
$del_Amazon90days_avg = intval($conf[del_Amazon90days_avg]);
$del_CategoriesTree = intval($conf[del_CategoriesTree]);
$delete_SalesRank30daysdrop = intval($conf[delete_SalesRank30daysdrop]);
$delete_SalesRank90daysdrop = intval($conf[delete_SalesRank90daysdrop]);
$listCategoriesRoot = array();
$tmp = explode('|', $conf[listCategoriesRoot]);
foreach ($tmp as $key => $val) {
    $val = trim($val);
    if ($val != '')
        $listCategoriesRoot[$val] = $val;
}

if (!function_exists('cmdexec')) {
    if (file_exists(__dir__ . "/update_log")) {
        unlink(__dir__ . "/update_log");
    }

    function cmdexec($command)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            //windows
            pclose(popen("start /B " . $command . " 1> " . __dir__ . "/update_log 2>&1 &",
                "r"));
        } else {
            //linux
            shell_exec($command . " > /dev/null 2>&1 &");
        }
    }
}

// ------------------------------------------------------------------------
$result[reset_filtr_display_ext] = 'none';
$result[reset_filtr_display] = 'none';
$result[find_txt] = '';

$result[id] = "";
$result[parser_id] = "";
$result[categories] = "";
$result[category_name] = "";
$result[shop_url] = "";
$result[author] = "";
$result[source_url] = "";
$result[r_title] = "";
$result[title_art] = "";
$result[art] = "";
$result[price_valute] = "";
$result[price] = "";
$result[available] = "";
$result[price2] = "";
$result[price3] = "";
$result[available1] = "";
$result[desc_small] = "";
$result[desc] = "";
$result[sticker] = "";
$result[status] = "";
$result[category] = "";
$result[tags] = "";
$result[tax] = "";
$result[desc_small2] = "";
$result[META_Keywords] = "";
$result[META_Description] = "";
$result[url_text] = "";
$result[compare_price_selectable] = "";
$result[purchase_price_selectable] = "";
$result[country] = "";
$result[brand] = "";
$result[size] = "";
$result[sex] = "";
$result[age] = "";
$result[size2] = "";
$result[info1] = "";
$result[term] = "";
$result[materials] = "";
$result[size3] = "";
$result[images] = "";
$result[url_id] = "";
$result[date_update] = "";
$result[date_add] = "";

$result[sort_marker_id] = "";
$result[sort_marker_item_date] = "";
$result[sort_marker_date_add] = "";
$result[sort_marker_developer] = "";

$result[date_start] = "";
$result[date_end] = "";
$result[date_start_add] = "";
$result[date_end_add] = "";
$result[date_start_update] = "";
$result[date_end_update] = "";

$result[categories_name_list] = "";

$result[min_star_export] = "";
$result[last_parser_id] = "";
$result[parser_id_list] = "";
$result[lists_id_list] = "";
$result[title_filter] = "";
$result[developer_filter] = "";
$result[restore_parse_num] = "";
$result[error] = "";

$result[restore_parse_asin] = "";
$result[chkStartComporatorAlibaba] = $conf['ali_chk_checking_image'] == '1' ?
    'checked=""' : '';
$result[chkStartTrademarkiaAlibaba] = $conf['ali_trademarkia_cmd'] == '1' ?
    'checked=""' : '';
// -------------------------------------------------------------------
if (isset($_GET[find_ext_clear])) {
    unset($_SESSION[find_ext_submit]);
    unset($_SESSION[categories]);
    unset($_SESSION[category_name]);
    unset($_SESSION[date_start]);
    unset($_SESSION[date_end]);
    unset($_SESSION[date_start_add]);
    unset($_SESSION[date_end_add]);
    unset($_SESSION[date_start_update]);
    unset($_SESSION[date_end_update]);
    unset($_SESSION[parser_id]);
    unset($_SESSION[list_id]);
    unset($_SESSION[min_star_export]);
    unset($_SESSION[title_filter]);
    unset($_SESSION[developer_filter]);
    unset($_SESSION[order_by]);
    unset($_SESSION[direction]);

    if (file_exists(__dir__ . '/data/_session')) {
        unlink(__dir__ . '/data/_session');
    }
}
// -------------------------------------------------------------------
if (isset($_POST[find_ext_submit])) {
    $_SESSION[find_ext_submit] = true;
    $_SESSION[categories] = $_POST[categories];
    $_SESSION[category_name] = $_POST[category_name];
    $_SESSION[date_start] = $_POST[date_start];
    $_SESSION[date_end] = $_POST[date_end];
    $_SESSION[date_start_add] = $_POST[date_start_add];
    $_SESSION[date_end_add] = $_POST[date_end_add];
    $_SESSION[date_start_update] = $_POST[date_start_update];
    $_SESSION[date_end_update] = $_POST[date_end_update];
    $_SESSION[parser_id] = $_POST[parser_id];
    $_SESSION[list_id] = $_POST[list_id];
    $_SESSION[min_star_export] = $_POST[min_star_export];
    $_SESSION[title_filter] = $_POST[title_filter];
    $_SESSION[developer_filter] = $_POST[developer_filter];
    $_SESSION[asin_filter] = $_POST[asin_filter];

    $_SESSION['fba_filter_from'] = $_POST['fba_filter_from'];
    $_SESSION['fbm_filter_from'] = $_POST['fbm_filter_from'];
    $_SESSION['bsr_filter_from'] = $_POST['bsr_filter_from'];
    $_SESSION['fba_filter_to'] = $_POST['fba_filter_to'];
    $_SESSION['fbm_filter_to'] = $_POST['fbm_filter_to'];
    $_SESSION['bsr_filter_to'] = $_POST['bsr_filter_to'];
    $_SESSION['category_filter'] = $_POST['category_filter'];
    $_SESSION['profile_filter'] = $_POST['profile_filter'];

    file_put_contents(__dir__ . '/data/_session', json_encode($_SESSION));
}
// -------------------------------------------------------------------
if (isset($_GET[page_str])) {
    //('page_str');
    $_SESSION[page_str] = (int)$_GET[page_str];
}
/////////////////////////////////////////////////////////////////////////////////////////////
// db_import_csv
if (isset($_POST[parser_google])) {
    require_once (dirname(__file__) . "\\parser\\config.php");
    echo load_template(dirname(__file__) . "/tpl/parser.html", (array )ParserConfig::
        load());
}

$CategoriesRoot = array_map(function ($item)
{
    return str_getcsv($item, ';'); }
, file(__dir__ . '/data/Categories Root.csv'));

/////////////////////////////////////////////////////////////////////////////////////////////
// db_import_csv
if (isset($_POST[db_import_csv])) {
    ini_set('max_execution_time', '864000');
    ini_set('memory_limit', '4024M');
    include_once ("lib/PHPExcel.php");
    include_once ("lib/PHPExcel/Writer/Excel2007.php");

    $del_title_list = array_map(function ($item)
    {
        return str_getcsv($item, ';'); }
    , file(__dir__ . '/data/del_title_list.csv'));

    $BrandCsv = array_map(function ($item)
    {
        return str_getcsv($item, ';'); }
    , file(__dir__ . '/data/Brand.csv'));

    // чистим архивную базу
    if ($save_deleted_asin_days > 0) {
        $sql = "DELETE FROM `$db_table_deleted` WHERE timestampdiff(day, date_add, current_timestamp) > " . ($save_deleted_asin_days) .
            "";
        //$sql = "SELECT *, timestampdiff(day, date_add, current_timestamp) as `timestampdiff` FROM `$db_table_deleted` LIMIT 20";// WHERE timestampdiff(day, date_add, current_timestamp) > ".($save_deleted_asin_days)." LIMIT 10";
        //$rows = db_sql_query($db, $sql);
        //printr($rows);
        db_sql_query($db, $sql);
        $del_cnt = (int)mysqli_affected_rows($db);
        $result[error] = 'Удалено записей из архивной базы: ' . (int)$del_cnt . "<br/>";
    }

    $csv = xls2arr(dirname(__file__) . '/' . 'data/Categories.Tree.xlsx');
    $categories_template = array();
    foreach ($csv as $key => $val) {
        if ($val[0] != '' && $val[0] != 'Categories: Tree') {
            $CategoriesTree = trim($val[0]);
            $categories_template[$CategoriesTree] = $CategoriesTree;
        }
    }
    //print_r($categories_template);

    if (isset($_FILES['import_file']) && isset($_FILES['import_file']['name']) &&
        is_array($_FILES['import_file']['name'])) {
        if ($conf['import_filter_brand_r'] > '0') {
            if (file_exists(__dir__ . '/parserbrand/script.bat')) {
                shell_exec(__dir__ . '/parserbrand/script.bat');
            }
        }

        foreach ($_FILES['import_file']['name'] as $filekey => $filename) {
            if ($_FILES['import_file']['tmp_name'][$filekey] != '') {
                file_put_contents(__dir__ . '/data/last_import_file', get_config_name() . ' / ' .
                    $_FILES['import_file']['name'][$filekey] . ' ' . date("d.m.Y H:i"));

                if ($_FILES['import_file']['type'][$filekey] !=
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    $result[error] .= "  - invalid file type<br/>";
                } else {
                    $target_file = dirname(__file__) . '/data/list.xlsx';
                    unlink($target_file);
                    move_uploaded_file($_FILES['import_file']['tmp_name'][$filekey], $target_file);

                    $result[error] .= "Загружен файл: '" . $_FILES['import_file']['name'][$filekey] .
                        "' сохранили: '$target_file'<br/>";

                    $header = array();
                    $csv = xls2arr(dirname(__file__) . '/' . 'data/list.xlsx');

                    foreach ($csv as $key => $val) {
                        if ($key == 0) {
                            foreach ($val as $k => $v) {
                                $v = trim($v);
                                //if($v != ''){
                                $key_val = array_search($v, $header);
                                if ($key_val !== false) {
                                    $v .= '-' . $key_val;
                                }
                                //if($v != 'asin' && $v != 'date_update')
                                $header[] = $v;
                                //}
                            }
                        } else {
                            foreach ($header as $k => $v) {
                                if ($v == 'asin' || $v == 'date_update' || $v == 'comparsion_info' || $v ==
                                    '1 - 1 Best result' || $v == '1-1 Best result URL' || $v ==
                                    'All-All Best result' || $v == 'All-All Best URL' || $v == 'All-All Best Images' ||
                                    $v == 'results_all_all' || $v == 'results_1_1')
                                    continue;

                                $items_all[2][$key][$v] = $val[$k];
                            }
                            //if($items_all[2][$key]['ASIN'] == ''){
                            //    unset($items_all[2][$key]);
                            //}else
                            if ($items_all[2][$key]['ASIN'] == '') {
                                unset($items_all[2][$key]);
                            } else {
                                //if($v != 'asin' && $v != 'date_update')
                                $items_all[1][$key] = $items_all[2][$key]['ASIN'];
                            }
                        }

                    }

                    $result[error] .= "Файл обработан. Всего ASIN: " . (int)count($items_all[1]) .
                        "<br/>";

                    foreach ($items_all[1] as $key => $val) {
                        $item_url = trim(html_entity_decode($items_all[1][$key], ENT_COMPAT, 'UTF-8'));
                        $item_data = $items_all[2][$key];
                        if ($item_url == '')
                            printr($item_url);

                        $conf['ali_price_info'] = explode('|', $conf['ali_price_info']);
                        $conf['ali_price_info'] = trim($conf['ali_price_info'][0]);

                        if ($conf['price_from'] && $conf['ali_price_info'] && $item_data[$conf['ali_price_info']]) {
                            if (floatstrval($item_data[$conf['ali_price_info']]) < floatstrval($conf['price_from'])) {
                                $delete_price_from++;
                                continue;
                            }
                        }

                        if ($conf['price_to'] && $conf['ali_price_info'] && $item_data[$conf['ali_price_info']]) {
                            if (floatstrval($item_data[$conf['ali_price_info']]) > floatstrval($conf['price_to'])) {
                                $delete_price_to++;
                                continue;
                            }
                        }

                        $item_data['pack'] = GetPack($item_data['Title']);

                        if ($conf['pack'] && $conf['max_pack'] && $item_data['pack'] > $conf['max_pack']) {
                            $item_data['pack'] = $conf['max_pack'];
                        }

                        $item_data['Sales30'] = round(floatstrval($item_data['Sales Rank: Drops last 30 days'] /
                            ($item_data['Count of retrieved live offers: New, FBA'] + 1)));
                        $item_data['Sales30,$'] = round(floatstrval($item_data['Sales30'] * $item_data[$conf['ali_price_info']]));

                        if ($conf['ali_type_price'] == '1') {
                            $PriceMaxFind = floatval((0.85 * floatstrval($item_data[$conf['ali_price_info']]) -
                                ($conf['ali_fba_fees'] ? floatstrval($item_data['FBA Fees:']) : 0) - floatstrval
                                ($conf['ali_ship_pp']) - floatstrval($item_data[$conf['ali_price_info']]) *
                                floatstrval($conf['ali_shipping_percent']) / 100) / (floatstrval($item_data['pack'] ?
                                $item_data['pack'] : 1) * (floatstrval($conf['ali_roi_min']) / 100 + 1)));
                            $MOQ_Find = floatval(floatstrval($conf['ali_total_max']) / ($PriceMaxFind + ($conf['include_shipping'] ==
                                '1' ? floatstrval($item_data[$conf['ali_price_info']]) * floatstrval($conf['ali_shipping_percent']) /
                                100 : 0)));
                        } else {
                            $PriceMaxFind = floatval((0.85 * floatstrval($item_data[$conf['ali_price_info']]) -
                                ($conf['ali_fba_fees'] ? floatstrval($item_data['FBA Fees:']) : 0) - floatstrval
                                ($conf['ali_ship_pp']) - floatstrval($conf['ali_shipping_kg']) * floatstrval($item_data['Package: Weight (g)']) /
                                1000) / (floatstrval($item_data['pack'] ? $item_data['pack'] : 1) * (floatstrval
                                ($conf['ali_roi_min']) / 100 + 1)));
                            $MOQ_Find = floatstrval(floatstrval($conf['ali_total_max']) / ($PriceMaxFind + ($conf['include_shipping'] ==
                                '1' ? floatstrval($conf['ali_shipping_kg']) * floatval($item_data['Package: Weight (g)']) /
                                1000 : 0)));
                        }

                        if (!($MOQ_Find > -100000000000000)) {
                            $MOQ_Find = null;
                        }

                        if ($PriceMaxFind) {
                            $PriceMaxFind = round($PriceMaxFind, 2);
                        }

                        $item_data['PriceMaxFind'] = $PriceMaxFind;
                        $item_data['MOQ_Find'] = $MOQ_Find;

                        $item_data['pack'] = GetPack($item_data['Title']);

                        if ($conf['pack'] && $conf['max_pack'] && $item_data['pack'] > $conf['max_pack']) {
                            $item_data['pack'] = $conf['max_pack'];
                        }

                        $pricef = round((floatstrval($conf['ali_price_min_percent']) / 100) * $PriceMaxFind,
                            1);

                        $minPriceMaxFind = $pricef;

                        if ($conf['ali_pricemax_min'] > $minPriceMaxFind) {
                            $minPriceMaxFind = $conf['ali_pricemax_min'];
                        }

                        if ($conf['del_by_price_max_find_min'] == '1' && $minPriceMaxFind > 0) {
                            if ($PriceMaxFind < $minPriceMaxFind) {
                                $delete_minPriceMaxFind++;
                                continue;
                            }
                        }

                        if ($conf['min_price_max_find'] == '1' && $minPriceMaxFind > 0) {
                            if ($PriceMaxFind > $conf['min_price_max_find']) {
                                $delete_maxPriceMaxFind++;
                                continue;
                            }
                        }

                        if ($conf['ali_type_price'] == '1') {
                            $item_data['Shipping'] = floatstrval($item_data[$conf['ali_price_info']]) *
                                floatstrval($conf['ali_shipping_percent']) / 100;
                        } else {
                            $item_data['Shipping'] = floatstrval($conf['ali_shipping_kg']) * floatstrval($item_data['Package: Weight (g)']) /
                                1000;
                        }

                        $item_data['Margin'] = floatstrval(floatstrval($item_data[$conf['ali_price_info']]) *
                            0.85) - floatstrval($item_data['FBA Fees:']) - floatstrval($conf['ali_ship_pp']) -
                            floatstrval(($PriceMaxFind + $item_data['Shipping']) * floatstrval($item_data['pack'] ?
                            $item_data['pack'] : 1));

                        if ($item_data['Margin']) {
                            $item_data['Margin'] = round($item_data['Margin'], 2);
                        }

                        $item_data['Profit30'] = round($item_data['Sales30'] * $item_data['Margin']);

                        if ($conf['ali_checking_profit30'] && $item_data['Profit30']) {
                            if ($item_data['Profit30'] < $conf['ali_checking_profit30']) {
                                $delete_Profit30++;
                                continue;
                            }
                        }

                        if ($del_CategoriesTree) {
                            $CategoriesTree_skeep = false;
                            foreach ($categories_template as $template) {
                                if (strpos(' ' . $item_data['Categories: Tree'], $template) !== false) {
                                    $CategoriesTree_skeep = true;
                                    break;
                                }
                            }
                            if ($CategoriesTree_skeep) {
                                $CategoriesTree_skeep_cnt++;
                                continue;
                            }
                        }

                        if ($del_Amazon90days_avg)
                            if ($item_data['Amazon: 90 days avg.'] != '') {
                                $del_Amazon90days_avg_cnt++;
                                continue;
                            }

                        if ($delete_SalesRankCurrent == 0)
                            $delete_SalesRankCurrent = 100000;
                        if (intval($item_data['Sales Rank: Current']) > $delete_SalesRankCurrent) {
                            //$result[error] .= " - запись {$item_data[ASIN]} пропущена (SalesRankCurrent: {$item_data['Sales Rank: Current']}) > {$delete_SalesRankCurrent}) "."<br/>";
                            $delete_SalesRankCurrent_cnt++;
                            continue;
                        }

                        if ($delete_SalesRank30daysdrop && $item_data['Sales Rank: 30 days drop %']) {
                            if (floatstrval($item_data['Sales Rank: 30 days drop %']) * 100 < $delete_SalesRank30daysdrop) {
                                $delete_SalesRank30daysdrop_cnt++;
                                continue;
                            }
                        }

                        if ($delete_SalesRank90daysdrop && $item_data['Sales Rank: 90 days drop %']) {
                            if (floatstrval($item_data['Sales Rank: 90 days drop %']) * 100 < $delete_SalesRank90daysdrop) {
                                $delete_SalesRank90daysdrop_cnt++;
                                continue;
                            }
                        }

                        if ($conf['ali_checking_sales30'] && $item_data['Sales30']) {
                            if ($item_data['Sales30'] < $conf['ali_checking_sales30']) {
                                $delete_Sales30++;
                                continue;
                            }
                        }

                        if ($conf['minimun_margin'] && $item_data['Margin']) {
                            if ($item_data['Margin'] < $conf['minimun_margin']) {
                                $delete_minimun_margin++;
                                continue;
                            }
                        }

                        if ($conf['ali_checking_sales30$'] && $item_data['Sales30,$']) {
                            if ($item_data['Sales30,$'] < $conf['ali_checking_sales30$']) {
                                $delete_Sales30_++;
                                continue;
                            }
                        }

                        if ($conf['chk_delete_epmty_fields'] == '1' && $conf['delete_epmty_fields']) {
                            foreach (explode('|', $conf['delete_epmty_fields']) as $field) {
                                $field = trim($field);

                                if ($field) {
                                    if (empty($item_data[$field])) {
                                        $delete_epmty_fields++;
                                        continue;
                                    }
                                }
                            }
                        }

                        if ($conf['chk_delete_noepmty_fields'] == '1' && $conf['delete_noepmty_fields']) {
                            foreach (explode('|', $conf['delete_noepmty_fields']) as $field) {
                                $field = trim($field);

                                if ($field) {
                                    if (!empty($item_data[$field])) {
                                        $delete_noepmty_fields++;
                                        continue;
                                    }
                                }
                            }
                        }

                        if ($conf['del_title_by_list']) {
                            if (count($del_title_list)) {
                                $is = false;

                                foreach ($del_title_list as $del_title) {
                                    if (stripos($item_data['Title'], $del_title[0]) !== false) {
                                        $is = true;
                                    }
                                }

                                if ($is) {
                                    $del_title_by_list++;
                                    continue;
                                }
                            }
                        }

                        if ($conf['check_import_filter_brand'] == '1' && $conf['import_filter_brand_r'] ==
                            '1' && $item_data['Brand']) {
                            if (count($BrandCsv)) {
                                $is = false;

                                foreach ($BrandCsv as $Br) {
                                    if ($item_data['Brand'] == $Br[0]) {
                                        $is = true;
                                    }
                                }

                                if ($is) {
                                    $del_by_Brand++;
                                    continue;
                                }
                            }
                        }

                        if ($conf['ali_count_retrieved_offers_fba_from'] != '') {
                            if ($conf['ali_count_retrieved_offers_fba_from'] == '0') {
                                if ($item_data['Count of retrieved live offers: New, FBA']) {
                                    $CountofretrievedliveoffersNewFBA++;
                                    continue;
                                }
                            } elseif ($item_data['Count of retrieved live offers: New, FBA'] < $conf['ali_count_retrieved_offers_fba_from']) {
                                $CountofretrievedliveoffersNewFBA++;
                                continue;
                            }
                        }
                        if ($conf['ali_count_retrieved_offers_fba_to'] != '') {
                            if ($conf['ali_count_retrieved_offers_fba_to'] == '0') {
                                if ($item_data['Count of retrieved live offers: New, FBA']) {
                                    $CountofretrievedliveoffersNewFBA++;
                                    continue;
                                }
                            } elseif ($item_data['Count of retrieved live offers: New, FBA'] > $conf['ali_count_retrieved_offers_fba_to']) {
                                $CountofretrievedliveoffersNewFBA++;
                                continue;
                            }
                        }

                        if ($conf['ali_count_retrieved_offers_fbm_from'] != '') {
                            if ($conf['ali_count_retrieved_offers_fbm_from'] == '0') {
                                if ($item_data['Count of retrieved live offers: New, FBM']) {
                                    $CountofretrievedliveoffersNewFBM++;
                                    continue;
                                }
                            } elseif ($item_data['Count of retrieved live offers: New, FBM'] < $conf['ali_count_retrieved_offers_fbm_from']) {
                                $CountofretrievedliveoffersNewFBM++;
                                continue;
                            }
                        }
                        if ($conf['ali_count_retrieved_offers_fbm_to'] != '') {
                            if ($conf['ali_count_retrieved_offers_fbm_to'] == '0') {
                                if ($item_data['Count of retrieved live offers: New, FBM']) {
                                    $CountofretrievedliveoffersNewFBM++;
                                    continue;
                                }
                            } elseif ($item_data['Count of retrieved live offers: New, FBM'] > $conf['ali_count_retrieved_offers_fbm_to']) {
                                $CountofretrievedliveoffersNewFBM++;
                                continue;
                            }
                        }

                        if (!empty($listCategoriesRoot)) {
                            $key_config_name = 1;

                            if (isset($CategoriesRoot[0])) {
                                foreach ($CategoriesRoot[0] as $keyitem => $item) {
                                    if ($keyitem > 0) {
                                        if ($item == get_config_name()) {
                                            $key_config_name = $keyitem;
                                        }
                                    }
                                }
                            }

                            if ($conf['check_import_categories_root'] == '1' && $conf['field_import_categories_root']) {
                                if ($item_data[$conf['field_import_categories_root']]) {
                                    $is_delete_CategoryRootSales = false;

                                    if (true) {
                                        if (true) {
                                            foreach ($CategoriesRoot as $keyCategoryRoot => $CategoryRoot) {
                                                if ($keyCategoryRoot > 0 && $item_data['Categories: Root'] == $CategoryRoot[0]) {
                                                    if ($item_data[$conf['field_import_categories_root']] > $CategoryRoot[$key_config_name]) {
                                                        $is_delete_CategoryRootSales = true;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if ($is_delete_CategoryRootSales) {
                                        $delete_CategoryRootSales++;
                                        continue;
                                    }
                                }
                            }

                            if (!isset($listCategoriesRoot[trim($item_data['Categories: Root'])])) {
                                //$result[error] .= " - запись {$item_data[ASIN]} пропущена (CategoriesRoot: {$item_data['Categories: Root']})"."<br/>";
                                $CategoriesRoot_cnt++;
                                continue;
                            }
                        }

                        if ($conf['check_profile_when_add'] == '1') {
                            $sql = "SELECT * FROM `$db_table_deleted` WHERE `asin` LIKE '" .
                                mysqli_real_escape_string($db, $item_url) . "' and `profile` LIKE '" .
                                mysqli_real_escape_string($db, get_config_name()) . "'";
                            $rows = db_sql_query($db, $sql);
                        } else {
                            $sql = "SELECT * FROM `$db_table_deleted` WHERE `asin` LIKE '" .
                                mysqli_real_escape_string($db, $item_url) . "'";
                            $rows = db_sql_query($db, $sql);
                        }

                        if (is_array($rows)) {
                            $exist_del_rows++;
                            continue;
                        }

                        $sql = "SELECT * FROM $db_table WHERE `asin` LIKE '" . mysqli_real_escape_string($db,
                            $item_url) . "'";
                        $rows = db_sql_query($db, $sql);


                        if (!is_array($rows)) {
                            $str = array();

                            if (isset($item_data['pack'])) {
                                if ($item_data['pack']) {
                                    $str[pack] = $item_data['pack'];
                                }

                                unset($item_data['pack']);
                            }

                            $str[title] = '';
                            $str[categories] = '';
                            $str[asin] = $item_data['ASIN'];
                            $str[info] = array();
                            $str[info] = $item_data;
                            $str[images] = array();
                            $str[images_url] = array();
                            $str[date_add] = '';
                            $str[profile] = get_config_name();

                            $str[info] = json_encode($str[info]);

                            mysql_insert_arr($db, $str, $db_table, false, 'asin', $str[asin]);
                            $new_rows++;
                        } else {
                            $exist_rows++;

                            if (isset($rows[0])) {
                                $profile = explode(',', $rows[0]['profile']);

                                if (!in_array(get_config_name(), $profile)) {
                                    $profile[] = get_config_name();

                                    $sql = "UPDATE `$db_table` SET `profile`='" . mysqli_real_escape_string($db,
                                        implode(',', $profile)) . "' WHERE `id` = " . intval($rows[0]['id']) . "";

                                    db_sql_query($db, $sql);
                                }
                            }
                        }
                    }


                    if (isset($_FILES['import_file']['name'][$filekey]))
                        db_sql_query($db, "INSERT INTO `$db_table_stat`(`id`, `parser_id`, `user_id`, `user_name`, `title`, `date_add`, `ip`, `browser`, `profile`) 
                    VALUES (null,'$parser_id',1,'','Импорт: " . $_FILES['import_file']['name'][$filekey] .
                            "',NOW(),'$this_user_ip','$new_rows', '" . mysqli_real_escape_string($db,
                            get_config_name()) . "')");


                }
            }
        }


        $result[error] .= "Загружено записей: " . (int)$new_rows .
            " / Уже есть в основной базе записей: " . (int)$exist_rows .
            " / Уже есть в архивной базе записей: " . (int)$exist_del_rows . "<br/>";
        $result[error] .= "Записей пропущено по SalesRankCurrent: " . (int)$delete_SalesRankCurrent_cnt .
            "<br/>";
        $result[error] .= "Записей пропущено по 'Sales Rank: 30 days drop %': " . (int)
            $delete_SalesRank30daysdrop_cnt . "<br/>";
        $result[error] .= "Записей пропущено по 'Sales Rank: 90 days drop %': " . (int)
            $delete_SalesRank90daysdrop_cnt . "<br/>";
        $result[error] .= "Записей пропущено по 'Amazon: 90 days avg.': " . (int)$del_Amazon90days_avg_cnt .
            "<br/>";
        $result[error] .= "Записей пропущено по CategoriesRoot: " . (int)$CategoriesRoot_cnt .
            "<br/>";
        $result[error] .= "Записей пропущено по Sales30: " . (int)$delete_Sales30 .
            "<br/>";
        $result[error] .= "Записей пропущено по Sales30,$: " . (int)$delete_Sales30_ .
            "<br/>";
        $result[error] .= "Записей пропущено по Profit30: " . (int)$delete_Profit30 .
            "<br/>";
        $result[error] .= "Записей пропущено по Count of retrieved live offers: New, FBA: " . (int)
            $CountofretrievedliveoffersNewFBA . "<br/>";
        $result[error] .= "Записей пропущено по Count of retrieved live offers: New, FBM: " . (int)
            $CountofretrievedliveoffersNewFBM . "<br/>";
        $result[error] .= "Записей пропущено по Brand: " . (int)$del_by_Brand . "<br/>";
        $result[error] .= "Записей пропущено по Del Title By List: " . (int)$del_title_by_list .
            "<br/>";
        $result[error] .= "Записей пропущено по CategoryRootBSR: " . (int)$delete_CategoryRootSales .
            "<br/>";
        $result[error] .= "Записей пропущено по 'Categories: Tree': " . (int)$CategoriesTree_skeep_cnt .
            "<br/>";
        $result[error] .= "Записей пропущено по minPriceMaxFind: " . (int)$delete_minPriceMaxFind .
            "<br/>";
        $result[error] .= "Записей пропущено по maxPriceMaxFind: " . (int)$delete_maxPriceMaxFind .
            "<br/>";
        $result[error] .= "Записей пропущено по Epmty Fields: " . (int)$delete_epmty_fields .
            "<br/>";
        $result[error] .= "Записей пропущено по NoEpmty Fields: " . (int)$delete_noepmty_fields .
            "<br/>";
        $result[error] .= "Записей пропущено по Minimun Margin: " . (int)$delete_minimun_margin .
            "<br/>";
        $result[error] .= "Записей пропущено по price Цена от: " . (int)$delete_price_from .
            "<br/>";
        $result[error] .= "Записей пропущено по price Цена до: " . (int)$delete_price_to .
            "<br/>";

        if ($conf['check_import_filter_brand'] == '1') {
            cmdexec(dirname(__dir__ ) . '/php.x64/php.exe -q ' . __dir__ .
                '/parseralibaba/sync_brand_r.php');
        }

        db_sql_query($db, 'TRUNCATE `' . $db_table_parser_stat . '`');
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////
// очистка базы данных
if (isset($_POST[del_resume])) {
    $ids = implode(',', $_POST[ids]);

    if ($ids != '') {
        $sql = "INSERT INTO `$db_table_deleted`(`asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile`) 
                SELECT `asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile` FROM $db_table WHERE `id` IN ($ids)";

        db_sql_query($db, $sql);

        $sql = "DELETE FROM $db_table WHERE `id` IN ($ids)";
        db_sql_query($db, $sql);
        $del_cnt = (int)mysqli_affected_rows($db);
        $sql = 'DELETE FROM `' . $db_table_results . '` WHERE (SELECT COUNT(*) FROM `' .
            $db_table . '` WHERE `parser_alibaba_results`.`asin` = `parser_china`.`asin`) = 0';
        db_sql_query($db, $sql);
    }

    $result[error] = 'Удалено записей: ' . (int)$del_cnt;
}
/////////////////////////////////////////////////////////////////////////////////////////////
// очистка базы данных
if (isset($_GET[db_clear])) {
    if (!isset($_POST[db_clear_prop]) || $_POST[db_clear_prop] == '') {
        //$sql = "TRUNCATE $db_table";
        //db_sql_query($db, $sql);
        //$result[error] = 'База данных очищена!';
        $sql = "SELECT `id` FROM `$db_table`";
        $rows = db_sql_query($db, $sql);
        foreach ($rows as $key => $val) {
            $sql = "INSERT INTO `$db_table_deleted`(`asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile`) 
                    SELECT `asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile` FROM $db_table WHERE `id` = '" .
                $val[id] . "'";
            db_sql_query($db, $sql);

            $sql = "DELETE FROM `$db_table_results` WHERE `asin` = '" . $val['asin'] . "'";
            db_sql_query($db, $sql);

            $sql = "DELETE FROM `$db_table` WHERE `id` = '" . $val[id] . "'";
            db_sql_query($db, $sql);
            $del_cnt += mysqli_affected_rows($db);
        }

        $sql = "DELETE FROM `$db_table_results` WHERE 1";
        db_sql_query($db, $sql);

        $result[error] = 'Удалено записей: ' . intval($del_cnt);
    } elseif ($_POST[db_clear_prop] != '') {
        $db_clear_prop = explode('/', mb_strtolower($_POST[db_clear_prop], 'UTF-8'));

        $sql = "SELECT * FROM `$db_table`";
        $rows = db_sql_query($db, $sql);
        foreach ($rows as $key => $val) {
            $info = json_decode($rows[$key][info], true);
            foreach ($db_clear_prop as $k => $v) {
                if ($v == 'не найденные' && $info[URLs_Ali] == '') {
                    $sql = "INSERT INTO `$db_table_deleted`(`asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile`) 
                            SELECT `asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile` FROM $db_table WHERE `id` = '" .
                        $val[id] . "'";
                    db_sql_query($db, $sql);

                    $sql = "DELETE FROM `$db_table_results` WHERE `asin` = '" . $val['asin'] . "'";
                    db_sql_query($db, $sql);

                    $sql = "DELETE FROM `$db_table` WHERE `id` = '" . $val[id] . "'";
                    db_sql_query($db, $sql);
                    $del_cnt += mysqli_affected_rows($db);
                    break;
                }
                if (strpos(' ' . mb_strtolower($info[Brand_R], 'UTF-8') . ' ', 'available') !== false) {
                    continue;
                }
                if (strpos(' ' . mb_strtolower($info[Brand_R], 'UTF-8') . ' ', $v) !== false) {
                    $sql = "INSERT INTO `$db_table_deleted`(`asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile`) 
                            SELECT `asin`, `info`, `comparsion_info`, `date_add`, `date_update`, `title`, `categories`, `images`, `images_url`, `pack`, `parse_at`, `profile` FROM $db_table WHERE `id` = '" .
                        $val[id] . "'";
                    db_sql_query($db, $sql);

                    $sql = "DELETE FROM `$db_table_results` WHERE `asin` = '" . $val['asin'] . "'";
                    db_sql_query($db, $sql);

                    $sql = "DELETE FROM `$db_table` WHERE `id` = '" . $val[id] . "'";
                    db_sql_query($db, $sql);
                    $del_cnt += mysqli_affected_rows($db);
                    break;
                }
            }
        }
        $result[error] = 'Удалено записей: ' . intval($del_cnt);
    }

    //foreach(glob($photo_dir.'*') as $file){unlink($file);}
    //foreach(glob($photo_small_dir.'*') as $file){unlink($file);}
    //removeDirRec($photo_dir);
    //removeDirRec($photo_small_dir);

    unlink(dirname(__file__) . '/data/parse.last');
    foreach (glob(dirname(__file__) . '/data/*.last.page') as $file) {
        unlink($file);
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////
// очистка базы данных
if (isset($_GET[db_clear_statistics]) && $this_user[user_level] == $admin_level) {
    $sql = "TRUNCATE $db_table_statistics";
    db_sql_query($db, $sql);

    $result[error] = 'База данных очищена!';
}
/////////////////////////////////////////////////////////////////////////////////////////////
// инициализация страницы
$_GET[page_str] = 50;
if (isset($_SESSION[page_str]))
    $_GET[page_str] = $_SESSION[page_str];
$page_str = $_GET[page_str];

for ($i = 0; $i <= 5; $i++) {
    $val = ($i * 2) * 50;
    if ($val == 0)
        $val = 50;
    if ($_GET[page_str] == $val)
        $val_sel = 'selected';
    else
        $val_sel = '';
    $result[page_str_list] .= '<option value="' . $val . '" ' . $val_sel . '>' . $val .
        '</option>';
}

$page = (int)$_GET[page];

if (isset($_GET[order_by]) && $_GET[order_by] != '') {
    $order_by = strip_tags($_GET[order_by]);
} else {
    $order_by = 'id';
}

$result[order_by] = $order_by;

switch ($order_by) {
    case 'asin':
        break;
    case 'title':
        $order_by = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Title\"'),'')), '')";

        break;
    case 'fba':
        $order_by = "CAST(IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBA\"'),'')), '') AS DECIMAL(10,2))";

        break;
    case 'fbm':
        $order_by = "CAST(IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBM\"'),'')), '') AS DECIMAL(10,2))";

        break;
    case 'bsr':
        $order_by = "CAST(IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Sales Rank: Current\"'),'')), '') AS DECIMAL(10,2))";

        break;
    case 'category':
        $order_by = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Categories: Root\"'),'')), '')";

        break;
    case 'Profile':
        break;
    case 'date_add':
        break;
    case 'date_update':
        break;
}


if ($page == 0) {
    if (isset($_GET[direction]) && $_GET[direction] == 'ASC') {
        $direction = 'DESC';
    } else {
        $direction = 'ASC';
    }
} else {
    $direction = $_GET[direction];
}

if (empty($direction) || $direction == '') {
    $direction = 'DESC';
}

if (!isset($_GET[order_by]) || $_GET[order_by] == '') {
    $_GET[order_by] = 'id';
}


$argv = $_POST + $_GET;
$find = array();
if (isset($_SESSION[find_ext_submit])) {
    $result[categories] = $find[categories] = mysqli_real_escape_string($db, $_SESSION[categories]);
    $result[category_name] = $find[category_name] = mysqli_real_escape_string($db, $_SESSION[category_name]);
    $result[date_start] = $find[date_start] = mysqli_real_escape_string($db, $_SESSION[date_start]);
    $result[date_end] = $find[date_end] = mysqli_real_escape_string($db, $_SESSION[date_end]);
    $result[date_start_add] = $find[date_start_add] = mysqli_real_escape_string($db,
        $_SESSION[date_start_add]);
    $result[date_end_add] = $find[date_end_add] = mysqli_real_escape_string($db, $_SESSION[date_end_add]);
    $result[date_start_update] = $find[date_start_update] =
        mysqli_real_escape_string($db, $_SESSION[date_start_update]);
    $result[date_end_update] = $find[date_end_update] = mysqli_real_escape_string($db,
        $_SESSION[date_end_update]);
    //$result[parser_id] = $find[parser_id] = mysqli_real_escape_string($db, $_SESSION[parser_id]);
    $result[list_id] = $find[list_id] = mysqli_real_escape_string($db, $_SESSION[list_id]);
    $result[min_star_export] = $find[min_star_export] = mysqli_real_escape_string($db,
        $_SESSION[min_star_export]);
    $result[title_filter] = $find[title_filter] = mysqli_real_escape_string($db, $_SESSION[title_filter]);
    $result[developer_filter] = $find[developer_filter] = mysqli_real_escape_string($db,
        $_SESSION[developer_filter]);
    $result[asin_filter] = $find[asin_filter] = mysqli_real_escape_string($db, $_SESSION[asin_filter]);

    $result['fba_filter_from'] = $find['fba_filter_from'] =
        mysqli_real_escape_string($db, $_SESSION['fba_filter_from']);
    $result['fbm_filter_from'] = $find['fbm_filter_from'] =
        mysqli_real_escape_string($db, $_SESSION['fbm_filter_from']);
    $result['bsr_filter_from'] = $find['bsr_filter_from'] =
        mysqli_real_escape_string($db, $_SESSION['bsr_filter_from']);
    $result['fba_filter_to'] = $find['fba_filter_to'] = mysqli_real_escape_string($db,
        $_SESSION['fba_filter_to']);
    $result['fbm_filter_to'] = $find['fbm_filter_to'] = mysqli_real_escape_string($db,
        $_SESSION['fbm_filter_to']);
    $result['bsr_filter_to'] = $find['bsr_filter_to'] = mysqli_real_escape_string($db,
        $_SESSION['bsr_filter_to']);
    $result['category_filter'] = $find['category_filter'] =
        mysqli_real_escape_string($db, $_SESSION['category_filter']);
    $result['profile_filter'] = $find['profile_filter'] = mysqli_real_escape_string($db,
        $_SESSION['profile_filter']);

    $where = [];

    if ($result[categories] != '')
        $where[] = "`categories` LIKE '%{$result[categories]}%'";
    if ($result[category_name] != '')
        $where[] = "`categories` LIKE '%{$result[category_name]}%'";
    if ($result[date_start] != '')
        $where[] = "`item-add-date` >= '{$result[date_start]} 00:00' ";
    if ($result[date_end] != '')
        $where[] = "`item-add-date` <= '{$result[date_end]} 23:59' ";
    if ($result[date_start_add] != '')
        $where[] = "`date_add` >= '{$result[date_start_add]} 00:00' ";
    if ($result[date_end_add] != '')
        $where[] = "`date_add` <= '{$result[date_end_add]} 23:59' ";
    if ($result[date_start_update] != '')
        $where[] = "`date_update` >= '{$result[date_start_update]} 00:00' ";
    if ($result[date_end_update] != '')
        $where[] = "`date_update` <= '{$result[date_end_update]} 23:59' ";
    //if($result[parser_id] != '') $where[] = "`parser_id` = '{$result[parser_id]}' ";
    if ($result[min_star_export] != '')
        $where[] = "`star` >= '" . intval($result[min_star_export]) . "' ";
    if ($result[asin_filter] != '')
        $where[] = "`asin` LIKE '%{$result[asin_filter]}%' ";
    if ($result[developer_filter] != '')
        $where[] = "`developer` LIKE '%{$result[developer_filter]}%' ";

    if (($result['title_filter']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Title\"'),'')), '') like '%" .
            $result['title_filter'] . "%'";
    }

    if (($result['fba_filter_from']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBA\"'),'')), '') >= " .
            floatstrval($result['fba_filter_from']) . "";
    }
    if (($result['fba_filter_to']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBA\"'),'')), '') <= " .
            floatstrval($result['fba_filter_to']) . "";
    }
    if (($result['fbm_filter_from']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBM\"'),'')), '') >= " .
            floatstrval($result['fbm_filter_from']) . "";
    }
    if (($result['fbm_filter_to']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Count of retrieved live offers: New, FBM\"'),'')), '') <= " .
            floatstrval($result['fbm_filter_to']) . "";
    }
    if (($result['bsr_filter_from']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Sales Rank: Current\"'),'')), '') >= " .
            floatstrval($result['bsr_filter_from']) . "";
    }
    if (($result['bsr_filter_to']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Sales Rank: Current\"'),'')), '') <= " .
            floatstrval($result['bsr_filter_to']) . "";
    }
    if (($result['category_filter']) != '') {
        $where[] = "IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Categories: Root\"'),'')), '') = '" .
            $result['category_filter'] . "'";
    }
    if (($result['profile_filter']) != '') {
        $where[] = "`profile` like '%" . ($result['profile_filter'] == '-' ? '' : $result['profile_filter']) .
            "%'";
    }


    $sql = "SELECT `$db_table`.* FROM `$db_table` ";
    $sql_pager = $sql;

    if ($result[list_id] != '') {
        $where[] = "`$db_table_lists_urls`.`list_id` = '{$result[list_id]}' ";
    }

    $where = implode(' and ', $where);
    if ($where != '') {
        $sql .= " WHERE " . $where;
    }

    $sql .= " ORDER BY $order_by $direction";
    //$sql .= " ORDER BY `item-add-date` DESC, `id` DESC";
    //echo $sql;

    $pager = pager($db, $db_table, $page, $page_str, $where, $sql_pager);

    $sql .= " LIMIT " . $pager[start] . ", " . $pager[end];
    $rows = db_sql_query($db, $sql);

} else
    if (isset($argv[find]) && $argv[find_txt] != '') {
        $result[find_txt] = $argv[find_txt];
        $search = mysqli_real_escape_string($db, $argv[find_txt]);

        $sql = "SELECT $db_table.* FROM `$db_table` WHERE IF(JSON_VALID(`info`), coalesce(JSON_EXTRACT(`info`, '$.\"Categories\"'),''), '') LIKE '%$search%' or IF(JSON_VALID(`info`), coalesce(JSON_EXTRACT(`info`, '$.\"Title\"'),''), '') LIKE '%$search%'";
        $rows = db_sql_query($db, $sql);

        $result[reset_filtr_display] = 'display';
    } else {
        $pager = pager($db, $db_table, $page, $page_str);

        $sql = "SELECT $db_table.* FROM `$db_table` ";

        $sql .= " ORDER BY $order_by $direction";
        //$sql .= " ORDER BY `item-add-date` DESC, `id` DESC";

        $sql .= " LIMIT " . $pager[start] . ", " . $pager[end];

        $rows = db_sql_query($db, $sql);
        //printr($rows);
    }
    //printr($sql);

    $result[direction2] = $_GET[direction];
//$result[direction2] = $direction;
$rows_tmp = $rows;

/////////////////////////////////////////////////////////////////////////////////////////////
if ($order_by == 'id' && $direction == 'ASC')
    $result[sort_marker_id] = '&#8679;';
if ($order_by == 'id' && $direction == 'DESC')
    $result[sort_marker_id] = '&#8681;';
//if($order_by == 'item-add-date' && $direction == 'ASC') $result[sort_marker_item_date] = '&#8679;';
//if($order_by == 'item-add-date' && $direction == 'DESC') $result[sort_marker_item_date] = '&#8681;';
if ($order_by == 'date_add' && $direction == 'ASC')
    $result[sort_marker_date_add] = '&#8679;';
if ($order_by == 'date_add' && $direction == 'DESC')
    $result[sort_marker_date_add] = '&#8681;';
if ($order_by == 'date_update' && $direction == 'ASC')
    $result[sort_marker_date_update] = '&#8679;';
if ($order_by == 'date_update' && $direction == 'DESC')
    $result[sort_marker_date_update] = '&#8681;';
if ($order_by == 'star' && $direction == 'ASC')
    $result[sort_marker_star] = '&#8679;';
if ($order_by == 'star' && $direction == 'DESC')
    $result[sort_marker_star] = '&#8681;';
if ($order_by == 'star_diff' && $direction == 'ASC')
    $result[sort_marker_star_diff] = '&#8679;';
if ($order_by == 'star_diff' && $direction == 'DESC')
    $result[sort_marker_star_diff] = '&#8681;';
if ($order_by == 'average_star' && $direction == 'ASC')
    $result[sort_marker_average_star] = '&#8679;';
if ($order_by == 'average_star' && $direction == 'DESC')
    $result[sort_marker_average_star] = '&#8681;';
if ($order_by == 'developer' && $direction == 'ASC')
    $result[sort_marker_developer] = '&#8679;';
if ($order_by == 'developer' && $direction == 'DESC')
    $result[sort_marker_developer] = '&#8681;';
/////////////////////////////////////////////////////////////////////////////////////////////
// csv export
if (isset($_GET[db_export_csv3])) {
    $_GET[db_export_csv] = '';
    //foreach(glob($contents_dir.'*.csv') as $file){unlink($file);}
}
/////////////////////////////////////////////////////////////////////////////////////////////
// csv export
if (isset($_GET[db_export_csv])) {
    ini_set('max_execution_time', '864000');
    ini_set('memory_limit', '4024M');
    include_once ("lib/PHPExcel.php");
    include_once ("lib/PHPExcel/Writer/Excel2007.php");

    if ($conf[export_path] != '')
        $contents_dir = $conf[export_path];

    //foreach(glob($contents_dir.'*.csv') as $file){unlink($file);}
    //foreach(glob($contents_dir.'*.xlsx') as $file){unlink($file);}
    //foreach(glob($contents_dir.'*.json') as $file){unlink($file);}

    $parse_ext = intval($_POST[db_export_json]);
    $str_json_all = array();

    $category_name_sql = '';
    if ($_POST[category_name] != '') {
        $category_name_sql = mysqli_real_escape_string($db, trim($_POST[category_name]));
        $category_name = str_replace('--', '-', trim(preg_replace('|[^a-z0-9]+|Uis', '-',
            strtolower(translit_ru($_POST[category_name])))));
        $category_name = "_{$category_name}_";
    }
    $parser_id_tmp = 'alibaba';
    $fname = $contents_dir . "result_" . date("Y.m.d.H.00") . '.csv';
    $fname_xls_name = $parser_id_tmp . ".result_" . date("Y.m.d.H.00") . $category_name .
        '.xlsx';
    $fname_xls = $contents_dir . $fname_xls_name;
    $fname_win1251_name = $parser_id_tmp . ".result_" . date("Y.m.d.H.00") . $category_name .
        '.cp1251.csv';
    $fname_win1251 = $contents_dir . $fname_win1251_name;
    $fname_json_name = $parser_id_tmp . ".result_" . date("Y.m.d.H.00") . $category_name .
        '.json';
    $fname_json = $contents_dir . $fname_json_name;

    $rows = array();
    $sql = "SELECT `$db_table`.* FROM `$db_table`";
    if ($category_name_sql != '')
        $sql .= " WHERE `categories` LIKE '$category_name_sql%'";
    //$sql .= " LIMIT 100";
    $rows = db_sql_query($db, $sql);

    foreach ($rows as $keyrow => $row) {
        foreach ($row as $k => $v) {
            if ($k == 'info') {
                $info = json_decode($v, true);

                if (is_array($info)) {
                    foreach ($info as $k2 => $v2) {
                        $info[$k2] = strfloatval($v2);
                    }
                }

                $rows[$keyrow][$k] = json_encode($info);
            } else {
                $rows[$keyrow][$k] = strfloatval($v);
            }
        }
    }


    $countrows = 0;

    if ($_POST[db_export_prop] != '') {
        $db_export_prop = explode('/', mb_strtolower($_POST[db_export_prop], 'UTF-8'));
        foreach ($rows as $key => $val) {
            $info = json_decode($rows[$key][info], true);
            foreach ($db_export_prop as $k => $v) {
                //printr($v);
                if ($v == 'найденные') {
                    if ($info[URLs_Ali] == '')
                        unset($rows[$key]);
                    break;
                } elseif (mb_strtolower($info[Brand_R], 'UTF-8') != $v) {
                    unset($rows[$key]);
                    break;
                }

                $countrows++;
            }
        }
    }

    db_sql_query($db, "INSERT INTO `$db_table_stat`(`id`, `parser_id`, `user_id`, `user_name`, `title`, `date_add`, `ip`, `browser`, `profile`) 
    VALUES (null,'$parser_id',1,'','Экспорт: $fname',NOW(),'$this_user_ip','$countrows', '" .
        mysqli_real_escape_string($db, get_config_name()) . "')");


    $parameters = array();
    $attributes = array();
    foreach ($rows as $key => $val) {
        unset($val['count_results']);
        unset($val['urls_ali']);
        $val[parameters] = json_decode($val[info], true);
        // -------------------------------------------------------------------
        unset($val[parameters]['comparsion_info']);
        unset($val[parameters]['results_all_all']);
        unset($val[parameters]['results_1_1']);
        unset($val[parameters]['1 - 1 Best result']);
        unset($val[parameters]['1-1 Best result URL']);
        unset($val[parameters]['All-All Best result']);
        unset($val[parameters]['All-All Best URL']);
        unset($val[parameters]['All-All Best Images']);
        // -------------------------------------------------------------------
        $attributes = $val[parameters][attributes];
        unset($val[parameters][attributes]);
        foreach ($val[parameters] as $k => $v) {
            //$k = 'cf_'.str_replace('-', '_', $k);
            $parameters[$k] = $k;
        }
    }

    $parameters['comparsion_info'] = '';
    $parameters['1 - 1 Best result'] = '';
    $parameters['1-1 Best result URL'] = '';
    $parameters['All-All Best result'] = '';
    $parameters['All-All Best URL'] = '';
    $parameters['All-All Best Images'] = '';
    /*
    if(isset($parameters['Характеристики'])){
    unset($parameters['Характеристики']);
    $parameters['Характеристики'] = 'Характеристики';
    }
    */
    //printr($parameters);

    /*
    $new_rows = array();
    foreach ($rows as $key => $val){
    $item_url = $val[item_url];
    if(!isset($new_rows[$item_url])) $new_rows[$item_url] = $val;
    }
    $rows = $new_rows;
    */

    $header = '';
    foreach ($rows as $key => $val) {
        //echo '<pre>';
        //print_r($val);
        $id = $val[id];
        $comparsion_info = $val[comparsion_info];
        unset($val[comparsion_info]);
        unset($val[results_all_all]);
        unset($val[results_1_1]);
        unset($val[id]);
        unset($val[parser_id]);
        unset($val['categories']);
        unset($val['article']);
        unset($val['title']);
        unset($val[asin]);
        $info = json_decode($val[info], true);
        unset($info[asin]);
        unset($info[date_update]);
        unset($val['count_results']);
        unset($val['urls_ali']);
        //$sets = json_decode($val[sets], true);
        //$sets = $val[sets];
        //$val[sets] = json_decode($val[sets], true);
        //$val[sets] = implode(", \n", $val[sets]);
        //$parameters[images] = $val[images];
        $images = $val[images];
        unset($val[images]);
        //$parameters[images_url] = $val[images_url];
        unset($val[images_url]);
        unset($val[sets]);
        unset($val[date_add]);
        unset($val[date_update]);
        unset($val[info]);
        unset($parameters[info]);
        //printr($info);
        $item_url = $val[item_url];
        //$parameters[item_url] = 'item_url';
        unset($val[item_url]);
        unset($val[item_url_md5]);
        $available_last_parse = $val[available_last_parse];
        unset($val[available_last_parse]);

        //$info[video] = $parameters[video];
        //$info[images] = $parameters[images];
        /*
        $images = explode(',', $images);
        foreach($images as $k=>$v){
        $info['Изображение'.($k+1)] = $v;
        }
        */
        //$info[images_url] = $parameters[images_url];
        //$info[item_url] = $item_url;
        // -------------------------------------------------------------------------------
        /*
        $val[info] = array();
        foreach($info as $k=>$v){
        $val[info][] = "$k: $v" ;
        }
        $val[info] = implode("\r\n", $val[info]);
        */

        /*
        $val[sets] = array();
        foreach($sets as $k=>$v){
        //$val[sets][] = "$k: $v" ;
        $val[sets][] = implode("\r\n", $v);              
        }
        $val[sets] = implode("\r\n\r\n\r\n", $val[sets]);
        */
        if ($header == '') {
            $header = array();
            $header_cp1251 = array();
            foreach ($val as $k => $v) {
                $header[] = $k;
                $header_cp1251[] = mb_convert_encoding($k, 'WINDOWS-1251', 'UTF-8');
            }

            foreach ($parameters as $k => $v) {
                $k = array_shift(explode('|', $k));
                $header[] = $k;
                $header_cp1251[] = mb_convert_encoding($k, 'WINDOWS-1251', 'UTF-8');
            }
            //$header[] = 'img';
            //$header = array_shift(str_read_csv('data/header.csv'));
            //$header_cp1251 = array_shift(str_read_csv('data/header.cp1251.csv'));

            if (!isset($_GET[db_export_csv3]))
                str_write_csv($fname, $header, 'w');

            if (isset($_GET[db_export_csv3]))
                str_write_csv($fname_win1251, $header_cp1251, 'w');
        }
        //$val[''] = '';

        foreach ($parameters as $k => $v) {
            $val[$k] = '';
        }
        foreach ($info as $k => $v) {
            $val[$k] = str_replace('\\', '\\\\', $v);
        }
        $_images = explode(',', $val[images]);
        $_images_url = explode(',', $val[images_url]);
        if (count($_images) != count($_images_url)) {
            $result[error] .= "{$val['Наименование']} {$val[item_url]} / " . count($_images) .
                " " . count($_images_url) . "<br>";
        }
        //if($available_last_parse == 0) $val['Наличие'] = 'Нет на сайте';
        /*
        $imgs = explode(',', $val[images]);
        foreach($imgs as $k=>$v){
        $img = $v;
        if(!file_exists(substr($photo_small_dir, 0, -1))) mkdir(substr($photo_small_dir, 0, -1));
        if(!file_exists($photo_small_dir.$img)) imageresize($photo_dir.$img, $photo_small_dir.$img, $img_w, $img_h);
        $val[] = $img;
        //break;
        }
        */
        //$val[info] = '';
        // -------------------------------------------------------------------
        /*
        if($val[info] != ''){
        $val[info] = explode(',', $val[info]);
        foreach($val[info] as $k=>$v){
        if(!file_exists(substr($photo_small_dir, 0, -1))) mkdir(substr($photo_small_dir, 0, -1));
        $img_w = 250; 
        $img_h = 100;
        if(!file_exists($photo_small_dir.$val[info][$k])) imageresize($photo_dir.$v, $photo_small_dir.$v, $img_w, $img_h, true, $quality=75, $min_size = 0, $type = 'png');
        }
        $val[info] = implode(',', $val[info]);
        }
        */
        // -------------------------------------------------------------------
        //$val[ExpirationDate] .= ' / '.date('Y-m-d', strtotime($val[ExpirationDate]));
        //$val[ExpirationDate] = date('Y-m-d', strtotime($val[ExpirationDate]));

        // -------------------------------------------------------------------
        if ($parse_ext) {
            $str_json = array();
            $str_json = $val;

            $str_json_all[] = $str_json;
        }
        // -------------------------------------------------------------------
        $comparsion_info = '{"best_res_1_1": "32.83", "best_url_1_1": "https://www.ebay.com/itm/182668631605", "best_res_all_all": "81.67", "best_url_all_all": "https://www.ebay.com/itm/182668631605", "best_pair_all_all": "https://images-na.ssl-images-amazon.com/images/I/41rA5VDFqKL.jpg;https://i.ebayimg.com/images/g/TaEAAOSwcx5ZaP0i/s-l500.jpg"}';
        $val['comparsion_info'] = $comparsion_info;
        $comparsion_info = json_decode($comparsion_info, true);
        //printr($comparsion_info);
        $val['1 - 1 Best result'] = $comparsion_info['best_res_1_1'];
        $val['1-1 Best result URL'] = $comparsion_info['best_url_1_1'];
        $val['All-All Best result'] = $comparsion_info['best_res_all_all'];
        $val['All-All Best URL'] = $comparsion_info['best_url_all_all'];
        $val['All-All Best Images'] = $comparsion_info['best_pair_all_all'];
        // -------------------------------------------------------------------
        //echo '<pre>';
        //print_r($str_json);
        //printr($val);

        if (isset($_GET[db_export_csv3])) {
            $str2 = array();
            foreach ($val as $k => $v) {
                $str2[] = mb_convert_encoding($v, 'WINDOWS-1251', 'UTF-8');
            }
            str_write_csv($fname_win1251, $str2, 'a');
        } else {
            $str = array();
            foreach ($val as $k => $v) {
                $str[] = trim($v);
            }
            str_write_csv($fname, $str, 'a');
        }

        $export_cnt++;
        $export_cnt_rows++;
    }

    rename($fname_win1251, str_replace('.csv', '.' . $export_cnt_rows . '.csv', $fname_win1251));
    $fname_xls_name = str_replace('.xlsx', '.' . $export_cnt_rows . '.xlsx', $fname_xls_name);
    $fname_xls = str_replace('.xlsx', '.' . $export_cnt_rows . '.xlsx', $fname_xls);
    $fname_win1251_name = str_replace('.csv', '.' . $export_cnt_rows . '.csv', $fname_win1251_name);

    if ($parse_ext) {
        bin_write($fname_json, json_encode($str_json_all), 'w');
        $result[error] .= 'Export json items: ' . (int)count($str_json_all) . '<br/>';
        if ($conf[export_path] != '') {
            $result[error] .= 'Результат: <a href="' . $conf[export_path] . $fname_json_name .
                '" target="_blank">' . $fname_json_name . '</a><br/>';
        } else {
            $result[error] .= 'Результат: <a href="contents/' . $fname_json_name .
                '" target="_blank">' . $fname_json_name . '</a><br/>';
        }
        //printr($str_json_all);
    }

    if (isset($_GET[db_export_csv3])) {
        $result[error] .= 'Export items: ' . (int)$export_cnt . ' / Export rows: ' . (int)
            $export_cnt_rows . '<br/>';
        if ($conf[export_path] != '') {
            $result[error] .= 'Результат: <a href="' . $conf[export_path] . $fname_win1251_name .
                '" target="_blank">' . $fname_win1251_name . '</a><br/>';
        } else {
            $result[error] .= 'Результат: <a href="contents/' . $fname_win1251_name .
                '" target="_blank">' . $fname_win1251_name . '</a><br/>';
        }
        unlink($fname);
    } else {
        $csv = array();
        $csv['Products'] = str_read_csv($fname);
        //csv2xls_array($csv, $fname_xls, true, $photo_small_dir, 14);
        csv2xls_array($csv, $fname_xls, true, $photo_small_dir, 12);
        $result[error] .= 'Export items: ' . (int)$export_cnt . ' / Export rows: ' . (int)
            $export_cnt_rows . '<br/>';
        if ($conf[export_path] != '') {
            $result[error] .= 'Результат: <a href="' . $conf[export_path] . $fname_xls_name .
                '" target="_blank">' . $fname_xls_name . '</a><br/>';
        } else {
            $result[error] .= 'Результат: <a href="contents/' . $fname_xls_name .
                '" target="_blank">' . $fname_xls_name . '</a><br/>';
        }
        //csv2xls_array($csv, '');
        unlink($fname);
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
// csv export
if (isset($_GET[db_export_csv3_result])) {
    $_GET[db_export_csv_result] = '';
    //foreach(glob($contents_dir.'*.csv') as $file){unlink($file);}
}
/////////////////////////////////////////////////////////////////////////////////////////////
// csv export
if (isset($_GET[db_export_csv_result])) {
    ini_set('max_execution_time', '864000');
    ini_set('memory_limit', '4024M');
    include_once ("lib/PHPExcel.php");
    include_once ("lib/PHPExcel/Writer/Excel2007.php");

    if ($conf[export_path] != '')
        $contents_dir = $conf[export_path];

    /*
    foreach (glob($contents_dir . '*.csv') as $file) {
    unlink($file);
    }
    foreach (glob($contents_dir . '*.xlsx') as $file) {
    unlink($file);
    }
    foreach (glob($contents_dir . '*.json') as $file) {
    unlink($file);
    }
    */

    $parse_ext = intval($_POST[db_export_json]);
    $str_json_all = array();

    $category_name_sql = '';
    if ($_POST[category_name] != '') {
        $category_name_sql = mysqli_real_escape_string($db, trim($_POST[category_name]));
        $category_name = str_replace('--', '-', trim(preg_replace('|[^a-z0-9]+|Uis', '-',
            strtolower(translit_ru($_POST[category_name])))));
        $category_name = "_{$category_name}_";
    }

    $fname = $contents_dir . "parse.result_" . date("Y.m.d.H.00") . '.csv';
    $fname_xls_name = $parser_id . ".parse.result_" . date("Y.m.d.H.00") . $category_name .
        '.xlsx';
    $fname_xls = $contents_dir . $fname_xls_name;
    $fname_win1251_name = $parser_id . ".parse.result_" . date("Y.m.d.H.00") . $category_name .
        '.cp1251.csv';
    $fname_win1251 = $contents_dir . $fname_win1251_name;
    $fname_json_name = $parser_id . ".parse.result_" . date("Y.m.d.H.00") . $category_name .
        '.json';
    $fname_json = $contents_dir . $fname_json_name;

    $rows = array();
    $sql = "SELECT `$db_table_results`.* FROM `$db_table_results`";
    //$sql .= " LIMIT 100";
    $rows = db_sql_query($db, $sql);

    foreach ($rows as $keyrow => $row) {
        foreach ($row as $k => $v) {
            if ($k == 'results') {
                $info = json_decode($v, true);

                unset($info[roi]);
                unset($info[Find_Ali]);
                unset($info[Margin]);
                unset($info[ROI]);
                unset($info[date_update]);

                if (is_array($info)) {
                    foreach ($info as $k2 => $v2) {
                        $info[$k2] = strfloatval($v2);
                    }
                }

                $rows[$keyrow][$k] = json_encode($info);
            } else {
                $rows[$keyrow][$k] = strfloatval($v);
            }
        }
    }

    $countrows = count($rows);


    db_sql_query($db, "INSERT INTO `$db_table_stat`(`id`, `parser_id`, `user_id`, `user_name`, `title`, `date_add`, `ip`, `browser`) 
    VALUES (null,'$parser_id',1,'','Експорт $fname',NOW(),'$this_user_ip','$countrows')");


    $parameters = array();
    $attributes = array();
    foreach ($rows as $key => $val) {
        $val[parameters] = json_decode($val[results], true);
        $attributes = $val[parameters][attributes];
        unset($val[parameters][attributes]);
        unset($val[parameters][roi]);
        unset($val[parameters][Find_Ali]);
        unset($val[parameters][margin]);
        unset($val[parameters][ROI]);
        foreach ($val[parameters] as $k => $v) {
            //$k = 'cf_'.str_replace('-', '_', $k);
            $parameters[$k] = $k;
        }
    }
    /*
    if(isset($parameters['Характеристики'])){
    unset($parameters['Характеристики']);
    $parameters['Характеристики'] = 'Характеристики';
    }
    */
    //printr($parameters);

    /*
    $new_rows = array();
    foreach ($rows as $key => $val){
    $item_url = $val[item_url];
    if(!isset($new_rows[$item_url])) $new_rows[$item_url] = $val;
    }
    $rows = $new_rows;
    */

    $header = '';
    foreach ($rows as $key => $val) {
        $id = $val[id];
        unset($val[id]);
        unset($val[parser_id]);
        unset($val['categories']);
        unset($val['article']);
        unset($val['title']);
        unset($val[asin]);
        $info = json_decode($val[results], true);
        unset($info[roi]);
        unset($info[Find_Ali]);
        unset($info[Margin]);
        unset($info[ROI]);
        unset($info[date_update]);
        $images = $val[images];
        unset($val[images]);
        unset($val[images_url]);
        unset($val[sets]);
        unset($val[date_add]);
        unset($val[date_update]);
        unset($val[info]);
        unset($val[results]);
        unset($parameters[info]);
        $item_url = $val[item_url];
        unset($val[item_url]);
        unset($val[item_url_md5]);
        $available_last_parse = $val[available_last_parse];
        unset($val[available_last_parse]);

        // -------------------------------------------------------------------------------
        if ($header == '') {
            $header = array();
            $header_cp1251 = array();
            foreach ($val as $k => $v) {
                $header[] = $k;
                $header_cp1251[] = mb_convert_encoding($k, 'WINDOWS-1251', 'UTF-8');
            }

            foreach ($parameters as $k => $v) {
                $k = array_shift(explode('|', $k));
                $header[] = $k;
                $header_cp1251[] = mb_convert_encoding($k, 'WINDOWS-1251', 'UTF-8');
            }
            if (!isset($_GET[db_export_csv3_result]))
                str_write_csv($fname, $header, 'w');

            if (isset($_GET[db_export_csv3_result]))
                str_write_csv($fname_win1251, $header_cp1251, 'w');
        }
        //$val[''] = '';

        foreach ($parameters as $k => $v) {
            $val[$k] = '';
        }
        foreach ($info as $k => $v) {
            $val[$k] = str_replace('\\', '\\\\', $v);
        }
        $_images = explode(',', $val[images]);
        $_images_url = explode(',', $val[images_url]);
        if (count($_images) != count($_images_url)) {
            $result[error] .= "{$val['Наименование']} {$val[item_url]} / " . count($_images) .
                " " . count($_images_url) . "<br>";
        }
        // -------------------------------------------------------------------
        if ($parse_ext) {
            $str_json = array();
            $str_json = $val;

            $str_json_all[] = $str_json;
        }
        // -------------------------------------------------------------------
        if (isset($_GET[db_export_csv3_result])) {
            $str2 = array();
            foreach ($val as $k => $v) {
                $str2[] = mb_convert_encoding($v, 'WINDOWS-1251', 'UTF-8');
            }
            str_write_csv($fname_win1251, $str2, 'a');
        } else {
            $str = array();
            foreach ($val as $k => $v) {
                $str[] = trim($v);
            }
            str_write_csv($fname, $str, 'a');
        }

        $export_cnt++;
        $export_cnt_rows++;
    }

    rename($fname_win1251, str_replace('.csv', '.' . $export_cnt_rows . '.csv', $fname_win1251));
    $fname_xls_name = str_replace('.xlsx', '.' . $export_cnt_rows . '.xlsx', $fname_xls_name);
    $fname_xls = str_replace('.xlsx', '.' . $export_cnt_rows . '.xlsx', $fname_xls);
    $fname_win1251_name = str_replace('.csv', '.' . $export_cnt_rows . '.csv', $fname_win1251_name);

    if ($parse_ext) {
        bin_write($fname_json, json_encode($str_json_all), 'w');
        $result[error] .= 'Export json items: ' . (int)count($str_json_all) . '<br/>';
        if ($conf[export_path] != '') {
            $result[error] .= 'Результат: <a href="' . $conf[export_path] . $fname_json_name .
                '" target="_blank">' . $fname_json_name . '</a><br/>';
        } else {
            $result[error] .= 'Результат: <a href="contents/' . $fname_json_name .
                '" target="_blank">' . $fname_json_name . '</a><br/>';
        }
        //printr($str_json_all);
    }

    if (isset($_GET[db_export_csv3])) {
        $result[error] .= 'Export items: ' . (int)$export_cnt . ' / Export rows: ' . (int)
            $export_cnt_rows . '<br/>';
        if ($conf[export_path] != '') {
            $result[error] .= 'Результат: <a href="' . $conf[export_path] . $fname_win1251_name .
                '" target="_blank">' . $fname_win1251_name . '</a><br/>';
        } else {
            $result[error] .= 'Результат: <a href="contents/' . $fname_win1251_name .
                '" target="_blank">' . $fname_win1251_name . '</a><br/>';
        }
        unlink($fname);
    } else {
        $csv = array();
        $csv['Products'] = str_read_csv($fname);
        //csv2xls_array($csv, $fname_xls, true, $photo_small_dir, 14);
        csv2xls_array($csv, $fname_xls, true, $photo_small_dir, 12);
        $result[error] .= 'Export items: ' . (int)$export_cnt . ' / Export rows: ' . (int)
            $export_cnt_rows . '<br/>';
        if ($conf[export_path] != '') {
            $result[error] .= 'Результат: <a href="' . $conf[export_path] . $fname_xls_name .
                '" target="_blank">' . $fname_xls_name . '</a><br/>';
        } else {
            $result[error] .= 'Результат: <a href="contents/' . $fname_xls_name .
                '" target="_blank">' . $fname_xls_name . '</a><br/>';
        }
        //csv2xls_array($csv, '');
        unlink($fname);
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////


$rows = $rows_tmp;
unset($rows_tmp);

$data = '';
foreach ($rows as $key => $val) {
    $i++;
    if ($i % 2 == 0) {
        $class = 'class="c1"';
    } else {
        $class = '';
    }

    $val[info] = $info = json_decode($val[info], true);

    $img = array();
    if ($val[images] != '') {
        $val[images] = explode(',', $val[images]);
        foreach ($val[images] as $k => $v) {
            if (!file_exists(substr($photo_small_dir, 0, -1)))
                mkdir(substr($photo_small_dir, 0, -1));
            if (!file_exists($photo_small_dir . $val[images][$k]))
                imageresize($photo_dir . $v, $photo_small_dir . $v, $img_w, $img_h);
            if (file_exists($photo_small_dir . $val[images][$k]))
                $img[] = '<a href="' . $photo_dir . $v . '" target="_blank"><img src="' . $photo_small_dir .
                    $v . '" border="0" alt="photo"></a> ';
            break;
        }
    }
    $img = implode('<br/>', $img);
    if (count($val[images]) > 1)
        $img .= '[' . count($val[images]) . ']';

    $data .= '<tr ' . $class . '>
                    <td><input type="checkbox" name="ids[]" class="checkbox" value="' .
        $val[id] . '"></td>
                    <!--<td width="180"><a href="view_id.php?id=' . $val[id] .
        '" title="Просмотреть запись #' . $val[id] . '">' . $val[title] . '</a></td>-->
                    <td width="100">' . $val[asin] . '</td>
                    <td>' . $val[info]['Title'] . '</td>
                    <td>' . $val[info]['Count of retrieved live offers: New, FBA'] .
        '</td>
                    <td>' . $val[info]['Count of retrieved live offers: New, FBM'] .
        '</td>
                    <td>' . $val[info]['Sales Rank: Current'] . '</td>
                    <td>' . $val[info]['Categories: Root'] . '</td>
                    <td>' . $val['profile'] . '</td>
                    <td width="120" style="white-space:nowrap;">' . str_replace(' ',
        '<br/>', $val[date_add]) . '</td>
                    <td width="120" style="white-space:nowrap;">' . str_replace(' ',
        '<br/>', $val[date_update]) . '</td>
                </tr>';
}
$data .= '</table>';

$result[db_data] = $data;
$result[direction] = $direction;
$order_by = $_GET[order_by];
//////////////////////////////////////////////////////////////////////////////////////////////////////
// формирование навигации по страницам
// первая стр
if ($pager[page] > $pager_count + 1) {
    $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
        array(
        'page_num' => '1',
        'order_by' => $order_by,
        'direction' => $direction)) . $pager_divider;
    if ($pager[page] > ($pager_count + 2))
        $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
            array(
            'page_num' => '...',
            'order_by' => $order_by,
            'direction' => $direction)) . $pager_divider;
}
// страницы до и после
for ($i = ($pager[page] - $pager_count); $i <= ($pager[page] + $pager_count); $i++) {
    if ($i > 0 && $i <= $pager[page_count]) {
        if ($i != $pager[page]) {
            $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
                array(
                'page_num' => $i,
                'order_by' => $order_by,
                'direction' => $direction)) . $pager_divider;
        } else {
            $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_active.tpl',
                array(
                'page_num' => $i,
                'order_by' => $order_by,
                'direction' => $direction)) . $pager_divider;
        }
    }
}
// последняя стр
if ($pager[page] < ($pager[page_count] - $pager_count)) {
    if ($pager[page] < ($pager[page_count] - ($pager_count + 1)))
        $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
            array(
            'page_num' => '...',
            'order_by' => $order_by,
            'direction' => $direction)) . $pager_divider;
    $res[pager_list] .= load_template(dirname(__file__) . '/tpl/page_noactive.tpl',
        array(
        'page_num' => $pager[page_count],
        'order_by' => $order_by,
        'direction' => $direction)) . $pager_divider;
}

$result[pager] = load_template(dirname(__file__) . '/tpl/pager.tpl', $res);
//////////////////////////////////////////////////////////////////////////////////////////////////////

$sql = "SELECT COUNT(*) as `cnt` FROM `$db_table`";
$rows = db_sql_query($db, $sql);
$result[total_cnt] = $rows[0][cnt];

if ($where != '') {
    $sql = "SELECT COUNT(*) as `cnt` FROM `$db_table` " . ($where != '' ? " WHERE " .
        $where : '');
    $rows = db_sql_query($db, $sql);
    $result[total_cnt_filter] = ' / Отфильтровано: ' . $rows[0][cnt];
} else {
    $result[total_cnt_filter] = '';
}

$sql = "SELECT COUNT(*) as `cnt` FROM `$db_table_deleted`";
$rows = db_sql_query($db, $sql);
$result[total_cnt_deleted] = $rows[0][cnt];

$sql = "SELECT COUNT(*) as `cnt` FROM `$db_table` where IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"URLs_Ali\"'),'')), '') != ''";
$rows = db_sql_query($db, $sql);
$result[total_is_cnt] = $rows[0][cnt];

foreach ($export_list as $key => $val) {
    $result['export_list'] .= '<option value="' . $val . '">' . $val . '</option>';
}
foreach ($delete_list as $key => $val) {
    $result['delete_list'] .= '<option value="' . $val . '">' . $val . '</option>';
}

// ---------------------------------------------------------------------------
$sql = "SELECT * FROM `$db_table_parser_stat` WHERE `parser_id` = 'trademarkia.com' ORDER BY `id` DESC LIMIT 1";
$rows = db_sql_query($db, $sql);

$str = array();
$description = '';
if ($rows[0][description] != '') {
    $description = json_decode($rows[0][description], true);
}
$date_start = strtotime($rows[0]['date_start']);
$date_end = '';
$time_diff = '<font color="red">-</font>';
$result_parse = '<font color="red">FALSE</font>';
if ($rows[0]['date_end'] != '' && $rows[0]['date_end'] != '0000-00-00 00:00:00') {
    $date_end = strtotime($rows[0]['date_end']);
    $time_diff = ($date_end - $date_start) / 60;
    $time_diff = round($time_diff);
    $result_parse = '<font color="green">OK</font>';
}
if ($rows[0]['date_end'] == '0000-00-00 00:00:00')
    $rows[0]['date_end'] = '-';

$str['start'] = $rows[0]['date_start'];
$str['end'] = $rows[0]['date_end'];
$str['time'] = $time_diff . ' min';
$dt1 = date_create($str['start']);
if ($str['start'] && $dt1) {
    $str['start'] = $dt1->format('d.m.Y H:i');
}
$dt2 = date_create($str['end']);
if ($str['end'] && $dt2) {
    $str['end'] = $dt2->format('d.m.Y H:i');
}
$str['parse'] = $description['parse_count_all'];
$str['result'] = $result_parse;
$result['parser_trademarkia_log'] = '-';
$result['parser_trademarkia_log'] = array();
foreach ($str as $k => $v) {
    $result['parser_trademarkia_log'][] = "<b>$k</b>: $v";
}
$result['parser_trademarkia_log'] = implode(' / ', $result['parser_trademarkia_log']);


// ---------------------------------------------------------------------------
$sql = "SELECT * FROM `$db_table_parser_stat` WHERE `parser_id` = 'comparator' ORDER BY `id` DESC LIMIT 1";
$rows = db_sql_query($db, $sql);

$str = array();
$description = '';
if ($rows[0][description] != '') {
    $description = json_decode($rows[0][description], true);
}
$date_start = strtotime($rows[0]['date_start']);
$date_end = '';
$time_diff = '<font color="red">-</font>';
$result_parse = '<font color="red">FALSE</font>';
if ($rows[0]['date_end'] != '' && $rows[0]['date_end'] != '0000-00-00 00:00:00') {
    $date_end = strtotime($rows[0]['date_end']);
    $time_diff = ($date_end - $date_start) / 60;
    $time_diff = round($time_diff);
    $result_parse = '<font color="green">OK</font>';
}
if ($rows[0]['date_end'] == '0000-00-00 00:00:00')
    $rows[0]['date_end'] = '-';

$str['start'] = $rows[0]['date_start'];
$str['end'] = $rows[0]['date_end'];
$str['time'] = $time_diff . ' min';
$dt1 = date_create($str['start']);
if ($str['start'] && $dt1) {
    $str['start'] = $dt1->format('d.m.Y H:i');
}
$dt2 = date_create($str['end']);
if ($str['end'] && $dt2) {
    $str['end'] = $dt2->format('d.m.Y H:i');
}
$str['parse'] = $description['parse_count_all'];
$str['result'] = $result_parse;
$result['parser_comparator_log'] = array();
foreach ($str as $k => $v) {
    $result['parser_comparator_log'][] = "<b>$k</b>: $v";
}
$result['parser_comparator_log'] = implode(' / ', $result['parser_comparator_log']);
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
$sql = "SELECT * FROM `$db_table_parser_stat` WHERE `parser_id` = 'alibaba' ORDER BY `id` DESC LIMIT 1";
$rows = db_sql_query($db, $sql);

$str = array();
$description = '';
if ($rows[0][description] != '') {
    $description = json_decode($rows[0][description], true);
}
$date_start = strtotime($rows[0]['date_start']);
$date_end = '';
$time_diff = '<font color="red">-</font>';
$result_parse = '<font color="red">FALSE</font>';


$dt = date_create($rows[0]['date_start']);

$dend = null;
$ttime = null;

if ($dt) {
    $duration = db_sql_query($db, "SELECT avg(duration) FROM `$db_table_parser_stat` WHERE `parser_id` = 'alibaba' and `duration` > 0");

    if (!empty($duration)) {
        $duration = floatstrval($duration[0]['avg(duration)']);
        $ttime = $duration * $result['total_cnt'];

        $dend = date('d.m.Y H:i', $dt->getTimestamp() + $ttime);
    }
}


if ($rows[0]['date_end'] != '' && $rows[0]['date_end'] != '0000-00-00 00:00:00') {
    $date_end = strtotime($rows[0]['date_end']);
    $time_diff = ($date_end - $date_start) / 60;
    $time_diff = round($time_diff);
    $result_parse = '<font color="green">OK</font>';
} elseif ($rows[0]['date_start'] != '' && $rows[0]['date_start'] !=
'0000-00-00 00:00:00') {

}
if ($rows[0]['date_end'] == '0000-00-00 00:00:00')
    $rows[0]['date_end'] = '-';

$str['start'] = $rows[0]['date_start'];
$str['end'] = $rows[0]['date_end'];
$str['time'] = $time_diff . ' min';
$dt1 = date_create($str['start']);
if ($str['start'] && $dt1) {
    $str['start'] = $dt1->format('d.m.Y H:i');
}
$dt2 = date_create($str['end']);
if ($str['end'] && $dt2) {
    $str['end'] = $dt2->format('d.m.Y H:i');
}
if ((!$str['end'] || $str['end'] == '-') && $dend && !empty($rows)) {
    $str['end'] = '<span style="color: red;">' . $dend . '</span>';

    $str['time'] = '<span style="color: red;">' . ($ttime > 60 ? round($ttime / 60,
        1) . ' мин.' : round($ttime, 1) . ' cек.') . '</span>';
}

$sql = "SELECT COUNT(*) as `cnt` FROM `parser_china` WHERE `parse_at` is not null";
$rows = db_sql_query($db, $sql);
$result[parse_count_all] = $rows[0][cnt];

$str['parse'] = $result['parse_count_all'];
$str['result'] = $result_parse;
$result['parser_alibaba_log'] = array();
foreach ($str as $k => $v) {
    $result['parser_alibaba_log'][] = "<b>$k</b>: $v";
}
$result['parser_alibaba_log'] = implode(' / ', $result['parser_alibaba_log']);
// ---------------------------------------------------------------------------

if (file_exists(__dir__ . '/data/last_import_file')) {
    $result['last_import_file'] = file_get_contents(__dir__ .
        '/data/last_import_file');
}

if ($conf['check_import_filter_brand'] == '1') {
    $result['parser_btn_trademarkia'] =
        '<input style="background-color: gray!important;" type="button" name="parser_trademark" id="parser_trademarkia" value="trademarkia" title="Запустить Парсер" class="search-button" data-onclick="alert(\'Отображение данных может идти с некоторой задержкой, после нажатия ОК ожидайте результатов.\'); return form_action(\'parser.trademarkia.com.multi.php?startalibaba=\'+($(\'#chkStartAlibabaTrademarkia\').prop(\'checked\') ? \'1\' : \'\')+\'&startcomparator=\'+($(\'#startComporatorAlibaba\').prop(\'checked\') ? \'\1\' : \'\'));" />';
} else {
    $result['parser_btn_trademarkia'] =
        '<input type="submit" name="parser_trademarkia" id="parser_trademarkia" value="trademark" title="Запустить Парсер" class="search-button" onclick="alert(\'Отображение данных может идти с некоторой задержкой, после нажатия ОК ожидайте результатов.\'); return form_action(\'parser.trademarkia.com.multi.php?startalibaba=\'+($(\'#chkStartAlibabaTrademarkia\').prop(\'checked\') ? \'1\' : \'\')+\'&startcomparator=\'+($(\'#startComporatorAlibaba\').prop(\'checked\') ? \'\1\' : \'\'));" />';
}

$result['setting_keys'] = [];

foreach (db_sql_query($db, "SELECT `" . $db_table_conf_list . "`.`name` FROM `" .
    $db_table_conf_list . "` GROUP BY `" . $db_table_conf_list . "`.`name`") as $row) {
    $result['setting_keys'][$row['name']] = $row['name'] ? $row['name'] :
        'По умолчанию';
}

if (empty($result['setting_keys'])) {
    $result['setting_keys'][''] = 'По умолчанию';
}
$result['settings_key_html'] = '';
foreach ($result['setting_keys'] as $key => $value) {
    if (get_config_name() == $key) {
        $result['settings_key_html'] .= '<option value="' . $key . '" selected="">' . $value .
            '</option>';
    } else {
        $result['settings_key_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}

$result['setting_keys'] = [];

$profiles = db_sql_query($db, "SELECT `" . $db_table .
    "`.`profile`, count(*) as count FROM `" . $db_table . "` GROUP BY `" . $db_table .
    "`.`profile`");

foreach ($profiles as $row) {
    $expl = explode(',', $row['profile']);

    foreach ($expl as $ex) {
        $result['setting_keys'][$ex] = $ex ? $ex : '';
    }
}


$result['profile_html'] = '';
foreach ($profiles as $key => $row) {
    if ($_SESSION['profile_filter'] == $key) {
        $result['profile_html'] .= '<option value="' . $row['profile'] .
            '" selected="">' . $row['profile'] . ' (' . $row['count'] . ')</option>';
    } else {
        $result['profile_html'] .= '<option value="' . $row['profile'] . '">' . $row['profile'] .
            ' (' . $row['count'] . ')</option>';
    }
}

$result['category_filter_html'] = '<option></option>';

$listsCategoriesInBd = db_sql_query($db, "SELECT IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"Categories: Root\"'),'')), '') as category_root, count(*) as count FROM `" .
    $db_table . "` GROUP BY category_root");

foreach ($listsCategoriesInBd as $key => $row) {
    $category_root = trim(trim($row['category_root'], '"'));

    if (!empty($category_root)) {
        if (stripos($result['category_filter'], $category_root) !== false) {
            $result['category_filter_html'] .= '<option value="' . $category_root .
                '" selected="">' . $category_root . ' (' . $row['count'] . '</option>';
        } else {
            $result['category_filter_html'] .= '<option value="' . $category_root . '">' . $category_root .
                ' (' . $row['count'] . ')</option>';
        }
    }
}

// ---------------------------------------------------------------------------
$sql = "SELECT * FROM `$db_table_parser_stat` WHERE `parser_id` = 'brand_r' ORDER BY `id` DESC LIMIT 1";
$rows = db_sql_query($db, $sql);

$str = array();
$description = '';
if ($rows[0][description] != '') {
    $description = json_decode($rows[0][description], true);
}
$date_start = strtotime($rows[0]['date_start']);
$date_end = '';
$time_diff = '<font color="red">-</font>';
$result_parse = '<font color="red">FALSE</font>';


$dt = date_create($rows[0]['date_start']);

$dend = null;
$ttime = null;

if ($dt) {
    $duration = db_sql_query($db, "SELECT avg(duration) FROM `$db_table_parser_stat` WHERE `parser_id` = 'brand_r' and `duration` > 0");

    if (!empty($duration)) {
        $duration = floatstrval($duration[0]['avg(duration)']);
        $ttime = $duration * $result['total_cnt'];

        $dend = date('d.m.Y H:i', $dt->getTimestamp() + $ttime);
    }
}


if ($rows[0]['date_end'] != '' && $rows[0]['date_end'] != '0000-00-00 00:00:00') {
    $date_end = strtotime($rows[0]['date_end']);
    $time_diff = ($date_end - $date_start);
    $time_diff = round($time_diff);
    $result_parse = '<font color="green">OK</font>';
} elseif ($rows[0]['date_start'] != '' && $rows[0]['date_start'] !=
'0000-00-00 00:00:00') {

}
if ($rows[0]['date_end'] == '0000-00-00 00:00:00')
    $rows[0]['date_end'] = '-';

$str['start'] = $rows[0]['date_start'];
$str['time'] = $time_diff . ' sec';
$dt1 = date_create($str['start']);
if ($str['start'] && $dt1) {
    $str['start'] = $dt1->format('d.m.Y H:i');
}
if (isset($description['parse_delete_all']))
    $str['delete'] = $description['parse_delete_all'];
if (isset($description['parse_update_all']))
    $str['update'] = $description['parse_update_all'];
$str['result'] = $result_parse;
$result['brand_r_log'] = array();
foreach ($str as $k => $v) {
    $result['brand_r_log'][] = "<b>$k</b>: $v";
}
$result['brand_r_log'] = implode(' / ', $result['brand_r_log']);
// ---------------------------------------------------------------------------

db_close($db);

echo load_template($index_tpl, $result);

?>