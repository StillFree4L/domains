<?php

require_once('blocks/func_key.php');
require_once('blocks/func_tbl_keys.php');
//------------------------------------------------------------------------------------------

if (!isset($_GET['dt'])) $_GET['dt'] = date('Y-m-d', time());
if (!isset($_GET['type'])) $_GET['type'] = 2;

if (trim($USER['wb_key']) != '')
{
    require_once('blocks/func_api.php');

    $r = $r0 = $buf2[1];
    $r = json_decode($r);

    $tbl_rows = $orig_tbl_rows = $r;
}

include('head.php');
?>

<script type="text/javascript">
    setTimeout(() => {$.get("/wb/load.php?type="+<?=$_GET['type']?>, function (dt){});},2*1000);
    setInterval(() => {$.get("/wb/load.php?type="+<?=$_GET['type']?>, function (dt){});},20*1000);
    setTimeout(() => {$.get("/wb/valid.php", function (dt){
        document.querySelectorAll('label#api_old')[0].innerHTML = 'Ключ api старый: '+JSON.parse(dt).data.url;
        document.querySelectorAll('label#api_new')[0].innerHTML = 'Ключ api новый: '+JSON.parse(dt).data.url_new;
        document.querySelectorAll('label#api_supplier')[0].innerHTML = 'Ключ поставщика: '+JSON.parse(dt).data.url_supplier;
    });},3*1000);
</script>

<div class="panel panel-default" >
    <div class="panel-heading"><h4>Продажи и заказы Wildberries</h4>
        <div class="dropdown" style="z-index: 99;">
            <button onclick="myFunction()" class="dropbtn"><i class="fa fa-key dropbtn"></i></button>
            <div id="myDropdown" class="dropdown-content">
                <form method="post" action="index.php">
                    <fieldset>
                        <p><label id="api_new" for="api">Ключ api новый: <font color="coral">проверка...</font></label><input style="width: 100%" type="text" name="key1" id="api" placeholder="<?=($lines[0] ? 'введен' : 'не введен')?>"></p>
                        <p><label id="api_old" style="padding-top: 5px" for="stats">Ключ api старый: <font color="coral">проверка...</font></label><input style="width: 100%" type="text" name="key2" id="stats" value="<?=$lines[1]?>"></p>
                        <p><label id="api_supplier" style="padding-top: 5px" for="supplierId">Ключ поставщика: <font color="coral">проверка...</font></label><input style="width: 100%" type="text" name="key3" id="supplierId" value="<?=$lines[2]?>"></p>
                    </fieldset>
                    <p  style="padding-top: 5px"><input type="submit" value="Изменить"></p>
                </form>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
            <div class="panel-body">

<?php

if (isset($_GET['dt1'])) $dop_dts_range = '&dt1=' . $_GET['dt1'] . '&dt2=' . $_GET['dt2'];
$tps_res = [2 => 'Заказы', 1 => 'Продажи', /*3 => 'Отмененные заказы', 4 => 'Отмененные продажи',*/ 10 => 'Возврат', 5 => 'Отчеты по реализации', 6 => 'Склад', 7 => 'Поставки', 8 => 'Себестоимость', 9 => 'Чистая прибыль'];

foreach ($tps_res as $key => $value)
{
    $pressed = '';
    if ($key == $_GET['type']) $pressed = 'btn-success';
    echo "<a  href='?page=wb&type=$key&dt=" . $_GET['dt'] . "&$dop_dts_range' class='btn $pressed' style='rfloat: right; display: inline-block; qmargin: 0px 5px; border: 1px solid #ccc; '><b>$value</b> </a> &nbsp;";
}



if ($_GET['type'] != 5 && $_GET['type'] != 6 && $_GET['type'] != 8 && $_GET['type'] != 9)
{

    echo '<hr>';

    $stats_res = ['Сегодня' => date('Y-m-d', time()) , 'Вчера' => date('Y-m-d', time() - 60 * 60 * 24) , '7 дней' => date('Y-m-d', time() - 60 * 60 * 24 * 7) , '30 дней' => date('Y-m-d', time() - 60 * 60 * 24 * 30) , '90 дней' => date('Y-m-d', time() - 60 * 60 * 24 * 90) ];
    foreach ($stats_res as $key => $value)
    {
        $pressed = '';
        if ($value == $_GET['dt'] && !isset($_GET['dt1'])) $pressed = 'btn-warning';
        echo "<a  href='?page=wb&type=$_GET[type]&dt=" . $value . "' class='btn $pressed' style='border: 1px solid #ccc; '><b>$key</b> </a> &nbsp;";
    }

?>

<input type="text" id="dd1" class="dsingle ddd form-control" name="dt1" value="<?=$_GET['dt1']; ?>"  placeholder="">
<input type="text" id="dd2" class="dsingle ddd form-control" name="dt2" value="<?=$_GET['dt2']; ?>"  placeholder="">

<a href='#' onclick="go_filtr(); return false;" class='btn' style='border: 1px solid #ccc; '>Фильтровать </a>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>


<script type="text/javascript">
   flatpickr(".dsingle", {
        "locale": "ru" , // locale for this instance only,
        enableTime: false,
        dateFormat: "Y-m-d",
    } );

function go_filtr() {
	a = '?page=wb&type=<?=$_GET['type']; ?>&dt1='+$('#dd1').val()+'&dt2='+$('#dd2').val();
	document.location.href = a;

}
</script>

<?php
}

// bar option start
if (in_array($_GET['type'],[2,1,10])){
    if (($_GET['dt'] == date('Y-m-d')) or ($_GET['dt2'] == date('Y-m-d') and $_GET['dt1'] == date('Y-m-d'))) {
        $dt1_bar = date("Y-m-d", strtotime("-1 DAY"));
    } else {
        $dt1_bar = date('Y-m-d', strtotime($_GET['dt']) - (strtotime(date('d-m-Y')) - strtotime($_GET['dt'])));
    }

    if ($_GET['dt'] and !$_GET['dt1']) {
        $dt2_bar = $dt1_bar_org = $_GET['dt'];
        $dt2_bar_org = date('Y-m-d');
        if ($_GET['dt'] == date('Y-m-d')) {
            $dt1_bar = date("Y-m-d", strtotime("-1 DAY"));
        } else {
            $dt1_bar = date('Y-m-d', strtotime($_GET['dt']) - (strtotime(date('d-m-Y')) - strtotime($_GET['dt'])));
        }
    } else {
        $dt2_bar = $dt1_bar_org = $_GET['dt1'];
        $dt2_bar_org = $_GET['dt2'];
        if ($_GET['dt1'] == date('Y-m-d') and $_GET['dt2'] == date('Y-m-d')) {
            $dt1_bar = date("Y-m-d", strtotime("-1 DAY"));
        } else {
            $dt1_bar = date('Y-m-d', strtotime($_GET['dt1']) - (strtotime(date('d-m-Y')) - strtotime($_GET['dt1'])));
        }
    }
    require_once('blocks/func.php');
}
// bar option end

