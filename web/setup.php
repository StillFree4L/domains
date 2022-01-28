<?php

///////////////////////////////////////////////////////////////////////

session_start();

///////////////////////////////////////////////////////////////////////

ini_set('log_errors', 'on');
ini_set('error_log', 'error_log.txt');

error_reporting(1);

require_once ("config.inc.php");

if (isset($_GET['del_setting_key'])) {
    db_sql_query($db, "DELETE FROM `".$db_table_conf_list."` WHERE `name` = '" .
        mysqli_real_escape_string($db, get_config_name()) . "'");
    set_config_name('');
    header('Location: setup.php');
    exit;
}

if (isset($_GET['set_setting_key'])) {
    set_config_name($_GET['set_setting_key']);
    header('Location: setup.php');
    exit;
}

if (isset($_POST['setting_key'])) {
    set_config_name($_POST['setting_key']);
}

$parser_id_main = 'alibaba';
$parser_id = 'alibaba.com';

$index_tpl = 'tpl/setup.html';

// ------------------------------------------------------------------------
$this_user_name = $_SESSION[user_name];
$this_user_user_pass = $_SESSION[user_pass];
$this_user = _user_info($this_user_name, $this_user_user_pass);
// ------------------------------------------------------------------------

// ------------------------------------------------------------------------


$result['brandr_trademarkia_cmd'] = '';
$result['ali_trademarkia_cmd'] = '';
$result['ali_base_url'] = '';
$result['ali_base_url_search_by_img'] = '';
$result['ali_cat_id'] = '';
$result['ali_add_brand_or_manufacturer'] = '';
$result['ali_verified_suplier'] = '';
$result['ali_moq_find'] = '';
$result['ali_trade_ashurance'] = '';
$result['ali_ready_to_ship'] = '';
$result['ali_1h_response_time'] = '';
$result['ali_country'] = '';
$result['ali_type_price'] = '';
$result['ali_search_by_title'] = '';
$result['ali_search_by_code'] = '';
$result['ali_search_by_img'] = '';
$result['ali_search_file_img'] = '';
$result['ali_price_info'] = '';
$result['ali_shipping_percent'] = '';
$result['ali_shipping_kg'] = '';
$result['ali_roi_min'] = '';
$result['ali_pack'] = '';
$result['ali_total_max'] = '';
$result['ali_search_img_more'] = '';
$result['ali_ship_pp'] = '';
$result['ali_count_page'] = '';
$result['ali_open_page'] = '';
$result['ali_add_to_cart'] = '';
$result['ali_search_by_img_count'] = '';
$result['ali_price_min_percent'] = '';
$result['ali_search_by_category'] = '';
$result['ali_checking_rating'] = '';
$result['ali_checking_reviews'] = '';
$result['ali_checking_yrs'] = '';
$result['ali_checking_shipping'] = '';
$result['ali_checking_image'] = '';
$result['ali_checking_searсh_ali'] = '';
$result['ali_checking_features'] = '';
$result['ali_checking_weight'] = '';
$result['ali_checking_length_model'] = '';
$result['ali_checking_model_check'] = '';
$result['ali_checking_est_time'] = '';
$result['ali_checking_color'] = '';
$result['ali_checking_package_size'] = '';
$result['ali_type_search_code'] = '';
$result['ali_type_3_search_code'] = '';
$result['ali_length_search_min'] = '';
$result['ali_is_search_by_upc_ean'] = '';
$result['ali_search_by_upc_ean'] = '';
$result['ali_fba_fees'] = '';
$result['ali_sort_sync'] = '';
$result['ali_sort_sync_'] = '';
$result['setting_key'] = get_config_name();
$result['ali_checking_delete_results'] = '';
$result['field_import_categories_root'] = '';
$result['ali_checking_sales30'] = '';
$result['ali_checking_profit30'] = '';
$result['check_import_categories_root'] = '';
$result['ali_chk_checking_image'] = '';
$result['ali_take_criteria_upload'] = '';
$result['ali_delete_not_found'] = '';
$result['ali_count_retrieved_offers_fba'] = '';
$result['ali_count_retrieved_offers_fbm'] = '';
$result['ali_count_retrieved_offers_fba_from'] = '';
$result['ali_count_retrieved_offers_fbm_from'] = '';
$result['ali_count_retrieved_offers_fba_to'] = '';
$result['ali_count_retrieved_offers_fbm_to'] = '';
$result['del_title_by_list'] = '';
$result['check_profile_when_add'] = '';
$result['ali_find_manufacturer'] = '';
$result['del_by_price_max_find_min'] = '';



$result['del_brand_famous'] = '';
$result['start_ebay'] = '';
$result['check_import_categories_root'] = '';
$result['save_parser_info'] = '';
$result['save_parser_info'] = '';


