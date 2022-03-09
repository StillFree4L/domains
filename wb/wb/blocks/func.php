<?php
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

        if($_GET['type'] == 10 and $g->totalPrice>0){
            $g->totalPrice *=-1;
        }
        if($_GET['type'] == 10 and $g->finishedPrice>0){
            $g->finishedPrice *=-1;
        }
        if($config_return=='off' and ($g->isCancel or $g->finishedPrice < 0) and ($_GET['type'] == 1 or $_GET['type'] == 2)){
            continue;
        }

        $flag = 1;
        $cdt_bar = $g->date;
        $cdt_bar = $g->lastChangeDate;
        $last_key_bar = - 1;

        if ($_GET['type'] == 3) $cdt_bar = $g->cancel_dt;
        if ($_GET['type'] == 4) $cdt_bar = $g->lastChangeDate;
        if ($_GET['type'] == 10 and ($cdt_bar == $g->cancel_dt or $g->doc_type_name == 'Возврат')) {
            $cdt_bar = $g->date;
            $g->qualification = 'Отмененные заказы';
        }
        elseif($_GET['type'] == 10) {
            $cdt_bar = $g->date;
            $g->qualification = 'Отмененные продажи';
        }
        if ($_GET['type'] == 1) {
            $cdt_bar = $g->lastChangeDate;
            $g->qualification = 'Отмененные заказы';
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