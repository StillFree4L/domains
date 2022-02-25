<?php
error_reporting(0);
$valid_passwords['admin'] = '123pass';
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (isset($valid_passwords[$user])) && ($pass == $valid_passwords[$user]);

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    function mb_ucfirst($string) {
        $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
        return $string;
    }
}

if (!$validated) {
  header('WWW-Authenticate: Basic realm="Авторизация"');
  header('HTTP/1.0 401 Unauthorized');
  die ("Not authorized");
}

$fileName = 'update/key.txt';
file_put_contents($fileName, '',FILE_APPEND);

$lines = file($fileName);
$lines[0]=trim($lines[0]);
$lines[1]=trim($lines[1]);
$lines[2]=trim($lines[2]);
$lines[3]=trim($lines[3]);

if (($_POST['key1'] or $_POST['key2'] or $_POST['key3'] or $_GET['r'])
    and (($_POST['key1'] != $lines[0])
        or ($_POST['key2'] != $lines[1])
        or ($_POST['key3'] != $lines[2])
        or ($_GET['r'] != $lines[3]))){
    if ($_POST['key1'] != '' or $_POST['key1'] != null){
        $lines[0] = $_POST['key1'];
    }
    if ($_POST['key2'] != '' or $_POST['key2'] != null){
        $lines[1] = $_POST['key2'];
    }
    if ($_POST['key3'] != '' or $_POST['key3'] != null){
        $lines[2] = $_POST['key3'];
    }
    if ($_GET['r'] != '' or $_GET['r'] != null){
        $lines[3] = $_GET['r'];
    }
    file_put_contents($fileName, $lines[0].PHP_EOL.$lines[1].PHP_EOL.$lines[2].PHP_EOL.$lines[3].PHP_EOL);
}

//---------------------------------------------
$auth = $lines[0];//'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2Nlc3NJRCI6ImRlMTExOTk0LTJlMjEtNGRhNy05Mzc0LTRiOTI2YjQwNTNhMiJ9.wBbe_HlSf2AFYvQfaPaJzWbWjr5Ro6JJ1Cq6U6HiD1U';
$USER['wb_key'] = $lines[1];//'NGQ0M2ZiMmYtMmZmYS00NGUzLWE5ODktMzIxNThmMzY3NTkw';
$supplierId = $lines[2];//'b541a87c-d482-4161-9f30-5edc1fded445';

$wb_key_new = $USER['wb_key'];
$config_return = $lines[3];

require_once ('http.lib.php');
require_once ('report.lib.php');

$USER['id'] = 2;

require_once ('blocks/func_keys.php');
//------------------------------------------------------------------------------------------

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    function mb_ucfirst($string) {
        $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
        return $string;
    }
}

if (!isset($_GET['dt'])) $_GET['dt'] = date('Y-m-d', time());
if (!isset($_GET['type'])) $_GET['type'] = 2;

require_once ('blocks/func_api.php');



include('head.php');
?>

<script type="text/javascript">
    setTimeout(() => {$.get("/wb/load.php?type="+<?=$_GET['type']?>, function (dt){});},3*1000);
</script>

