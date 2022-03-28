<?php
//чистая прибыль
    if (isset($_GET['rid']) && isset($_GET['bc']))
    {
      $templateHTML = "<a href='?page=wb&type=9'>Чистая прибыль </a>   № <a href='?page=wb&type=9&rid=$_GET[rid]'>" . $_GET['rid'] . '</a> по штрихкоду: ' . $_GET['bc'];
    }
    elseif (isset($_GET['rid']))
    {
      $templateHTML = "<a href='?page=wb&type=9'>Чистая прибыль </a>    № <a href='?page=wb&type=9&rid=$_GET[rid]'>" . $_GET['rid'] . '</a>';
    }

    $dp_save_list = 2;
    $ss_dop_fields = $ss_dop = dop_list('2');
    $sums = explode("\n", ('cost_amount
retail_price
retail_amount
retail_commission
customer_reward
supplier_reward
retail_price_withdisc_rub
ppvz_for_pay
delivery_amount
return_amount
delivery_rub
quantity
ppvz_vw
ppvz_vw_nds'));
    $sums_pr = explode("\n", trim('Стоимость единицы товара
        '.$ss_dop));
    $ss_dom_lat = array();

    foreach ($sums_pr as $sum) {
        $sum = trim($sum);
        $sum_lat = ru2Lat($sum);
        $ss_dom_lat[$sum_lat] = $sum;
    }

    if(!isset($_GET['rid'])){
        $correct_lines = file_read('7');
    }

    $last_key = - 1;
    foreach ($tbl_rows as $g)
    {
        $g->rr_dt = date('d.m.Y H:i:s', strtotime($g->rr_dt));
        $g->sale_dt = date('d.m.Y H:i:s', strtotime($g->sale_dt));
        $g->order_dt = date('d.m.Y H:i:s', strtotime($g->order_dt));

        if (isset($_GET['rid']) && isset($_GET['bc']))
        {
            if ($g->realizationreport_id != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;
        }
        else if (isset($_GET['rid']))
        {
            if ($g->realizationreport_id != $_GET['rid']) continue;
        }
        else
        {
            if (isset($keys_bc2[$g->realizationreport_id]))
            {
                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);
                    if (array_key_exists($fieldsum,$ss_dom_lat))
                    {
                        $reps[$keys_bc2[$g->realizationreport_id]]->$fieldsum += $g->$fieldsum*$g->quantity;
                    }
                    elseif (isset($g->supplier_oper_name) && ($g->supplier_oper_name == 'Продажа' || $fieldsum == 'delivery_rub'))
                    {
                        $reps[$keys_bc2[$g->realizationreport_id]]->$fieldsum += $g->$fieldsum;
                    }
                    else
                    {
                        $reps[$keys_bc2[$g->realizationreport_id]]->$fieldsum -= $g->$fieldsum;
                    }
                }
            }
            if ($last_code != $g->realizationreport_id)
            {
                $last_code = $g->realizationreport_id;
            }
            else
            {
                continue;
            }
        }

        $reps[] = $g;
        $last_key = count($reps) - 1;
        $keys_bc[$g->barcode] = $last_key;
        $keys_bc2[$g->realizationreport_id] = $last_key;
        $g->barcode2 = $g->barcode;
        $g->realizationreport_id2 = $g->realizationreport_id;
    }

foreach ($reps as $keys=>$g) {
    foreach ($ss_dom_lat as $k=>$item){
        foreach ($correct_lines as $key => $correct_line) {
            if ($g->sa_name == $correct_line->supplierArticle and $g->barcode == $correct_line->barcode) {
                foreach ($correct_line as $k => $item) {
                    $reps[$keys]->$k += $item;
                }
            }
        }
    }
}
    $tbl_rows = $reps;
    
    $tbl_keys = $pribil_keys;

    foreach ($ss_dom_lat as $key => $item) {
      $tbl_keys[$key] = $item;
    }
