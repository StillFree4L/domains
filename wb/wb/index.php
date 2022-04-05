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

<?php
$resultStatusData = mysqli_query($link, 'SELECT type,status FROM `wb_data` WHERE userId='.$USER["id"]);
$arrStatusData = [];
$keyStatusData = ['0','1','2','3'];
foreach ($resultStatusData as $key => $value) {
  $arrStatusData[] = $value['status'];
}
?>
<script type='text/javascript'>
setTimeout(() => {
<?php if(in_array("0",$arrStatusData)): ?>
  $('.grid-data-empty')[0].innerHTML = '<font color="red">Данные отсутствуют или нет ответа от API-сервера. <a href="#" style="color: red; text-decoration: revert;" onclick="parent.location.reload(); return false;">Попробуйте позднее</a></font>';
  $('.loadForcibly')[0].innerHTML = '<font color="red">Данные отсутствуют или нет ответа от API-сервера. <a href="#" style="color: red; text-decoration: revert;" onclick="parent.location.reload(); return false;">Попробуйте позднее</a></font>';
<?php elseif(in_array("1",$arrStatusData)): ?>

  $('.grid-data-empty')[0].innerHTML = '<font class="loading" color="#0059fc">Получение данных</font>';
  $('.loadForcibly')[0].innerHTML = '<font class="loading" color="#0059fc">Получение данных</font>';

<?php elseif(in_array("2",$arrStatusData)): ?>

  $('.grid-data-empty')[0].innerHTML = '<font color="green">Данные полученны успешно</font>';
  $('.loadForcibly')[0].innerHTML = '<font color="green">Данные полученны успешно</font>';

<?php elseif(in_array("3",$arrStatusData)): ?>

  $('.grid-data-empty')[0].innerHTML = '<font color="green">Данные обновлены. <a href="#" style="color: green; text-decoration: revert;" onclick="parent.location.reload(); return false;">Перезагрузите страницу</a></font>';
  $('.loadForcibly')[0].innerHTML = '<font color="green">Данные обновлены. <a href="#" style="color: green; text-decoration: revert;" onclick="parent.location.reload(); return false;">Перезагрузите страницу</a></font>';

<?php endif; ?>
}, 1000);
</script>

<script defer type="text/javascript">
//async load data
let i=1;
while(i<12){
  if(i===3 || i===4){i=5;}
  $.ajax({
    method: "GET",
    url: "/wb/load.php?type="+i+"&async=on",
    async: true,
    timeout: 10000,
  }).done(function() {
      return false;
    });
  i++;
}

 function typeLoad() {
   let i=1;
   while(i<12){
     if(i===3 || i===4){i=5;}
     $.ajax({
       method: "GET",
       url: "/wb/load.php?type="+i+"&async=on&forcibly=on",
       async: true,
       timeout: 10000,
     }).done(function() {
         return false;
       });
     i++;
   }
 }

//valid key
setTimeout(() => {
  $.get("/wb/valid.php", function (dt){
      document.querySelectorAll('label#api_old')[0].innerHTML = 'Ключ api старый: '+JSON.parse(dt).data.url;
      document.querySelectorAll('label#api_new')[0].innerHTML = 'Ключ api новый: '+JSON.parse(dt).data.url_new;
      document.querySelectorAll('label#api_supplier')[0].innerHTML = 'Ключ поставщика: '+JSON.parse(dt).data.url_supplier;
  });
}, 1000);
</script>

<div class="panel panel-default" style="margin: 0px 10px 10px 10px;">
    <div class="panel-heading" style="display: flex;"><h4 style="font-size: 13px;margin-top: 5px;">Продажи и заказы Wildberries</h4>
        <div class="dropdown" style="z-index: 99;">
          <input class='dropbtn' title='Очистить строку'  value='' style='margin-top: 4px;' onclick="myFunction()">
            <div id="myDropdown" class="dropdown-content">
                <form method="post">
                    <fieldset>
                        <p><label id="api_new" for="api">Ключ api новый: <font color="coral">проверка...</font></label><input style="width: 100%" type="text" name="key1" id="api" placeholder="<?=($auth ? 'введен' : 'не введен')?>"></p>
                        <p><label id="api_old" style="padding-top: 5px" for="stats">Ключ api старый: <font color="coral">проверка...</font></label><input style="width: 100%" type="text" name="key2" id="stats" value="<?=$wb_key_new?>"></p>
                        <p><label id="api_supplier" style="padding-top: 5px" for="supplierId">Ключ поставщика: <font color="coral">проверка...</font></label><input style="width: 100%" type="text" name="key3" id="supplierId" value="<?=$supplierId?>"></p>
                        <p><label id="api_nalog" style="padding-top: 5px; margin-right:5px;" for="nalog">Процент налога: </label><input style="width: 3%" type="text" name="key4" id="nalog" value="<?=$perc?>">
                          <label id="api_doh" style="padding-top: 5px; margin-right:5px;" for="doh1"><input type="radio" name="key5" value="off" id="doh1" <?=$pay=='off' ? "checked" : ""?>> Доходы</label>
                          <label id="api_doh" style="padding-top: 5px; margin-right:5px;" for="doh2"><input type="radio" name="key5" value="on" id="doh2" <?=$pay=='on' ? "checked" : ""?>> Доходы - Расходы</label>
                        </p>
                    </fieldset>
                    <p  style="padding-top: 5px"><input type="submit" value="Изменить"></p>
                </form>
            </div>
        </div>
        <h4 style="margin-top: 5px;font-size: 13px;"><?=$buf2[0] ? date('d.m.Y H:i:s T',$buf2[0]) : $buf2[0]?></h4>
        <button class="btn btn-sm btn1 btn-color" id="quan" onclick="typeLoad();$('.loadForcibly').show();" style="border-radius: 4px; margin-right: 10px; margin-left: 10px; font-size: 12px; padding: 4px 6px; margin-top: px;">Обновить</button>
        <h4 style="margin-top: 5px;font-size: 13px;" class="loadForcibly"><font class="loading" color="#0059fc">Получение данных</font></h4>

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
//category
if (isset($_GET['dt1'])) $dop_dts_range = '&dt1=' . $_GET['dt1'] . '&dt2=' . $_GET['dt2'];
$tps_res = [2 => 'Заказы', 1 => 'Продажи', /*3 => 'Отмененные заказы', 4 => 'Отмененные продажи',*/ 10 => 'Возврат', 5 => 'Отчеты по реализации', 6 => 'Склад', 7 => 'Поставки', 8 => 'Себестоимость', 9 => 'Чистая прибыль', 11 => 'Калькулятор'];

