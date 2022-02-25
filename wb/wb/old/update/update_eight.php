<?php

function preg_barcode($barcode)
{
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

$valid = false;
if ($_POST["rid"] and $_POST["barcode"] and $_POST["real"]){
    file_put_contents('json/8.json', '', FILE_APPEND);
    $corrects = json_decode(file_get_contents('json/8.json'));
    $name = $_POST["name"];
    $j = 0;
    $i=0;
    if ($corrects){
        foreach ($corrects as $correct) {
            if ($correct->incomeId == $_POST["real"]
                and $correct->supplierArticle == $_POST["rid"]
                and $correct->barcode == $_POST["barcode"]){
                $corrects[$j]->$name = $_POST["val"];
                $i=1;
            }
            $j++;
        }
    }
    if ($i==0){
        $arr = array('incomeId' => $_POST["real"], 'barcode' => $_POST["barcode"], 'supplierArticle' => $_POST["rid"],$_POST["name"] => $_POST["val"]);
        $corrects[] = $arr;
    }
    if ($corrects){
        file_put_contents('json/8.json', json_encode($corrects));
        $valid=true;
    }
    $data = json_decode(file_get_contents('../cache/data.json'));
    $j = 0;
    if($data){
        foreach ($data as $datum) {
            if ($datum->incomeId==$_POST["real"] and $datum->supplierArticle==$_POST["rid"] and $datum->barcode==$_POST["barcode"]){
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

}elseif ($_POST["data"]){
    echo '<pre>';var_dump(json_decode($_POST["data"])->arr);
}
echo $valid;

?>