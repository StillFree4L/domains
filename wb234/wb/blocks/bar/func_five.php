<?php
//отчет реализаций

    if (isset($_GET['rid']) && isset($_GET['bc']))
    {
        echo "<h4 style='margin-left: 10px;'><a href='?page=wb&type=5'>Все отчеты</a>  / Отчет № <a href='?page=wb&type=5&rid=$_GET[rid]'>" . $_GET['rid'] . '</a> по штрихкоду: ' . $_GET['bc'] . '</h4>';
    }
    elseif (isset($_GET['rid']))
    {
        echo "<h4 style='margin-left: 10px;'><a href='?page=wb&type=5'>Все отчеты</a>  / Отчет № <a href='?page=wb&type=5&rid=$_GET[rid]'>" . $_GET['rid'] . '</a></h4>';
    }

    $sale_perc = 0;
    $last_key_bar = - 1;
    foreach ($tbl_rows as $g)
    {
        $g->rr_dt = date('d.m.Y H:i:s', strtotime($g->rr_dt));
        $g->sale_dt = date('d.m.Y H:i:s', strtotime($g->sale_dt));
        $g->order_dt = date('d.m.Y H:i:s', strtotime($g->order_dt));

        if (isset($_GET['rid']) && isset($_GET['bc']))
        {
            if ($g->realizationreport_id != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;
        }
        elseif (isset($_GET['rid']))
        {

            if ($g->realizationreport_id != $_GET['rid']) continue;

            /* if (isset($keys_bc[$g->barcode]))
             {

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
                 foreach ($sums as $fieldsum)
                 {
                     $fieldsum = trim($fieldsum);

                     if (isset($g->supplier_oper_name) && ($g->supplier_oper_name == 'Продажа' || $fieldsum == 'delivery_rub'))
                     {
                         $repa_bar[$keys_bc[$g
                             ->barcode]]->$fieldsum += $g->$fieldsum;
                     }
                     else
                     {
                         $repa_bar[$keys_bc[$g
                             ->barcode]]->$fieldsum -= $g->$fieldsum;
                     }

                 }

             }

             if (isset($keys_bc[$g->barcode])) continue;*/

        }
        else
        {

            if (isset($keys_bc2_bar[$g->realizationreport_id]))
            {

                /* $sums = explode("\n", ('cost_amount
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
 quantity'));*/
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

                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);
                    // $repa_bar[$keys_bc2_bar[$g->realizationreport_id]]->$fieldsum += $g->$fieldsum;

                    if ($g->doc_type_name == 'Возврат' and $fieldsum != 'quantity' and $fieldsum != 'delivery_amount' and $fieldsum != 'return_amount'){
                        $repa_bar[$keys_bc2_bar[$g->realizationreport_id]]->$fieldsum -= $g->$fieldsum;
                    }else{
                        $repa_bar[$keys_bc2_bar[$g->realizationreport_id]]->$fieldsum += $g->$fieldsum;
                    }
                }

            }

            if ($last_code_bar != $g->realizationreport_id)
            {
                $last_code_bar = $g->realizationreport_id;
            }
            else
            {
                continue;
            }
        }

        $repa_bar[] = $g;
        $last_key_bar = count($repa_bar) - 1;
        $keys_bc[$g->barcode] = $last_key_bar;
        $keys_bc2_bar[$g->realizationreport_id] = $last_key_bar;

        $g->barcode = '<a href="?page=wb&type=5&rid=' . $g->realizationreport_id . '&bc=' . $g->barcode . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';

        //$g->realizationreport_id = '<a href="?page=wb&type=5&rid=' . $g->realizationreport_id . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->realizationreport_id . '</a>';

    }
    $tbl_rows_bar = $repa_bar;
