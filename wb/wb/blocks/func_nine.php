<?php
if ($_GET['type'] == 9)
{

    if (isset($_GET['rid']) && isset($_GET['bc']))
    {
        echo "<h4><a href='?page=wb&type=9'>Все отчеты</a>  / Отчет № <a href='?page=wb&type=9&rid=$_GET[rid]'>" . $_GET['rid'] . '</a> по штрихкоду: ' . $_GET['bc'] . '</h4>';

    }
    elseif (isset($_GET['rid']))
    {
        echo "<h4><a href='?page=wb&type=9'>Все отчеты </a>  / Отчет № <a href='?page=wb&type=9&rid=$_GET[rid]'>" . $_GET['rid'] . '</a></h4>';
    }
    else
    {

        echo "<br><input type=button  onclick='save_sebes(); $(this).fadeOut(); setTimeout(function(){document.location.reload();}, 2000);' class='btn btn-success' value='Сохранить все'>";

        echo '
<script>
function save_sebes()
{
	var dt = "";
	var inps = $("input[psf=save_cost]");
	for (i=0;i<inps.length;i++)
	{
		k = $(inps[i]).attr("key_row");
		dt = dt + k+"@save_cost@"+ $(inps[i]).val() + "|";
	}
	console.log(dt);
	$.post("", {save_chist:1, data:dt}, function (dt)
	{

	})

}
</script>
';

    }

    function read_csv($fl)
    {
        $ROWS = array();
        $handle = fopen($fl, "r");
        while (($data = fgetcsv($handle, 1000, ";")) !== false)
        {
            $ROWS[] = $data;
        }
        fclose($handle);
        return $ROWS;
    }

    $logist = read_csv('logist.csv');
    //unset($logist[0]);
    foreach ($logist as $r)
    {
        $LOGIST[iconv('windows-1251', 'utf-8', $r[1]) ] = iconv('windows-1251', 'utf-8', $r[5]);
    }

    //print_r($LOGIST);


    $last_key = - 1;
    foreach ($tbl_rows as $g)
    {

        if (isset($_GET['rid']) && isset($_GET['bc']))
        {
            if ($g->realizationreport_id != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

        }
        else if (isset($_GET['rid']))
        {
            if ($g->realizationreport_id != $_GET['rid']) continue;

            //if (isset($g->supplier_oper_name) && ($g->supplier_oper_name != 'Продажа')) continue;


            if (isset($keys_bc[$g->barcode]))
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
                        $reps[$keys_bc[$g
                            ->barcode]]->$fieldsum += $g->$fieldsum;
                    }
                    else
                    {

                        $reps[$keys_bc[$g
                            ->barcode]]->$fieldsum -= $g->$fieldsum;

                    }

                }

            }

            if (isset($keys_bc[$g->barcode])) continue;

            //continue;

        }
        else
        {

            if (isset($keys_bc2[$g->realizationreport_id]))
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

                    //if (isset($g->supplier_oper_name) && $g->supplier_oper_name == 'Продажа')
                    if (isset($g->supplier_oper_name) && ($g->supplier_oper_name == 'Продажа' || $fieldsum == 'delivery_rub'))

                    {
                        $reps[$keys_bc2[$g
                            ->realizationreport_id]]->$fieldsum += $g->$fieldsum;
                    }
                    else
                    {

                        $reps[$keys_bc2[$g
                            ->realizationreport_id]]->$fieldsum -= $g->$fieldsum;

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
        $g->realizationreport_id = '<a href="./index.php?page=wb&type=9&rid=' . $g->realizationreport_id . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->realizationreport_id . '</a>';

    }

    $tbl_rows = $reps;

    //echo '<pre>';print_r($keys_bc2);

    /*$pribil_keys = 'realizationreport_id Номер отчета
    rr_dt Дата операции
    //////////////cat Категория
    brand_name Бренд
    subject_name Предмет
    nm_id Артикул
    barcode Баркод
    sa_name Артикул поставщика
    ts_name Размер
    quantity Количество
    ///////retail_price Цена розничная
    retail_amount Сумма продаж(Возвратов)
    retail_commission Сумма комиссии продаж

    save_cost Стоимость хранения

    delivery_rub Стоимость логистики

    itogo_k_oplate Итого к оплате

    sebes Себестоимость

    nalog7 Налоги, УСН доходы 7%
    pribil Чистая прибыль
    marga Маржинальность, %
    speed_back Скорость возврата инвестиций
    ';
    */

    foreach ($tbl_rows as $g)
    {

        $save_cost = $db->getOne("SELECT value FROM ss_dops WHERE key_row=?s and key_col=?s", "{$g->realizationreport_id2}", 'save_cost');
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

        $g->itogo_k_oplate = $g->retail_amount - $g->retail_commission - $g->save_cost2 - $g->delivery_rub;

        $g->nalog7 = $g->retail_amount * 0.07;

        $sebes = $db->getOne("SELECT sebes FROM sebes_vals WHERE art=?s and user_id=?i", $g->sa_name, $USER['id']);

        $g->sebes = $sebes * $g->quantity;

        $g->pribil = $g->itogo_k_oplate - $g->sebes - $g->nalog7;

        $g->marga = round($g->pribil / $g->sebes * 100, 2);

        // SUMS --------
        foreach ($g as $gk => $gv)
        {
            $ITOGO_SUMS[$gk] += $gv;
        }

    }

    if (!isset($_GET['rid']))
    {

        $tbl2_keys = 'realizationreport_id Номер отчета
rr_dt Дата операции
//////////////cat Категория
quantity Количество
/////retail_price Цена розничная
retail_amount Сумма продаж(Возвратов)
retail_commission Сумма комиссии продаж

save_cost Стоимость хранения

delivery_rub Стоимость логистики

itogo_k_oplate Итого к оплате

sebes Себестоимость

nalog7 Налоги, УСН доходы 7%
pribil Чистая прибыль
marga Маржинальность, %
speed_back Скорость возврата инвестиций

';
        $tbl_keys = make_tbl_keys($tbl2_keys);

    }
    else
    {
        $pribil_keys = 'realizationreport_id Номер отчета
rr_dt Дата операции
//////////////cat Категория
brand_name Бренд
subject_name Предмет
nm_id Артикул
barcode Баркод
sa_name Артикул поставщика
ts_name Размер
quantity Количество
/////retail_price Цена розничная
retail_amount Сумма продаж(Возвратов)
retail_commission Сумма комиссии продаж

///save_cost Стоимость хранения

delivery_rub Стоимость логистики

itogo_k_oplate Итого к оплате

sebes Себестоимость

nalog7 Налоги, УСН доходы 7%
pribil Чистая прибыль
marga Маржинальность, %
speed_back Скорость возврата инвестиций
supplier_oper_name Обоснование для оплаты

';
        $tbl_keys = make_tbl_keys($pribil_keys);
    }
}