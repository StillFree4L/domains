<?php

require_once('blocks/func_key.php');

$resultCheckbox = mysqli_query($link, 'SELECT name,value FROM `params` WHERE userId='.$USER["id"]);

foreach ($resultCheckbox as $key => $value) {
  if($value['name'] == 'forcibly'){
    $forciblyCheckbox = $value['value'];
  }
}

$forciblyCheckbox = abs(time() - (int)$forciblyCheckbox);
$forciblyCheckbox = floor($forciblyCheckbox / 60);

$resultStatusData = mysqli_query($link, 'SELECT status,data_time FROM `data_status` WHERE userId='.$USER["id"]);
$arrStatusData = [];
$keyStatusData = ['0','1','2','3'];
foreach ($resultStatusData as $key => $value) {
  $arrStatusData[] = $value['status'];
}

$status=$forcibly=$activeForcibly='';

if(in_array("0",$arrStatusData)){
  $status='<font color="red">Данные отсутствуют или нет ответа от API-сервера. <a href="#" style="color: red; text-decoration: revert;" onclick="parent.location.reload(); return false;">Попробуйте позднее</a></font>';
}
elseif(in_array("1",$arrStatusData)){
  $forcibly=false;
  $status='<font class="loading" color="#0059fc">Получение данных</font>';
}
elseif(in_array("2",$arrStatusData)){
  $status='<font color="green">Данные получены успешно</font>';
}
elseif(in_array("3",$arrStatusData)){
  $status='<font color="green">Данные обновлены. <a href="#" style="color: green; text-decoration: revert;" onclick="parent.location.reload(); return false;">Перезагрузите страницу</a></font>';
}

if($forciblyCheckbox <= 14 and !in_array("0",$arrStatusData)){
  $forcibly=false;
}else{
  $forcibly=true;
}

if($forciblyCheckbox >= 15 and !in_array("0",$arrStatusData)){
  $forcibly=true;
  $activeForcibly='<font color="green">Данные возможно не актуальны. Обновите данные</font>';
}

$v_api = ["success"=>true,"status"=>$status,"forcibly"=>$forcibly,'active'=>$activeForcibly];
echo json_encode($v_api);
?>
