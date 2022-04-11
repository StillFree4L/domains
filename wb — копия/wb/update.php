<?php

  require_once('blocks/func_key.php');

if(isset($_GET['type'])){

  $keySql = $val = $search = "";
  foreach ($_POST as $key => $value) {
    $keySql .=','.(string)$key.'';
    $val .=',"'.(string)$value.'"';
    if($key!='value'){$search .= ' and '.(string)$key.'="'.(string)$value.'"';}
  }


  $result = mysqli_query($link, 'SELECT count(id)>0 FROM `goods` WHERE userId='.$USER["id"].' and type='.intval($_GET["type"]).$search);
  $row = mysqli_fetch_row($result);

  if ($row[0]>0){
    $result = mysqli_query($link, 'UPDATE `goods` SET value="'.$_POST["value"].'" WHERE userId='.$USER["id"].' and type='.intval($_GET["type"]).$search);
  }else{
    $result = mysqli_query($link, 'INSERT INTO `goods` (userId,type'.$keySql.') VALUES('.$USER["id"].','.intval($_GET["type"]).$val.')');
  }
  if ($result == false) {
    print(mysqli_error($link));
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
  $search = '(';
  $i=0;
  foreach ($_POST['all'] as $k => $v){
    if($i!=0){$search .= ' or ';}
    $search .= 'name="'.(string)$v['name'].'"';
    $i++;
  }
  $search .= ')';
  if($i>0){
    $result = mysqli_query($link, 'DELETE FROM `goods` WHERE userId='.$USER["id"].' and type=7 and '.$search);
    if ($result == false) {
      print(mysqli_error($link));
    }
  }
  $val = "";
  $i = 0;
  foreach ($_POST['all'] as $k => $v){
    $val .= '('.$USER["id"].',7';
    foreach ($v as $key => $value) {
      $val .= ',"'.$value.'"';
    }
    $val .= '),';
    $i++;
  }

//  var_dump('INSERT INTO `goods` (userId,type,incomeId,supplierArticle,barcode,name,value) VALUES '.rtrim($val, ","));
  if($i>0){
    $result1 = mysqli_query($link, 'INSERT INTO `goods` (userId,type,incomeId,supplierArticle,barcode,name,value) VALUES '.rtrim($val, ","));
    if ($result1 == false) {
      print(mysqli_error($link));
    }
  }

}
if(isset($_GET['del'])){
  $search = "";
  foreach ($_POST as $key => $value) {
    if($key!='value'){$search .= ' and '.(string)$key.'="'.(string)$value.'"';}
  }

  $result = mysqli_query($link, 'SELECT count(id)>0 FROM `goods` WHERE userId='.$USER["id"].' and type='.intval($_GET["del"]).$search);
  $row = mysqli_fetch_row($result);

  if ($row[0]>0){
    $result = mysqli_query($link, 'DELETE FROM `goods` WHERE userId='.$USER["id"].' and type='.intval($_GET["del"]).$search);
  }

  if ($result == false) {
    print(mysqli_error($link));
  }
}
if(isset($_GET['clear'])){
  $search = "";
  foreach ($_POST as $key => $value) {
    if($key!='value'){$search .= ' and '.(string)$key.'="'.(string)$value.'"';}
  }

  $result = mysqli_query($link, 'SELECT count(id)>0 FROM `goods` WHERE userId='.$USER["id"].' and type='.intval($_GET["clear"]).$search);
  $row = mysqli_fetch_row($result);

  if ($row[0]>0){
    $result = mysqli_query($link, 'UPDATE `goods` SET value="" WHERE userId='.$USER["id"].' and type='.intval($_GET["clear"]).$search);
  }

  if ($result == false) {
    print(mysqli_error($link));
  }
}

if(isset($_GET['all']) and $_GET['all']==11){
  $search = '(';
  $i=0;
  foreach ($_POST['sf'] as $k => $v){
    if($i!=0){$search .= ' or ';}
    $search .= 'name="'.(string)$k.'"';
    $i++;
  }
  $search .= ')';
  if($i>0){
    $result1 = mysqli_query($link, 'DELETE FROM `goods` WHERE userId='.$USER["id"].' and type=11 and '.$search);
    $result2 = mysqli_query($link, 'DELETE FROM `goods` WHERE userId='.$USER["id"].' and type=12 and '.$search);
    if ($result1 == false) {
      print(mysqli_error($link));
    }
    if ($result2 == false) {
      print(mysqli_error($link));
    }
  }

  $val = $goods = "";
  $i = $j = 0;
  foreach ($_POST['like'] as $key => $value) {

    if(!$value['goods']){

      foreach ($_POST['sf'] as $k => $v){
        $val .='('.$USER["id"].',11';
        foreach ($value as $vk => $vv) {
          $val .=',"'.(string)$vv.'"';
        }
        $val .=',"'.(string)$k.'"';
        $val .= ',"'.(string)$v.'"';
        $val .='),';
      }
      $i++;
    }else{
      foreach ($_POST['sf'] as $k => $v){
        $goods .='('.$USER["id"].',12';
        foreach ($value as $vk => $vv) {
          $goods .=',"'.(string)$vv.'"';
        }
        $goods .=',"'.(string)$k.'"';
        $goods .= ',"'.(string)$v.'"';
        $goods .='),';
      }
      $j++;
    }
  }
  if($i>0){
    $result1 = mysqli_query($link, 'INSERT INTO `goods` (userId,type,supplierArticle,barcode,name,value) VALUES '.rtrim($val, ","));
    if ($result1 == false) {
      print(mysqli_error($link));
    }
  }
  if($j>0){
    $result2 = mysqli_query($link, 'INSERT INTO `goods` (userId,type,goods,name,value) VALUES '.rtrim($goods, ","));
    if ($result2 == false) {
      print(mysqli_error($link));
    }
  }
}

if(isset($_GET['status'])){
  echo barOption($link,'status',$_GET['status'],$USER["id"]);
}
if(isset($_GET['option'])){
  echo barOption($link,'option',$_GET['option'],$USER["id"]);
}
if(isset($_POST['hide'])){
  $value = addslashes(json_encode($_POST['hide']));
  echo barOption($link,'hide',$value,$USER["id"]);
}

if(isset($_GET['copy'])){
  $val=$goods='';
  $i=0;
  foreach ($_POST['copy'] as $pkey => $pvalue) {
    if(!$pvalue['checkbox_del'] or $pvalue['edit']==1){$pvalue['edit']=1;}
    else{$pvalue['edit']=0;}
    $pvalue['checkbox_del']=time()+$i;
      foreach ($pvalue as $key => $value) {
        if($key!='checkbox_del' and $key!='id'){
          $goods .= '('.$USER["id"].',12,"'.$pvalue['checkbox_del'].'",'.$pvalue['edit'].',"'.$key.'",\''.$value.'\'),';
        }
      }
$i++;
  }

    if($goods!=''){$result = mysqli_query($link, 'INSERT INTO `goods` (userId,type,goods,edit,name,value) VALUES '.rtrim($goods, ","));}
  if ($result == false) {
    print(mysqli_error($link));
  }
}

mysqli_close($link);

 ?>
