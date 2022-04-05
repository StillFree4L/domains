<?php


//предыдущий период для графика

if ($tbl_rows && $_GET['type'] != 5 && $_GET['type'] != 6 && $_GET['type'] != 8 && $_GET['type'] != 9)
{

    foreach ($tbl_rows as $g)
    {

        if ($g->discountPercent==0 and $g->finishedPrice){
            $g->discountPercent = 100-(($g->finishedPrice*100)/$g->totalPrice);
        }
        if ($g->finishedPrice==0 and $g->discountPercent){
            $g->finishedPrice = $g->totalPrice-(($g->totalPrice*$g->discountPercent)/100);
        }

        if($_GET['type'] == 10 and $g->doc_type_name == 'Возврат'){
            $g->qualification = 'Отмененные продажи';
        }
        elseif($_GET['type'] == 10){
            $g->qualification = 'Отмененные заказы';
        }

        if($config_return=='off' and ($g->isCancel == 1 || $g->forPay < 0 || $g->doc_type_name == 'Возврат' || $g->finishedPrice < 0 || $g->RED == 1) and ($_GET['type'] == 1 or $_GET['type'] == 2)){
            continue;
        }

        $flag = 1;
        //$cdt_bar = $g->date;
        $cdt_bar = $g->lastChangeDate;
        $last_key_bar = - 1;

        if ($_GET['type'] == 3) $cdt_bar = $g->cancel_dt;
        if ($_GET['type'] == 4) $cdt_bar = $g->lastChangeDate;
        if($_GET['type'] == 10) {
            $cdt_bar = $g->date;
        }
        if ($_GET['type'] == 1) {
            $cdt_bar = $g->date;
        }
        if ($_GET['type'] == 2) {
            $cdt_bar = $g->date;
        }

            if (isset($dt1_bar))
            {
                if (strtotime($cdt_bar) >= strtotime($dt1_bar) && strtotime($cdt_bar) <= strtotime($dt2_bar) + 60 * 60 * 24) $flag = 0;
            }
            else
            {
                if ($stats_res['Вчера'] == $_GET['dt'])
                {
                    if (date('Y-m-d', strtotime($cdt_bar)) == date('Y-m-d', strtotime($_GET['dt']))) $flag = 0;
                }
                else
                {
                    if (strtotime($cdt_bar) >= strtotime($_GET['dt'])) $flag = 0;
                }
            }

        if ($flag == 1) continue;

        if (isset($_GET['f1']) && isset($_GET['bc']))
        {
            if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;
        }
        elseif (isset($_GET['f1']))
        {
            if ($g->barcode != $_GET['f1']) continue;
        }
        elseif($_GET['type'] == 10)
        {
            if (isset($keys_bc_bar[$g->odid]))
            {
                $sums = explode("\n", trim('
quantity
totalPrice
promoCodeDiscount
forPay
finishedPrice
priceWithDisc
'));
                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);
                    $rows_after_date_bar[$keys_bc_bar[$g->odid]]->$fieldsum += $g->$fieldsum;
                }
            }
            if (isset($keys_bc_bar[$g->odid]))continue;
        }

        $g->lastChangeDate = date('d.m.Y H:i:s', strtotime($g->lastChangeDate));
        $g->date = date('d.m.Y H:i:s', strtotime($g->date));

        $rows_after_date_bar[] = $g;
        if ($_GET['type'] == 10) {
            $last_key_bar = count($rows_after_date_bar) - 1;
            $keys_bc_bar[$g->odid] = $last_key_bar;
            $keys_bc_bar2[$g->odid] = $last_key_bar;
        }
    }
    $tbl_rows_bar = $rows_after_date_bar;
    unset($rows_after_date_bar);
}

$data_bar = [];
$data_rows_bar = [];

if($tbl_rows_bar){
    $tbl_rows_bar = array_reverse($tbl_rows_bar);
}

