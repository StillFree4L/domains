<?php

function fileN(){
    if ($_GET['type'] == 1 or $_GET['type'] == 2){
        return 'cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'].'-'.$GLOBALS['config_return'];
    }else{
        return 'cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'];
    }
}

function r_url($url,$bool=false){
    if ($url){
        return http_json($url,$bool);
    }else{
        return null;
    }
}

function report_filter($r){
    $arr = array();
    foreach ($r as $rs) {
        if (api_filter($rs)){
            $arr[] = $rs;
        }
    }
    if ($_GET['type']==2){
        $arr = orders_object_report($arr);
    }elseif ($_GET['type']==1){
        $arr = sales_object_report($arr);
    }elseif ($_GET['type']==10){
        $arr = sales_object_report($arr);
    }
    return $arr;
}

function fbs_fbo($r){
    $arr = array();
    foreach ($r as $g){
        /*if ($g->barcode == null){
            var_dump($g);
        }*/
        if ($g->barcode and ($_GET['type']==1 or $_GET['type']==2 or $_GET['type']==10)){
            $dir = 'cache';
                $fileName = $dir . '/' . $GLOBALS['auth'] . '-stocks.txt';
                $lines = file($fileName);
                foreach ($lines as $line_num => $line) {
                    $line = json_decode($line);
                    if ($line->barcode == $g->barcode) {
                        $g->fbs = $line->stock;
                    }
                }
                $lines = stock_cache_old();
                if ($lines) {
                    $g->fbo = 0;
                    foreach ($lines as $line_num => $line) {
                        $line = json_decode($line);
                        if ($line->barcode == $g->barcode) {
                            $g->techSize = $line->techSize;
                            $g->fbo += $line->quantity;
                            $g->isSupply = $line->isSupply;
                            $g->isRealization = $line->isRealization;
                            $g->quantityFull = $line->quantityFull;
                            $g->quantityNotInOrders = $line->quantityNotInOrders;
                        }
                    }
                }
            $g->fbs_fbo = $g->fbs + $g->fbo;
        }
        $arr[] = $g;
    }
    return json_encode($arr);
}

$fileN = fileN();

if (!file_exists('cache')) {mkdir('cache', 0777, true);}
file_put_contents($fileN, '', FILE_APPEND);

$buf = explode('@@---@@', file_get_contents($fileN));

if ($buf == "" || json_decode($buf[1]) == NULL || time() - intval($buf[0]) > 60*5 || strpos($buf, 'can\'t decode supplier key') != false){
    $r_url_new = r_url($api_url_new,true);
    $r_url_sales = r_url($api_url_sales);
    $r_url = r_url($api_url);
    $r_url = json_decode(array_unite($r_url,$r_url_new,$r_url_sales));
    $r_url_report =  report_filter(json_decode(report_cache()));
    $r = unity_report($r_url_report,$r_url);
    $r = fbs_fbo($r);
    file_put_contents($fileN, time() . '@@---@@' . $r);
}else{
    $r = $buf[1];
}

