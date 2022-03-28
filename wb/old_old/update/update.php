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
  if(filesize($file.'-'.$name.'.json') >= 4094965296){
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

      if($value != "" and $key != ""){$arr[$i]->$key = $value;}
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
        if($valid){
         file_put_contents($file, json_encode($read),LOCK_EX);
        }
      }
    }
  }else{
    $valid = write($dir_file.$name,'1',$like,$val);
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
	//var_dump($_POST);
	file_put_contents('json/list.json',json_encode($_POST),LOCK_EX);
}
