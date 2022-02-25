<?php

$inputJSON = file_get_contents('php://input');

if ($_GET['_dc'] and ($inputJSON!=null or $inputJSON!='')){

    $input = json_decode($inputJSON, TRUE);
    $valid = false;
    file_put_contents('../cache/data.json', '', FILE_APPEND);
    $lines = json_decode(file_get_contents('../cache/data.json'));

    foreach ($input["data"] as $key=>$d){
        if ($key == "storage_cost" or $key == "acceptance_fee" or $key == "other_deductions"){
            $v = 1;
        }elseif($key !="id"){
            $v = 2;
        }
    }

    if ($v==1)
    {
        file_put_contents('json/5.json', '', FILE_APPEND);
        $corrects = json_decode(file_get_contents('json/5.json'));
    }else{
        file_put_contents('json/7.json', '', FILE_APPEND);
        $corrects = json_decode(file_get_contents('json/7.json'));
    }

    function preg_barcode($barcode)
    {
        preg_match_all("'>(.*?)<'si", $barcode, $match);
        return $match[1][1];
    }

    function json_data_five($datum, $lines, $corrects)
    {
        $i = 0;
        $count_line = str_replace("-", "", trim(stristr($datum["id"], '-'), '-'));

        foreach ($lines as $line) {
            $j = 0;
            $k = 0;
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
            if ($count_line == $i + 1) {
                $arr = array('rid' => $line->rid, 'barcode' => preg_barcode($line->barcode),
                    'realizationreport_id' => $line->realizationreport_id,
                    'storage_cost' => trim($datum["storage_cost"]),
                    'acceptance_fee' => trim($datum["acceptance_fee"]),
                    'other_deductions' => trim($datum["other_deductions"])
                );
            }
            $i++;
        }
        if ($k == 0 and $arr) {
            $corrects[] = $arr;
        }
        if ($corrects) {
            file_put_contents('json/5.json', json_encode($corrects));
            return true;
        }
        return false;
    }
    function json_data_seven($datum, $lines, $corrects)
    {
        $i = 0;
        $count_line = str_replace("-", "", trim(stristr($datum["id"], '-'), '-'));

        foreach ($lines as $line) {
            $j = 0;
            $k = 0;
            if ($corrects) {
                foreach ($corrects as $correct) {
                    if ($count_line == $i + 1 and $correct->incomeId == preg_barcode($line->incomeId)
                        and $correct->supplierArticle == $line->supplierArticle
                        and $correct->barcode == $line->barcode) {

                        foreach ($datum as $key=>$datumm) {
                            if ($key!='id') {
                                $corrects[$j]->$key = $datumm;
                            }
                        }

                        $k++;
                    }
                    $j++;
                }
            }
            if ($count_line == $i + 1) {
                $arr = array('incomeId' => preg_barcode($line->incomeId), 'barcode' => $line->barcode,
                    'supplierArticle' => $line->supplierArticle,);
                foreach ($datum as $key=>$datumm) {
                    if ($key!='id'){
                        $arr[$key] = $datumm;
                    }
                }
            }
            $i++;
        }
        if ($k == 0 and $arr) {
            $corrects[] = $arr;
        }
        if ($corrects) {
            file_put_contents('json/7.json', json_encode($corrects));
            return true;
        }
        return false;
    }
    if ($input["data"][1]) {
        foreach ($input["data"] as $datum) {
            if ($v==1)
            {
                $valid = json_data_five($datum, $lines, $corrects);
            }else{
                $valid = json_data_seven($datum, $lines, $corrects);
            }
        }
    } else {

        if ($v==1)
        {
            $valid = json_data_five($input["data"], $lines, $corrects);
        }else{
            $valid = json_data_seven($input["data"], $lines, $corrects);
        }
    }
    if ($valid == true) {
        echo json_encode(array('success' => true, 'message' => 'Успех', 'data' => $input["data"]));
    }
}elseif($_POST['dp_save_list'] and $_POST['list']){
    $contents = json_decode(file_get_contents('json/list.json'));
    $i=0;
    if($contents != '') {
        foreach ($contents as $key => $content) {
            if ($content->dp_save_list == $_POST['dp_save_list']) {
                $contents[$key]->list = $_POST['list'];
                $i = 1;
            }
        }
    }else{$i=0;}
    if ($i==0){
        $contents[]=array('dp_save_list'=>$_POST['dp_save_list'],'list'=>$_POST['list']);
    }

    file_put_contents('json/list.json',json_encode($contents));
}
?>