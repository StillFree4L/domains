<?php

function api_filter($line){
    $l = $line;
    if ($_GET['type']==1 or $_GET['type']==2){
        if ($GLOBALS['config_return']=='on'){
            if ($_GET['type']==1 and (($l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Продажа'
                and $l->delivery_amount == 0 and $l->return_amount == 0)
                or (($l->doc_type_name == 'Возврат' and  $l->supplier_oper_name == 'Возврат'
                        and $l->delivery_amount == 0 and $l->return_amount == 0) or ($l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Логистика'
                        and $l->delivery_amount == 0 and $l->return_amount > 0)))){
                return true;
            }elseif ($_GET['type']==2 and (($l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Логистика'
                and $l->delivery_amount > 0 and $l->return_amount == 0)
                    or (($l->doc_type_name == 'Возврат' and  $l->supplier_oper_name == 'Возврат'
                    and $l->delivery_amount == 0 and $l->return_amount == 0) or ($l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Логистика'
                    and $l->delivery_amount == 0 and $l->return_amount > 0)))){
                return true;
            }
        }else{
            if ($_GET['type']==1 and $l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Продажа'
                and $l->delivery_amount == 0 and $l->return_amount == 0){
                return true;
            }elseif ($_GET['type']==2 and $l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Логистика'
                and $l->delivery_amount > 0 and $l->return_amount == 0){
                return true;
            }
        }
    }
    /*if ($_GET['type']==1 and $l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Продажа'
        and $l->delivery_amount == 0 and $l->return_amount == 0){
        return true;
    }elseif ($_GET['type']==2 and $l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Логистика'
        and $l->delivery_amount > 0 and $l->return_amount == 0){
        return true;
    }*/
    elseif ($_GET['type']==10 and (($l->doc_type_name == 'Возврат' and  $l->supplier_oper_name == 'Возврат'
        and $l->delivery_amount == 0 and $l->return_amount == 0) or ($l->doc_type_name == 'Продажа' and  $l->supplier_oper_name == 'Логистика'
            and $l->delivery_amount == 0 and $l->return_amount > 0))){
        return true;
    }elseif ($_GET['type']==5){
        return true;
    }
    return false;
}

function sales_object_report($r){
    $arr = array();
    $i = 0;
    foreach ($r as $col) {
       // $arr[$i]['number'] = $col->realizationreport_id;
        $arr[$i]['date'] = $col->order_dt;
        $arr[$i]['lastChangeDate'] = $col->sale_dt;
        $arr[$i]['supplierArticle'] = $col->sa_name;
        $arr[$i]['techSize'] = $col->ts_name;
        $arr[$i]['barcode'] = $col->barcode;
        $arr[$i]['quantity'] = $col->quantity;
        $arr[$i]['totalPrice'] = $col->retail_price;
        $arr[$i]['discountPercent'] = $col->product_discount_for_report;
        $arr[$i]['isRealization'] = $col->suppliercontract_code ;
        $arr[$i]['promoCodeDiscount'] = $col->supplier_promo ;
        $arr[$i]['spp'] = $col->ppvz_spp_prc;
        $arr[$i]['forPay'] = $col->ppvz_for_pay;
        $arr[$i]['doc_type_name'] = $col->doc_type_name;
        $arr[$i]['finishedPrice '] = $col->retail_price_withdisc_rub;
        $arr[$i]['warehouseName'] = $col->office_name;
        $arr[$i]['incomeID'] = $col->gi_id;
        $arr[$i]['odid'] = $col->rid;
        $arr[$i]['nmId'] = $col->nm_id;
        $arr[$i]['subject'] = $col->subject_name;
        $arr[$i]['brand'] = $col->brand_name;
        $i++;
        //if ($i>0)break;
    }
    return $arr;
}