$result['save_deleted_asin_days'] = '';
$result['del_Amazon90days_avg'] = '';
$result['del_CategoriesTree'] = '';
$result['db_clear_hours'] = '';
$result['db_clear_cnt'] = '';
$result['db_clear_hours'] = '';
$result['delete_SalesRank30daysdrop'] = '';
$result['delete_SalesRank90daysdrop'] = '';
$result['ali_checking_sales30$'] = '';
$result['check_import_filter_brand'] = '';
$result['check_import_filter_brand_r'] = '';
$result['import_filter_brand_r'] = '';
$result['ali_pricemax_min'] = '';
$result['delete_epmty_fields'] = '';
$result['delete_noepmty_fields'] = '';
$result['chk_delete_epmty_fields'] = '';
$result['chk_delete_noepmty_fields'] = '';
$result['minimun_margin'] = '';
$result['include_shipping'] = '';
$result['max_pack'] = '';
$result['min_price_max_find'] = '';
$result['price_from'] = '';
$result['price_to'] = '';
$result['save_parser_info'] = '';
$result['ali_trademarkia_cmd'] = '';
    
$result[del_brand_famous] = '';
$result[del_abandoned] = '';
$result[start_ebay] = '';
$result[brand_check_first_word_trademarkia] = '';

$result[site_url] = '';
$result[parser_login] = '';
$result[parser_pwd] = '';
$result[daily_limit] = '';
$result[timeout] = '';
$result[url_id] = '';
$result[url_title] = '';
$result[url] = '';
$result[only_photo_check] = '';
$result[url_status_check] = '';
$result[resume_path] = '';
$result[proxy] = '';
$result[test_limit] = '';
$result[test_limit_url] = '';
$result[img_limit] = '';
$result[day_limit] = '';
$result[apikey] = '';
$result[email_report] = '';

$result[article_list] = '';

$result[yandex_xml] = '';

$result[email_send_export] = '';
$result[email_send_export_check] = '';

$result[min_star] = '';

$result[list_title] = '';
$result[list_desc] = '';

$result[price_percent] = '';

$result[export_path] = '';
$result[eBay_rating] = '';
$result[E_feedb] = '';
$result[eBay_stock] = '';
$result[eBay_stock_min] = '';
$result[title_rows_cnt] = '';
$result[products_search_ok] = '';
$result[min_price_absolute] = '';
$result[min_price_procent] = '';
$result[new_listing] = '';
$result[search_upc_disable] = '';
$result[search_img_disable] = '';
$result[search_img_disable_Ebay_count_null] = '';
$result[search_partNum_disable] = '';
$result[search_title_disable] = '';
$result[check_google_Ebay_noncheck] = '';
$result[check_categories] = '';
$result[search_description] = '';
$result[max_return] = '';
$result[E_ratingS] = '';
$result[part_number_chr_cnt] = '';
$result[price_name_cell] = '';

$result[Margin_min] = '';
$result[ROI_min] = '';
$result[part_number_exact_search] = '';
$result[title_exact_search] = '';
$result[brand_title_ebay] = '';
$result[MPN_percent] = '';
$result[brand_percent] = '';
$result[brand_check_first_word] = '';

$result[delete_SalesRankCurrent] = '';

$result[max_search_img_google] = '';

$result[delete_rows_time] = '';
$result[listCategoriesRoot] = '';

$result[parser_config_country] = '';
$result[save_to_table_alibaba] = '';

