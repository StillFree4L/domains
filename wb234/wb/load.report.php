<?php

error_reporting(0);

if($argv[2] or $argv[1]){
  $_GET['type'] = $argv[1];
  $_GET['load'] = $argv[2];
}

require_once ('http.lib.php');
require_once ('report.lib.php');

$USER['id'] = 2;

$link = mysqliLink();

mysqli_set_charset($link, "utf8");

$resultCheckbox = mysqli_query($link, 'SELECT name,value FROM `params` WHERE userId='.$USER["id"]);
$lines = [];

foreach ($resultCheckbox as $key => $value) {
  if($value['name'] == 'api_key'){
    $auth = $value['value'];
  }
  if($value['name'] == 'stats_key'){
    $wb_key_new = $USER['wb_key'] = $value['value'];
  }
  if($value['name'] == 'supplierId'){
    $supplierId = $value['value'];
  }
  if($value['name'] == 'config_return'){
    $config_return = $value['value'];
  }
  if($value['name'] == 'perc'){
    $perc = $value['value'];
  }
  if($value['name'] == 'pay'){
    $pay = $value['value'];
  }
}

function paramsOption($link,$name,$value,$user){
  //var_dump('SELECT count(id)>0 FROM `params` WHERE name="'.$name.'" and userId='.$user);
  $result = mysqli_query($link, 'SELECT count(id)>0 FROM `params` WHERE name="'.$name.'" and userId='.$user);
  $row = mysqli_fetch_row($result);

  if ($row[0]>0){
    $result = mysqli_query($link, 'UPDATE `params` SET value="'.$value.'" WHERE name="'.$name.'" and userId='.$user);
  }else{
    $result = mysqli_query($link, 'INSERT INTO `params` (userId,name,value) VALUES('.$user.',"'.$name.'","'.$value.'")');
    if ($result == false) {
      print(mysqli_error($link));
    }
  }
  return $value;
}

if ($_POST['key1']){
  $auth = paramsOption($link,'api_key',$_POST['key1'],$USER["id"]);
}
if($_POST['key2']){
  $wb_key_new = paramsOption($link,'stats_key',$_POST['key2'],$USER["id"]);
}
if ($_POST['key3']){
  $supplierId = paramsOption($link,'supplierId',$_POST['key3'],$USER["id"]);
}
if ($_GET['r'] != '' or $_GET['r'] != null){
  $config_return = paramsOption($link,'config_return',$_GET['r'],$USER["id"]);
}
if ($_POST['key4']){
  $perc = paramsOption($link,'perc',$_POST['key4'],$USER["id"]);
}
if ($_POST['key5']){
  $pay = paramsOption($link,'pay',$_POST['key5'],$USER["id"]);
}

require_once('blocks/func_api.php');

mysqli_close($link);

$report = '';

if($_GET['type'] and $_GET['load']=='stock_cache_old'){
  $report=stock_cache_old();
}else if($_GET['type'] and $_GET['load']=='stock_cache_new'){
  $report=stock_cache_new();
}else if($_GET['type'] and $_GET['load']=='report_cache'){
  $report=json_decode(report_cache());
}else if($_GET['type'] and $_GET['load']=='r_url'){
  $report=http_json($api_url);
}else if($_GET['type'] and $_GET['load']=='r_url_sales'){
//  var_dump($api_url_sales);
  $report=http_json($api_url_sales);
}else if($_GET['type'] and $_GET['load']=='r_url_new'){
  $report=http_json($api_url_new);
}else if($_GET['load']=='valid_url'){
  $dt1 = date('Y-m-d', time() - 60 * 60 * 24 * 80);
  $report=http_json("https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=" . $dt1 . "T00:00:00.000Z&flag=0&key=" . $GLOBALS['wb_key_new']);
}else if($_GET['load']=='valid_url_new'){
  $dt1 = date('Y-m-d', time() - 60 * 60 * 24 * 80);
  $report=http_json("https://suppliers-api.wildberries.ru/api/v2/orders?date_start=" . $dt1 . "T00:00:00.000Z&take=100&skip=0");
}else if($_GET['load']=='valid_supplier'){
  $report=http_new_url('https://suppliers-api.wildberries.ru/card/list',
      json_encode(['jsonrpc' => '2.0', 'params' =>
          ['query' => ['limit' => 1, 'offset' => 0, 'total' => 0],
              'supplierID' => $GLOBALS['supplierId']]]));
}else if($_GET['load']=='card_list'){
  $url1 = 'https://suppliers-api.wildberries.ru/card/list';
  $query = array('limit' => 1000,'offset' => 0,'total' => 0);
  $params = array('query' => $query,'supplierID' => $GLOBALS['supplierId']);
  $jsonDatas  = array('jsonrpc' => '2.0','params' => $params);
  $report = http_new_url($url1,json_encode($jsonDatas));
}else if($_GET['load']=='v1_info'){
  $url2 = 'https://suppliers-api.wildberries.ru/public/api/v1/info';
  $report = http_new_url($url2);
}else if($_GET['load']=='warehouses'){
  $report = json_decode(http('https://suppliers-api.wildberries.ru/api/v2/warehouses'));
}

echo json_encode($report);

?>
