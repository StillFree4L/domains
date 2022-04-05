<?php

function preg_barcode($barcode)
{
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

function diff($name,$like,$read){
  if($name=='11' and $like->barcode == $read->barcode and urldecode($like->supplierArticle) == urldecode($read->supplierArticle)){
    return true;
  }else if($name=='5' and urldecode($like->realizationreport_id) == urldecode($read->realizationreport_id)){
    return true;
  }else if($name=='7' and urldecode($like->incomeId) == urldecode($read->incomeId) and $like->barcode == $read->barcode and urldecode($like->supplierArticle) == urldecode($read->supplierArticle)){
    return true;
  }else if($name=='products' and urldecode($like->checkbox_del) == urldecode($read->checkbox_del)){
    return true;
  }
  return false;
}

function filt_double($arr){
  $res = [];
  $diff = [];
  foreach ($arr as $key => $value) {

    if(!in_array($value->checkbox_del,$diff)){
      $res[] = $value;
      $diff[] = $value->checkbox_del;
    }
  }

  return $res;
}

function file_write($name,$like,$valk,$valv){
  $dir_file = 'json/';
  $files=glob($dir_file.$name."-*.json");
  $diff=$valid=false;

  if($files){
  foreach($files as $file){
    preg_match('|-(.*?).json|si', $file, $fileName);
    $read = json_decode(file_get_contents($file));
    $nameFile = $fileName[1];

    if (flock($file, LOCK_EX | LOCK_NB)){
      if($read && is_object($read)){
      if(diff($name,(object)$like,$read)){
        $read->$valk = $valv;
        $valid=true;
      }
    }
    if($read && is_array($read)){
      foreach($read as $k=>$v){
        if(diff($name,(object)$like,$v)){
          $v->$valk = $valv;
          $valid=true;
        }
      }
    }
    if($valid==true){
      ftruncate($file, 0);
      file_put_contents($file, json_encode($read),LOCK_EX);
      fflush($file);
      flock($file, LOCK_UN);
      echo true;break;
    }
  }
  }
}else{
  $arr=[];
  $write[$valk] = $valv;
  $arr[] = array_merge($like,$write);
  file_put_contents($dir_file.$name.'-1.json', json_encode($arr),LOCK_EX);
}
if($valid==false){

  $size=true;

  if(intval(filesize($file.'-'.$name.'.json')) >= 4094965296){
    $name = intval($name)+1;
    $size = false;
  }

  $arr=[];
  if($size == true and $read && is_object($read)){
    $arr[] = $read;
  }else if($size == true and $read && is_array($read)){
    $arr = $read;
  }
  $write[$valk] = $valv;
  $arr[] = array_merge($like,$write);

  if($read){
    $path_file = $dir_file.$name.'-'.$nameFile.'.json';
    ftruncate($path_file, 0);
    file_put_contents($path_file, json_encode($arr),LOCK_EX);
    fflush($path_file);
    flock($path_file, LOCK_UN);
  }else{
    file_put_contents($dir_file.$name.'-'.$nameFile.'.json', json_encode($arr),LOCK_EX);
  }


  echo true;
}
if (flock($file, LOCK_UN)){
  return $valid;
}
return $valid;
}

$like = [];
$val = [];

//5,7,8,9,11
if(in_array($_GET['type'],[5,7,11]) and !$_GET['status'] and !$_GET['percent'] and $_POST['val'] != null and $_POST['val'] != "" and $_POST['val'] != null){
    foreach ($_POST as $key => $value) {
        if($key != "val" and $key != 'name'){
            $like[$key] = $value;
        }
    }

    echo file_write($_GET['type'],$like,$_POST['name'],$_POST['val']);
}

//9
elseif($_GET['percent']){
	file_put_contents('json/9.json',json_encode($_GET['percent']),LOCK_EX);

	echo true;
}

//bar
elseif($_GET['status'] or $_GET['option'] or $_POST['hide']){
	$read = json_decode(file_get_contents('json/bar.json'));
	if ($_GET['status']){$read->status = $_GET['status'];}
	if ($_GET['option']){$read->option = $_GET['option'];}
	if ($_POST['hide']){
        $read->hide = $_POST['hide'];
        //var_dump($read->hide);
	}
	file_put_contents('json/bar.json',json_encode($read),LOCK_EX);
}

//column name 7,8
elseif($_POST['dp_save_list']){
	file_put_contents('json/list.json',json_encode($_POST),LOCK_EX);
}

//products add
elseif($_GET['products']=="add"){
  $like['checkbox_del'] = $_POST['checkbox_del'];
  if($like['checkbox_del']){
    echo file_write('products',$like,$_POST['name'],$_POST['val']);
  }
}
//products del
elseif($_GET['products']=="del"){
  $dir_file = 'json/';
  $name = 'products';
  $files=glob($dir_file.$name."-*.json");

  if($files){
    foreach($files as $fileKey=>$file){

      if (preg_match('|-(.*?).json|si', $file, $fileName)){
        $read = json_decode(file_get_contents($file));


        if($read && is_object($read)){
          $readValid = (array) $read;
          if(!$readValid[1]){
            if($read->checkbox_del === $_POST['checkbox_del']){
              unset($read);
            }
          }else{
            foreach ($read as $key => $value) {
              if($value->checkbox_del === $_POST['checkbox_del']){
                unset($read);
              }
            }
          }
        }
        elseif($read and is_array($read)){
          foreach ($read as $key => $value) {
            if($value->checkbox_del === $_POST['checkbox_del']){
              unset($read[$key]);
            }
          }
          $read = array_values($read);
        }
        file_put_contents($file,json_encode($read),LOCK_EX);
      }
    }
  }
  echo true;
}