//printr($_POST);
/////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST[setup_save])) {
    $res_id['export_path'] = trim($_POST['export_path']);

    if($_POST['ali_shipping_kg'] == 0){
        $_POST['ali_type_price'] = 1;
    }

    $res_id['ali_trademarkia_cmd'] = (int)$_POST['ali_trademarkia_cmd'];
    $res_id['ali_base_url'] = trim($_POST['ali_base_url']);
    $res_id['ali_base_url_search_by_img'] = trim($_POST['ali_base_url_search_by_img']);
    $res_id['ali_cat_id'] = (int)$_POST['ali_cat_id'];
    $res_id['ali_add_brand_or_manufacturer'] = (int)$_POST['ali_add_brand_or_manufacturer'];
    $res_id['ali_verified_suplier'] = (int)$_POST['ali_verified_suplier'];
    $res_id['ali_moq_find'] = (int)($_POST['ali_moq_find']);
    $res_id['ali_trade_ashurance'] = (int)$_POST['ali_trade_ashurance'];
    $res_id['ali_ready_to_ship'] = (int)$_POST['ali_ready_to_ship'];
    $res_id['ali_1h_response_time'] = (int)$_POST['ali_1h_response_time'];
    $res_id['ali_country'] = is_array($_POST['ali_country']) ? implode(',', $_POST['ali_country']) :
        trim($_POST['ali_country']);
    $res_id['ali_type_price'] = (int)($_POST['ali_type_price']);
    $res_id['ali_search_by_title'] = (int)$_POST['ali_search_by_title'];
    $res_id['ali_search_by_code'] = (int)$_POST['ali_search_by_code'];
    $res_id['ali_search_by_img'] = (int)$_POST['ali_search_by_img'];
    $res_id['ali_price_info'] = trim($_POST['ali_price_info']);
    $res_id['ali_shipping_percent'] = floatstrval($_POST['ali_shipping_percent']);


    $res_id['ali_shipping_kg'] = floatstrval($_POST['ali_shipping_kg']);
    $res_id['ali_pack'] = floatstrval($_POST['ali_pack']);
    $res_id['ali_roi_min'] = floatstrval($_POST['ali_roi_min']);
    $res_id['ali_total_max'] = floatstrval($_POST['ali_total_max']);
    $res_id['ali_search_img_more'] = (int)$_POST['ali_search_img_more'];
    $res_id['ali_ship_pp'] = floatstrval($_POST['ali_ship_pp']);
    $res_id['ali_count_page'] = (int)$_POST['ali_count_page'];
    $res_id['ali_open_page'] = (int)$_POST['ali_open_page'];
    $res_id['ali_add_to_cart'] = (int)$_POST['ali_add_to_cart'];
    $res_id['ali_search_by_img_count'] = (int)$_POST['ali_search_by_img_count'];
    $res_id['ali_price_min_percent'] = round(floatstrval($_POST['ali_price_min_percent']),
        1);
    $res_id['ali_search_by_category'] = (int)$_POST['ali_search_by_category'];
    $res_id['ali_checking_rating'] = trim($_POST['ali_checking_rating']);
    $res_id['ali_checking_reviews'] = trim($_POST['ali_checking_reviews']);
    $res_id['ali_checking_yrs'] = trim($_POST['ali_checking_yrs']);
    $res_id['ali_checking_shipping'] = (int)($_POST['ali_checking_shipping']);
    $res_id['ali_checking_image'] = trim($_POST['ali_checking_image']);
    $res_id['ali_checking_searсh_ali'] = trim($_POST['ali_checking_searсh_ali']);
    $res_id['ali_checking_features'] = trim($_POST['ali_checking_features']);
    $res_id['ali_checking_weight'] = trim($_POST['ali_checking_weight']);
    $res_id['ali_checking_length_model'] = (int)$_POST['ali_checking_length_model'];
    $res_id['ali_checking_model_check'] = (int)$_POST['ali_checking_model_check'];
    $res_id['ali_checking_est_time'] = (int)$_POST['ali_checking_est_time'];
    $res_id['ali_checking_color'] = (int)$_POST['ali_checking_color'];
    $res_id['ali_checking_package_size'] = trim($_POST['ali_checking_package_size']);
    $res_id['ali_type_search_code'] = trim($_POST['ali_type_search_code']);
    $res_id['ali_type_3_search_code'] = trim($_POST['ali_type_3_search_code']);
    $res_id['ali_length_search_min'] = (int)$_POST['ali_length_search_min'];
    $res_id['ali_is_search_by_upc_ean'] = (int)$_POST['ali_is_search_by_upc_ean'];
    $res_id['ali_search_by_upc_ean'] = (int)$_POST['ali_search_by_upc_ean'];
    $res_id['ali_fba_fees'] = (int)$_POST['ali_fba_fees'];
    $res_id['ali_sort_sync'] = trim($_POST['ali_sort_sync']);
    $res_id['ali_sort_sync_'] = (int)$_POST['ali_sort_sync_'];
    $res_id['ali_sort_sync_'] = (int)$_POST['ali_sort_sync_'];  
    $res_id['field_import_categories_root'] = trim($_POST['field_import_categories_root']);
    $res_id['check_import_categories_root'] = (int)$_POST['check_import_categories_root'];
    $res_id['ali_chk_checking_image'] = (int)$_POST['ali_chk_checking_image'];
    $res_id['ali_take_criteria_upload'] = (int)$_POST['ali_take_criteria_upload'];
    $res_id['del_title_by_list'] = (int)$_POST['del_title_by_list'];
    $res_id['brandr_trademarkia_cmd'] = (int)$_POST['brandr_trademarkia_cmd'];
    
    $res_id['listCategoriesRoot'] = is_array($_POST['listCategoriesRoot']) ? implode('|', $_POST['listCategoriesRoot']) :
        trim($_POST['listCategoriesRoot']);

    $res_id['delete_SalesRankCurrent'] = trim($_POST['delete_SalesRankCurrent']);
    $res_id['save_deleted_asin_days'] = intval($_POST['save_deleted_asin_days']);

    $res_id['save_deleted_asin_days'] = intval($_POST['save_deleted_asin_days']);
    $res_id['del_Amazon90days_avg'] = intval($_POST['del_Amazon90days_avg']);
    $res_id['del_CategoriesTree'] = intval($_POST['del_CategoriesTree']);



    $res_id['delete_SalesRank30daysdrop'] = intval($_POST['delete_SalesRank30daysdrop']);
    $res_id['delete_SalesRank90daysdrop'] = intval($_POST['delete_SalesRank90daysdrop']);
    $res_id['ali_checking_delete_results'] = (int)$_POST['ali_checking_delete_results'];
    $res_id['ali_checking_sales30'] = floatstrval($_POST['ali_checking_sales30']);
    $res_id['ali_checking_profit30'] = floatstrval($_POST['ali_checking_profit30']);
    $res_id['ali_delete_not_found'] = floatstrval($_POST['ali_delete_not_found']);
    $res_id['ali_count_retrieved_offers_fba'] = trim($_POST['ali_count_retrieved_offers_fba']);
    $res_id['ali_count_retrieved_offers_fbm'] = trim($_POST['ali_count_retrieved_offers_fbm']);
    $res_id['ali_checking_sales30$'] = floatstrval($_POST['ali_checking_sales30$']);
    $res_id['ali_count_retrieved_offers_fba_from'] = trim($_POST['ali_count_retrieved_offers_fba_from']);
    $res_id['ali_count_retrieved_offers_fbm_from'] = trim($_POST['ali_count_retrieved_offers_fbm_from']);
    $res_id['ali_count_retrieved_offers_fba_to'] = trim($_POST['ali_count_retrieved_offers_fba_to']);
    $res_id['ali_count_retrieved_offers_fbm_to'] = trim($_POST['ali_count_retrieved_offers_fbm_to']);
    $res_id['check_profile_when_add'] = (int)$_POST['check_profile_when_add'];
    $res_id['ali_find_manufacturer'] = (int)$_POST['ali_find_manufacturer'];
    $res_id['check_import_filter_brand'] = (int)$_POST['check_import_filter_brand'];
    $res_id['check_import_filter_brand_r'] = (int)$_POST['check_import_filter_brand_r'];
    $res_id['import_filter_brand_r'] = trim($_POST['import_filter_brand_r']);
    $res_id['ali_pricemax_min'] = trim($_POST['ali_pricemax_min']);
    $res_id['del_by_price_max_find_min'] = (int)$_POST['del_by_price_max_find_min'];
    $res_id['save_to_table_alibaba'] = (int)$_POST['save_to_table_alibaba'];
    $res_id['delete_epmty_fields'] = trim($_POST['delete_epmty_fields']);
    $res_id['delete_noepmty_fields'] = trim($_POST['delete_noepmty_fields']);
    $res_id['chk_delete_epmty_fields'] = (int)$_POST['chk_delete_epmty_fields'];
    $res_id['chk_delete_noepmty_fields'] = (int)$_POST['chk_delete_noepmty_fields'];
    $res_id['minimun_margin'] = trim($_POST['minimun_margin']);
    $res_id['include_shipping'] = (int)$_POST['include_shipping'];
    $res_id['max_pack'] = trim($_POST['max_pack']);
    $res_id['min_price_max_find'] = trim($_POST['min_price_max_find']);
    $res_id['price_from'] = trim($_POST['price_from']);
    $res_id['price_to'] = trim($_POST['price_to']);

    $res_id[del_brand_famous] = intval($_POST[del_brand_famous]);  
    $res_id[del_abandoned] = intval($_POST[del_abandoned]);
    $res_id[start_ebay] = intval($_POST[start_ebay]);
    $res_id[save_parser_info] = intval($_POST[save_parser_info]);
    $res_id[ali_trademarkia_cmd] = intval($_POST[ali_trademarkia_cmd]);
    $res_id[brand_check_first_word_trademarkia] = intval($_POST[brand_check_first_word_trademarkia]);

    if (isset($_FILES['ali_search_file_img']) && isset($_FILES['ali_search_file_img']['tmp_name']) &&
        $_FILES['ali_search_file_img']['tmp_name']) {
        move_uploaded_file($_FILES['ali_search_file_img']['tmp_name'], __dir__ .
            "/upload/" . $_FILES['ali_search_file_img']['name']);
        $res_id['ali_search_file_img'] = $_FILES['ali_search_file_img']['name'];
    }
    
    if($res_id[export_path] != ''){
        $res_id[export_path] = str_replace('\\', '/', $res_id[export_path]);
        if(mb_substr($res_id[export_path], -1, 1, 'UTF-8') != '/') $res_id[export_path] .= '/';
    }

    set_config_id($db, $db_table_conf_list, $res_id, 'parser_id', $parser_id_main);

    $result[error] .= "Данные сохранены.<br/>";
}

