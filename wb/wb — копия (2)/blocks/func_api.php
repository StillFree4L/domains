<?php
//запросы к api
   // $dt1 = $_GET['dt']; //date('Y-m-d', time() - 60*60*24);
    $dt1 = date('Y-m-d', time() - 60 * 60 * 24 * 80);

    if ($_GET['type'] == 1)
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url_sales = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sales_keys);

    }
    /*elseif ($_GET['type'] == 4)
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
    elseif (in_array($_GET['type'],[6,11]))
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/stocks?skip=0&take=1000';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/stocks?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        if($calc_keys and $_GET['type'] == 11){
          $tbl_keys = ($calc_keys);
        }else if($_GET['type'] == 6){
          $tbl_keys = ($sklad_keys);
        }
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
        $fileN = 'cache/wb-cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'].'-on';
    }else{
        $fileN = 'cache/wb-cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'];
    }

    if (!file_exists('cache/wb-cache')) {mkdir('cache/wb-cache', 0777, true);}
    file_put_contents($fileN, '', FILE_APPEND);
    $buf = file_get_contents($fileN);
    $buf2 = explode('@@---@@', $buf);