<div class="panel panel-default" >
    <div class="panel-heading"><h4>Продажи и заказы Wildberries</h4>
        <div class="dropdown" style="z-index: 99;">
            <button onclick="myFunction()" class="dropbtn"><i class="fa fa-key dropbtn"></i></button>
            <div id="myDropdown" class="dropdown-content">
                <form method="post" action="index.php">
                    <fieldset>
                        <p><label for="api">Ключ api новый: <?=$valid_url_new?></label><input style="width: 100%" type="text" name="key1" id="api" placeholder="<?=($lines[0] ? 'введен' : 'не введен')?>"></p>
                        <p><label style="padding-top: 5px" for="stats">Ключ api старый: <?=$valid_url?></label><input style="width: 100%" type="text" name="key2" id="stats" value="<?=$lines[1]?>"></p>
                        <p><label style="padding-top: 5px" for="supplierId">Ключ поставщика: <?=($valid_url_new!="invalid token" ? ($valid_results ? $valid_results : '<font color="red">не валиден</font>') : '<font color="red">ключ api новый не валиден</font>')?></label><input style="width: 100%" type="text" name="key3" id="supplierId" value="<?=$lines[2]?>"></p>
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
            $cdt = $g->date;
            $g->qualification = 'Отмененные продажи';
        }
        if ($_GET['type'] == 1) {
            $cdt = $g->lastChangeDate;
            $g->qualification = 'Отмененные заказы';
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

<div id='stat' style="padding: 0 10px;"></div>


<?php

require_once ('blocks/func_five.php');

//=================================================================================

// поставки
require_once ('blocks/func_seven.php');

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
        foreach ($tbl_rows as $g) {

            $g = (object)$g;

            if ($g->Discount == 0) {
                $g->Discount = 100 - floor(($g->price_min_discount * 100) / $g->Price);
            }
            if ($g->price_min_discount == 0) {
                $g->price_min_discount = $g->Price - floor(($g->Price * $g->Discount) / 100);
            }

            $g->lastChangeDate = date('d.m.Y H:i:s', strtotime($g->lastChangeDate));
            if (isset($_GET['f1']) && isset($_GET['bc'])) {
                if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

            } else if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;
            } else {
                if (isset($keys_bc[$g->barcode])) {
                    $sums = explode("\n", trim('
quantity
quantityFull
quantityNotInOrders
inWayToClient
inWayFromClient
Price
'));

                    $u = 0;
                    foreach ($sums as $fieldsum) {
                        $fieldsum = trim($fieldsum);
                        //var_dump($g->v);
                        if ($u == 0 and $g->v == 'new') {
                            $reps[$keys_bc[$g->barcode]]->fbs += $g->quantity;
                        } elseif ($u == 0) {
                            $reps[$keys_bc[$g->barcode]]->fbo += $g->quantity;
                        }
                        $reps[$keys_bc[$g->barcode]]->$fieldsum += $g->$fieldsum;
                        $u++;
                    }
                    $reps[$keys_bc[$g->barcode]]->fbs_fbo = $reps[$keys_bc[$g->barcode]]->fbo + $reps[$keys_bc[$g->barcode]]->fbs;
                    $reps[$keys_bc[$g->barcode]]->isSupply = $g->isSupply;
                    $reps[$keys_bc[$g->barcode]]->isRealization = $g->isRealization;
                    $reps[$keys_bc[$g->barcode]]->daysOnSite = $g->daysOnSite;
                    $reps[$keys_bc[$g->barcode]]->SCCode = $g->SCCode;
                }
                if (isset($keys_bc[$g->barcode])) continue;
            }
            if ($g->v == 'new') {
                $g->fbs = $g->quantity;
            } else {
                $g->fbo = $g->quantity;
            }

            $reps[] = $g;
            // var_dump($reps);
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

//////////////////////////////////////////////////////////////
// Склад - доп поля
if ($_GET['type'] == 6)
{
    file_put_contents('cache/wb-cache/' . $USER['wb_key'] . '-1','');
    $buf = file_get_contents('cache/wb-cache/' . $USER['wb_key'] . '-1');
    $buf = explode('@@---@@', $buf);
    $r = $r0 = $buf[1];

    $sales_rows = json_decode($r);

    file_put_contents('cache/wb-cache/' . $USER['wb_key'] . '-2','');
    $buf = file_get_contents('cache/wb-cache/' . $USER['wb_key'] . '-2');
    $buf = explode('@@---@@', $buf);
    $r = $r0 = $buf[1];

    $orders_rows = json_decode($r);

    //	var_dump($r, $sales_rows, $orders_rows);

        foreach ($tbl_rows as $g) {
            //var_dump($g->quantity);

            $g->price_min_discount = $g->Price * (100 - $g->Discount) / 100;

            $cnt_refund_sales = $cnt_sales = 0;
            $cnt_refund_sales30 = $cnt_sales30 = 0;

            $cnt_refund_orders = $cnt_orders = 0;
            $cnt_refund_orders30 = $cnt_orders30 = 0;

            $idps = $idps30 = array();

            if ($sales_rows){
                foreach ($sales_rows as $sale_row) {
                    if ($sale_row->nmId == $g->nmId) {
                        $cdt = $sale_row->lastChangeDate;
                        //$cdt = $sale_row->date;
                        //var_dump($cdt, $g);exit;


                        //if (strtotime($cdt) >= time()-60*60*24* 8 )


                        if (strtotime($cdt) >= strtotime(date('Y-m-d', time() - 60 * 60 * 24 * 7))) {

                            if ($sale_row->totalPrice < 0) {
                                $cnt_refund_sales++;
                            } else {
                                $cnt_sales++;
                                $idps[$sale_row->odid] = 1;
                            }

                        }

                        if (strtotime($cdt) >= time() - 60 * 60 * 24 * 30) {

                            if ($sale_row->totalPrice < 0) {
                                $cnt_refund_sales30++;
                            } else {
                                $cnt_sales30++;

                                $idps30[$sale_row->odid] = 1;

                            }

                        }

                    }

                }
            }

            if ($orders_rows){
                foreach ($orders_rows as $sale_row) {
                    if ($sale_row->nmId == $g->nmId) {
                        $cdt = $sale_row->lastChangeDate;
                        //$cdt = $sale_row->date;
                        //$cdt = $g->cancel_dt;


                        if (strtotime($cdt) >= strtotime(date('Y-m-d', time() - 60 * 60 * 24 * 7))) {

                            if ($sale_row->isCancel) {
                                $cnt_refund_orders++;

                            } else {
                                if (!isset($idps[$sale_row->odid])) $cnt_orders++;
                            }
                        }

                        if (strtotime($cdt) >= time() - 60 * 60 * 24 * 30) {
                            if ($sale_row->isCancel) {
                                $cnt_refund_orders30++;

                            } else {
                                if (!isset($idps30[$sale_row->odid]))

                                    $cnt_orders30++;
                            }
                        }

                    }

                }
            }

            if ($cnt_orders + $cnt_sales != 0){
                $g->refund_7 = intval(($cnt_refund_sales + $cnt_refund_orders) / ($cnt_orders + $cnt_sales) * 100) . '% <br>';
                $g->refund_7 .= ($cnt_refund_sales + $cnt_refund_orders) . ' / ' . ($cnt_orders + $cnt_sales);
            }

            if ($cnt_orders30 + $cnt_sales30 != 0){
                $g->refund_30 = intval(round(($cnt_refund_sales30 + $cnt_refund_orders30) / ($cnt_orders30 + $cnt_sales30) * 100, 2)) . '% <br> ';
                $g->refund_30 .= ($cnt_refund_sales30 + $cnt_refund_orders30) . ' / ' . ($cnt_orders30 + $cnt_sales30);
            }

            // speed_30s Скорость продаж за месяц, шт/день по продажам
            //$g->refund_30s = intval(round(($cnt_refund_sales30+$cnt_refund_orders30) / ($cnt_sales30 + $cnt_orders30) * 100, 2)) . '% <br> ';
            //$g->refund_30s .= ($cnt_refund_sales30+$cnt_refund_orders30).' / '.($cnt_sales30+$cnt_orders30);


            $g->speed_30 = round($cnt_sales30 / 30, 1);
            $g->speed_7 = round($cnt_sales / 7, 1);
            $g->speed_7_order = round($cnt_orders / 7, 1);

            if ($cnt_sales / 7 != 0){
                $g->speed = '~ ' . intval(round($g->quantity / ($cnt_sales / 7), 1));
            }
            //if ($cnt_sales == 0)
            //$g->speed = '~ '.floatval(round($g->quantity / ($cnt_sales30 / 30), 1));
        }

}
////////////////////////////////////////////////////////////////////

//=================================================================================

// Себестоимость
require_once ('blocks/func_eight.php');

//=====================================================================================================

// Чистая прибыль
require_once ('blocks/func_nine.php');

//=================================================================================

?>

             <table class="items table table-striped" style="margin: 10px; font-size: 11px;" >
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
$columns[] = (object) [
    'text' => '<span data-qtip="Изображение">Изображение</span>',
    'id' => 'image',
    'dataIndex' =>
    'image',
    'sortable' => false,
    'hideable' => true,
    'width' => 45
];
if ($_GET['type'] == 5 and !$_GET['rid']){
    $columns[0]->hidden = true;
}

foreach ($tbl_keys as $k => $str) {
    if (($_GET['type'] == 7 or $_GET['type'] == 8) and !$_GET['rid'] and !$_GET['f1']
        and ($k == 'Stoimosty_edinicy_tovara' or $k == 'Srednyaya_sebestoimosty_edinicy')){
        if ($k == 'Stoimosty_edinicy_tovara'){
            $str = 'Стоимость товара';
        }elseif ($k == 'Srednyaya_sebestoimosty_edinicy') {
            $str = 'Средняя себестоимость';
        }
    }
    if ($k == 'save'){continue;}
    $column = [
        'text' => '<span data-qtip="'.mb_ucfirst($str).'">'.mb_ucfirst($str).'</span>',
        'id' => $k,
        'dataIndex' => $k,
        'sortable' => true,
        'hideable' => true,
        'useNull' => true,
        'defaultValue' => '---',
    ];

    if($_GET['type'] == 5 and !$_GET['rid']){
            if ($k == 'realizationreport_id' or $k == 'rr_dt' or $k == 'return_amount' or $k == 'delivery_amount'
                or $k == 'quantity' or $k == 'retail_amount' or $k == 'ppvz_vw' or $k == 'ppvz_vw_nds'
                or $k == 'storage_cost' or $k == 'acceptance_fee' or $k == 'other_deductions'
                or $k == 'ppvz_for_pay' or $k == 'delivery_rub' or 'storage_cost' == $k
                or $k == 'acceptance_fee' or $k == 'other_deductions' or $k == 'total_payable'){
                $column['hidden'] = false;
            }else{
                $column['hidden'] = true;
            }

    }elseif($_GET['type'] == 5 and $_GET['rid']){
        $column['hidden'] = false;
    }
    if ('barcode' == $k) {
        $column['width'] = 140;
    }
    if ('number' == $k) {
        $column['width'] = 120;
    }
        $column['xtype'] = 'templatecolumn';

    if ($_GET['type'] == 5){
        if ('realizationreport_id' == $k){
            $column['tpl'] = '<a href="?page=wb&type=5&rid={'.$k.'}"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">{'.$k.'}</a>';
        }else {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&rid={realizationreport_id}'>{" . $k . "}</a>";
        }
    }elseif ($_GET['type'] == 7){
        if ('incomeId' == $k){
            $column['tpl'] = '<a href="?page=wb&type=7&rid={'.$k.'}&dt='.$_GET['dt'].'"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">{'.$k.'}</a>';
        }else {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&rid={incomeId}&dt=".$_GET['dt']."'>{" . $k . "}</a>";
        }
    }elseif ($_GET['type'] == 8){
        if ('supplierArticle' == $k){
            $column['tpl'] = '<a href="?page=wb&type=8&f1={'.$k.'}&dt='.$_GET['dt'].'"><img hidden height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">{'.$k.'}</a>';
        }else {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&f1={supplierArticle}&dt=".$_GET['dt']."'>{" . $k . "}</a>";
        }
    }else{
        $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3={".$k."}'>{".$k."}</a>";
    }
    if ('date' == $k || 'lastChangeDate' == $k) {
        $column['width'] = 145;
    }
    if ('storage_cost' == $k or $k == 'acceptance_fee' or $k == 'other_deductions' or ((($_GET['f1'] and $_GET['type']==8) or ($_GET['rid'] and $_GET['type']==7)) and (ru2Lat($k)=='Stoimosty_edinicy_tovara' or strpos(ru2Lat($ss_dop),ru2Lat($k))!==false))){
        if ($_GET['type']==5) {
            $column['tpl'] = "<input type=\"text\" id='$k' realizationreport_id='{realizationreport_id}' rid='{rid}' class='inputValue' onblur=\"number_update('{id}',this.value,this.id,{realizationreport_id},{rid})\" onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='{" . $k . "}'>";
        }elseif ($_GET['type']==7) {
            $column['tpl'] = "<input type=\"text\" id='$k' incomeId='".$_GET['rid']."' barcode='{barcode}' supplierArticle='{supplierArticle}' class='inputValue' onblur=\"number_update('{id}',this.value,this.id,".$_GET['rid'].",'{supplierArticle}',{barcode})\" onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='{" . $k . "}'>";
        }elseif ($_GET['type']==8) {
            $column['tpl'] = "<input type=\"text\" idd='{id}' hidden='false' id='$k' incomeId='{incomeId}' barcode='{barcode}' supplierArticle='{supplierArticle}' class='inputValue' onblur=\"number_update('{id}',this.value,this.id,{incomeId},'{supplierArticle}',{barcode})\" onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='{" . $k . "}'>";
        }

    }
    if ($_GET['type']==7 and $_GET['rid']){
        if ($k == 'Obschaya_sebestoimosty_edinicy_tovara') {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3={" . $k . "}'><div class='inputSum' incomeId='" . $_GET['rid'] . "' barcode='{barcode}' supplierArticle='{supplierArticle}'>{" . $k . "}</div></a>";
        }
        if ($k == 'Obschaya_sebestoimosty_s_uchetom_kolichestva') {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3={" . $k . "}'><div class='inputSumKol' incomeId='" . $_GET['rid'] . "' barcode='{barcode}' supplierArticle='{supplierArticle}'>{" . $k . "}</div></a>";
        }
        if ($k == 'quantity') {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3={" . $k . "}'><div class='inputKol' incomeId='" . $_GET['rid'] . "' barcode='{barcode}' supplierArticle='{supplierArticle}'>{" . $k . "}</div></a>";
        }
    }elseif ($_GET['type']==8 and $_GET['f1']){
        if ($k == 'ss_one') {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3={" . $k . "}'><div class='inputSum' incomeId='{incomeId}' barcode='{barcode}' supplierArticle='{supplierArticle}'>{" . $k . "}</div></a>";
        }
        if ($k == 'ss_all') {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3={" . $k . "}'><div class='inputSumKol' incomeId='{incomeId}' barcode='{barcode}' supplierArticle='{supplierArticle}'>{" . $k . "}</div></a>";
        }
        if ($k == 'quantity') {
            $column['tpl'] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3={" . $k . "}'><div class='inputKol' incomeId='{incomeId}' barcode='{barcode}' supplierArticle='{supplierArticle}'>{" . $k . "}</div></a>";
        }
    }
    $columns[] = (object) $column;
}

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

    if (count($tbl_rows)) {
        foreach ($tbl_rows as $g) {
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
            } elseif ($_GET['type'] == 2 and $g->totalPrice >= 0) { // ЗАКАЗЫ
                // $PRICE_SUM += $g->finishedPrice;
                $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
            } elseif ($_GET['type'] == 10) {
                $PRICE_SUM += $g->finishedPrice;
            } else {
                $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
            }

            $data_cols['refund_color'] = $REFUND_COLOR;

            // image
            if ($_GET['type'] == 5 || $_GET['type'] == 9 || $_GET['type'] == 7) {

                if (isset($g->nmId)) $g->nm_id = $g->nmId;
                $img = 'https://images.wbstatic.net/small/new/' . substr($g->nm_id, 0, -4) . '0000/' . $g->nm_id . '.jpg';
                //var_dump($img);
                if (!isset($_GET['rid']) and $_GET['type'] != 5 and $_GET['type'] != 7) {
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


if ($_GET['type'] == 5 and !$_GET['rid']){
    $correct_lines = json_decode(file_get_contents('update/json/5.json'));
}elseif($_GET['type'] == 7 or $_GET['type'] == 8){
    $correct_lines = json_decode(file_get_contents('update/json/7.json'));
}

function preg_barcode($barcode){
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}
//echo '<pre>';var_dump($ss_dop);

$sums_report = explode("\n", 'totalPrice
finishedPrice
quantity
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
Obschaya_sebestoimosty');

if ($_GET['f1'] and $_GET['type'] == 8){
    foreach (explode("\n", $ss_dop) as $item) {
        $item = ru2Lat(trim($item));
        $sum_s_r[] = $item;
    }
    $sum_s_r[] = 'Stoimosty_edinicy_tovara';
}

if ((!$_GET['f1'] and $_GET['type'] == 8) or (!$_GET['rid'] and $_GET['type'] == 7)){
    foreach (explode("\n", $ss_dop) as $item) {
        $item = ru2Lat(trim($item));
        $sum_s_r[] = $item;
        $sums_report[] = $item;
    }
    $sums_report[] = 'Stoimosty_edinicy_tovara';
}
$sum_m = explode("\n",ru2Lat($ss_dop));
//echo '<pre>';var_dump($sums_report);
$l=0;
if($data){
    foreach ($data as $d_k_k => $dat) {
        if ($_GET['type'] == 5 and !$_GET['rid'] and $correct_lines) {
            foreach ($correct_lines as $correct_line) {
                if ($dat->rid == $correct_line->rid
                    and $dat->realizationreport_id == $correct_line->realizationreport_id) {

                    $data[$l]->storage_cost = $correct_line->storage_cost;
                    $data[$l]->acceptance_fee = $correct_line->acceptance_fee;
                    $data[$l]->other_deductions = $correct_line->other_deductions;
                }
            }
            $dat->total_payable = $dat->ppvz_for_pay - ($dat->delivery_rub + $dat->storage_cost + $dat->other_deductions + $dat->acceptance_fee);
        }
        if ($_GET['type'] == 7 or $_GET['type'] == 8) {

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
        $l++;
    }
    if ($_GET['type'] == 7 or $_GET['type'] == 8 or $_GET['type'] == 5) {
        $l = 0;
        foreach ($data as $d_k_k => $dat) {
            if ($_GET['type'] == 7 or $_GET['type'] == 8){
                if ($dat->category == null) {
                    $data[$l]->category = $data[$l - 1]->category;
                }
                if ($dat->subject == null) {
                    $data[$l]->subject = $data[$l - 1]->subject;
                }
                if ($dat->brand == null) {
                    $data[$l]->brand = $data[$l - 1]->brand;
                }

            }

            if ($_GET['type'] == 8){
                foreach ($dat as $gk => $gv) {
                    if (in_array($gk, ['date','dateClose','lastChangeDate','refund_color','image','supplierArticle', 'barcode', '', 'save', "subject", "category", "brand", "warehouseName", "status"]) === false) {
                        if (($_GET['f1'] and $gk != 'incomeId') or (!$_GET['f1'])){
                            $ITOGO_SUMS[$gk] += $gv;
                        }
                    }
                }
            }
            if ($_GET['type']==8 and !$_GET['f1']){
                $data[$l]->incomeId = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&f1=".$data[$l]->supplierArticle."&dt=".$_GET['dt']."'>".$data[$l]->incomeId." шт</a>";
            }


            foreach ($dat as $d_k => $da) {
                foreach ($sums_report as $fieldsum) {
                    $fieldsum = trim($fieldsum);
                    if (is_numeric($da) and $d_k == $fieldsum) {
                        $data[$l]->$d_k = number_format((string)$da, 2, '.', ' ');
                    }
                }
                if ($d_k == 'cancel_dt' and ($da == '0001-01-01 00:00:00' or $da == null or !$da)) {
                    $data[$l]->$d_k = '';
                }
            }
            $l++;
        }

        if ($ITOGO_SUMS and $_GET['type'] == 8){
            foreach ($ITOGO_SUMS as $key => $ITOGO_SUM) {
                $ITOGO_SUMS[$key] = number_format((string)$ITOGO_SUM, 2, '.', ' ');
            }
            $data[] = $ITOGO_SUMS;
        }
    }
}
file_put_contents('cache/data.json', json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_ERROR_INF_OR_NAN | LOCK_EX));
$dom = $_GET['type'] ? $_GET['type'] : '';
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

<script type = "text/javascript">

    <?php if ($_GET['type'] == 8): ?>
    setInterval(() => {
        let img = document.querySelectorAll('td.x-grid-cell-supplierArticle img');
        let i= 0;
        while (i<img.length){
            if (i != Ext.select("td.x-grid-cell-supplierArticle").elements.length-1){
                img[i].hidden = false;
            }
            i++;
        }
        <?php if ($_GET['f1']): ?>
        let sss_one = Ext.select("td.x-grid-cell-ss_one");
        let sss_all = Ext.select("td.x-grid-cell-ss_all");
        let store_data = store.data.map;
        let inp = document.querySelectorAll('input.inputValue');
        i= 0;
        while (i<inp.length){
            if (inp[i].getAttribute('idd') != 'Data-'+sss_all.elements.length){
                inp[i].hidden = false;
            }
            i++;
        }

        Ext.select("td.x-grid-cell-supplierArticle img").last().update('');
        let inputs = <?=json_encode($sum_s_r);?>;
        let sum = 0;
        i= 0;
        sum_ss_one = store_data['Data-'+sss_one.elements.length].data.ss_one;
        sum_ss_all = store_data['Data-'+sss_all.elements.length].data.ss_all;
        while (i<inputs.length){
            s_inputs = Ext.select("td.x-grid-cell-" + inputs[i]);
            sum = store_data['Data-'+s_inputs.elements.length].data[inputs[i]];
            if (!sum){
                sum = 0;
            }
            s_inputs.last().update('<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum + '</div>');
            i++;
        }
        sss_one.last().update('<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_ss_one + '</div>');
        sss_all.last().update('<div unselectable="on" class="x-grid-cell-inner " style="text-align:left;">' + sum_ss_all + '</div>');
        <?php endif; ?>
    }, 500);

    <?php endif; ?>

    <?php
    if ($_GET['type'] == 5 or $_GET['type'] == 7 or $_GET['type'] == 8){
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
            }]
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
if ($_GET['type']==5) {
    echo '<script>
    function number_update(id,val,name,real,rid) {
        $.post("/wb/update/update_five.php", {val:val, name:name, real:real, rid:rid}, function (res){
            var re = /\B(?=(\d{3})+(?!\d))/g;
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
            Ext.select("td.x-grid-cell-total_payable").item(id.replace("Data-","")-1).update("<div unselectable=\"on\" class=\"x-grid-cell-inner \" style=\"text-align:left;\"><a href=\"?page=wb&amp;type='.$_GET["type"].'&amp;rid="+(Ext.select("td.x-grid-cell-realizationreport_id").item(id.replace("Data-","")-1).dom.innerText)+"\">"+(total_payable.toFixed(2).replace(re," "))+"</a></div>");
            store.data.map[id].data.total_payable = total_payable.toFixed(2).replace(re," ");
        });
    }
</script>';
}elseif ($_GET['type']==7) {
    echo '<script>
    function number_update(id,val,name,real,rid,barcode) {
        $.post("/wb/update/update_seven.php", {val:val, name:name, real:real, rid:rid, barcode:barcode}, function (res){
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
        $.post("/wb/update/update_eight.php", {val:val, name:name, real:real, rid:rid, barcode:barcode}, function (res){
            
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
        store_data[id].data.ss_one = summinput.toFixed(2).replace(re," ");
        store_data[id].data.ss_all = (summinput*summkol).toFixed(2).replace(re," ");
        
        
        let inputs = document.querySelectorAll("input.inputValue#"+name);
       
        sum = 0;
        i = 0;
        while (i<inputs.length){
                sum += Number((inputs[i].value).toString().replace(" ",""));
                i++;
            }
       store_data["Data-"+Ext.select("td.x-grid-cell-"+name).elements.length].data[name] = sum.toFixed(2).replace(re," ");
        
        let sum_inp = document.querySelectorAll("div.inputSum");
        sum = 0;
        i = 0;
        while (i<sum_inp.length){
                sum += Number((sum_inp[i].innerHTML).toString().replace(" ",""));
                i++;
        }
        store_data["Data-"+Ext.select("td.x-grid-cell-ss_one").elements.length].data.ss_one = sum.toFixed(2).replace(re," ");
        
        let sum_kol = document.querySelectorAll("div.inputSumKol");
        sum = 0;
        i = 0;
        while (i<sum_kol.length){
                sum += Number((sum_kol[i].innerHTML).toString().replace(" ",""));
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
}
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



$('#stat').html("Суммарное кол-во товаров: <b><?=abs($CNT_SUM); ?></b> | Суммарная цена: <b><?=number_format($PRICE_SUM, 2, '.', ''); ?></b> руб. <?=$ret?>");

<?php
} ?>


</script>


        </form>


    </div>
</div>