foreach ($tps_res as $key => $value)
{
    $pressed = '';
    if ($key == $_GET['type']) $pressed = 'btn-success';
    echo "<a  href='?page=wb&type=$key&dt=" . $_GET['dt'] . "&$dop_dts_range' class='btn btn-sm $pressed' style='rfloat: right; display: inline-block; qmargin: 0px 5px; border: 1px solid #ccc; '>$value </a> &nbsp;";
}

//period
if (!in_array($_GET['type'],[5,6,8,9,11]))
{
    echo '<hr style="margin-top: 10px; margin-bottom: 10px;">';

    $stats_res = ['Сегодня' => date('Y-m-d', time()) , 'Вчера' => date('Y-m-d', time() - 60 * 60 * 24) , '7 дней' => date('Y-m-d', time() - 60 * 60 * 24 * 7) ,
    '30 дней' => date('Y-m-d', time() - 60 * 60 * 24 * 30) , '90 дней' => date('Y-m-d', time() - 60 * 60 * 24 * 90) ];
    foreach ($stats_res as $key => $value)
    {
        $pressed = '';
        if ($value == $_GET['dt'] && !isset($_GET['dt1'])) $pressed = 'btn-warning';
        echo "<a  href='?page=wb&type=$_GET[type]&dt=" . $value . "' class='btn btn-sm  $pressed' style='border: 1px solid #ccc; '>$key </a> &nbsp;";
    }

//filter
?>


<input type="text" style="height: 30px; <?=strtotime($_GET['dt1']) > strtotime($_GET['dt2']) ? 'color: red;' : ""?>" id="dd1" class="dsingle ddd form-control" name="dt1" value="<?=$_GET['dt1']; ?>"  placeholder="">
<input type="text" style="height: 30px;" id="dd2" class="dsingle ddd form-control" name="dt2" value="<?=$_GET['dt2']; ?>"  placeholder="">

<a href='#' onclick="go_filtr(); return false;" class='btn btn-sm' style='border: 1px solid #ccc; '>Фильтровать </a>

<?php } ?>

<?php if (in_array($_GET['type'],[2,1,10,9,5])):

  $resultCheckbox = mysqli_query($link, 'SELECT name,value FROM `params` WHERE userId='.$USER["id"]);

  foreach ($resultCheckbox as $key => $value) {
    if($value['name'] == 'status'){
      $statusCheckbox = $value['value'];
    }
    if($value['name'] == 'option'){
      $optionCheckbox = $value['value'];
    }
    if($value['name'] == 'hide'){
      $hideCheckbox = $value['value'];
    }
  }

  if($optionCheckbox == "total"){
      $optionCheckbox = ' руб';
  }else{
      $optionCheckbox = ' шт';
  }


  ?>

<?php endif;?>

<?php if (in_array($_GET['type'],[2,1,10])):?>
<div class="tools-container" style="display: inline;float: right;">
  <button class="<?=$optionCheckbox!=" шт" ? "btn btn-sm btn1 btn-color" : "btn btn-sm btn1 btn-warning"?>" id="quan" onclick="quanButton()">Количество</button>
  <button class="<?=$optionCheckbox!=" руб" ? "btn btn-sm btn1 btn-color" : "btn btn-sm btn1 btn-warning"?>" id="total" onclick="totalButton()">Рубли</button>
  <button class="<?=$statusCheckbox=="line" ? "btn btn-sm btn1 btn-color" : "btn btn-sm btn1 btn-warning"?>" id="check" onclick="checkboxButton()">Сглаживание</button>
</div>
<?php elseif (in_array($_GET['type'],[9,5])):?>
  <div class="tools-container" style="display: inline;float: right;">
    <button class="btn btn-sm btn1 btn-color" id="total" onclick="showHideButton()">Выделить/снять выделение</button>
    <button class="<?=$statusCheckbox=="line" ? "btn btn-sm btn1 btn-color" : "btn btn-sm btn1 btn-warning"?>" id="check" onclick="checkboxButton()">Сглаживание</button>
  </div>
<?php endif;?>

