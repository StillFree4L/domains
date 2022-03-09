<?php

require_once('blocks/func_key.php');

$dt1 = date('Y-m-d', time() - 60 * 60 * 24 * 80);

$r_url = http_json("https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=" . $dt1 . "T00:00:00.000Z&flag=0&key=" . $GLOBALS['wb_key_new']);
$r_url_new = http_json("https://suppliers-api.wildberries.ru/api/v2/orders?date_start=" . $dt1 . "T00:00:00.000Z&take=100&skip=0",true);
$r_supplier = http_new_url('https://suppliers-api.wildberries.ru/card/list',
        json_encode(['jsonrpc' => '2.0', 'params' =>
            ['query' => ['limit' => 1, 'offset' => 0, 'total' => 0],
                'supplierID' => $GLOBALS['supplierId']]]));

$v_api = ["success"=>true,"message"=>"Done!","data"=>["url"=>api_valid($r_url_new),"url_new"=>api_valid($r_url),"url_supplier"=>api_valid($r_supplier)]];

echo json_encode($v_api);

