<?php

  require_once('blocks/func_key.php');

if(isset($_GET['type'])){

  $keySql = $val = $search = "";
  foreach ($_POST as $key => $value) {
    $keySql .=','.(string)$key.'';
    $val .=',"'.(string)$value.'"';
    if($key!='value'){$search .= ' and '.(string)$key.'="'.(string)$value.'"';}
  }

  $result = mysqli_query($link, 'SELECT count(id)>0 FROM `values` WHERE userId='.$USER["id"].' and type='.intval($_GET["type"]).$search);
  $row = mysqli_fetch_row($result);

  if ($row[0]>0){
    $result = mysqli_query($link, 'UPDATE `values` SET value="'.$_POST['value'].'" WHERE userId='.$USER["id"].' and type='.intval($_GET["type"]).$search);
  }else{
    $result = mysqli_query($link, 'INSERT INTO `values` (userId,type'.$keySql.') VALUES('.$USER["id"].','.intval($_GET["type"]).$val.')');
    if ($result == false) {
      print(mysqli_error($link));
    }
  }
}
if(isset($_POST['list'])){

  $result = mysqli_query($link, 'SELECT count(id)>0 FROM `list` WHERE userId='.$USER["id"]);
  $row = mysqli_fetch_row($result);

  if ($row[0]>0){
    $result = mysqli_query($link, 'UPDATE `list` SET list="'.$_POST['list'].'" WHERE userId='.$USER["id"]);
  }else{
    $result = mysqli_query($link, 'INSERT INTO `list` (userId,list) VALUES('.$USER["id"].',"'.$_POST['list'].'")');
    if ($result == false) {
      print(mysqli_error($link));
    }
  }
}
if(isset($_GET['all']) and $_GET['all']==8){

  foreach ($_POST['all'] as $key => $value) {
    $keySql = $val = $search = "";
    foreach ($value as $k => $v) {
      $keySql .=','.(string)$k.'';
      $val .=',"'.(string)$v.'"';
      if($k!='value'){$search .= ' and '.(string)$k.'="'.(string)$v.'"';}
    }

    $result = mysqli_query($link, 'SELECT count(id)>0 FROM `values` WHERE userId='.$USER["id"].' and type=7'.$search);
    $row = mysqli_fetch_row($result);

    if ($row[0]>0){
      $result = mysqli_query($link, 'UPDATE `values` SET value="'.$value['value'].'" WHERE userId='.$USER["id"].' and type=7'.$search);
    }
    else{
      $result = mysqli_query($link, 'INSERT INTO `values` (userId,type'.$keySql.') VALUES('.$USER["id"].',7'.$val.')');
      if ($result == false) {
        print(mysqli_error($link));
      }
    }
  }
}
 ?>
