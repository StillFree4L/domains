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

    $fp = @fopen ($file, "w");
    @flock ($fp, lock_ex);

    $nameFile = $fileName[1];

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

    if($valid==true and $read){
      file_put_contents($file, json_encode($read));
      @flock ($fp, lock_un);
      @fclose ($fp);
      echo true;break;
    }
  }
}else{
  $arr=[];
  $write[$valk] = $valv;
  $arr[] = array_merge($like,$write);
  file_put_contents($dir_file.$name.'-1.json', json_encode($arr));
}

if($valid==false){

  $size=true;
  $path_file = $dir_file.$name.'-'.$nameFile.'.json';

  if(intval(filesize($path_file)) >= 4094965296){
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


    file_put_contents($path_file, json_encode($arr),LOCK_EX);

  echo true;
}

if($fp){
  @flock ($fp, lock_un);
  @fclose ($fp);
}

return $valid;
}

$like = [];
$val = [];

//5,7,8,9,11
if(in_array($_GET['type'],[5,7,11]) and !$_GET['status'] and !$_GET['percent']){
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
  $file = 'json/bar.json';
	$read = json_decode(file_get_contents($file));
  $fp = @fopen ($file, "w");
  @flock ($fp, lock_ex);
	if ($_GET['status']){$read->status = $_GET['status'];}
	if ($_GET['option']){$read->option = $_GET['option'];}
	if ($_POST['hide']){
        $read->hide = $_POST['hide'];
        //var_dump($read->hide);
	}
	file_put_contents($file,json_encode($read));
  @flock ($fp, lock_un);
  @fclose ($fp);
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

        $fp = @fopen ($file, "w");
        @flock ($fp, lock_ex);

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
        file_put_contents($file,json_encode($read));
        @flock ($fp, lock_un);
        @fclose ($fp);
      }
    }
  }
  echo true;
}