// filter
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
        $cdt = $g->date;
        $cdt = $g->lastChangeDate;
        $last_key = - 1;

        if ($_GET['type'] == 3) $cdt = $g->cancel_dt;
        if ($_GET['type'] == 4) $cdt = $g->lastChangeDate;
        if ($_GET['type'] == 10 and ($cdt == $g->cancel_dt or $g->doc_type_name == 'Возврат')) {
            $cdt = $g->date;
            $g->qualification = 'Отмененные заказы';
        }
        elseif($_GET['type'] == 10) {
            //$cdt = $g->date;
            $cdt = $g->lastChangeDate;
            $g->qualification = 'Отмененные продажи';
        }
        if ($_GET['type'] == 1) {
           // $cdt = $g->lastChangeDate;
           $cdt = $g->date;
        }
        if ($_GET['type'] == 2) {
            $cdt = $g->date;
        }

            if (isset($_GET['dt1']))
            {
                if (strtotime($cdt) >= strtotime($_GET['dt1']) && strtotime($cdt) <= strtotime($_GET['dt2']) + 60 * 60 * 24) $flag = 0;
            }
            else
            {
                if ($stats_res['Вчера'] == $_GET['dt'])
                {
                    if (date('Y-m-d', strtotime($cdt)) == date('Y-m-d', strtotime($_GET['dt']))) $flag = 0;
                }
                else
                {
                    if (strtotime($cdt) >= strtotime($_GET['dt'])) $flag = 0;
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
            if (isset($keys_bc[$g->odid]))
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
                    $rows_after_date[$keys_bc[$g->odid]]->$fieldsum += $g->$fieldsum;
                }
            }
            if (isset($keys_bc[$g->odid]))continue;
        }

        $g->lastChangeDate = date('d.m.Y H:i:s', strtotime($g->lastChangeDate));
        $g->date = date('d.m.Y H:i:s', strtotime($g->date));



        $rows_after_date[] = $g;
        if ($_GET['type'] == 10) {
            $last_key = count($rows_after_date) - 1;
            $keys_bc[$g->odid] = $last_key;
            $keys_bc2[$g->odid] = $last_key;
        }
    }

    $tbl_rows = $rows_after_date;
}

    //var_dump($PRICE_SUM);
//-------------------------------------------------------------------

if ($_GET['type'] == 5 && !isset($_GET['rid']) && !isset($_GET['bc']))
{
    $tbl_keys = make_tbl_keys('realizationreport_id Номер отчета
rr_dt Дата операции
quantity Количество продаж
rid Уникальный идентификатор позиции заказа
retail_price Цена розничная
retail_amount Сумма продаж(Возвратов)
sale_percent Согласованная скидка
storage_cost стоимость хранения
acceptance_fee стоимость платной приемки
other_deductions прочие удержания
commission_percent Процент комиссии
retail_price_withdisc_rub Цена розничная с учетом согласованной скидки
ppvz_for_pay К перечислению Продавцу за реализованный Товар
ppvz_vw Вознаграждение Вайлдберриз (ВВ), без НДС
ppvz_vw_nds НДС с Вознаграждения Вайлдберриз
delivery_amount Кол-во доставок
return_amount Кол-во возвратов
delivery_rub Стоимость логистики
product_discount_for_report Согласованный продуктовый дисконт
supplier_promo Промокод
ppvz_spp_prc Скидка постоянного покупателя
total_payable Итого к оплате');
}

?>
	    </div>
	</div>

<?php if (in_array($_GET['type'],[2,1,10])){
    require_once('blocks/bar.php');
} ?>

<div id='stat' style="padding: 0 10px;"></div>

<?php

if ($_GET['type'] == 5){
    require_once('blocks/func_five.php');
}

//=================================================================================

// поставки
if ($_GET['type'] == 7){
    require_once('blocks/func_seven.php');
}

//=================================================================================


// Склад
if ($_GET['type'] == 6)
{

    if (isset($_GET['rid']) && isset($_GET['bc']))
    {
        echo "<h4><a href='?page=wb&type=7&dt=" . $_GET['dt'] . "''>Все отчеты</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=" . $_GET['dt'] . "'>" . $_GET['rid'] . '</a> по баркоду: ' . $_GET['bc'] . '</h4>';

    }
    elseif (isset($_GET['f1']))
    {
        echo "<h4><a href='?page=wb&type=6&dt=" . $_GET['dt'] . "''>Склад</a>  / Баркод № <a href='?page=wb&type=6&f1=$_GET[f1]&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a></h4>';
    }

    if ($tbl_rows){
        $last_key = -1;
        $sums = explode("\n", trim('quantity
quantityFull
quantityNotInOrders
inWayToClient
inWayFromClient
Price
speed
speed_7
speed_7_order
speed_30
price_min_discount
ref_7
refa_7
ref_30
refa_30
Discount
coun'));
        //$sim_arr = [];
        foreach ($tbl_rows as $g) {
    //var_dump($g);

            $g->coun = 1;

            if ($g->Discount == 0) {
                $g->Discount = 100 - floor(($g->price_min_discount * 100) / $g->Price);
            }

            if (!$g->price_min_discount or $g->price_min_discount == 0 or $g->price_min_discount == null) {
                $g->price_min_discount = $g->Price - (($g->Price * $g->Discount) / 100);
            }

            $g->lastChangeDate = date('d.m.Y H:i:s', strtotime($g->lastChangeDate));
            if (isset($_GET['f1']) && isset($_GET['bc'])) {
                if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

            } else if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;
            } else {
               /* var_dump($keys_bc[$g->barcode]);
                if ($g->barcode == '2019568401004'){
                    var_dump($keys_bc[$g->barcode]);
                    var_dump($reps[32]);
                }*/
                if (isset($keys_bc[$g->barcode])) {


                    foreach ($sums as $fieldsum) {
                        $fieldsum = trim($fieldsum);

                     //   if ($g->v == 'new' and $fieldsum=='quantity') {
                     //       continue;
                       // if ($g->barcode=='2005207438005'){var_dump($g->quantityFull);}
                       // }else{

                        if ($fieldsum != 'speed'){
                            $reps[$keys_bc[$g->barcode]]->$fieldsum += $g->$fieldsum;
                        }else{
                            $reps[$keys_bc[$g->barcode]]->$fieldsum += preg_replace("/[^0-9]/", '', $g->$fieldsum);
                        }//preg_replace("/[^0-9]/", '', $g->$fieldsum);
                     //   }
                    }
                    $reps[$keys_bc[$g->barcode]]->isSupply = $g->isSupply;
                    $reps[$keys_bc[$g->barcode]]->isRealization = $g->isRealization;
                    $reps[$keys_bc[$g->barcode]]->daysOnSite = $g->daysOnSite;
                    $reps[$keys_bc[$g->barcode]]->SCCode = $g->SCCode;
                    /*if ($g->barcode == '2011333590043'){
                        echo '<pre>';var_dump($reps[$keys_bc[$g->barcode]]);
                    }*/
                }

                if (isset($keys_bc[$g->barcode])) continue;
            }

            $reps[] = $g;

            $last_key = count($reps) - 1;
            $keys_bc[$g->barcode] = $last_key;
            $keys_bc2[$g->barcode] = $last_key;

            $g->barcode = '<a href="./index.php?page=wb&type=6&f1=' . $g->barcode . '&dt=' . $_GET['dt'] . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';

        }
        $tbl_rows = $reps;
    }
}
//=================================================================================


// Заказы - группировка
if ($_GET['type'] == 2 || $_GET['type'] == 3)
{
    if (isset($_GET['rid']) && isset($_GET['bc'])) {
        // echo "<h4><a href='?page=wb&type='.$_GET['type'].'&dt=". $_GET['dt'] ."''>Все отчеты</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=" .$_GET['dt']. "'>" . $_GET['rid'] . '</a> по штрихкоду: ' .$_GET['bc'] . '</h4>';
    }
    elseif (isset($_GET['f1'])) {
        echo "<hr><h5 style='margin-left: 10px;'><a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>Заказы</a>  / Баркод № <a href='?page=wb&type=" . $_GET['type'] . "&f1={$_GET['f1']}&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a></h4>';
    }
    elseif (isset($_GET['f2'])) {
        echo "<hr><h5 style='margin-left: 10px;'><a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>Заказы</a>  / " . (isset($tbl_keys[$_GET['f2']]) ? mb_ucfirst($tbl_keys[$_GET['f2']]) : '') . ": <a href='?page=wb&type=" . $_GET['type'] . "&f2={$_GET['f2']}&dt=" . $_GET['dt'] . "&f3={$_GET['f3']}'>" . $_GET['f3'] . '</a></h4>';
    }

        $tbl_rows = array_reverse($tbl_rows);

        foreach ($tbl_rows as $g) {
            $g = (object)$g;

            if ((!$g->isCancel && $_GET['type'] == 3) || (!$g->isCancel && $g->oblast && $_GET['type'] == 10)) continue;
            //if ($g->isCancel && $_GET['type'] == 2) continue;
            // var_dump($g);


            if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;

            } else {
                if (isset($keys_bc[$g->barcode])) {

                    // $sums = explode("\n", trim('

// quantity
// '));


                    //if ($g->isCancel==1) {$reps[$keys_bc[$g->barcode]]->RED = 1;}
                    // var_dump($g);

                    /*foreach ($sums as $fieldsum)
                    {
                        $fieldsum = trim($fieldsum);

                        $reps[$keys_bc[$g
                            ->barcode]]->$fieldsum += $g->$fieldsum;
                        //$reps[ $keys_bc[$g->barcode]  ]->$fieldsum .= ' - '.$g->$fieldsum;

                    }*/

                }

                // if (isset($keys_bc[$g->barcode])) continue;

            }

            $reps[] = $g;
            $last_key = count($reps) - 1;
            $keys_bc[$g->barcode] = $last_key;
            $keys_bc2[$g->barcode] = $last_key;

            $g->barcode = '<a href="./index.php?page=wb&type=' . $_GET['type'] . '&f1=' . $g->barcode . '&dt=' . $_GET['dt'] . $dop_dts_range . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';

        }

        //echo '<pre>';var_dump($keys_bc);var_dump($tpl_rows, $reps);
        $tbl_rows = $reps;
}

// Продажи - группировка
if ($_GET['type'] == 1 || $_GET['type'] == 4 || $_GET['type'] == 10) {
   // var_dump($tbl_rows);
    if (isset($_GET['rid']) && isset($_GET['bc'])) {
        //  echo "<h4><a href='?page=wb&type='.$_GET['type'].'&dt=". $_GET['dt'] ."''>Все отчеты</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=" .$_GET['dt']. "'>" . $_GET['rid'] . '</a> по штрихкоду: ' .$_GET['bc'] . '</h4>';
    }
    elseif (isset($_GET['f1'])) {
        echo "<hr><h5 style='margin-left: 10px;'><a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>Продажи</a>  / Баркод № <a href='?page=wb&type=" . $_GET['type'] . "&f1={$_GET['f1']}&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a></h4>';
    }
    elseif (isset($_GET['f2'])) {
        echo "<hr><h5 style='margin-left: 10px;'><a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>Продажи</a>  / " . (isset($tbl_keys[$_GET['f2']]) ? mb_ucfirst($tbl_keys[$_GET['f2']]) : '') . ": <a href='?page=wb&type=" . $_GET['type'] . "&f2={$_GET['f2']}&dt=" . $_GET['dt'] . "&f3={$_GET['f3']}'>" . $_GET['f3'] . '</a></h4>';
    }

            $tbl_rows = array_reverse($tbl_rows);

        foreach ($tbl_rows as $g) {
            //var_dump($tbl_rows);
            if (($g->totalPrice > 0 && $_GET['type'] == 4) || ($g->totalPrice > 0 and $_GET['type'] == 10 and /*(*/
                    $g->forPay /*or $g->finishedPrice)*/)) continue;
            //if ($g->totalPrice < 0 && $_GET['type'] == 1) continue;
            //var_dump($g);

            if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;
            }
            /* elseif($_GET['type'] == 1)
             {
                 if (isset($keys_bc[$g->barcode]))
                 {
                     $sums = explode("\n", trim('
     quantity
     totalPrice
     '));
                     if ($g->forPay < 0) {$reps[$keys_bc[$g->barcode]]->RED = 1;}
                     foreach ($sums as $fieldsum)
                     {
                         $fieldsum = trim($fieldsum);
                         $reps[$keys_bc[$g
                             ->barcode]]->$fieldsum += $g->$fieldsum;
                         //$reps[ $keys_bc[$g->barcode]  ]->$fieldsum .= ' - '.$g->$fieldsum;

                     }
                 }
                  if (isset($keys_bc[$g->barcode])) continue;
             }*/
            $reps[] = $g;
            /* $last_key = count($reps) - 1;
             $keys_bc[$g->barcode] = $last_key;
             $keys_bc2[$g->barcode] = $last_key;*/
            $g->barcode = '<a href="./index.php?page=wb&type=' . $_GET['type'] . '&f1=' . $g->barcode . '&dt=' . $_GET['dt'] . $dop_dts_range . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';
        }
        //echo '<pre>';var_dump($keys_bc);var_dump($tpl_rows, $reps);
        $tbl_rows = $reps;
}
////////////////////////////////////////////////////////////////////

