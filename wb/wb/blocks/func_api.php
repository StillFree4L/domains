<?php

if (trim($USER['wb_key']) != '')
{
    $dt1 = $_GET['dt']; //date('Y-m-d', time() - 60*60*24);
    $dt1 = date('Y-m-d', time() - 60 * 60 * 24 * 80);

    if ($_GET['type'] == 1)
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url_sales = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sales_keys);

    }
    /*elseif ($_GET['type']d == 4)
    {
        //$api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sales_keys);
    }*/
    elseif ($_GET['type'] == 5)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/reportDetailByPeriod?dateFrom=' . $dt1 . '&key=' . $USER['wb_key'] . '&limit=1000000&rrdid=0&dateto=' . date('Y-m-d');
        $tbl_keys = ($report_keys);
    }
    elseif ($_GET['type'] == 9)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/reportDetailByPeriod?dateFrom=' . $dt1 . '&key=' . $USER['wb_key'] . '&limit=1000000&rrdid=0&dateto=' . date('Y-m-d');
        $tbl_keys = ($pribil_keys);
    }
    elseif ($_GET['type'] == 6)
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/stocks?skip=0&take=1000';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/stocks?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sklad_keys);
    }
    elseif ($_GET['type'] == 7)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/incomes?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($postav_keys);
    }
    elseif ($_GET['type'] == 8)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/incomes?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sebes_keys);
    }elseif ($_GET['type'] == 10)
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url_sales = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $dt1 . 'T00:00:00.000Z&flag=0&key=' . $USER['wb_key'];
        $tbl_keys = ($orders_sales_keys);
    }
    /* elseif ($_GET['type'] d== 3)
     {
         //$api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
         $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $dt1 . 'T00:00:00.000Z&flag=0&key=' . $USER['wb_key'];
         $tbl_keys = ($orders_keys);
     }*/
    else
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $dt1 . 'T00:00:00.000Z&flag=0&key=' . $USER['wb_key'];
        $tbl_keys = ($orders_keys);
    }
    if ($_GET['type'] == 1 or $_GET['type'] == 2){
        $fileN = 'cache/wb-cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'].'-'.$GLOBALS['config_return'];
    }else{
        $fileN = 'cache/wb-cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'];
    }

    if (!file_exists('cache/wb-cache')) {mkdir('cache/wb-cache', 0777, true);}
    file_put_contents($fileN, '', FILE_APPEND);
    $buf = file_get_contents($fileN);
    $buf2 = explode('@@---@@', $buf);

    $r_url_new = 'true';
    $r_url = 'true';
    $r_url_sales = 'true';
    $r = $r0 = $buf2[1];
    $r = json_decode($r);

    if ($lines[0] and ($r_url_new or $r_url_sales)){
        $valid_url_new = api_valid($r_url_new ? $r_url_new : $r_url_sales);
    }else{
        $valid_url_new = '<font color="red">не валиден</font>';
    }
    if ($lines[1] and $r_url){
        $valid_url = api_valid($r_url);
    }else{
        $valid_url = '<font color="red">не валиден</font>';
    }

    if ($lines[2]){
        $url1 = 'https://suppliers-api.wildberries.ru/card/list';
        $query = array('limit' => 1,'offset' => 0,'total' => 0);
        $params = array('query' => $query,'supplierID' => $GLOBALS['supplierId']);
        $jsonDatas  = array('jsonrpc' => '2.0','params' => $params);
        $valid_results = http_new_url($url1,json_encode($jsonDatas));
        $valid_results = api_valid($valid_results);
    }else{
        $valid_results = '<font color="red">не валиден</font>';
    }

    $tbl_rows = $orig_tbl_rows = $r;
}