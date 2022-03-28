<?php


function preg_barcode($barcode)
{
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

function write($file,$name,$like,$val){
  $write = array_merge($like,$val);
  //var_dump($val);
  if($write){
    file_put_contents($file.'-'.$name.'.json', json_encode($write),LOCK_EX);
    return true;
  }
    return false;
}
function diff($like,$read){
  $c = array_intersect($like, $read);
  if(count($c) == count($like)){
    return true;
  }
  return false;
}
function read_write($file,$name,$like,$val){
  $size = true;
  if(intval(filesize($file.'-'.$name.'.json')) >= 4094965296){
    $name = intval($name)+1;
    $size = false;
  }

if($size){
  $read = json_decode(file_get_contents($file.'-'.$name.'.json'));
  $arr = [];

  if($read && is_object($read)){
    $arr[] = $read;
    $arr[] = array_merge($like,$val);
  }
  elseif($read and is_array($read)){
    $arr = $read;
    $write = array_merge($like,$val);
    $i = count($read);
    foreach ($write as $key => $value) {

      if($value != null and $value != "" and $key != ""){$arr[$i]->$key = $value;}
    }
  }
  elseif($read==null){
    $arr[] = array_merge($like,$val);
  }
}else{
  $arr[] = array_merge($like,$val);
}

  if($arr){
    file_put_contents($file.'-'.$name.'.json', json_encode($arr),LOCK_EX);
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

function file_write($name,$like,$val){
  $dir_file = 'json/';
  $files=glob($dir_file.$name."-*.json");
  $valid = false;
  $nameFile = '';
  $read = '';

  if($files){
    foreach ($files as $key => $file){
      if (preg_match('|-(.*?).json|si', $file, $fileName)){
        $nameFile = $fileName[1];
        $read = json_decode(file_get_contents($file));

        if($read && is_object($read)){
          $c = diff($like,(array)$read);
          if($c){
            foreach($val as $valk=>$valv){
              $read->$valk = $valv;
            }
            $valid = true;
          }
        }
        elseif($read and is_array($read)){

          $read = filt_double($read);

          foreach ($read as $key => $value) {
            $c = diff($like,(array)$value);
            if($c){
              foreach($val as $valk=>$valv){
                $read[$key]->$valk = $valv;
              }
              $valid = true;
            }

          }
        }
        //var_dump($valid);
        if($valid){
         file_put_contents($file, json_encode($read),LOCK_EX);
        }
      }
    }
  }else{
    $files=glob($dir_file.$name."-*.json");

    if(!$files){$valid = write($dir_file.$name,'1',$like,$val);}
  }

  if(!$valid){
    $valid = read_write($dir_file.$name,$nameFile,$like,$val);
  }

  return $valid;
}

$like = [];
$val = [];

//5,7,8,9
if(in_array($_GET['type'],[5,7,11]) and !$_GET['status'] and !$_GET['percent'] and $_POST['val'] != ""){
    $val[$_POST['name']] = $_POST['val'];
    foreach ($_POST as $key => $value) {
        if($key != "val" and $key != 'name'){
            $like[$key] = $value;
        }
    }

    echo file_write($_GET['type'],$like,$val);
}

//9
elseif($_GET['percent']){
	file_put_contents('json/9.json',json_encode($_GET['percent']),LOCK_EX);

	echo file_write($_GET['type'],$like,$val);
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
  $val[$_POST['name']] = $_POST['val'];
  $like['checkbox_del'] = $_POST['checkbox_del'];
  if($like['checkbox_del']){echo file_write('products',$like,$val);}

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
            if($read->image === $_POST['image']){
              unset($read);
            }
          }else{
            foreach ($read as $key => $value) {
              if($value->image === $_POST['image']){
                unset($read);
              }
            }
          }
        }
        elseif($read and is_array($read)){
          foreach ($read as $key => $value) {
            if($value->image === $_POST['image']){
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