//=================================================================================

// Себестоимость
if ($_GET['type'] == 8){
    require_once('blocks/func_eight.php');
}

//=====================================================================================================

// Чистая прибыль
if ($_GET['type'] == 9){
    require_once('blocks/func_nine.php');
}

//=================================================================================
//<table class="items table table-striped" style="margin: 10px; font-size: 11px;" >
?>


               <thead>
<?php
/*
if ($_GET['type'] == 8)
{

    echo "<tr><td colspan=9></td><th  colspan=" . (count($ss_dop_fields) - 1) . " class='warning' style='font-size: 14px; text-align:center;' >Общие затраты на поставку (<a href='#' onclick='$(\"#set_fields_div\").hide(); $(\"#dop_fields_div\").show(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'>Редактировать список полей</a>)</th></tr>";

}

if ($_GET['type'] == 7)
{
    if (!isset($_GET['rid']))
    {
        echo "<tr><td colspan=13></td><th  colspan=" . (count($ss_dop_fields) - 1) . " class='warning' style='font-size: 14px; text-align:center;' >Общие затраты на поставку (<a href='#' onclick='$(\"#set_fields_div\").hide(); $(\"#dop_fields_div\").show(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'>Редактировать список полей</a>)</th></tr>";
    }
    else
    {
        echo "<tr><td colspan=11></td><th  colspan=" . (count($ss_dop_fields) - 1) . " class='warning' style='font-size: 14px; text-align:center;' >Общие затраты на поставку (<a href='#' onclick='$(\"#set_fields_div\").hide(); $(\"#dop_fields_div\").show(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'>Редактировать список полей</a>)</th></tr>";

    }
}
*/

?>

<tr>

		<!-- <th></th> -->

		<?php /* foreach ($tbl_keys as $k => $str)
{

    //if (($_GET['type'] == 1 || $_GET['type'] == 2) && $k == 'lastChangeDate') continue;



?>
                  <th ><?=$str; ?></th>
		<?php
} */ ?>