<?php if (!in_array($_GET['type'],[5,6,8,9,11])){?>
<link rel="stylesheet" href="css/flatpickr.min.css">
<script src="js/flatpickr"></script>
<script src="js/ru.js"></script>


<script type="text/javascript">
   flatpickr(".dsingle", {
        "locale": "ru" , // locale for this instance only,
        enableTime: false,
        dateFormat: "d.m.Y",
    } );

function go_filtr() {
	a = '?page=wb&type=<?=$_GET['type']; ?>&dt1='+$('#dd1').val()+'&dt2='+$('#dd2').val();
	document.location.href = a;
}

</script>

<?php
}
//dt2 - dt1 = кол-во дней периода
//dt1 - кол-во дней периода = dt1_bar
function minusDate($dt1,$dt2){
    $dt = floor((strtotime($dt2)-strtotime($dt1))/(3600*24));
    $dt = date('Y-m-d',strtotime($dt1." -".$dt." DAY"));
    return $dt;
}

// настройки периодов для графиков
if (in_array($_GET['type'],[2,1,10])){
    if (($_GET['dt'] == date('Y-m-d')) or ($_GET['dt2'] == date('Y-m-d') and $_GET['dt1'] == date('Y-m-d'))) {
        $dt1_bar = date("Y-m-d", strtotime("-1 DAY"));
    } else {
        $dt1_bar = minusDate($_GET['dt'],date('d-m-Y'));
    }

    if ($_GET['dt'] and !$_GET['dt1']) {
        //фильтр по дням
        $dt2_bar = $dt1_bar_org = $_GET['dt'];
        $dt2_bar_org = date('Y-m-d');
        if ($_GET['dt'] == date('Y-m-d')) {
            $dt1_bar = date("Y-m-d", strtotime("-1 DAY"));
        } else {
            $dt1_bar = minusDate($_GET['dt'],date('d-m-Y'));
        }
    } else if($_GET['dt1'] == $_GET['dt2']) {
      $dt2_bar_org = $dt1_bar_org = $_GET['dt1'];
      $dt2_bar = $dt1_bar = date("Y-m-d", strtotime("-1 DAY",strtotime($_GET['dt1'])));
    } else {
        //фильтр по периодам
        $dt2_bar = $dt1_bar_org = $_GET['dt1'];
        $dt2_bar_org = $_GET['dt2'];
      //  if ($_GET['dt1'] == date('Y-m-d') or $_GET['dt2'] == date('Y-m-d')) {
        //    $dt1_bar = date("Y-m-d", strtotime("-1 DAY"));
      //  }else {
            $dt1_bar = minusDate($_GET['dt1'],$_GET['dt2']);
          //  echo '<pre>';var_dump($dt1_bar);
      //  }

        //echo '<pre>';var_dump(  $dt1_bar.' - '.  $dt2_bar);
    }

    require_once('blocks/bar/func.php');
}


