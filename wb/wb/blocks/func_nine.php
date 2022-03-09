<?php
//чистая прибыль
    if (isset($_GET['rid']) && isset($_GET['bc']))
    {
        echo "<h4 style='margin-left: 10px'><a href='?page=wb&type=9'>Чистая прибыль </a>   № <a href='?page=wb&type=9&rid=$_GET[rid]'>" . $_GET['rid'] . '</a> по штрихкоду: ' . $_GET['bc'] . '</h4>';
    }
    elseif (isset($_GET['rid']))
    {
        echo "<h4 style='margin-left: 10px'><a href='?page=wb&type=9'>Чистая прибыль </a>    № <a href='?page=wb&type=9&rid=$_GET[rid]'>" . $_GET['rid'] . '</a></h4>';
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
    //$correct_lines = json_decode(file_get_contents('update/json/7.json'));
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
                    //if (isset($g->supplier_oper_name) && $g->supplier_oper_name == 'Продажа')
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
        //$g->barcode  = '<a href="./index.php?page=wb&type=9&rid='.$g->realizationreport_id.'&bc='.$g->barcode.'"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">'.$g->barcode .'</a>';
        $g->realizationreport_id2 = $g->realizationreport_id;
       // $g->realizationreport_id = '<a href="./index.php?page=wb&type=9&rid=' . $g->realizationreport_id . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->realizationreport_id . '</a>';

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


    foreach ($tbl_rows as $g)
    {
        /*$save_cost = $db->getOne("SELECT value FROM ss_dops WHERE key_row=?s and key_col=?s", "{$g->realizationreport_id2}", 'save_cost');
        if ($save_cost === false)
        {
            $g->save_cost = $g->save_cost2 = str_replace(',', '.', $LOGIST[$g
                    ->subject_name]) * $g->quantity * 7;
        }
        else
        {
            $g->save_cost = $g->save_cost2 = $save_cost;
        }

        if (!isset($_GET['rid']) && !isset($_GET['bc']))
        {
            $g->save_cost = "<input type='text' key_row='{$g->realizationreport_id2}' psf='save_cost' class='form-control dp_{$kkk}_{$g->barcode}' value='" . $g->save_cost . "'>";
        }
        */
        //$g->itogo_k_oplate = $g->retail_amount - $g->retail_commission - $g->save_cost2 - $g->delivery_rub;

        $g->nalog7 = $g->retail_amount * 0.07;

        /*$sebes = $db->getOne("SELECT sebes FROM sebes_vals WHERE art=?s and user_id=?i", $g->sa_name, $USER['id']);
        $g->sebes = $sebes * $g->quantity;
        $g->pribil = $g->itogo_k_oplate - $g->sebes - $g->nalog7;
        $g->marga = round($g->pribil / $g->sebes * 100, 2);*/

        // SUMS --------
       /* foreach ($g as $gk => $gv)
        {
            $ITOGO_SUMS[$gk] += $gv;
        }*/
    }
$tbl_keys = $pribil_keys;

foreach ($ss_dom_lat as $key => $item) {
    $tbl_keys[$key] = $item;
}