</tr>
               </thead>


<div id="grid<?php echo $_GET['type'] ? $_GET['type'] : ''; ?>" class="grid"></div>

<?php

$title = '';
foreach ($tps_res as $key => $value) {
    if ($key == $_GET['type'])
        $title = $value;
}

$fields = array_keys($tbl_keys);
array_unshift($fields, 'image');


/*
if ($_GET['type'] == 8){
    $ssums = explode("\n", ('techSize
ss_all
ss_one
quantity'));
    $ssums_dops = explode("\n", ($ss_dop));
    $i = 0;
    $fields_json = array();
    foreach ($fields as $field) {
        $k = 0;
        foreach ($ssums as $ssum) {
            $ssum = trim($ssum);
            foreach ($ssums_dops as $ssums_dop) {
                $ssums_dop = ru2Lat(trim($ssums_dop));
                if ($ssum == $field or $ssums_dop == $field or $field == 'Stoimosty_edinicy_tovara') {
                    $fields_json[$i]['name'] = $field;
                    $fields_json[$i]['type'] = 'int';
                    $k++;
                }
            }
        }
        if ($k == 0) {
            $fields_json[] = $field;
        }
        $i++;
    }
    $fields = $fields_json;
}
*/

//столбцы таблиц
$columns[] = (object) [
    'text' => '<span data-qtip="Изображение">Изображение</span>',
    'id' => 'image',
    'dataIndex' =>
    'image',
    'sortable' => false,
    'hideable' => true,
    'width' => 45
];
if ($_GET['type'] == 9) {
    $columns[0]->width = 80;
}
if ($_GET['type'] == 5 and !$_GET['rid']){
    $columns[0]->hidden = true;
}
    require_once('blocks/func_columns.php');

    $data = [];
    $data_rows = [];

    $tbl_rows = array_reverse($tbl_rows);

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

function preg_barcode($barcode){
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

    if (count($tbl_rows)) {
        foreach ($tbl_rows as $g) {

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
            foreach ($sums_null as $fie) {
                $fie = trim($fie);
                if (!$g->$fie) {
                    $g->$fie = '0';
                }
            }
            //кол-во по умолч 1
            if (!$g->quantity and $_GET['type'] != 5) {
                $g->quantity = 1;
            }

            if (isset($_GET['f2']) && isset($_GET['f3']) && $g->{$_GET['f2']} <> $_GET['f3']) {
                continue;
            }

            $data_cols = [];

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
            // if ($flag == 1) continue;

            // $CNT_SUM += $g->quantity;

            //   var_dump($PRICE_SUM);

            if (($_GET['type'] == 1 and $g->forPay >= 0) || $_GET['type'] == 4) {
                $PRICE_SUM += $g->forPay * $g->quantity;
            }
            elseif ($_GET['type'] == 2 and $g->totalPrice >= 0 and $g->isCancel != 1) {
            // ЗАКАЗЫ
                 $PRICE_SUM += $g->finishedPrice;
               // $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
            }
            elseif ($_GET['type'] == 10) {
                $PRICE_SUM += $g->finishedPrice;
            }
            elseif(!in_array($_GET['type'],[1,2,10])) {
                $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
            }

            $data_cols['refund_color'] = $REFUND_COLOR;

            // image
            if ($_GET['type'] == 5 || $_GET['type'] == 9 || $_GET['type'] == 7) {

                if (isset($g->nmId)) $g->nm_id = $g->nmId;
                $img = 'https://images.wbstatic.net/small/new/' . substr($g->nm_id, 0, -4) . '0000/' . $g->nm_id . '.jpg';
                //var_dump($img);
                if (!isset($_GET['rid']) and $_GET['type'] != 5 and $_GET['type'] != 7 and $_GET['type'] != 9) {
                    $img = '';
                } else $img = '<a href="https://www.wildberries.ru/catalog/' . $g->nm_id . '/detail.aspx?targetUrl=MS" target=_blank><img src="' . $img . '" style="height: 40px;"></a>';

            } else {
                $img = 'https://images.wbstatic.net/small/new/' . substr($g->nmId, 0, -4) . '0000/' . $g->nmId . '.jpg';
                $img = '<a href="https://www.wildberries.ru/catalog/' . $g->nmId . '/detail.aspx?targetUrl=MS" target=_blank><img src="' . $img . '" style="height: 40px;"></a>';
            }

            $data_cols['image'] = $img;

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
                    $data_cols[$k] = $g->$k;
                }
            }
            $data_rows[] = (object)$data_cols;
        }
        $CNT_SUM = count($data_rows);
    }
    $data = $data_rows;

if (in_array($_GET['type'],[5,9]) and !$_GET['rid']){
    $correct_lines = file_read('5');
    //$correct_lines = json_decode(file_get_contents('update/json/5.json'));
}elseif(in_array($_GET['type'],[7,8,9])){
    $correct_lines = file_read('7');
    //$correct_lines = json_decode(file_get_contents('update/json/7.json'));
}


