<?php


require_once ('http.lib.php');
require_once ('report.lib.php');

$USER['id'] = 2;

  $link = mysqli_connect("localhost", "root", "","wb");

  //$link = mysqli_connect("localhost", "nvhelp_wb", "xSazFpm3","nvhelp_wb");
/*
if ($link == false){
  print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
  exit;
}
else {
  print("Соединение установлено успешно");
}
*/

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

if ($_POST['key1'] != '' or $_POST['key1'] != null){
  $auth = paramsOption($link,'api_key',$_POST['key1'],$USER["id"]);
}
if ($_POST['key2'] != '' or $_POST['key2'] != null){
  $wb_key_new = paramsOption($link,'stats_key',$_POST['key2'],$USER["id"]);
}
if ($_POST['key3'] != '' or $_POST['key3'] != null){
  $supplierId = paramsOption($link,'supplierId',$_POST['key3'],$USER["id"]);
}
if ($_GET['r'] != '' or $_GET['r'] != null){
  $config_return = paramsOption($link,'config_return',$_GET['r'],$USER["id"]);
}
if ($_POST['key4'] != '' and $_POST['key4'] != null){
  $perc = paramsOption($link,'perc',$_POST['key4'],$USER["id"]);
}
if ($_POST['key5']){
  $pay = paramsOption($link,'pay',$_POST['key5'],$USER["id"]);
}

require_once('blocks/func_api.php');

$report='';
if($_GET['type'] and $_GET['load']=='stock_cache_old'){
  $report=stock_cache_old();
}else if($_GET['type'] and $_GET['load']=='stock_cache_new'){
  $report=stock_cache_new();
}else if($_GET['type'] and $_GET['load']=='report_cache'){
  $report=json_decode(report_cache());
}else if($_GET['type'] and $_GET['load']=='r_url'){
  $report=http_json($api_url);
}else if($_GET['type'] and $_GET['load']=='r_url_sales'){
  $report=http_json($api_url_sales);
}else if($_GET['type'] and $_GET['load']=='r_url_new'){
  $report=http_json($api_url_new,true);
}

//var_dump($api_url);
//$r_url = http_json($api_url);
var_dump($report);

?>