// ---------------------------------------------------------------------------
$result = array_merge($result, (array )get_config_id($db, $db_table_conf_list,
    'parser_id', $parser_id_main));
    


// очистка базы данных
if (isset($_GET[db_clear])) {
    $db_clear_hours = intval(trim($_POST[db_clear_hours]));
    $db_clear_cnt = intval(trim($_POST[db_clear_cnt]));
    if ($db_clear_cnt > 0) {
        $sql = "DELETE FROM `$db_table_deleted` ORDER BY `id` DESC LIMIT " . $db_clear_cnt;
        db_sql_query($db, $sql);
        $del_cnt = (int)mysqli_affected_rows($db);
        //$result[error] .= "$sql<br/>";
        $result[error] .= 'Удалено записей: ' . (int)$del_cnt;
    } elseif ($db_clear_hours > 0) {
        $sql = "DELETE FROM `$db_table_deleted` WHERE timestampdiff(minute, date_add, current_timestamp) <= " . ($db_clear_hours *
            60) . "";
        db_sql_query($db, $sql);
        $del_cnt = (int)mysqli_affected_rows($db);
        //$result[error] .= "$sql<br/>";
        $result[error] .= 'Удалено записей: ' . (int)$del_cnt;
    } elseif($result['save_deleted_asin_days']) {
        $sql = "DELETE FROM `$db_table_deleted` WHERE `created_at` > '" . date("Y-m-d H:i:s", time() - intval($result['save_deleted_asin_days']) * 86400) . "'";
        db_sql_query($db, $sql);
        //$result[error] .= "$sql<br/>";
        $result[error] .= 'База данных очищена от данных старше '.$result['save_deleted_asin_days'].' дней!';
    } else {
        $sql = "TRUNCATE $db_table_deleted";
        db_sql_query($db, $sql);
        //$result[error] .= "$sql<br/>";
        $result[error] .= 'База данных очищена!';
    }
}

