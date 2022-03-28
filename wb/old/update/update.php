<?php


function preg_barcode($barcode)
{
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

function file_write($name,$like,$val){
	$dir = $_SERVER['DOCUMENT_ROOT'].'/wb/update/json/';
	$dir_file = 'json/';
	$files = scandir($dir);
	$files = array_diff($files , array('..', '.'));
	$bool_file = $bool_size = $valid = false;
	$write_file = '';
	$file1 = '';
	$count = 0;

foreach($files as $file){
	$file3 = substr($file, 0, strpos($file, "-"));
	if(basename($file3,'.json') == $name){
		$read = json_decode(file_get_contents($dir_file.$file));
		$bool_read = false;

		foreach($read as $readk => $readv){

			$readvv = (array)$readv;
			$c = array_intersect($like, $readvv);

			if (count($c) == count($like)) {
				$bool_read = $valid = true;
				foreach($val as $valk=>$valv){
					$read[$readk]->$valk = $valv;
				}
			}
		}
		if ($bool_read) {
			$bool_file = true;
			file_put_contents($dir_file.$file,json_encode($read));
		}
		if(filesize($dir_file.$file) <= 4294966296){
			$bool_size = true;
			$write_file = $file;
		}

		$file1 = substr($file, strpos($file, '-') + 1 );//preg_replace("/.+?(–)/", '', strstr($file, '.', true));
		$file1 = intval(basename($file1,'.json'));

		if($count < $file1){
			$count = $file1;
		}
	}
}

if (!$bool_file) {
	if($bool_size){
		//var_dump($dir_file.$write_file);
		$read = json_decode(file_get_contents($dir_file.$write_file));
		$write = (object) array_merge($like,$val);

		if(is_array($read)){
			$read[] =  $write;
		}else{
			$arr = [];
		if($read != null){
			$arr[] = $read;
		}
			$arr[] =  $write;
			$read = $arr;
		}
		file_put_contents($dir_file.$write_file, json_encode($read));
	}
	else{
		$write = array_merge($like,$val);
		file_put_contents($dir_file.$name.'-'.($count+1).'.json', json_encode($write));
	}
	$valid = true;
}

return $valid;

}


$like = [];
$val = [];

//5,7,8,9
if(in_array($_GET['type'],[5,7,11]) and !$_GET['status'] and !$_GET['percent']){
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
	file_put_contents('json/9.json',json_encode($_GET['percent']));

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
	file_put_contents('json/bar.json',json_encode($read));
}
//column name 7,8
elseif($_POST['dp_save_list']){
	var_dump($_POST);
	file_put_contents('json/list.json',json_encode($_POST));
}
