<?php

function preg_barcode($barcode)
{
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

$valid = false;
if ($_POST["rid"] and $_POST["real"]){
    file_put_contents('json/5.json', '', FILE_APPEND);
    $corrects = json_decode(file_get_contents('json/5.json'));
    $name = $_POST["name"];
    $j = 0;
    $i=0;
    if ($corrects){
        foreach ($corrects as $correct) {
            if ($correct->realizationreport_id == $_POST["real"]
                and $correct->rid == $_POST["rid"]){
                $corrects[$j]->$name = $_POST["val"];
                $i=1;
            }
            $j++;
        }
    }
    if ($i==0){
        $arr = array('realizationreport_id' => $_POST["real"], 'rid' => $_POST["rid"],$_POST["name"] => $_POST["val"]);
        $corrects[] = $arr;
    }
    if ($corrects){
        file_put_contents('json/5.json', json_encode($corrects));
        $valid=true;
    }

    $data = json_decode(file_get_contents('../cache/data.json'));
    $j = 0;
    if($data){
        foreach ($data as $datum) {
            if ($datum->realizationreport_id==$_POST["real"] and $datum->rid==$_POST["rid"]){
                $data[$j]->$name=$_POST["val"];
            }
            $j++;
        }
    }
    $valid=false;
    if ($data){
        file_put_contents('../cache/data.json', json_encode($data));
        $valid=true;
    }

}
echo $valid;

?>