$sums_report = explode("\n", 'totalPrice
finishedPrice
cost_amount
retail_price
retail_amount
retail_commission 
sale_percent 
commission_percent 
for_pay_nds 
forPay 
ppvz_for_pay
delivery_amount 
return_amount 
delivery_rub 
product_discount_for_report 
retail_price_withdisc_rub
ppvz_sales_commission
ppvz_reward
supplier_promo 
supplier_spp 
ppvz_spp_prc
ppvz_vw
ppvz_vw_nds
total_payable
ss_one
ss_all
Obschaya_sebestoimosty_edinicy_tovara
Obschaya_sebestoimosty_s_uchetom_kolichestva
Srednyaya_sebestoimosty_edinicy
Obschaya_sebestoimosty
Price
price_min_discount
speed_7_order
speed_7
speed_30
Discount
priceWithDisc
nalog7
all_cost
pribil
marga');
// 0 умолчанию
    $dat_null = explode("\n", 'speed
speed_7
speed_7_order
speed_30
inWayToClient
inWayFromClient
price_min_discount
daysOnSite
upush
refund_7
refund_30
quantityFull
quantityNotInOrders
nalog7
totalPrice
finishedPrice
quantity
cost_amount
retail_price
retail_amount
retail_commission
sale_percent
commission_percent
forPay
ppvz_for_pay
delivery_amount
return_amount
delivery_rub
product_discount_for_report
retail_price_withdisc_rub
ppvz_sales_commission
ppvz_reward
supplier_promo
supplier_spp
ppvz_spp_prc
ppvz_vw
ppvz_vw_nds
total_payable
ss_one
ss_all
Obschaya_sebestoimosty_edinicy_tovara
Obschaya_sebestoimosty_s_uchetom_kolichestva
Srednyaya_sebestoimosty_edinicy
Obschaya_sebestoimosty
Price
Discount
priceWithDisc
nalog7
all_cost
pribil
ppvz_kvw_prc_base
ppvz_kvw_prc
marga');

foreach ($ss_dom_lat as $k=>$item) {
    $dat_null[] = $k;
}
// ----
if (in_array($_GET['type'],[1,2,10,6,9])){
    $dat_minus = explode("\n", 'incomeID
number
orderId
countryName
oblastOkrugName
regionName
saleID
odid
oblast
ppvz_office_id
ppvz_office_name
ppvz_supplier_name
ppvz_inn
declaration_number
incomeId
category
status
office_name');
}

if (in_array($_GET['type'],[7,8])) {
    foreach (explode("\n", $ss_dop) as $item){
        $item = ru2Lat(trim($item));
        $sum_s_r[] = $item;
        if (!$_GET['f1'] and !$_GET['rid']){$sums_report[] = $item;}
    }
    $sums_report[] = $sum_s_r[] = 'Stoimosty_edinicy_tovara';
}

if ($ss_dop){
    $sum_m = explode("\n", ru2Lat($ss_dop));
}

if($data){
    $l=0;
    foreach ($data as $d_k_k => $dat) {
        if ($_GET['type'] == 6){
            if (!$_GET['f1'] and !$_GET['f2']){
                $dat->quantity += $dat->fbs;
            }
            if (!$dat->refund_7){
                $dat->refund_7 = '0%<br>0 / 0';
            }
            if (!$dat->refund_30){
                $dat->refund_30 = '0%<br>0 / 0';
            }
            if (strpos($dat->speed, '~') === false){
                $dat->speed = '~ '.$dat->speed;
            }
        }
        if (in_array($_GET['type'],[5,9]) and !$_GET['rid'] and $correct_lines) {
            foreach ($correct_lines as $correct_line) {
                if ($dat->realizationreport_id == $correct_line->realizationreport_id) {
                    $data[$l]->storage_cost = $correct_line->storage_cost;
                    $data[$l]->acceptance_fee = $correct_line->acceptance_fee;
                    $data[$l]->other_deductions = $correct_line->other_deductions;
                }
            }
            $dat->total_payable = $dat->ppvz_for_pay - ($dat->delivery_rub + $dat->storage_cost + $dat->other_deductions + $dat->acceptance_fee);
        }

        if ($_GET['rid'] and in_array($_GET['type'],[9])){
            foreach ($correct_lines as $key => $correct_line) {
                if ($dat->incomeId == $correct_line->incomeId and $dat->sa_name == $correct_line->supplierArticle and $dat->barcode == $correct_line->barcode) {
                    foreach ($correct_line as $k=>$item) {
                        if (array_key_exists($k,$ss_dom_lat)){
                            $data[$l]->$k = $item;
                            $data[$l]->ss_one += $item;
                        }
                    }


                }
            }
            $data[$l]->all_cost = $data[$l]->delivery_rub + $data[$l]->ppvz_vw + $data[$l]->ppvz_vw_nds + $data[$l]->nalog7 + $data[$l]->ss_one;
                 $data[$l]->pribil = $data[$l]->retail_amount - $data[$l]->all_cost;
                 $data[$l]->marga = ($data[$l]->pribil / $data[$l]->all_cost)*100;

                 $data[$l]->total_payable = $data[$l]->ppvz_for_pay - $data[$l]->delivery_rub;

        }elseif (!$_GET['rid'] and in_array($_GET['type'],[9])){
            foreach ($dat as $d_k => $da) {
                if (array_key_exists($d_k,$ss_dom_lat)) {
                    $data[$l]->ss_one += $da;
                }
            }
            $data[$l]->all_cost = $data[$l]->storage_cost + $data[$l]->acceptance_fee + $data[$l]->other_deductions + $data[$l]->delivery_rub + $data[$l]->ppvz_vw + $data[$l]->ppvz_vw_nds + $data[$l]->nalog7 + $data[$l]->ss_one;
            $data[$l]->pribil = $data[$l]->retail_amount - $data[$l]->all_cost;
            $data[$l]->marga = ($data[$l]->pribil / $data[$l]->all_cost)*100;
        }

        if (in_array($_GET['type'],[7,8])) {
            if ($_GET['rid'] or $_GET['f1']) {
                foreach ($correct_lines as $key => $correct_line) {
                    if ((preg_barcode($dat->incomeId) == $correct_line->incomeId or $dat->incomeId == $correct_line->incomeId)
                        and (preg_barcode($dat->supplierArticle) == $correct_line->supplierArticle or $dat->supplierArticle == $correct_line->supplierArticle)
                        and $dat->barcode == $correct_line->barcode) {

                        foreach ($correct_line as $key => $datumm) {
                            if (($key == 'Stoimosty_edinicy_tovara' or in_array($key, $sum_m) or in_array('_' . $key, $sum_m))
                                and ($key != 'id' and $key != 'incomeId' and $key != 'barcode' and $key != 'supplierArticle')) {

                                $data[$l]->$key = $datumm;
                                if ($_GET['type'] == 7) $data[$l]->Obschaya_sebestoimosty_edinicy_tovara += $datumm;
                                elseif ($_GET['type'] == 8) $data[$l]->ss_one += $datumm;
                            }
                        }
                    }
                }
                if ($_GET['type'] == 7) $data[$l]->Obschaya_sebestoimosty_s_uchetom_kolichestva = $data[$l]->quantity * $data[$l]->Obschaya_sebestoimosty_edinicy_tovara;
                elseif ($_GET['type'] == 8) $data[$l]->ss_all = $data[$l]->quantity * $data[$l]->ss_one;

            } elseif (!$_GET['rid'] and !$_GET['f1']) {
                foreach ($dat as $d_k => $da) {
                    if ($d_k == 'Stoimosty_edinicy_tovara' or in_array($d_k, $sum_m) or in_array('_' . $d_k, $sum_m)) {
                        //echo '<pre>';var_dump($d_k.' - '.$da);
                        if ($_GET['type'] == 7) $data[$l]->Obschaya_sebestoimosty += $da;
                        elseif ($_GET['type'] == 8) $data[$l]->ss_all += $da;
                    }
                }

                if ($_GET['type'] == 7) $data[$l]->Srednyaya_sebestoimosty_edinicy = $data[$l]->Obschaya_sebestoimosty / $data[$l]->quantity;
                elseif ($_GET['type'] == 8) $data[$l]->ss_one = $data[$l]->ss_all / $data[$l]->quantity;
            }
        }
        foreach ($dat as $d_k => $da) {
            foreach ($sums_report as $fieldsum) {
                $fieldsum = trim($fieldsum);
                if (is_numeric($da) and $d_k == $fieldsum) {
                    if (in_array($_GET['type'],[1,10]) and $da < 0){$da *= -1;}
                        $data[$l]->$d_k = number_format((string)$da, 2, '.', ' ');

                }
            }
            if ($d_k == 'cancel_dt' and ($da == '0001-01-01 00:00:00' or $da == null or !$da)) {
                $data[$l]->$d_k = '';
            }
        }
        if ($dat_null){
            foreach ($dat_null as $kitem) {
                $kitem = trim($kitem);
                if (!$dat->$kitem || $dat->$kitem==null) {
                    $dat->$kitem = 0;
                }
            }
        }
        if ($dat_minus){
            foreach ($dat_minus as $kitem) {
                $kitem =trim($kitem);
                if (!$dat->$kitem){
                    $dat->$kitem = '---';
                }
            }
        }
        $l++;
    }
    if (in_array($_GET['type'],[5,7,8,9])) {
        $l = 0;
        $arrCancel = ['date','dateClose','lastChangeDate','refund_color','supplierArticle', 'barcode', '', 'save', "subject", "category", "brand", "warehouseName", "status",
        'realizationreport_id','rr_dt','image','rid','rrd_id','gi_id','subject_name','nm_id','brand_name','sa_name','doc_type_name','office_name','supplier_oper_name','order_dt','sale_dt',
            'shk_id','gi_box_type_name','ppvz_office_id','ppvz_office_name','ppvz_supplier_id','ppvz_supplier_name','ppvz_inn'];

        foreach ($data as $d_k_k => $dat) {
            if (in_array($_GET['type'],[7,8,9])){
                foreach ($dat as $gk => $gv) {
                    if (!in_array($gk, $arrCancel)) {
                        if (($_GET['f1'] and $gk != 'incomeId') or (!$_GET['f1'] and !$_GET['f2']) or ($_GET['f2'] and $gk != 'incomeId')){
                            if ($_GET['rid'] and $_GET['type']==9 and $gk == 'incomeId'){$ITOGO_SUMS['incomeId'] = 'Итого:';continue;}

                            $ITOGO_SUMS[$gk] += intval(str_replace(" ", "", $gv));

                        }
                    }
                }
            }
            if ($_GET['type']==8 and !$_GET['f1'] and !$_GET['f2']){
                $data[$l]->incomeId = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&f1=".$data[$l]->supplierArticle."&dt=".$_GET['dt']."'>".$data[$l]->incomeId." шт</a>";
            }
            $l++;
        }

if (in_array($_GET['type'],[7,8,9])){
    if($_GET['type']!=9){$ITOGO_SUMS["incomeId"] = "Итого:";}
    else{$ITOGO_SUMS["image"] = "Итого:";}
}
        if ($ITOGO_SUMS and in_array($_GET['type'],[7,8,9])){

            foreach ($ITOGO_SUMS as $key => $ITOGO_SUM) {
                if ($key != "image" and $key != "incomeId") {

                    $ITOGO_SUMS[$key] = number_format((string)$ITOGO_SUM, 2, '.', ' ');

                }
            }
            $data[] = $ITOGO_SUMS;
        }
    }
}

file_put_contents('cache/data.json', json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_ERROR_INF_OR_NAN | LOCK_EX));

$perc = file_get_contents('update/json/9.json');

$dom = $_GET['type'] ? $_GET['type'] : '';

$pay1 = "<span id=\"pay-btnWrap\" data-ref=\"btnWrap\" role=\"presentation\" unselectable=\"on\" style=\"\" class=\"x-btn-wrap x-btn-wrap-default-toolbar-small x-btn-split x-btn-split-right\"><span id=\"pay-btnEl\" data-ref=\"btnEl\" role=\"presentation\" unselectable=\"on\" style=\"\" class=\"x-btn-button x-btn-button-default-toolbar-small x-btn-text    x-btn-button-center \"><span id=\"pay-btnIconEl\" data-ref=\"btnIconEl\" role=\"presentation\" unselectable=\"on\" class=\"x-btn-icon-el x-btn-icon-el-default-toolbar-small  \" style=\"\"></span><span id=\"pay-btnInnerEl\" data-ref=\"btnInnerEl\" unselectable=\"on\" class=\"x-btn-inner x-btn-inner-default-toolbar-small\">";
$pay2 = "</span></span></span><span id=\"pay-arrowEl\" class=\"x-btn-arrow-el\" data-ref=\"arrowEl\" role=\"button\" hidefocus=\"on\" unselectable=\"on\" tabindex=\"0\" aria-haspopup=\"true\" aria-owns=\"menu-1015\" aria-hidden=\"false\" aria-disabled=\"false\" aria-labelledby=\"pay\" style=\"\" data-componentid=\"pay\"></span>";



?>

<script>
    var title = '<?php echo $title; ?>';
    var fields = <?php echo json_encode($fields); ?>;
    var data = <?php echo json_encode($data,JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_ERROR_INF_OR_NAN); ?>;
    var columns = <?php echo json_encode($columns); ?>;
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/classic/theme-triton/resources/theme-triton-all.css" rel="stylesheet" />
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/ext-all.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/classic/locale/locale-ru.js"></script>-->
<script type="text/javascript" src="js/ext-all.js"></script>
<script type="text/javascript" src="js/locale-ru.js"></script>

<?php if ($_GET['type'] == 9) {require_once('blocks/interval_nine.php');}?>
<?php if ($_GET['type'] == 8) {require_once('blocks/interval_eight.php');}?>
<?php if ($_GET['type'] == 7) {require_once('blocks/interval_seven.php');}?>

<script type = "text/javascript">

    <?php
    if (in_array($_GET['type'],[6,7,8,9])){
        echo 'localStorage.clear();';
    }
    ?>
    if(performance.navigation.type == 2)
    {
        document.location.reload();
    }

    Ext.onReady(function() {

        Ext.QuickTips.init();
        //var Editing = Ext.create('Ext.grid.plugin.CellEditing');
        var Editing = Ext.create('Ext.grid.plugin.RowEditing');
        Ext.define('Data', {
            extend: 'Ext.data.Model',
            fields: fields,
        });

        store = Ext.create('Ext.data.Store', {
            autoLoad: true,
            autoSync: true,
            model: 'Data',
            //data: data,
            proxy: {// описание прокси
                type: 'rest', // способ взаимодействия с сервером
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                },
                writer: {
                    type: 'json',
                    rootProperty: 'data'
                },
                api: {
                    read: 'cache/data.json',
                    update: 'update/update.php',
                }
            }
        });

        store.load();

        Ext.state.Manager.setProvider(new Ext.state.LocalStorageProvider());

        grid = Ext.create('Ext.grid.Panel', {
            renderTo: 'grid<?php echo $dom; ?>',
            plugins: [Editing], // указали плагин для редактирования
            store: store,
            padding: '10 10 10 10',
            height: 600,
            enableColumnMove: true,
            enableColumnResize: true,
            title: title,
            columns: columns,
            stateful: true,
            features: [{
                groupHeaderTpl: '{columnName}: {name} ({children.length})',
                ftype:'grouping',
                listeners: {
                    onGroupMenuItemClick: function() {
                       alert('click');
                    }
                }
            }<?php /*echo ($_GET['type']==8 ? ',{ftype: \'summary\'}' : '')*/?>
            ],
            stateId: 'grid<?php echo $dom; ?>',
            stateEvents: ['columnmove', 'columnresize', 'columnhide', 'columnshow'],
            viewConfig: {
                getRowClass: function(record, rowIndex, rowParams, store){
                    return record.get("refund_color") ? record.get("refund_color") : "";
                },
                preserveScrollOnRefresh: true,
                deferEmptyText: true,
                emptyText: '<div class="grid-data-empty">Нет результатов</div>'
            },
            tbar:[{
                iconCls:'x-fa fa-plus-square',
                handler:function(btn) {
                    btn.up('grid').getView().findFeature("grouping").expandAll();
                }
            },{
                iconCls:'x-fa fa-minus-square',
                handler:function(btn) {
                    btn.up('grid').getView().findFeature("grouping").collapseAll();
                }
            },<?php echo (($_GET['type']==9 and !$_GET['rid']) ? "{
                   // xtype: 'button',
                    width: 180,
                    text : 'Доходы',
                    id:'pay',
                    menu: [
                        {text: 'Доходы',handler: function() {Ext.select('#pay').update('$pay1 Доходы $pay2'); return true;}},
                        {text: 'Доходы - Расходы',handler: function() {Ext.select('#pay').update('$pay1 Доходы - Расходы $pay2'); return true;}},
                    ],
                },{
                xtype: 'textfield',
                value: $perc,
                emptyText: '0',
                width: 40,
                id: 'percent',

            },{
                xtype: 'label',
                forId: 'percent',
                text: '%',
            }" : "")?>
            ]
        });
        view = grid.getView();
        view.tip = Ext.create('Ext.tip.ToolTip', {
            target: view.el,
            delegate: view.cellSelector,
            trackMouse: true,
            renderTo: Ext.getBody(),
            listeners: {
                beforeshow: function updateTipBody(tip) {
                    var gridColums = view.getGridColumns();
                    var column = gridColums[tip.triggerElement.cellIndex];
                    var coltip = view.getRecord(tip.triggerElement.parentNode).get(column.dataIndex);
                    if (coltip) {
                        var val = column.text + ': ' + coltip;
                    }else{
                        var val = column.text;
                    }
                    tip.update(val);
                }
            }
        });

        store.on('load', function(store) {
            change_groped_title(store);
        });
        store.on('groupchange', function(store) {
            alert('dd');
            change_groped_title(store);
        });

        function change_groped_title(store) {
            var group_column_txt = '';
            var groups = store.getGroups();
            if (groups) {
                var group_index = store.getGroups()._grouper._property;
                $('#modal input:checkbox#' + group_index).prop('checked', true);
                var group_column = columns.filter(function (column) { return column.dataIndex == group_index });
                group_column_txt = group_column[0].text;
            }
            if (group_column_txt) {
                grid.setTitle(title + ' - Записи сгруппированы по полю <b style="cursor: pointer;" data-toggle="modal" data-target="#modal" title="Кликните чтобы открыть окно выбора поля для группировки">' + group_column[0].text + '</b>. Кликнете на поле для подробной детализации. <b style="cursor: pointer;" title="Кликните чтобы cнять все групировки" onclick="groping_control_remove()">Cнять все групировки</b>');
            } else {
                grid.setTitle(title + ' - Записи несгруппированы - <b style="cursor: pointer;" data-toggle="modal" data-target="#modal" title="Кликните чтобы открыть окно выбора поля для группировки">нажмите сгруппировать</b>');
            }
            return true;
        }



    });

    function groping_control(el) {
        $('#modal input:checkbox').removeAttr('checked').prop("checked", false);
        grid.getStore().group($(el).val());
        $('#modal').modal('hide');
        return true;
    }
    function groping_control_remove() {
        $('#modal input:checkbox').removeAttr('checked').prop("checked", false);
        grid.getStore().clearGrouping();
        return true;
    }

</script>
<?php
//ввод данных через редактируемые ячейки
if ($_GET['type']==5 or $_GET['type']==9) {
    echo '<script>
    function number_update(id,val,name,real) {
        $.post("/wb/update/update.php?type=5", {val:val, name:name, realizationreport_id:real}, function (res){
            var re = /\B(?=(\d{3})+(?!\d))/g;

            let realizationreport_id = document.querySelectorAll("td.x-grid-cell-realizationreport_id");

            i=0;
            while(i<realizationreport_id.length){
                if (realizationreport_id[i].innerText == real) {
                    id1 = "Data-"+(i+1);
                }
                i++;
            }

            store.data.map[id].data[name] = val;
            val = Number(val);
            if (name == "storage_cost"){
                var storage_cost = val;
            }else{
                var storage_cost = store.data.map[id].data.storage_cost;
                if (storage_cost != null){
                    storage_cost = Number(storage_cost.replace(" ",""));
                }else{
                    storage_cost = 0;
                }
            }
            if (name == "acceptance_fee"){
                var acceptance_fee = val;
            }else{
                var acceptance_fee = store.data.map[id].data.acceptance_fee;
                if (acceptance_fee != null){
                    acceptance_fee = Number(acceptance_fee.replace(" ",""));
                }else{
                    acceptance_fee = 0;
                }
            }if (name == "other_deductions"){
                var other_deductions = val;
            }else{
                var other_deductions = store.data.map[id].data.other_deductions;
                if (other_deductions != null){
                    other_deductions = Number(other_deductions.replace(" ",""));
                }else{
                    other_deductions = 0;
                }
            }
            var ppvz_for_pay = store.data.map[id].data.ppvz_for_pay;
            if (ppvz_for_pay != null){
                    ppvz_for_pay = Number(ppvz_for_pay.replace(" ",""));
                }else{
                    ppvz_for_pay = 0;
                }
            var delivery_rub = store.data.map[id].data.delivery_rub;
            if (delivery_rub != null){
                    delivery_rub = Number(delivery_rub.replace(" ",""));
                }else{
                    delivery_rub = 0;
                }
            var total_payable = ppvz_for_pay - (delivery_rub + acceptance_fee + other_deductions + storage_cost);

            id=id1;

            Ext.select("td.x-grid-cell-total_payable").item(id.replace("Data-","")-1).update("<div unselectable=\"on\" class=\"x-grid-cell-inner \" style=\"text-align:left;\"><a href=\"?page=wb&amp;type='.$_GET["type"].'&amp;rid="+(Ext.select("td.x-grid-cell-realizationreport_id").item(id.replace("Data-","")-1).dom.innerText)+"\">"+(total_payable.toFixed(2).replace(re," "))+"</a></div>");
            store.data.map[id].data.total_payable = total_payable.toFixed(2).replace(re," ");
        });
    }
</script>';
}elseif ($_GET['type']==7) {
    echo '<script>
    function number_update(id,val,name,real,rid,barcode) {
        $.post("/wb/update/update.php?type=7", {val:val, name:name, incomeId:real, supplierArticle:rid, barcode:barcode}, function (res){
            var input = document.querySelectorAll(\'input.inputValue\');
        var sum = document.querySelectorAll(\'div.inputSum\');
        var sumkol = document.querySelectorAll(\'div.inputSumKol\');
        var kol = document.querySelectorAll(\'div.inputKol\');
        var re = /\B(?=(\d{3})+(?!\d))/g;
        let i = 0;
        while (i < sum.length) {
            if (sum[i].getAttribute(\'incomeid\') ==real && sum[i].getAttribute(\'barcode\') ==barcode && sum[i].getAttribute(\'supplierarticle\') ==rid){
                summdiv = sum[i];
            }
            i++;
        }
        i = 0;
        while (i < sumkol.length) {
            if (sumkol[i].getAttribute(\'incomeid\') ==real && sumkol[i].getAttribute(\'barcode\') ==barcode 
            && sumkol[i].getAttribute(\'supplierarticle\') ==rid) {
                summdivkol = sumkol[i];
            }
            i++;
        }
        i = 0;
        while (i < kol.length) {
            if (kol[i].getAttribute(\'incomeid\') ==real && kol[i].getAttribute(\'barcode\') ==barcode && kol[i].getAttribute(\'supplierarticle\') ==rid){
                summkol = Number(kol[i].innerHTML);
            }
            i++;
        }
        i = 0;
        let summinput = 0;
        while (i < input.length) {
            if (input[i].getAttribute(\'incomeid\') ==real && input[i].getAttribute(\'barcode\') ==barcode && input[i].getAttribute(\'supplierarticle\') ==rid){
                summinput += Number(input[i].value);
            }
            if (input[i].getAttribute(\'incomeid\') ==real && input[i].getAttribute(\'barcode\') ==barcode 
            && input[i].getAttribute(\'supplierarticle\') ==rid && input[i].getAttribute(\'id\') ==name){
                input[i].setAttribute(\'value\',val);
            }
            i++;
        }
        summdiv.innerHTML = summinput.toFixed(2).replace(re," ");
        summdivkol.innerHTML = (summinput*summkol).toFixed(2).replace(re," ");
        store.data.map[id].data[name] = val;
        store.data.map[id].data.Obschaya_sebestoimosty_edinicy_tovara = summinput.toFixed(2).replace(re," ");
        store.data.map[id].data.Obschaya_sebestoimosty_s_uchetom_kolichestva = (summinput*summkol).toFixed(2).replace(re," ");
        
        });
    }
</script>';
}elseif ($_GET['type']==8) {
    echo '<script>
    function number_update(id,val,name,real,rid,barcode) {
        $.post("/wb/update/update.php?type=7", {val:val, name:name, incomeId:real, supplierArticle:rid, barcode:barcode}, function (res){
            
        let store_data = store.data.map;
        let input = document.querySelectorAll(\'input.inputValue\');
        let sum = document.querySelectorAll(\'div.inputSum\');
        let sumkol = document.querySelectorAll(\'div.inputSumKol\');
        let kol = document.querySelectorAll(\'div.inputKol\');
        let re = /\B(?=(\d{3})+(?!\d))/g;
        let i = 0;
        while (i < sum.length) {
            if (sum[i].getAttribute(\'incomeid\') ==real && sum[i].getAttribute(\'barcode\') ==barcode && sum[i].getAttribute(\'supplierarticle\') ==rid){
                summdiv = sum[i];
            }
            i++;
        }
        i = 0;
        while (i < sumkol.length) {
            if (sumkol[i].getAttribute(\'incomeid\') ==real && sumkol[i].getAttribute(\'barcode\') ==barcode 
            && sumkol[i].getAttribute(\'supplierarticle\') ==rid) {
                summdivkol = sumkol[i];
            }
            i++;
        }
        i = 0;
        while (i < kol.length) {
            if (kol[i].getAttribute(\'incomeid\') ==real && kol[i].getAttribute(\'barcode\') ==barcode && kol[i].getAttribute(\'supplierarticle\') ==rid){
                summkol = Number(kol[i].innerHTML);
            }
            i++;
        }
        i = 0;
        let summinput = 0;
        while (i < input.length) {
            if (input[i].getAttribute(\'incomeid\') ==real && input[i].getAttribute(\'barcode\') ==barcode && input[i].getAttribute(\'supplierarticle\') ==rid){
                summinput += Number(input[i].value);
            }
            if (input[i].getAttribute(\'incomeid\') ==real && input[i].getAttribute(\'barcode\') ==barcode 
            && input[i].getAttribute(\'supplierarticle\') ==rid && input[i].getAttribute(\'id\') ==name){
                input[i].setAttribute(\'value\',val);
            }
            i++;
        }
        summdiv.innerHTML = summinput.toFixed(2).replace(re," ");
        summdivkol.innerHTML = (summinput*summkol).toFixed(2).replace(re," ");
        
        store_data[id].data[name] = val;
        store_data[id].data.ss_one = summinput.toFixed(2).replace(re," ").replace(" ","");
        store_data[id].data.ss_all = (summinput*summkol).toFixed(2).replace(re," ").replace(" ","");
        
        
        let inputs = document.querySelectorAll("input.inputValue#"+name);
       
        sum = 0;
        i = 0;
        while (i<inputs.length){
                sum += Number((inputs[i].value).toString().replace(" ","").replace(" ",""));
                i++;
            }
       store_data["Data-"+Ext.select("td.x-grid-cell-"+name).elements.length].data[name] = sum.toFixed(2).replace(re," ");
        
        let sum_inp = document.querySelectorAll("div.inputSum");
        sum = 0;
        i = 0;
        while (i<sum_inp.length){
                sum += Number((sum_inp[i].innerHTML).toString().replace(" ","").replace(" ",""));
                i++;
        }

        store_data["Data-"+Ext.select("td.x-grid-cell-ss_one").elements.length].data.ss_one = sum.toFixed(2).replace(re," ");
        
        let sum_kol = document.querySelectorAll("div.inputSumKol");
        sum = 0;
        i = 0;
        while (i<sum_kol.length){
                sum += Number((sum_kol[i].innerHTML).toString().replace(" ","").replace(" ",""));
                i++;
        }
        store_data["Data-"+Ext.select("td.x-grid-cell-ss_all").elements.length].data.ss_all = sum.toFixed(2).replace(re," ");
        });
    }
</script>';
}
?>

<div class="modal fade" id="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Выберите поле для группировки</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
            foreach ($columns as $column) {
                echo '<div class="form-check">
                        <input class="form-check-input" type="checkbox" onclick="groping_control(this);" value="' . $column->dataIndex . '" id="' . $column->dataIndex . '">
                        <label style="font-weight: normal;" class="form-check-label" for="' . $column->dataIndex . '">
                          ' . $column->text . '
                        </label>
                      </div>';
            }
        ?>
      </div>
    </div>
  </div>
</div>
                 
<?php
/*
//----------------- ITOGO ROW ------------------------
if ($_GET['type'] == 9)
{

    echo "
<tr class='warning' style='font-size: 14px;' ><td><b>Итого:</b></td>";

    foreach ($tbl_keys as $gk => $str)
    {
        if ($gk == 'save_cost') $gk = 'save_cost2';
        if (in_array($gk, ['realizationreport_id', 'rr_dt', 'marga']) !== false)
        {
            $ITOGO_SUMS[$gk] = '';
        }
        echo "<td><b>{$ITOGO_SUMS[$gk]}</b></td>";
    }

    echo "
</tr>";
}*/
?>
<?php /*if ($_GET['type'] == 8){ ?>
    <tr class='warning' id="itogo_update" style='font-size: 14px;'><td><b>Итого:</b> </td>
    <?php foreach ($tbl_keys as $gk => $str){
        if (in_array($gk, ['supplierArticle', 'barcode', '', 'save',"subject","category","brand","warehouseName","status"]) !== false){
            if (($_GET['f1'] and $gk != 'incomeId') or (!$_GET['f1'])){
                $ITOGO_SUMS[$gk] = '';
            }
        }
        ?>
            <td><b title="<?=$str?>" id="<?=$gk?>"><?=number_format((string)$ITOGO_SUMS[$gk], 2, '.', ' ');?></b></td>
    <?php } ?>
    </tr>
<?php }*/ ?>
<?php
if ($config_return and ($_GET['type'] == 1 or $_GET['type'] == 2)){
    $return_url = "?page=wb&type=$_GET[type]&r=".($config_return=='on' ? 'off' : 'on' );
    if (isset($_GET['dt'])){
        $return_url .= "&dt=$_GET[dt]";
    }
    if (isset($_GET['dt1'])){
        $return_url .= "&dt1=$_GET[dt1]";
    }
    if (isset($_GET['dt2'])){
        $return_url .= "&dt2=$_GET[dt2]";
    }

    if ($return_url){
        $ret = "<a href='".$return_url."' class='btn' style='margin-left:10px;padding: 5px 10px;border: 1px solid #ccc; '>".($config_return=='on' ? 'Откл' : 'Вкл')." возвраты </a>";
    }
}
?>

<script>

<?php
if ($_GET['type'] != 5 && $_GET['type'] != 6 && $_GET['type'] != 7 && $_GET['type'] != 8 && $_GET['type'] != 9)
{
if ($_GET['type'] == 10){$PRICE_SUM *= -1;}
?>



$('#stat').html("Суммарное кол-во товаров: <b><?=abs($CNT_SUM); ?></b> | Суммарная цена: <b><?=number_format($PRICE_SUM, 2, '.', ' '); ?></b> руб. <?=$ret?>");

<?php
} ?>


</script>


        </form>


    </div>
</div>