if (!$result['ali_base_url'])
    $result['ali_base_url'] = 'https://www.alibaba.com/trade/search';
if (!$result['ali_base_url_search_by_img'])
    $result['ali_base_url_search_by_img'] =
        'https://www.alibaba.com/picture/search.htm?imageType=oss&escapeQp=true&imageAddress=/icbuimgsearch/cbebHr7Gwz1630141671000.jpg&sourceFrom=imageupload&originFrom=https://www.alibaba.com/?__redirected__=1';

$result['ali_cats'] = array_map(function ($item) {return str_getcsv($item, ';');}, file(__dir__ . '/data/categories.csv'));

$result['ali_cats_html'] = '';
foreach ($result['ali_cats'] as $key => $value) {
    if ($result['ali_cat_id'] == $value['id']) {
        $result['ali_cats_html'] .= '<option value="' . $value['id'] . '" selected="">' .
            $value['name'] . '</option>';
    } else {
        $result['ali_cats_html'] .= '<option value="' . $value['id'] . '">' . $value['name'] .
            '</option>';
    }
}

$result['ali_types_price'] = [1 => 'Shipping%', 2 => 'ShippingKg', ];
$result['ali_type_price_html'] = '';
foreach ($result['ali_types_price'] as $key => $value) {
    if ($result['ali_type_price'] == $key) {
        $result['ali_type_price_html'] .= '<option value="' . $key . '" selected="">' .
            $value . '</option>';
    } else {
        $result['ali_type_price_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}
$result['linkCategoriesRootCsv'] = '/data/Categories Root.csv';
$result['listsCategories'] = array_map(function ($item) {return str_getcsv($item, ';');}, file(__dir__ . '/data/Categories Root.csv'));
$result['listsCategories_html'] = '';
foreach ($result['listsCategories'] as $key => $value) {
    if ($key > 0) {
        if (stripos($result['listCategoriesRoot'], $value[0]) !== false) {
            $result['listsCategories_html'] .= '<option value="' . $value[0] . '" selected="">' . $value[0] .
                '</option>';
        } else {
            $result['listsCategories_html'] .= '<option value="' . $value[0] . '">' . $value[0] .
                '</option>';
        }
    }
}

$result['listsSelectCategories_html'] = '';
foreach (explode('|', $result['listCategoriesRoot']) as $Cat) {
    $result['listsSelectCategories_html'] .= '<div>' . $Cat . '</div>';
}

$result['ali_countries'] = ['CN' => 'Китай', 'IN' => 'Индия', 'ID' =>
    'Индонезия', 'HK' => 'Гонг Конг', 'TH' => 'Тайвань', 'US' => 'США', ];
$result['ali_countries_html'] = '';
$result['ali_countries_select_html'] = '';
foreach ($result['ali_countries'] as $key => $value) {
    if (stripos($result['ali_country'], $key) !== false) {
        $result['ali_countries_html'] .= '<option value="' . $key . '" selected="">' . $value .
            '</option>';
            
        $result['ali_countries_select_html'] .= '<div>' . $value . '</div>';
    } else {
        $result['ali_countries_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}
$result['ali_add_brand_or_manufacturer_check'] = '';
if ($result['ali_add_brand_or_manufacturer'] == '1') {
    $result['ali_add_brand_or_manufacturer_check'] = 'checked=""';
}
if ($result['ali_verified_suplier'] == '1') {
    $result['ali_verified_suplier'] = 'checked=""';
}
if ($result['ali_trade_ashurance'] == '1') {
    $result['ali_trade_ashurance'] = 'checked=""';
}
if ($result['ali_ready_to_ship'] == '1') {
    $result['ali_ready_to_ship'] = 'checked=""';
}
if ($result['ali_1h_response_time'] == '1') {
    $result['ali_1h_response_time'] = 'checked=""';
}
if ($result['ali_search_by_title'] == '1') {
    $result['ali_search_by_title'] = 'checked=""';
}
if ($result['brandr_trademarkia_cmd'] == '1') {
    $result['brandr_trademarkia_cmd'] = 'checked=""';
}
if ($result['ali_search_by_code'] == '1') {
    $result['ali_search_by_code'] = 'checked=""';
}
if ($result['ali_search_by_img'] == '1') {
    $result['ali_search_by_img'] = 'checked=""';
}
if ($result['ali_open_page'] == '1') {
    $result['ali_open_page'] = 'checked=""';
}
if ($result['ali_add_to_cart'] == '1') {
    $result['ali_add_to_cart'] = 'checked=""';
}
if ($result['ali_search_by_category'] == '1') {
    $result['ali_search_by_category'] = 'checked=""';
}
if ($result['ali_checking_shipping'] == '1') {
    $result['ali_checking_shipping'] = 'checked=""';
}
if ($result['ali_checking_model_check'] == '1') {
    $result['ali_checking_model_check'] = 'checked=""';
}
if ($result['ali_checking_color'] == '1') {
    $result['ali_checking_color'] = 'checked=""';
}
if ($result['check_profile_when_add'] == '1') {
    $result['check_profile_when_add'] = 'checked=""';
}
if ($result['ali_find_manufacturer'] == '1') {
    $result['ali_find_manufacturer'] = 'checked=""';
}
if ($result['del_by_price_max_find_min'] == '1') {
    $result['del_by_price_max_find_min'] = 'checked=""';
}
if ($result['check_import_filter_brand'] == '1') {
    $result['check_import_filter_brand'] = 'checked=""';
}
$result['linkBrandCsv'] = '/data/Brand.csv';
if ($result['check_import_filter_brand_r'] == '1') {
    $result['check_import_filter_brand_r'] = 'checked=""';
}

if (file_exists(__dir__ . '/data/Brand.csv')) {
    $result['lastTimeBrandCsv'] = date("d.m.Y H:i", filectime(__dir__ . '/data/Brand.csv'));
}

$result['import_filter_brand_rs'] = [
    0 => '',
    1 => 'по полному совпадению',
    2 => 'по частичному совпадению с их записью в Brand_R',
    3 => 'по частичному совпадению с их удалением',
];
$result['import_filter_brand_r_html'] = '';
foreach ($result['import_filter_brand_rs'] as $key => $value) {
    if ($result['import_filter_brand_r'] == $key) {
        $result['import_filter_brand_r_html'] .= '<option value="' . $key . '" selected="">' . $value .
            '</option>';
    } else {
        $result['import_filter_brand_r_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}
$result['linkBrandRCsv'] = '/data/Brand_R.csv';
$result['ali_types_search_code'] = [1 => 'PartNumber(Model)', 2 =>
    'Model(PartNumber)', 3 => 'PartNumber+Model', ];
$result['ali_type_search_code_html'] = '';
foreach ($result['ali_types_search_code'] as $key => $value) {
    if ($result['ali_type_search_code'] == $key) {
        $result['ali_type_search_code_html'] .= '<option value="' . $key .
            '" selected="">' . $value . '</option>';
    } else {
        $result['ali_type_search_code_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}
$result['ali_types_3_search_code'] = [1 => 'Brand', 2 => 'Manufacturer', ];
$result['ali_type_3_search_code_html'] = '';
foreach ($result['ali_types_3_search_code'] as $key => $value) {
    if ($result['ali_type_3_search_code'] == $key) {
        $result['ali_type_3_search_code_html'] .= '<option value="' . $key .
            '" selected="">' . $value . '</option>';
    } else {
        $result['ali_type_3_search_code_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}
if ($result['ali_is_search_by_upc_ean'] == '1') {
    $result['ali_is_search_by_upc_ean'] = 'checked=""';
}
$result['search_by_upc_ean'] = [1 => 'UPC(EAN)', 2 => 'EAN/(UPC)', ];
$result['ali_search_by_upc_ean_html'] = '';
foreach ($result['search_by_upc_ean'] as $key => $value) {
    if ($result['ali_search_by_upc_ean'] == $key) {
        $result['ali_search_by_upc_ean_html'] .= '<option value="' . $key .
            '" selected="">' . $value . '</option>';
    } else {
        $result['ali_search_by_upc_ean_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}
if ($result['ali_checking_searсh_ali'] == '1') {
    $result['ali_checking_searсh_ali'] = 'checked=""';
}
if ($result['ali_fba_fees'] == '1') {
    $result['ali_fba_fees'] = 'checked=""';
}
$result['sort_sync'] = [];
$result['sort_sync']['asin'] = 'asin';
$result['sort_sync']['roi'] = 'roi';
$result['sort_sync']['id_Ali'] = 'id_Ali';
$result['sort_sync']['URL_Ali'] = 'URL_Ali';
$result['sort_sync']['Title_Ali'] = 'Title_Ali';
$result['sort_sync']['PriceMax_Ali'] = 'PriceMax_Ali';
$result['sort_sync']['Verified_Ali'] = 'Verified_Ali';
$result['sort_sync']['Reviews_Ali'] = 'Reviews_Ali';
$result['sort_sync']['Image_Ali'] = 'Image_Ali';
$result['sort_sync']['Rating_Ali'] = 'Rating_Ali';
$result['sort_sync']['Yrs_Ali'] = 'Yrs_Ali';
$result['sort_sync']['CountryS_Ali'] = 'CountryS_Ali';
$result['sort_sync']['MOQ_Ali'] = 'MOQ_Ali';
$result['sort_sync']['Ready To Ship'] = 'Ready To Ship';
$result['sort_sync']['Shipping'] = 'Shipping';
$result['sort_sync']['Seller_Ali'] = 'Seller_Ali';
$result['sort_sync']['Features'] = 'Features';
$result['sort_sync']['Details'] = 'Details';
$result['sort_sync']['Trade$_Ali'] = 'Trade$_Ali';
$result['sort_sync']['Category_Ali'] = 'Category_Ali';
$result['sort_sync']['Url_Search_Ali'] = 'Url_Search_Ali';
$result['sort_sync']['Find_Ali'] = 'Find_Ali';
$result['sort_sync']['Find_PriceMin_Ali'] = 'Find_PriceMin_Ali';
$result['sort_sync']['Find_PriceMax_Ali'] = 'Find_PriceMax_Ali';
$result['sort_sync']['Find_Length_Ali'] = 'Find_Length_Ali';
$result['sort_sync']['Find_Width_Ali'] = 'Find_Width_Ali';
$result['sort_sync']['Find_Height_Ali'] = 'Find_Height_Ali';
$result['sort_sync']['CheckingFind'] = 'CheckingFind';
$result['sort_sync']['Total_Ali*'] = 'Total_Ali*';
$result['sort_sync']['Shipping_Ali*'] = 'Shipping_Ali*';
$result['sort_sync']['Model_Ali'] = 'Model_Ali';
$result['sort_sync']['Brand_Ali'] = 'Brand_Ali';
$result['sort_sync']['Profile_Ali'] = 'Profile_Ali';
$result['sort_sync']['CountryOrigin_Ali'] = 'CountryOrigin_Ali';
$result['sort_sync']['Weight_Ali'] = 'Weight_Ali';
$result['sort_sync']['EstTime_Ali'] = 'EstTime_Ali';
$result['sort_sync']['ShippingTime*'] = 'ShippingTime*';
$result['sort_sync']['Incoterms_Ali*'] = 'Incoterms_Ali*';
$result['sort_sync']['Single package size'] = 'Single package size';
$result['sort_sync']['Color_Ali'] = 'Color_Ali';
$result['sort_sync']['%Weight'] = '%Weight';
$result['sort_sync']['%Length'] = '%Length';
$result['sort_sync']['%Width'] = '%Width';
$result['sort_sync']['%Height'] = '%Height';
$result['sort_sync']['%Package'] = '%Package';
$result['sort_sync']['Model'] = 'Model';
$result['sort_sync']['Profit30'] = 'Profit30';
$result['sort_sync_html'] = '';
foreach ($result['sort_sync'] as $key => $value) {
    if ($result['ali_sort_sync'] == $key) {
        $result['sort_sync_html'] .= '<option value="' . $key . '" selected="">' . $value .
            '</option>';
    } else {
        $result['sort_sync_html'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
}
$result['sort_sync_'] = [];
$result['sort_sync_'][0] = 'По возрастнию';
$result['sort_sync_'][1] = 'По убыванию';
$result['sort_sync_html_'] = '';
foreach ($result['sort_sync_'] as $key => $value) {
    if ($result['ali_sort_sync_'] == $key) {
        $result['sort_sync_html_'] .= '<option value="' . $key . '" selected="">' . $value .
            '</option>';
    } else {
        $result['sort_sync_html_'] .= '<option value="' . $key . '">' . $value .
            '</option>';
    }
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
if ($result['ali_checking_delete_results'] == '1') {
    $result['ali_checking_delete_results'] = 'checked=""';
}
if($result[del_brand_famous]) $result[del_brand_famous] = 'checked';
if($result[del_abandoned]) $result[del_abandoned] = 'checked';
if($result[start_ebay]) $result[start_ebay] = 'checked';
if($result[brand_check_first_word_trademarkia]) $result[brand_check_first_word_trademarkia] = 'checked';
if ($result['ali_trademarkia_cmd'] == '1') {
    $result['ali_trademarkia_cmd'] = 'checked=""';
}

if ($result['del_brand_famous'] == '1') {
    $result['del_brand_famous'] = 'checked=""';
}
if ($result['start_ebay'] == '1') {
    $result['start_ebay'] = 'checked=""';
}
if ($result['check_import_categories_root'] == '1') {
    $result['check_import_categories_root'] = 'checked=""';
}
if ($result['save_parser_info'] == '1') {
    $result['save_parser_info'] = 'checked=""';
}
if ($result['ali_trademarkia_cmd'] == '1') {
    $result['ali_trademarkia_cmd'] = 'checked=""';
}

if ($result['del_title_by_list'] == '1') {
    $result['del_title_by_list'] = 'checked=""';
}
if ($result['ali_chk_checking_image'] == '1') {
    $result['ali_chk_checking_image'] = 'checked=""';
}
if ($result['ali_take_criteria_upload'] == '1') {
    $result['ali_take_criteria_upload'] = 'checked=""';
}
if ($result['ali_delete_not_found'] == '1') {
    $result['ali_delete_not_found'] = 'checked=""';
}
if ($result['chk_delete_epmty_fields'] == '1') {
    $result['chk_delete_epmty_fields'] = 'checked=""';
}
if ($result['chk_delete_noepmty_fields'] == '1') {
    $result['chk_delete_noepmty_fields'] = 'checked=""';
}
if ($result['save_to_table_alibaba'] == '1') {
    $result['save_to_table_alibaba'] = 'checked=""';
}
if ($result['include_shipping'] == '1') {
    $result['include_shipping'] = 'checked=""';
}
if ($result[del_Amazon90days_avg])
    $result[del_Amazon90days_avg] = 'checked';
if ($result[del_CategoriesTree])
    $result[del_CategoriesTree] = 'checked';
// инициализация страницы
// -------------------------------------------------------------------------------------------------------------
if ($result[brand_check_first_word])
    $result[brand_check_first_word] = 'checked';
if ($result[brand_title_ebay])
    $result[brand_title_ebay] = 'checked';
if ($result[part_number_exact_search])
    $result[part_number_exact_search] = 'checked';
if ($result[title_exact_search])
    $result[title_exact_search] = 'checked';
if ($result[new_listing])
    $result[new_listing] = 'checked';
if ($result[search_upc_disable])
    $result[search_upc_disable] = 'checked';
if ($result[search_img_disable])
    $result[search_img_disable] = 'checked';
if ($result[search_img_disable_Ebay_count_null])
    $result[search_img_disable_Ebay_count_null] = 'checked';
if ($result[search_partNum_disable])
    $result[search_partNum_disable] = 'checked';
if ($result[search_title_disable])
    $result[search_title_disable] = 'checked';
if ($result[search_description])
    $result[search_description] = 'checked';
if ($result[check_google_Ebay_noncheck])
    $result[check_google_Ebay_noncheck] = 'checked';
if ($result[check_categories])
    $result[check_categories] = 'checked';
// -------------------------------------------------------------------------------------------------------------
$result[urls_list] = $data;
// -------------------------------------------------------------------------------------------------------------

$sql = "SELECT COUNT(*) as `cnt` FROM `$db_table_results`";
$rows = db_sql_query($db, $sql);
$result[total_cnt] = $rows[0][cnt];

$sql = "SELECT COUNT(*) as `cnt` FROM `$db_table` where IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"URLs_Ali\"'),'')), '') != ''";
$rows = db_sql_query($db, $sql);
$result[total_is_cnt] = $rows[0][cnt];

$sql = "SELECT COUNT(*) as `cnt` FROM `$db_table_deleted`";
$rows = db_sql_query($db, $sql);
$result[total_cnt_deleted] = $rows[0][cnt];

$sql = "SELECT COUNT(*) as `cnt` FROM `$db_table` where IF(JSON_VALID(`info`), TRIM('\"' FROM COALESCE(JSON_EXTRACT(`info`, '$.\"URLs_Ali\"'),'')), '') != ''";
$rows = db_sql_query($db, $sql);
$result[total_is_results] = $rows[0][cnt];

db_close($db);

echo load_template($index_tpl, $result);

?>
