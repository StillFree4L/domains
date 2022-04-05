<?php
//ключи

    error_reporting(0);
$valid_passwords['admin'] = '123pass';
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (isset($valid_passwords[$user])) && ($pass == $valid_passwords[$user]);

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    function mb_ucfirst($string) {
        $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
        return $string;
    }
}

if (!$validated) {
    header('WWW-Authenticate: Basic realm="Авторизация"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Not authorized");
}

require_once ('http.lib.php');
require_once ('report.lib.php');

$USER['id'] = 2;

  $link = mysqli_connect("localhost", "root", "","wb");

  //$link = mysqli_connect("localhost", "nvhelp_wb", "xSazFpm3","nvhelp_wb");
/*
if ($link == false){
  print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
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

  //var_dump($config_return);

/*
$fileName = 'update/key.txt';
file_put_contents($fileName, '',FILE_APPEND);

$lines = file($fileName);
$lines[0]=trim($lines[0]);
$lines[1]=trim($lines[1]);
$lines[2]=trim($lines[2]);
$lines[3]=trim($lines[3]);
$perc=trim($lines[4]);
$pay=trim($lines[5]);

if (($_POST['key1'] or $_POST['key2'] or $_POST['key3'] or $_POST['key4'] or $_POST['key5'] or $_GET['r'])
    and (($_POST['key1'] != $lines[0])
        or ($_POST['key2'] != $lines[1])
        or ($_POST['key3'] != $lines[2])
        or ($_GET['r'] != $lines[3]))){
    if ($_POST['key1'] != '' or $_POST['key1'] != null){
        $lines[0] = $_POST['key1'];
    }
    if ($_POST['key2'] != '' or $_POST['key2'] != null){
        $lines[1] = $_POST['key2'];
    }
    if ($_POST['key3'] != '' or $_POST['key3'] != null){
        $lines[2] = $_POST['key3'];
    }
    if ($_GET['r'] != '' or $_GET['r'] != null){
        $lines[3] = $_GET['r'];
    }
    if ($_POST['key4'] != '' and $_POST['key4'] != null){
        $lines[4] = $_POST['key4'];
    }
    if ($_POST['key5']){
        $lines[5] = $pay = $_POST['key5'];
    }

    file_put_contents($fileName, $lines[0].PHP_EOL.$lines[1].PHP_EOL.$lines[2].PHP_EOL.$lines[3].PHP_EOL.$lines[4].PHP_EOL.$lines[5].PHP_EOL);
}
*/

  //$auth = $lines[0];
  //$USER['wb_key'] = $lines[1];
  //$supplierId = $lines[2];

  //$wb_key_new = $USER['wb_key'];
  //$config_return = $lines[3];