// фильтр по датам и редактура данных заказов, продаж, возвратов
if ($tbl_rows && !in_array($_GET['type'],[5,6,8,9,11]))
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
      //  $cdt = $g->date;
        $cdt = $g->lastChangeDate;
        $last_key = - 1;



        if ($_GET['type'] == 3) $cdt = $g->cancel_dt;
        if ($_GET['type'] == 4) $cdt = $g->lastChangeDate;

        if($_GET['type'] == 10){
          $cdt = $g->date;
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
        if($g->cancel_dt){
          $g->cancel_dt = date('d.m.Y H:i:s', strtotime($g->cancel_dt));
        }

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
storage_cost Стоимость хранения
acceptance_fee Стоимость платной приемки
other_deductions Прочие удержания
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

<?php

//=================================================================================
//фильтры по типам вкладок
if(in_array($_GET['type'],[5,7,8,9,11])){
  $updateType = $_GET['type'];
  require_once('blocks/filter/'.$updateType.'.php');
}
//фильтры по типам вкладок
//=================================================================================

// Склад
if ($_GET['type'] == 6)
{
    if (isset($_GET['rid']) && isset($_GET['bc']))
    {
        $templateHTML = "<a href='?page=wb&type=7&dt=".$_GET['dt']."''>Все отчеты</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=".$_GET['dt']."'>".$_GET['rid'].'</a> по баркоду: '.$_GET['bc'];
    }
    elseif (isset($_GET['f1']))
    {
        $templateHTML = "<a href='?page=wb&type=6&dt=".$_GET['dt']."''>Склад</a>  / Баркод № <a href='?page=wb&type=6&f1=$_GET[f1]&dt=".$_GET['dt']."'>".$_GET['f1'].'</a>';
    }

    if ($tbl_rows){
        $last_key = -1;
        $sums = explode("\n", trim('quantity
quantityFull
quantityNotInOrders
inWayToClient
inWayFromClient
speed
Price
price_min_discount
Discount
coun'));
        foreach ($tbl_rows as $g) {

            $g->coun = 1;
            if ($g->Discount == 0) {
                $g->Discount = 100 - floor(($g->price_min_discount * 100) / $g->Price);
            }
            if (!$g->price_min_discount or $g->price_min_discount == 0 or $g->price_min_discount == null) {
                $g->price_min_discount = $g->Price - (($g->Price * $g->Discount) / 100);
            }
            if($g->speed_7 and $g->quantity){$g->speed = $g->quantity/$g->speed_7;}

            $g->lastChangeDate = date('d.m.Y H:i:s', strtotime($g->lastChangeDate));
            if (isset($_GET['f1']) && isset($_GET['bc'])) {
                if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

            } else if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;
            } else {
                if (isset($keys_bc[$g->barcode])) {
                    foreach ($sums as $fieldsum) {
                        $fieldsum = trim($fieldsum);
                            $reps[$keys_bc[$g->barcode]]->$fieldsum += $g->$fieldsum;
                    }
                    $reps[$keys_bc[$g->barcode]]->isSupply = $g->isSupply;
                    $reps[$keys_bc[$g->barcode]]->isRealization = $g->isRealization;
                    $reps[$keys_bc[$g->barcode]]->daysOnSite = $g->daysOnSite;
                    $reps[$keys_bc[$g->barcode]]->SCCode = $g->SCCode;
                  /*  if($reps[$keys_bc[$g->barcode]]->speed_7 and $reps[$keys_bc[$g->barcode]]->quantity){
                      $reps[$keys_bc[$g->barcode]]->speed = $reps[$keys_bc[$g->barcode]]->quantity/$reps[$keys_bc[$g->barcode]]->speed_7;
                    }*/
                    //var_dump($g->quantity/$g->speed_7);
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
        if(is_numeric($_GET['f1'])){
            $tmpN = number_format((string)$_GET['f1'], 2, '.', ' ');
        }else{
            $tmpN = $_GET['f1'];
        }
        $templateHTML = "<a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>Заказы</a>  / Баркод № <a href='?page=wb&type=" . $_GET['type'] . "&f1={$_GET['f1']}&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a>';
    }
    elseif (isset($_GET['f2'])) {
        if(is_numeric($_GET['f3'])){
            $tmpN = number_format((string)$_GET['f3'], 2, '.', ' ');
        }else{
            $tmpN = $_GET['f3'];
        }
        $templateHTML = "<a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>Заказы</a>  / " . (isset($tbl_keys[$_GET['f2']]) ? mb_ucfirst($tbl_keys[$_GET['f2']]) : '') . ": <a href='?page=wb&type=" . $_GET['type'] . "&f2={$_GET['f2']}&dt=" . $_GET['dt'] . "&f3={$_GET['f3']}'>" . $tmpN . '</a>';
    }

    if($tbl_rows) {
        $tbl_rows = array_reverse($tbl_rows);

        foreach ($tbl_rows as $g) {
            $g = (object)$g;

            if ((!$g->isCancel && $_GET['type'] == 3) || (!$g->isCancel && $g->oblast && $_GET['type'] == 10)) continue;

            if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;

            }

            $reps[] = $g;
            $last_key = count($reps) - 1;
            $keys_bc[$g->barcode] = $last_key;
            $keys_bc2[$g->barcode] = $last_key;

            $g->barcode = '<a href="./index.php?page=wb&type=' . $_GET['type'] . '&f1=' . $g->barcode . '&dt=' . $_GET['dt'] . $dop_dts_range . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';

        }

        $tbl_rows = $reps;
    }
}

// Продажи - группировка
if ($_GET['type'] == 1 || $_GET['type'] == 4 || $_GET['type'] == 10) {

    if($_GET['type'] == 10){
        $text_ten = 'Возвраты';
    }
    else{
        $text_ten = 'Продажи';
    }

    if (isset($_GET['rid']) && isset($_GET['bc'])) {
        //  echo "<h4><a href='?page=wb&type='.$_GET['type'].'&dt=". $_GET['dt'] ."''>Все отчеты</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=" .$_GET['dt']. "'>" . $_GET['rid'] . '</a> по штрихкоду: ' .$_GET['bc'] . '</h4>';
    }
    elseif (isset($_GET['f1'])) {
        if(is_numeric($_GET['f1'])){
            $tmpN = number_format((string)$_GET['f1'], 2, '.', ' ');
        }else{
            $tmpN = $_GET['f1'];
        }
        $templateHTML = "<a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>".$text_ten."</a>  / Баркод № <a href='?page=wb&type=" . $_GET['type'] . "&f1={$_GET['f1']}&dt=" . $_GET['dt'] . "'>" . $tmpN . '</a>';
    }
    elseif (isset($_GET['f2'])) {
        if(is_numeric($_GET['f3'])){
            $tmpN = number_format((string)$_GET['f3'], 2, '.', ' ');
        }else{
            $tmpN = $_GET['f3'];
        }
        $templateHTML = "<a href='?page=wb&type=" . $_GET['type'] . "&dt=" . $_GET['dt'] . "''>".$text_ten."</a>  / " . (isset($tbl_keys[$_GET['f2']]) ? mb_ucfirst($tbl_keys[$_GET['f2']]) : '') . ": <a href='?page=wb&type=" . $_GET['type'] . "&f2={$_GET['f2']}&dt=" . $_GET['dt'] . "&f3={$_GET['f3']}'>" . $tmpN . '</a>';
    }

            $tbl_rows = array_reverse($tbl_rows);

        foreach ($tbl_rows as $g) {
            if (($g->totalPrice > 0 && $_GET['type'] == 4) || ($g->totalPrice > 0 and $_GET['type'] == 10 and $g->forPay)) continue;
            if (isset($_GET['f1'])) {
                if ($g->barcode != $_GET['f1']) continue;
            }
            $reps[] = $g;
            $g->barcode = '<a href="./index.php?page=wb&type=' . $_GET['type'] . '&f1=' . $g->barcode . '&dt=' . $_GET['dt'] . $dop_dts_range . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';
        }
        $tbl_rows = $reps;
}
////////////////////////////////////////////////////////////////////

$title = '';
foreach ($tps_res as $key => $value) {
    if ($key == $_GET['type'])
        $title = $value;
}

$fields = array_keys($tbl_keys);

if($_GET['type'] == 11){
array_unshift($fields, 'checkbox_del');
$columns[] = (object) [
    'text' => '<span data-qtip="Выделение строк">Выделение строк</span>',
    'id' => 'checkbox_del',
    'dataIndex' => 'checkbox_del',
    'sortable' => false,
    'hideable' => true,
    'width' => 45
];

}
array_unshift($fields, 'image');

//столбцы таблиц
$columns[] = (object) [
    'text' => '<span data-qtip="Изображение">Изображение</span>',
    'id' => 'image',
    'dataIndex' => 'image',
    'sortable' => false,
    'hideable' => true,
    'width' => 80
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

if($tbl_rows){
    $tbl_rows = array_reverse($tbl_rows);
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

function preg_barcode($barcode){
    preg_match_all("'>(.*?)<'si", $barcode, $match);
    return $match[1][1];
}

if ($tbl_rows and count($tbl_rows)) {
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
      /*  //по умолчанию 0
        foreach ($sums_null as $fie) {
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

        $data_cols = [];

        foreach ($sums as $fieldsum) {
            $fieldsum = trim($fieldsum);
            $g->$fieldsum = abs($g->$fieldsum);
        }

        $flag = 0;
        $REFUND_COLOR = '';

        if ($_GET['type'] == 1) {
            if ($g->isCancel == 1 || $g->forPay < 0 || $g->doc_type_name == 'Возврат' || $g->finishedPrice < 0 || $g->RED == 1) $REFUND_COLOR = 'danger'; //$flag = 1;
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

        if ($_GET['type'] == 1 and ($g->isCancel != 1 && $g->forPay > 0 && $g->doc_type_name != 'Возврат' && $g->finishedPrice > 0 && $g->RED != 1)) {
             $PRICE_SUM += $g->forPay * $g->quantity;
         }
         elseif ($_GET['type'] == 2 and ($g->isCancel != 1 and $g->doc_type_name != 'Возврат' and $g->finishedPrice > 0 and $g->RED != 1)) {
             $PRICE_SUM += $g->finishedPrice;
         }
         elseif ($_GET['type'] == 10) {
             $PRICE_SUM += $g->finishedPrice;
            // $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
         }
         elseif(!in_array($_GET['type'],[1,2,10])) {
             $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
         }

    //  $g->isCancel == 1 || $g->forPay < 0 || $g->doc_type_name == 'Возврат' || $g->finishedPrice < 0 || $g->RED == 1
    /*   if (($_GET['type'] == 1 and $g->forPay >= 0) || $_GET['type'] == 4) {
            $PRICE_SUM += $g->forPay * $g->quantity;
        }
        elseif ($_GET['type'] == 2 and $g->totalPrice >= 0 and $g->isCancel != 1) {
            $PRICE_SUM += $g->finishedPrice;
        }
        elseif ($_GET['type'] == 10) {
            $PRICE_SUM += $g->finishedPrice;
        }
        elseif(!in_array($_GET['type'],[1,2,10])) {
            $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
        }
*/

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

if((($_GET['type']==5 or $_GET['type']==11) and !$_GET['rid']) or (in_array($_GET['type'],[7,8]) and ($_GET['rid'] or $_GET['f1']))){
  $rid="";
  if($_GET['type']==7 and $_GET['rid']){$rid=' and incomeId="'.$_GET['rid'].'"';}
  elseif($_GET['type']==8 and $_GET['f1']){$rid=' and supplierArticle="'.$_GET['f1'].'"';}
  if(in_array($_GET['type'],[7,8])){$type=7;}else{$type=$_GET['type'];}

  $result = mysqli_query($link, 'SELECT * FROM `goods` WHERE `userId`='.$USER["id"].' and `type`='.$type.$rid);

  if ($result == false) {
    print(mysqli_error($link));
  }
}
elseif($_GET['type']==9 and !$_GET['rid']){

  $result5 = mysqli_query($link, 'SELECT * FROM `goods` WHERE `userId`='.$USER["id"].' and `type`=5');

  if ($result == false) {
    print(mysqli_error($link));
  }
}
if($_GET['type']==11 and !$_GET['rid']){

  $result12 = mysqli_query($link, 'SELECT * FROM `goods` WHERE `userId`='.$USER["id"].' and `type`=12');

  if ($result == false) {
    print(mysqli_error($link));
  }
}

/*
if (in_array($_GET['type'],[5,9]) and !$_GET['rid']){
    $correct_lines = file_read('5');
}elseif(in_array($_GET['type'],[7,8,9])){
    $correct_lines = file_read('7');
}elseif(in_array($_GET['type'],[11])){
    $correct_lines = file_read('11');
    $correct_lines_products = file_read('products');
}
*/

  if($_GET['type']!=11){
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
speed
speed_7_order
speed_7
speed_30
Discount
priceWithDisc
nalog7
all_cost
pribil
marga');
}
// 0 умолчанию
  if($_GET['type']!=11){
$dat_null = explode("\n", 'speed
techSize
fbs
fbo
fbs_fbo
discountPercent
promoCodeDiscount
spp
priceWithDisc
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
marga
storage_cost
acceptance_fee
other_deductions
speed_back');
}


if($ss_dom_lat){
    foreach ($ss_dom_lat as $k => $item) {
        $dat_null[] = $item;
      //  var_dump($item);
    }

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
office_name
isCancel
cancel_dt');
}

if (in_array($_GET['type'],[7,8,9])) {
    foreach (explode("\n", $ss_dop) as $item){
        $item = ru2Lat(trim($item));
        $sum_s_r[] = $item;
        if (!$_GET['f1'] and !$_GET['rid']){
          $sums_report[] = $item;
        }
        if($_GET['type']==9 and $_GET['rid']){
          $sums_report[] = $item;
        }
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
            if ($dat->speed and strpos($dat->speed, '~') === false){
                $dat->speed = '~ '.number_format((string)$dat->speed, 2, '.', ' ');
            }else{
              $dat->speed = '~ 0';
            }
        }

        if($result and (($_GET['type']==7 and $_GET['rid']) or ($_GET['type']==8 and $_GET['f1']))){

          foreach ($result as $key => $value) {
            if($value['barcode']==$dat->barcode and $value['incomeId']==$dat->incomeId and $value['supplierArticle']==$dat->supplierArticle){
              $tmp = $value['name'];
              $dat->$tmp = (int)$value['value'];
              if($_GET['type']==7)$dat->Obschaya_sebestoimosty_edinicy_tovara += (int)$value['value'];
              if($_GET['type']==8)$dat->ss_one += (int)$value['value'];
          }
        }
        if($_GET['type']==7)$dat->Obschaya_sebestoimosty_s_uchetom_kolichestva = intval((int)$dat->quantity * (int)$dat->Obschaya_sebestoimosty_edinicy_tovara);
        if($_GET['type']==8)$dat->ss_all = intval((int)$dat->quantity * (int)$dat->ss_one);

      }else if($result and (($_GET['type']==7 and !$_GET['rid']) or ($_GET['type']==8 and !$_GET['f1']))){

        foreach ($dat as $d_k => $da) {
          if ($d_k == 'Stoimosty_edinicy_tovara' or in_array($d_k, $sum_m) or in_array('_' . $d_k, $sum_m)) {
            if($_GET['type']==7)$dat->Obschaya_sebestoimosty += (int)$da;
            if($_GET['type']==8)$dat->ss_all += (int)$da;
          }
        }
        if($_GET['type']==7)$dat->Srednyaya_sebestoimosty_edinicy = intval((int)$dat->Obschaya_sebestoimosty / (int)$dat->quantity);
        if($_GET['type']==8)$data[$l]->ss_one = intval((int)$data[$l]->ss_all / (int)$data[$l]->quantity);

      }

      if ($result and $_GET['type']==5 and !$_GET['rid']){
        foreach ($result as $key => $value) {
          if($value['realizationreport_id']==$dat->realizationreport_id){
            $tmp = $value['name'];
            $dat->$tmp = (int) $value['value'];
          }
        }
        $dat->total_payable = $dat->ppvz_for_pay - ($dat->delivery_rub + $dat->storage_cost + $dat->other_deductions + $dat->acceptance_fee);
      }

      if ($result5 and $_GET['type']==9 and !$_GET['rid']){
        foreach ($result5 as $key => $value) {
          if($value['realizationreport_id']==$dat->realizationreport_id){
            $tmp = $value['name'];
            $dat->$tmp = (int) $value['value'];
          }
        }
        foreach ($dat as $d_k => $da) {
          if ($d_k == 'Stoimosty_edinicy_tovara' or in_array($d_k, $sum_m) or in_array('_' . $d_k, $sum_m)) {
            $dat->ss_one += (int)$da;
          }
        }
        $dat->total_payable = intval($dat->ppvz_for_pay - ($dat->delivery_rub + $dat->storage_cost + $dat->other_deductions + $dat->acceptance_fee));
        if ($pay == 'off'){$dat->nalog7 = $dat->retail_amount * ($perc/100);}
        else{$dat->nalog7 = ($dat->retail_amount - ($dat->storage_cost + $dat->acceptance_fee + $dat->other_deductions + $dat->delivery_rub + $dat->ppvz_vw + $dat->ppvz_vw_nds + $dat->nalog7 + $dat->ss_one)) * ($perc/100);}
        $dat->all_cost = $dat->storage_cost + $dat->acceptance_fee + $dat->other_deductions + $dat->delivery_rub + $dat->ppvz_vw + $dat->ppvz_vw_nds + $dat->nalog7 + $dat->ss_one;
        $dat->pribil = $dat->retail_amount - $dat->all_cost;
        $dat->marga = ($dat->pribil / $dat->all_cost)*100;

      }else if($_GET['type']==9 and $_GET['rid']){
        if ($pay == 'off'){$dat->nalog7 = $dat->retail_amount * ($perc/100);}
        else{$dat->nalog7 = ($dat->retail_amount - ($dat->delivery_rub + $dat->ppvz_vw + $dat->ppvz_vw_nds + $dat->ss_one)) * ($perc/100);}

        $dat->all_cost = $dat->delivery_rub + $dat->ppvz_vw + $dat->ppvz_vw_nds + $dat->nalog7 + $dat->ss_one;
        $dat->pribil = $dat->retail_amount - $dat->all_cost;
        $dat->marga = ($dat->pribil / $dat->all_cost)*100;
        $dat->total_payable = $dat->ppvz_for_pay - $dat->delivery_rub;
      }

      if($result and $_GET['type']==11){
        foreach ($result as $key => $value) {
          if($value['barcode']==$dat->barcode and $value['supplierArticle']==$dat->supplierArticle){
            $tmp = $value['name'];
            $dat->$tmp = (int)$value['value'];
        }
      }
    }

        if ($dat_null){
            foreach ($dat_null as $kitem) {
                $kitem = trim($kitem);
                if (!$dat->$kitem || $dat->$kitem==null) {
                    $dat->$kitem = (int)0;
                }
            }
        }
        if ($dat_minus){
            foreach ($dat_minus as $kitem) {
                $kitem =trim($kitem);
                if (!$dat->$kitem or $dat->$kitem =='01.01.0001 00:00:00'){
                    $dat->$kitem = '---';
                }
            }
        }
        $l++;
    }

}

  //file_put_contents('cache/data.json', json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_ERROR_INF_OR_NAN));

$dom = ($_GET['type'] ? $_GET['type'] : '').($_GET['rid'] ? $_GET['rid'] : ($_GET['f1'] ? $_GET['f1'] : ""));

if ($_GET['type'] == 1 or $_GET['type'] == 2){
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
}


if (in_array($_GET['type'],[2,1,10])){
    require_once('blocks/bar/bar.php');
}

if (in_array($_GET['type'],[5,9]) and !$_GET['rid']){
    require_once('blocks/bar/bar_five.php');
}

$whileTbar = false;
if(!in_array($_GET['type'],[7,8,9,5,11]) or ($_GET['type'] == 5 and $_GET['rid']) or ($_GET['type'] == 9 and $_GET['rid'])){
    $whileTbar = true;
}


if($result12 and $_GET['type']==11){
  $tmpArr = [];
  $i=0;
  foreach ($result12 as $key => $value) {
    $valTmp=false;
    if($tmpArr[0]){
      foreach ($tmpArr as $tmpK => $tmpV) {
        if($tmpV['checkbox_del'] == $value['goods']){
          $tmpArr[$tmpK][$value['name']] = $value['value'];
          $valTmp=true;
        }
      }
    }
    if($valTmp==false){
      $tmpArr[$i]['checkbox_del'] = $value['goods'];
      $tmpArr[$i][$value['name']] = $value['value'];
    }
    $i++;
  }
  foreach ($tmpArr as $key => $value) {
    array_unshift($data, $value);
  }
}

//  var_dump($data[0]);

?>

<thead>


<tr>


</tr>
</thead>


<div style="overflow: hidden;" id="grid<?php echo $_GET['type'] ? $_GET['type'] : ''; ?><?php echo $_GET['rid'] ? $_GET['rid'] : ($_GET['f1'] ? $_GET['f1'] : ""); ?>" class="grid"></div>


<script>
    var title = '<?php echo $title; ?>';
    var fields = <?php echo json_encode($fields); ?>;
    var data = <?php echo json_encode($data,JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_ERROR_INF_OR_NAN); ?>;
    var columns = <?php echo json_encode($columns); ?>;
</script>

<link href="css/theme-triton-all.css" rel="stylesheet" />
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/ext-all.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/classic/locale/locale-ru.js"></script>-->
<script type="text/javascript" src="js/ext-all.js"></script>
<script type="text/javascript" src="js/locale-ru.js"></script>

<?php
if(in_array($_GET['type'],[9])){
  $updateType = $_GET['type'];
  require_once('blocks/interval/'.$updateType.'.php');
}

require_once('blocks/renderer.php');
?>

<script type = "text/javascript">

    //console.log(document.referrer+' - '+document.location.href);

    <?php
    /*if (in_array($_GET['type'],[6,7,8,9]) && document.referrer && document.location.href && document.referrer !== document.location.href && (performance.navigation.type==255 || performance.navigation.type==0)){
        echo 'localStorage.clear();';
    }*/
    ?>
    if(performance.navigation.type == 2)
    {
        document.location.reload();
    }

    Ext.onReady(function() {
        Ext.QuickTips.init();

        Ext.define('Data', {
            extend: 'Ext.data.Model',
            fields: fields,
        });

        store = Ext.create('Ext.data.Store', {
            autoLoad: true,
            autoSync: true,
            model: 'Data',
            data: data,
            proxy: {
                type: 'rest',
              reader: {
                    type: 'json',
                    rootProperty: 'data'
                },
                writer: {
                    type: 'json',
                    rootProperty: 'data'
                },
                api: {
                    //read: 'cache/data.json',
                    update: 'update/update.php',
                    destroy: 'update/update.php',
                }
            }
        });

        store.load();
        Ext.state.Manager.setProvider(new Ext.state.LocalStorageProvider());

        grid = Ext.create('Ext.grid.Panel', {
            renderTo: 'grid<?php echo $dom; ?>',
            store: store,
            padding: '10 10 10 10',
            height: 600,
            enableColumnMove: true,
            enableColumnResize: true,
           // title: title,
            columns: columns,
            stateful: true,
            features: [{
                groupHeaderTpl: '{columnName}: {name} ({children.length})',
                ftype:'grouping',
                listeners: {
                  beforecellmousedown: function () {
                    return false;
                  },
                  onGroupMenuItemClick: function() {
                    alert('click');
                  }
                }
            }

            <?php if($data and (in_array($_GET['type'],[7,8,9]) or ($_GET['type']==5 and !$_GET['rid']))): ?>
            ,{
              ftype: 'summary'
            }
            <?php endif;?>

            ],
            stateId: 'grid<?php echo $dom; ?>',
            stateEvents: ['columnmove', 'columnresize', 'columnhide', 'columnshow'],
            viewConfig: {
                getRowClass: function(record, rowIndex, rowParams, store){
                    return record.get("refund_color") ? record.get("refund_color") : "";
                },
                preserveScrollOnRefresh: true,
                deferEmptyText: true,
                emptyText: '<div class="grid-data-empty"><font class="loading" color="#0059fc">Получение данных</font></div>'
            },
            tbar:[

                <?php //кнопка + -
                if($whileTbar):
                ?>
                {
                id:'plus1',
                iconCls:'x-fa fa-plus-square',
                hidden: true,
                handler:function(btn) {
                    btn.up('grid').getView().findFeature("grouping").expandAll();
                }
            },{
                id:'minus1',
                iconCls:'x-fa fa-minus-square',
                hidden: true,
                handler:function(btn) {
                    btn.up('grid').getView().findFeature("grouping").collapseAll();
                }
            },
                <?php endif;?>

                <?php //sum count
                if (!in_array($_GET['type'],[5,6,7,8,9,11])):
                if ($_GET['type'] == 10){$PRICE_SUM *= -1;}
                ?>
                {
                    xtype: 'label',
                    text: '<?=$lenghtDate['Текущий период'][0] ? $lenghtDate['Текущий период'][0] : (!is_array($lenghtDate['Текущий период']) ? $lenghtDate['Текущий период'] : '')?>',
                    style:{
                        fontWeight: 'normal',
                    },
                },{
                    xtype: 'label',
                    text: '<?=number_format($PRICE_SUM, 2, '.', ' ')?> руб',
                    style:{
                        fontWeight: 'normal',
                    },
                },{
                    xtype: 'label',
                    text: '<?=abs($CNT_SUM)?> шт',
                    style:{
                        fontWeight: 'normal',
                    },
                },
                <?php endif;?>
                <?php //кнопка вкл откл возвраты
                if (!in_array($_GET['type'],[5,6,7,8,9,11,10])): ?>
                {
                    href: '<?=$return_url?>',
                    text: '<?=$config_return=='on' ? 'Откл' : 'Вкл'?> возвраты',
                    handler: function() {
                        if (this.href) {
                            window.location.href = this.href;
                        }
                    }
                },
                <?php endif;?>

                <?php
                if($templateHTML):
                ?>
                {
                    xtype : 'label',
                    html  : "<?=$templateHTML?>",
                    style:{
                        fontWeight: 'normal',
                    },
                },
                <?php endif;?>

                <?php //группировка
                if($whileTbar):?>
                {
                    xtype : 'label',
                    html  : ' ',
                },
                <?php endif;?>

            ]
        });

        view = grid.getView();


        view.tip = Ext.create('Ext.tip.ToolTip', {
            target: view.el,
            delegate: view.cellSelector,
            anchor: 'bottom',
            constrainPosition :false,
            trackMouse: false,
            renderTo: Ext.getBody(),
            listeners: {
                beforeshow: function updateTipBody(tip) {
                  let re = /\B(?=(\d{3})+(?!\d))/g;
                    var gridColums = view.getGridColumns();
                    var column = gridColums[tip.triggerElement.cellIndex];
                  //  var coltip = view.getRecord(tip.triggerElement.parentNode).get(column.dataIndex);
                  //  if (coltip) {
                  //      var val = column.text + ': ' + (Number(coltip).toFixed(2).replace(re, " "));
                  //  }else{
                        var val = column.text;
                  //  }
                    tip.update(val);
                }
            }
        });

        store.on('load', function(store) {
            showHideTbar(store);

        });
        store.on('groupchange', function(store) {
            let hidden = Ext.getCmp('plus1').hidden;
            if(hidden){
                Ext.getCmp('plus1').show();
                Ext.getCmp('minus1').show();
            }else{
                Ext.getCmp('plus1').hide();
                Ext.getCmp('minus1').hide();
            }
            showHideTbar(store);
        });
        //скрыть + -
        function showHideTbar(store){
            let type = '<?=$whileTbar?>';
            if(type==='1'){
                let labelId = 1018;
                let label = '';
                let bool = false;
                while (bool === false) {
                    label = $('#label-' + labelId);
                    if (label.length > 0) {
                        bool = true;
                    }
                    labelId -= 1;
                }
                label[0].innerHTML = change_groped_title(store);
            }
        }

        function change_groped_title(store) {
            var group_column_txt = '';
            var groups = store.getGroups();
            if (groups) {
                var group_index = store.getGroups()._grouper._property;
                $('#modal input:checkbox#' + group_index).prop('checked', true);
                var group_column = columns.filter(function (column) { return column.dataIndex == group_index });
                group_column_txt = group_column[0].text;
            }
            let groupColText = '';

            if (group_column_txt) {
               groupColText = '<span style="font-weight: normal;">'+title + ' - Записи сгруппированы по полю <b style="cursor: pointer;" data-toggle="modal" data-target="#modal">' + group_column[0].text + '</b>. <b style="cursor: pointer;" title="Кликните чтобы cнять все групировки" onclick="groping_control_remove()">Cнять все групировки</b></span>';
            } else {
               groupColText = '<span style="font-weight: normal;">'+title + ' - Записи несгруппированы - <b style="cursor: pointer;" data-toggle="modal" data-target="#modal" title="Кликните чтобы открыть окно выбора поля для группировки">нажмите сгруппировать</b></span>';
           }
            return groupColText;
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
if(in_array($_GET['type'],[5,7,8,9,11])){
  $updateType = $_GET['type'] != 9 ? $_GET['type'] : 5;
  require_once('blocks/number_update/'.$updateType.'.php');
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



        </form>


    </div>
</div>