function unity_report($r,$r_apies){
    $i=0;
    $date = 0;
    $odid = array();

    if ($_GET['type']==2){
        $cdt = 'date';
    }else{
        $cdt = 'lastChangeDate';
    }

    foreach ($r as $rs){
        $r[$i] = (object)$rs;
        if (strtotime($rs->$cdt) > $date){
            $date = strtotime($rs->$cdt);
        }
        $odid[] = $rs->odid;
        $i++;
    }
    foreach ($r as $g) {
        if ($g->barcode == null){
            var_dump($g);
        }
    }


    foreach ($r as $rs) {
        if ($r_apies and $_GET['type'] != 10){
        foreach ($r_apies as $r_api) {
            if ($rs->barcode == $r_api->barcode and $rs->odid == $r_api->odid) {
                $r[$i]->category = $r_api->category;
                if ($r_api->oblast){$r[$i]->oblast = $r_api->oblast;}
                if ($r_api->countryName){$r[$i]->countryName = $r_api->countryName;}
                if ($r_api->oblastOkrugName){$r[$i]->oblastOkrugName = $r_api->oblastOkrugName;}
                if ($r_api->regionName){$r[$i]->regionName = $r_api->regionName;}
                if ($r_api->IsStorno){$r[$i]->IsStorno = $r_api->IsStorno;}
                $r[$i]->discountPercent = $r_api->discountPercent;
                $r[$i]->finishedPrice = $r_api->finishedPrice;
                $r[$i]->totalPrice = $r_api->totalPrice;
                $r[$i]->quantity = $r_api->quantity;
            }elseif ($rs->barcode == $r_api->barcode){
                $r[$i]->category = $r_api->category;
                if ($r_api->countryName and !$r[$i]->countryName){$r[$i]->countryName = $r_api->countryName;}
                if ($r_api->oblastOkrugName and !$r[$i]->oblastOkrugName){$r[$i]->oblastOkrugName = $r_api->oblastOkrugName;}
                if ($r_api->regionName and !$r[$i]->regionName){$r[$i]->regionName = $r_api->regionName;}
                if ($r_api->oblast and !$r[$i]->oblast){$r[$i]->oblast = $r_api->oblast;}
                if ($r_api->IsStorno and !$r[$i]->IsStorno){$r[$i]->IsStorno = $r_api->IsStorno;}
                if(!$r[$i]->discountPercent){$r[$i]->discountPercent = $r_api->discountPercent;}
                if(!$r[$i]->finishedPrice){$r[$i]->finishedPrice = $r_api->finishedPrice;}
                if(!$r[$i]->totalPrice){$r[$i]->totalPrice = $r_api->totalPrice;}
                if(!$r[$i]->quantity){$r[$i]->quantity = $r_api->quantity;}
            }
        }
        }
        $i++;
    }

    foreach ($r_apies as $r_api){
        if (strtotime($r_api->$cdt) > $date) {
            if (!in_array($r_api->odid, $odid)){
                $r[] = $r_api;
            }
        }
    }

    return $r;
}

//заказ из отчета
function orders_object_report($r){
    $arr = array();
    $i = 0;
    foreach ($r as $col) {
        //$arr[$i]['number'] = $col->realizationreport_id;
        $arr[$i]['date'] = $col->order_dt;
        $arr[$i]['lastChangeDate'] = $col->sale_dt;
        $arr[$i]['supplierArticle'] = $col->sa_name;
        $arr[$i]['techSize'] = $col->ts_name;
        $arr[$i]['barcode'] = $col->barcode;
        $arr[$i]['quantity'] = $col->quantity;
        $arr[$i]['totalPrice'] = $col->retail_price;
       // $arr[$i]['doc_type_name'] = $col->doc_type_name
        $arr[$i]['discountPercent'] = $col->product_discount_for_report;
        $arr[$i]['finishedPrice '] = $col->retail_price_withdisc_rub;
        $arr[$i]['warehouseName'] = $col->office_name;
        $arr[$i]['incomeID'] = $col->gi_id;
        $arr[$i]['odid'] = $col->rid;
        $arr[$i]['nmId'] = $col->nm_id;
        $arr[$i]['subject'] = $col->subject_name;
        $arr[$i]['brand'] = $col->brand_name;
        if (($col->doc_type_name == 'Возврат' and $col->supplier_oper_name == 'Возврат'
                and $col->delivery_amount == 0 and $col->return_amount == 0)
            or ($col->doc_type_name == 'Продажа' and $col->supplier_oper_name == 'Логистика'
                and $col->delivery_amount == 0 and $col->return_amount > 0)){
            $arr[$i]['isCancel'] = 1;
            $arr[$i]['cancel_dt'] = $col->rr_dt;
        }
        $i++;
        //if ($i>0)break;
    }
    return $arr;
}

//кэшир отчета реализ
function report_cache(){
    $dir = 'cache';
    $fileName = $dir.'/'.$GLOBALS['wb_key_new'];

    $http = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/reportDetailByPeriod?dateFrom=' . date('Y-m-d', time() - 60 * 60 * 24 * 80) . '&key=' . $GLOBALS['wb_key_new'] . '&limit=1000000&rrdid=0&dateto=' . date('Y-m-d');

    if (!file_exists($dir)) {mkdir($dir, 0777, true);}
    file_put_contents($fileName, '',FILE_APPEND);

    $this_week = strtotime(date('Y-m-d'));
    $lines = file($fileName);

    if ($lines[0] == "" or json_decode($lines[1]) == NULL or intval($lines[0]) < $this_week){
        $r = http_json($http);
        file_put_contents($fileName, time().PHP_EOL.json_encode($r).PHP_EOL, FILE_APPEND);
        return report_cache();
    }else{
        return $lines[1];
    }
    return false;
}
