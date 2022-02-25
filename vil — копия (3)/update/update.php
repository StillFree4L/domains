<?php

$inputJSON = file_get_contents('php://input');
$input= json_decode( $inputJSON, TRUE );
$valid = false;
file_put_contents('../cache/data.json','', FILE_APPEND);
file_put_contents('json/5.json','', FILE_APPEND);
$lines = json_decode(file_get_contents('../cache/data.json'));
$corrects = json_decode(file_get_contents('json/5.json'));

function preg_barcode($barcode){
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

function json_data($datum,$lines,$corrects){
    $i=0;
    $count_line = str_replace("-", "", trim(stristr($datum["id"],'-'), '-'));

    foreach ($lines as $line) {
        $j=0;
        $k=0;
        if ($corrects) {
            foreach ($corrects as $correct) {
                if ($count_line == $i + 1 and $correct->rid == $line->rid
                    and $correct->realizationreport_id == $line->realizationreport_id
                    and $correct->barcode == preg_barcode($line->barcode)) {

                    $corrects[$j]->storage_cost = trim($datum["storage_cost"]);
                    $corrects[$j]->acceptance_fee = trim($datum["acceptance_fee"]);
                    $corrects[$j]->other_deductions = trim($datum["other_deductions"]);
                    $k++;
                }
                $j++;
            }
        }
        if ($count_line == $i + 1){
            $arr = array('rid' => $line->rid,'barcode' => preg_barcode($line->barcode),
                'realizationreport_id' => $line->realizationreport_id,
                'storage_cost' => trim($datum["storage_cost"]),
                'acceptance_fee' => trim($datum["acceptance_fee"]),
                'other_deductions' => trim($datum["other_deductions"])
            );
        }
        $i++;
    }
    if ($k == 0 and $arr){
        $corrects[] = $arr;
    }
    if ($corrects){
        file_put_contents('json/5.json',json_encode($corrects));
        return true;
    }
    return false;
}

if ($input["data"][1]){
    foreach ($input["data"] as $datum) {
        $valid = json_data($datum,$lines,$corrects);
    }
}else{
    $valid = json_data($input["data"],$lines,$corrects);
}
if ($valid == true){
    echo json_encode(array('success'=>true,'message'=>'Успех','data'=>$input["data"]));
}
?>