$sums_null = explode("\n", ('techSize
fbs
fbo
fbs_fbo
quantity
totalPrice
discountPercent
promoCodeDiscount
finishedPrice
spp
forPay
priceWithDisc
quantity'));

// REFUND_COLOR && PRICE_SUM
$sums = explode("\n", ('cost_amount
retail_price
retail_amount
retail_commission
customer_reward
supplier_reward
retail_price_withdisc_rub
for_pay
for_pay_nds
delivery_amount
return_amount
delivery_rub
quantity'));

$PRICE_SUM_BAR = 0;

if ($tbl_rows_bar and count($tbl_rows_bar)) {
    foreach ($tbl_rows_bar as $g) {

        if ($_GET['type'] == 6 and !$_GET['f1'] and !$_GET['f2']){
            if ($g->refa_7 !=0){
                $g->refund_7 = intval($g->ref_7 / $g->refa_7 * 100) . '% <br>';
                $g->refund_7 .= $g->ref_7 . ' / ' . $g->refa_7;
            }
            if ($g->refa_30){
                $g->refund_30 = intval(round($g->ref_30 / $g->refa_30 * 100, 2)) . '% <br> ';
                $g->refund_30 .= $g->ref_30 . ' / ' . $g->refa_30;
            }
            $g->Discount = $g->Discount/$g->coun;
        }
        //по умолчанию 0
      /*  foreach ($sums_null as $fie) {
            $fie = trim($fie);
            if (!$g->$fie) {
                $g->$fie = '0';
            }
        }*/
        //кол-во по умолч 1
        if (!$g->quantity and $_GET['type'] != 5) {
            $g->quantity = 1;
        }

        if (isset($_GET['f2']) && isset($_GET['f3']) && $g->{$_GET['f2']} <> str_replace(" ", "", $_GET['f3'])) {
            continue;
        }

        $data_cols_bar = [];

        foreach ($sums as $fieldsum) {
            $fieldsum = trim($fieldsum);
            $g->$fieldsum = abs($g->$fieldsum);
        }

        $flag = 0;
        $REFUND_COLOR = '';

        if ($_GET['type'] == 1) {
            if ($g->forPay < 0 || $g->finishedPrice < 0 || $g->RED == 1) $REFUND_COLOR = 'danger'; //$flag = 1;
        }

        if ($_GET['type'] == 4 || ($g->totalPrice > 0 and $_GET['type'] == 10 and ($g->forPay or $g->finishedPrice))) {
            if ($g->totalPrice > 0) $flag = 1;
        }

        if ($_GET['type'] == 2) {
            if ($g->isCancel == 1 || $g->RED == 1) $REFUND_COLOR = 'danger'; //$flag = 1;
        }

        if ($_GET['type'] == 3 || ($g->oblast && $_GET['type'] == 10)) {
            if ($g->isCancel != 1) $flag = 1;
        }

        if ($_GET['type'] == 5) {
            $flag = 0;
        }

        if (($_GET['type'] == 1 and $g->forPay >= 0) || $_GET['type'] == 4) {
            $PRICE_SUM_BAR += $g->forPay * $g->quantity;
        }
        elseif ($_GET['type'] == 2 and $g->totalPrice >= 0 and $g->isCancel != 1) {
            // ЗАКАЗЫ
            $PRICE_SUM_BAR += $g->finishedPrice;
            // $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
        }
        elseif ($_GET['type'] == 10) {
            $PRICE_SUM_BAR += $g->finishedPrice;
        }
        elseif(!in_array($_GET['type'],[1,2,10])) {
            $PRICE_SUM_BAR += $g->totalPrice * ((100 - $g->discountPercent) / 100);
        }

        // other
        foreach ($tbl_keys as $k => $str) {

            if ($k == 'save'){continue;}

            if (strpos($str, 'дата') !== false) {
                $g->$k = str_replace('T', ' ', $g->$k);
            }
            if ($g->$k === true) {
                $g->$k = 1;
            } elseif ($g->$k === false) {
                $g->$k = '';
            }
            if (($g->$k != null and $_GET['type'] != 5) or ($_GET['type'] == 5)) {
                $data_cols_bar[$k] = $g->$k;
            }
        }

        $data_rows_bar[] = (object)$data_cols_bar;
    }

}
$data_bar = $data_rows_bar;