//11 del
elseif($_GET['del']=="11"){
  $dir_file = 'json/';
  $name = '11';
  $files=glob($dir_file.$name."-*.json");
  //var_dump($files);
  if($files){
    foreach($files as $fileKey=>$file){

      if (preg_match('|-(.*?).json|si', $file, $fileName)){
        $read = json_decode(file_get_contents($file));

        $fp = @fopen ($file, "w");
        @flock ($fp, lock_ex);

        if($read && is_object($read)){
          $readValid = (array) $read;
          if(!$readValid[1]){
            if($read->barcode == $_POST['barcode'] and urldecode($read->supplierArticle) == urldecode($_POST['supplierArticle'])){
              unset($read);
            }
          }else{
            foreach ($read as $key => $value) {
              if($value->barcode == $_POST['barcode'] and urldecode($value->supplierArticle) == urldecode($_POST['supplierArticle'])){
                unset($read);
              }
            }
          }
        }
        elseif($read and is_array($read)){
          foreach ($read as $key => $value) {
            if($value->barcode == $_POST['barcode'] and urldecode($value->supplierArticle) == urldecode($_POST['supplierArticle'])){
              unset($read[$key]);
            }
          }
          $read = array_values($read);
        }
        //var_dump($read);
        if($read){file_put_contents($file,json_encode($read));}
        @flock ($fp, lock_un);
        @fclose ($fp);
      }
    }
  }
  echo true;
}
//products del
elseif($_GET['products']=="clear"){
  $dir_file = 'json/';
  $name = 'products';
  $files=glob($dir_file.$name."-*.json");

  if($files){
    foreach($files as $fileKey=>$file){

      if (preg_match('|-(.*?).json|si', $file, $fileName)){
        $read = json_decode(file_get_contents($file));

        $fp = @fopen ($file, "w");
        @flock ($fp, lock_ex);

        if($read && is_object($read)){
          $readValid = (array) $read;
          if(!$readValid[1]){
            if($read->checkbox_del === $_POST['checkbox_del']){
              foreach ($read as $vk => $vv) {
                if($vk!='checkbox_del'){
                  $read->$vk = "";
                }
              }
            }
          }else{
            foreach ($read as $key => $value) {
              if($value->checkbox_del === $_POST['checkbox_del']){
                foreach ($value as $vk => $vv) {
                  if($vk!='checkbox_del'){
                    $value->$vk = "";
                  }
                }
              }
            }
          }
        }
        elseif($read and is_array($read)){
          foreach ($read as $key => $value) {
            if($value->checkbox_del === $_POST['checkbox_del']){
              foreach ($value as $vk => $vv) {
                if($vk!='checkbox_del'){
                  $value->$vk = "";
                }
              }
            }
          }
          $read = array_values($read);
        }
        file_put_contents($file,json_encode($read));
        @flock ($fp, lock_un);
        @fclose ($fp);
      }
    }
  }
  echo true;
}
//11 add
elseif($_GET['add']=="11"){
  $dir_file = 'json/';
  $name = '11';
  $like = $_POST['like'];
  $sf = $_POST['sf'];
  $files=glob($dir_file.$name."-*.json");
  $valid=false;

  if($files){
    foreach($files as $fileKey=>$file){

      if (preg_match('|-(.*?).json|si', $file, $fileName)){
        $nameFile = $fileName[1];
        $read = json_decode(file_get_contents($file));

        $fp = @fopen ($file, "w");
        @flock ($fp, lock_ex);

        if($read && is_object($read)){
          $readValid = (array) $read;
          if(!$readValid[1]){
            foreach ($like as $kl => $vl) {
              if($read->supplierArticle == $vl['supplierArticle'] and $read->barcode == $vl['barcode']){
                $valid=true;
              //  unset($like[$kl]);
                foreach ($sf as $vk => $vv) {
                  $read->$vk=$vv;
                }
              }
            }
          }else{
            foreach ($read as $key => $value) {
              foreach ($like as $kl => $vl) {
                if($value->supplierArticle == $vl['supplierArticle'] and $value->barcode == $vl['barcode']){
                  $valid=true;
              //    unset($like[$kl]);
                  foreach ($sf as $vk => $vv) {
                    $value->$vk=$vv;
                  }
                }
              }
            }
          }
        }
        elseif($read and is_array($read)){
          foreach ($read as $key => $value) {
            foreach ($like as $kl => $vl) {
              if($value->supplierArticle == $vl['supplierArticle'] and $value->barcode == $vl['barcode']){
                $valid=true;
              //  unset($like[$kl]);
                foreach ($sf as $vk => $vv) {
                  $value->$vk=$vv;
                }
              }
            }
          }
          $read = array_values($read);
        }
        file_put_contents($file,json_encode($read));
        @flock ($fp, lock_un);
        @fclose ($fp);
      }
    }
  }

  if($valid==false){
    $path_file = $dir_file.$name.'-'.$nameFile.'.json';

    $arr=[];
    $wr=[];
    if($read && is_object($read)){
      $arr[] = $read;
    }else if($read && is_array($read)){
      $arr = $read;
    }
    foreach ($like as $kl => $vl) {
      $wr=[];
      foreach ($sf as $vk => $vv) {
        $wr[$vk]=$vv;
      }
      $arr[] = array_merge($vl,$wr);
    }

    file_put_contents($path_file, json_encode($arr),LOCK_EX);

    echo true;
  }

  $name = 'products';
  $files=glob($dir_file.$name."-*.json");

  if($files){
    foreach($files as $fileKey=>$file){

      if (preg_match('|-(.*?).json|si', $file, $fileName)){
        $read = json_decode(file_get_contents($file));

        $fp = @fopen ($file, "w");
        @flock ($fp, lock_ex);

        if($read && is_object($read)){
          $readValid = (array) $read;
          if(!$readValid[1]){
            foreach ($sf as $vk => $vv) {
              $read->$vk=$vv;
            }
          }else{
            foreach ($read as $key => $value) {
              foreach ($sf as $vk => $vv) {
                $value->$vk=$vv;
              }
            }
          }
        }
        elseif($read and is_array($read)){
          foreach ($read as $key => $value) {
            foreach ($sf as $vk => $vv) {
              $value->$vk=$vv;
            }
          }
          $read = array_values($read);
        }
        file_put_contents($file,json_encode($read));
        @flock ($fp, lock_un);
        @fclose ($fp);
      }
    }
  }
  echo true;
}
