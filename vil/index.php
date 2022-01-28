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
//---------------------------------------------
$auth = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2Nlc3NJRCI6ImY5MmY5Y2NmLTE5ZmUtNDQ4Ny04NmI3LWRjMmM3NjgzNGVjZSJ9.KdO7hVoY5hNmAciFrcK8zfxr6blu8AEe-qNx8IrfsDM';

require_once ('http.lib.php');

$USER['id'] = 2;
$USER['wb_key'] = 'M2ZjZGI0YTUtNWRjMi00ODI4LTk0YjYtMjVhZDg3YTM1NTZl';

$wb_key_new = $USER['wb_key'];

//------------------------------------------------------------------------------------------
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
save_cost Стоимость хранения
delivery_rub Стоимость логистики
itogo_k_oplate Итого к оплате
sebes Себестоимость
nalog7 Налоги, УСН доходы 7%
pribil Чистая прибыль
marga Маржинальность, %
speed_back Скорость возврата инвестиций
supplier_oper_name Обоснование для оплаты

';

/*
rrd_id Номер строки
gi_id номер поставки
doc_type_name Тип документа
nds Ставка НДС
cost_amount Себестоимость Сумма
sale_percent Согласованная скидка
commission_percent Процент комиссии
customer_reward Вознаграждение покупателю
supplier_reward Вознаграждение поставщику
office_name Склад
supplier_oper_name Обоснование для оплаты
order_dt Даты заказа
sale_dt Дата продажи
shk_id ШК
retail_price_withdisc_rub Цена розничная с учетом согласованной скидки
for_pay К перечислению поставщику
for_pay_nds К перечислению поставщику НДС
delivery_amount Кол-во доставок
return_amount Кол-во возвратов
gi_box_type_name Тип коробов
product_discount_for_report Согласованный продуктовый дисконт
supplier_promo Промокод
supplier_spp Скидка постоянного покупателя';
*/

$sebes_keys = 'incomeId Поставки
supplierArticle Ваш артикул
techSize Размер
barcode Баркод
quantity кол-во
ss_all Общая себестоимость
ss_one Себестоимость единицы
';

//=============================================
$postav_keys = 'incomeId номер поставки
number номер УПД
date дата поступления
lastChangeDate дата и время обновления информации в сервисе
supplierArticle ваш артикул
techSize размер
barcode Баркод
quantity кол-во
totalPrice цена из УПД
dateClose дата принятия (закрытия) у нас
warehouseName название склада
nmId Код WB
status Текущий статус поставки';

$postav_keys = 'incomeId номер поставки
date дата поступления
quantity кол-во
status Текущий статус поставки
warehouseName название склада
dateClose дата принятия (закрытия) у нас
lastChangeDate дата и время обновления информации в сервисе
number номер УПД
totalPrice цена из УПД
';

//===========================================
$sklad_keys = 'lastChangeDate дата и время обновления информации в сервисе
supplierArticle ваш артикул
techSize размер
barcode штрих-код
quantity кол-во, доступное для продажи
speed Остатков хватит примерно, дни
speed_7 Скорость продаж за неделю, шт/день
speed_30 Скорость продаж за месяц, шт/день
isSupply договор поставки
isRealization договор реализации
quantityFull кол-во полное
quantityNotInOrders кол-во не в заказе
warehouseName название склада
inWayToClient в пути к клиенту (штук)
inWayFromClient в пути от клиента (штук)
nmId код WB
subject предмет
category категория
daysOnSite кол-во дней на сайте
brand бренд
SCCode код контракта
refund_7 % возврата за неделю
refund_30 % возврата за месяц
Price Цена
Discount Дисконт
price_min_discount Цена после вычета дисконта
';

$sklad_keys = 'lastChangeDate Дата и время обновления информации в сервисе
category Категория
brand Бренд
subject Предмет
barcode Баркод
supplierArticle Артикул
nmId Код WB
techSize Размер
quantity Кол-во, доступное для продажи
quantityNotInOrders Кол-во не в заказе
quantityFull Кол-во полное
upush Упущенная выручка
speed Остатков хватит примерно, дни
speed_7_order Скорость заказов за неделю, шт/день
speed_7 Скорость продаж за неделю, шт/день
speed_30 Скорость продаж за месяц, шт/день
inWayToClient в пути к клиенту (штук)
inWayFromClient в пути от клиента (штук)
refund_7 % возврата за неделю
refund_30 % возврата за месяц
Price Цена
Discount Дисконт
price_min_discount Цена после вычета дисконта
warehouseName Склад
daysOnSite Кол-во дней на сайте
isSupply Договор поставки
isRealization Договор реализации
SCCode код контракта
';
//---------------------------------------------------------------------
$report_keys = 'realizationreport_id Номер отчета
suppliercontract_code Договор
rr_dt Дата операции
rrd_id Номер строки
gi_id номер поставки
subject_name Предмет
nm_id Артикул
brand_name Бренд
sa_name Артикул поставщика
ts_name Размер
barcode Баркод
doc_type_name Тип документа
quantity Количество
nds Ставка НДС
cost_amount Себестоимость Сумма
retail_price Цена розничная
retail_amount Сумма продаж(Возвратов)
retail_commission Сумма комиссии продаж
sale_percent Согласованная скидка
commission_percent Процент комиссии
customer_reward Вознаграждение покупателю
supplier_reward Вознаграждение поставщику
office_name Склад
supplier_oper_name Обоснование для оплаты
order_dt Даты заказа
sale_dt Дата продажи
shk_id ШК
retail_price_withdisc_rub Цена розничная с учетом согласованной скидки
for_pay К перечислению поставщику
for_pay_nds К перечислению поставщику НДС
delivery_amount Кол-во доставок
return_amount Кол-во возвратов
delivery_rub Стоимость логистики
gi_box_type_name Тип коробов
product_discount_for_report Согласованный продуктовый дисконт
supplier_promo Промокод
supplier_spp Скидка постоянного покупателя';

$orders_keys = 'number № заказа
date дата заказа
lastChangeDate дата изменения
supplierArticle артикул
techSize размер
barcode Баркод
quantity кол-во
totalPrice цена до скидки/промо/спп
discountPercent итоговый дисконт
warehouseName склад
oblast область
incomeID номер поставки
odid ид.позиции заказа
nmId Код WB
subject предмет
category категория
brand бренд
isCancel отменен
cancel_dt дата отмены заказа
status статус
userStatus статус клиента
deliveryType тип доставки
';

$sales_keys = 'number № документа
date дата продажи
lastChangeDate дата изменения
supplierArticle артикул
techSize размер
barcode Баркод
quantity кол-во
totalPrice начальная розничная цена
discountPercent скидка на товар
isSupply договор поставки
isRealization договор реализации
orderId Номер заказа
promoCodeDiscount промокод
warehouseName склад
countryName страна
oblastOkrugName округ
regionName регион
incomeID номер поставки
saleID ид. продажи/возврата
odid ид. позиции заказа
spp скидка постоянного покупателя
forPay к перечислению поставщику
finishedPrice фактическая цена
priceWithDisc цена, от которой считается вознаграждение поставщика
nmId код WB
subject предмет
category категория
brand бренд
IsStorno сторнирована
status статус
userStatus статус клиента
deliveryType тип доставки
';

$orders_sales_keys = 'number №
date дата
lastChangeDate дата изменения
supplierArticle артикул
techSize размер
barcode Баркод
quantity кол-во
totalPrice цена до скидки/промо/спп
discountPercent дисконт
isSupply договор поставки
isRealization договор реализации
orderId Номер заказа
promoCodeDiscount промокод
warehouseName склад
countryName страна
oblast область
oblastOkrugName округ
regionName регион
incomeID номер поставки
saleID ид. продажи/возврата
odid ид. позиции заказа
spp скидка постоянного покупателя
forPay к перечислению поставщику
finishedPrice фактическая цена
priceWithDisc цена, от которой считается вознаграждение поставщика
nmId код WB
subject предмет
category категория
brand бренд
IsStorno сторнирована
isCancel отменен
cancel_dt дата отмены заказа
status статус
userStatus статус клиента
deliveryType тип доставки
';

function make_tbl_keys($a)
{
    $a = explode("\n", $a);
    foreach ($a as $b)
    {
        if (trim($b) == '' || strpos($b, '//') !== false) continue;
        $b = explode(' ', trim($b));
        $k = $b[0];
        unset($b[0]);
        $r[trim($k) ] = trim(implode(' ', $b));
    }
    return $r;
}

$pribil_keys = make_tbl_keys($pribil_keys);
$report_keys = make_tbl_keys($report_keys);
$sales_keys = make_tbl_keys($sales_keys);
$orders_keys = make_tbl_keys($orders_keys);
$orders_sales_keys = make_tbl_keys($orders_sales_keys);
$postav_keys = make_tbl_keys($postav_keys);
$sklad_keys = make_tbl_keys($sklad_keys);
$sebes_keys = make_tbl_keys($sebes_keys);

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    function mb_ucfirst($string) {
        $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
        return $string;
    }
}

if (!isset($_GET['dt'])) $_GET['dt'] = date('Y-m-d', time());
if (!isset($_GET['type'])) $_GET['type'] = 2;

if (trim($USER['wb_key']) != '')
{
    $dt1 = $_GET['dt']; //date('Y-m-d', time() - 60*60*24);
    $dt1 = date('Y-m-d', time() - 60 * 60 * 24 * 80);

    if ($_GET['type'] == 1)
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url_sales = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sales_keys);
    }
    /*elseif ($_GET['type']d == 4)
    {
        //$api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sales_keys);
    }*/
    elseif ($_GET['type'] == 5)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/reportDetailByPeriod?dateFrom=' . $dt1 . '&key=' . $USER['wb_key'] . '&limit=1000000&rrdid=0&dateto=' . date('Y-m-d');
        $tbl_keys = ($report_keys);
    }
    elseif ($_GET['type'] == 9)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/reportDetailByPeriod?dateFrom=' . $dt1 . '&key=' . $USER['wb_key'] . '&limit=1000000&rrdid=0&dateto=' . date('Y-m-d');
        $tbl_keys = ($pribil_keys);
    }
    elseif ($_GET['type'] == 6)
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/stocks?skip=0&take=1000';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/stocks?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sklad_keys);
    }
    elseif ($_GET['type'] == 7)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/incomes?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($postav_keys);
    }
    elseif ($_GET['type'] == 8)
    {
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/incomes?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $tbl_keys = ($sebes_keys);
    }elseif ($_GET['type'] == 10)
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url_sales = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/sales?dateFrom=' . $dt1 . 'T00:00:00.000Z&key=' . $USER['wb_key'];
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $dt1 . 'T00:00:00.000Z&flag=0&key=' . $USER['wb_key'];
        $tbl_keys = ($orders_sales_keys);
    }
   /* elseif ($_GET['type'] d== 3)
    {
        //$api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $dt1 . 'T00:00:00.000Z&flag=0&key=' . $USER['wb_key'];
        $tbl_keys = ($orders_keys);
    }*/
    else
    {
        $api_url_new = 'https://suppliers-api.wildberries.ru/api/v2/orders?date_start='.$dt1.'T00:00:00.000Z&take=1000&skip=0';
        $api_url = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/orders?dateFrom=' . $dt1 . 'T00:00:00.000Z&flag=0&key=' . $USER['wb_key'];
        $tbl_keys = ($orders_keys);
    }
    if ($api_url and $api_url_new and $api_url_sales){$r = api_object($USER['wb_key'],$api_url,$api_url_new,$api_url_sales);}
    elseif ($api_url and $api_url_new and !$api_url_sales){$r = api_object($USER['wb_key'],$api_url,$api_url_new);}
    elseif ($api_url and !$api_url_new and !$api_url_sales){$r = api_object($USER['wb_key'],$api_url,'');}
    elseif (!$api_url and $api_url_new and !$api_url_sales){$r = api_object($USER['wb_key'],'',$api_url_new);}
    elseif (!$api_url and $api_url_new and $api_url_sales){$r = api_object($USER['wb_key'],'',$api_url_new,$api_url_sales);}
    else{$r='';}

    $tbl_rows = $orig_tbl_rows = $r;
}

include('head.php');
?>

	<div class="panel panel-default" >
            <div class="panel-heading"><h4>Продажи и заказы Wildberries</h4> </div>
            <div class="panel-body">

<?php

if (isset($_GET[dt1])) $dop_dts_range = '&dt1=' . $_GET[dt1] . '&dt2=' . $_GET[dt2];
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


<input type="text" id="dd1" class="dsingle ddd form-control" name="dt1" value="<?=$_GET[dt1]; ?>"  placeholder="">
<input type="text" id="dd2" class="dsingle ddd form-control" name="dt2" value="<?=$_GET[dt2]; ?>"  placeholder="">

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
	a = '?page=wb&type=<?=$_GET[type]; ?>&dt1='+$('#dd1').val()+'&dt2='+$('#dd2').val();
	document.location.href = a;

}
</script>
<style>
.ddd
{
	width: 100px;
	display: inline-block;
}
</style>


<?php
}

if ($_GET['type'] != 5 && $_GET['type'] != 6 && $_GET['type'] != 8 && $_GET['type'] != 9)
{
    //var_dump($tbl_rows);
    foreach ($tbl_rows as $g)
    {
       // $g = (object) $g;

        //var_dump($g->lastChangeDate);
        $flag = 1;
        $cdt = $g->lastChangeDate;
        $cdt = $g->date;

        if ($_GET['type'] == 3) $cdt = $g->cancel_dt;
        if ($_GET['type'] == 4) $cdt = $g->lastChangeDate;
        if ($_GET['type'] == 10 and $cdt = $g->cancel_dt) {$cdt = $g->cancel_dt;} elseif($_GET['type'] == 10) {$cdt = $g->lastChangeDate;}

        {
            if (isset($_GET['dt1']))
            {
                if (strtotime($cdt) >= strtotime($_GET['dt1']) && strtotime($cdt) <= strtotime($_GET['dt2']) + 60 * 60 * 24) $flag = 0;

            }
            else
            {
                if ($stats_res['Вчера'] == $_GET['dt'])
                {
                   // var_dump($flag);
                    if (date('Y-m-d', strtotime($cdt)) == date('Y-m-d', strtotime($_GET['dt']))) $flag = 0;
                }
                else
                {

                    if (strtotime($cdt) >= strtotime($_GET['dt'])) $flag = 0;
                   // var_dump($flag);
                }
            }

        }
        //var_dump($stats_res);
        if ($flag == 1) continue;

        $rows_after_date[] = $g;

    }
    $tbl_rows = $rows_after_date;
   //var_dump($tbl_rows);
}
//-------------------------------------------------------------------


if ($_GET['type'] == 5 && !isset($_GET['rid']) && !isset($_GET['bc']))
{

    $tbl_keys = make_tbl_keys('realizationreport_id Номер отчета
rr_dt Дата операции
quantity Количество
nds Ставка НДС
cost_amount Себестоимость Сумма
retail_price Цена розничная
retail_amount Сумма продаж(Возвратов)
retail_commission Сумма комиссии продаж
sale_percent Согласованная скидка
commission_percent Процент комиссии
customer_reward Вознаграждение покупателю
supplier_reward Вознаграждение поставщику
retail_price_withdisc_rub Цена розничная с учетом согласованной скидки
for_pay К перечислению поставщику
for_pay_nds К перечислению поставщику НДС
delivery_amount Кол-во доставок
return_amount Кол-во возвратов
delivery_rub Стоимость логистики
product_discount_for_report Согласованный продуктовый дисконт
supplier_promo Промокод
supplier_spp Скидка постоянного покупателя');

}

?>
	    </div>
	</div>

<div id='stat' style="padding: 0 10px;"></div>

<?php

if ($_GET['type'] == 5)
{

    if (isset($_GET['rid']) && isset($_GET['bc']))
    {
        echo "<h4><a href='?page=wb&type=5'>Все отчеты</a>  / Отчет № <a href='?page=wb&type=5&rid=$_GET[rid]'>" . $_GET['rid'] . '</a> по штрихкоду: ' . $_GET['bc'] . '</h4>';

    }
    elseif (isset($_GET['rid']))
    {
        echo "<h4><a href='?page=wb&type=5'>Все отчеты</a>  / Отчет № <a href='?page=wb&type=5&rid=$_GET[rid]'>" . $_GET['rid'] . '</a></h4>';
    }

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

        $g->barcode = '<a href="?page=wb&type=5&rid=' . $g->realizationreport_id . '&bc=' . $g->barcode . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';

        $g->realizationreport_id = '<a href="?page=wb&type=5&rid=' . $g->realizationreport_id . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->realizationreport_id . '</a>';

    }

    $tbl_rows = $reps;

    //echo '<pre>';print_r($keys_bc2);

}

//=================================================================================


// поставки
if ($_GET['type'] == 7)
{

    if (isset($_GET['rid']))
    {
        echo "<h4><a href='?page=wb&type=7&dt=" . $_GET['dt'] . "''>Все поставки</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=" . $_GET['dt'] . "'>" . $_GET['rid'] . '</a></h4>';

        echo "<br><input type=button  onclick='dp_save(this);' class='btn btn-success' value='Сохранить все'>";

        $postav_keys = 'incomeId номер поставки
///number номер УПД
date дата поступления
lastChangeDate дата и время обновления информации в сервисе
supplierArticle ваш артикул
techSize размер
barcode Баркод
quantity кол-во

///dateClose дата принятия (закрытия) у нас
///warehouseName название склада
///nmId Код WB
///status Текущий статус поставки';

        $tbl_keys = make_tbl_keys($postav_keys);

        echo '
<script>

var dp_krows = [];

var not_reload = 0;

function dp_save(e)
{


var dt = "";
for(j=0;j < dp_krows.length; j++)
{
	cl = dp_krows[j];

	lst = $(".dp_" + cl);

	for (i=0; i<lst.length; i++)
	{
		key_col = $(lst[i]).attr("psf");
		val = $(lst[i]).val();

		dt += cl+ "@" +key_col + "@" + val + "|";
	}
}

	//console.log(dt);return;

	$(e).fadeOut();

	$.post("", {save_dops_new:1, ker_row:"", data:dt}, function (res){
		console.log(res);

		document.location.reload();

		//alert("Данные по поставке сохранены! Для перерасчета - обновите страницу!");
	});
}


</script>

';

    }
    else
    {

        echo '

       <div class="panel panel-default" >

            <div class="panel-heading">Синхронизация кол-ва товаров с личным кабинетом WB (укажите файл экспортированный из кабинета - например - recieved_goods_3458937.xls)</div>

            <div class="panel-body" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; qbackground: rgb(239, 239, 239);">

               <form role="form" method="post" action="" enctype="multipart/form-data">

		<table style="width: 100%"><tr><td style="width: 400px;">
                     XLSX файл с кол-вом товаров в поставке</td><td>
                     <input type=file name="expfile" style="width: 300px; display: inline-block;" class="form-control" value="" />

		<button type="submit" name="syncxlsx" class="btn btn-success">Синхронизировать кол-во товаров</button></td></tr></table>


               </form>


            </div>
      </div>


';

    }

    $ss_dop_fields = $USER['dp_list'];
    if (trim($ss_dop_fields) == '') $ss_dop_fields = "Затраты на поиск товара\n Затраты на забор товара\n Затраты на услуги фулфилмента\n Затраты на фото/видео материалы\n Затраты на внутреннюю рекламу\n Затраты на внешнюю рекламу\n Затраты на самовыкупы\n Затраты прочие";

    if (!isset($_GET['rid']))
    {
        echo "<input class='btn btn-default' id='btn_pd_lst' value='Редактировать список полей' style='width: 260px;' onclick='$(\"#dop_fields_div\").toggle();$(\"#set_fields_div\").hide(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'> ";

        echo '<div id="dop_fields_div" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "><img onclick="$(\'#dop_fields_div\').toggle();" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png"> <b>Список Ваших полей для вычета - общие на каждую поставку</b>';
        echo "<textarea class='form-control' style='height: 150px;' id='dop_fileds' >$ss_dop_fields</textarea><td style='width: 10px;'> <input class='btn btn-success' value='Сохранить список полей' onclick='save_list();'> </div>";

    }

    $ss_dop_fields = "Общая себестоимость\nСредняя себестоимость единицы\nСтоимость единицы товара\n" . $ss_dop_fields;

    $ss_dop_fields = explode("\n", trim($ss_dop_fields));

    foreach ($ss_dop_fields as & $sf)
    {
        $sf = trim($sf);
        if ($sf == '') continue;
        $tbl_keys[$sf] = $sf;
    }

    $last_key = - 1;
    foreach ($tbl_rows as $g)
    {

        // $g->quantity
        $quant = $db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], $g->incomeId . '_' . $g->supplierArticle . '_' . $g->techSize, 'quantity');
        if ($quant !== false)
        {
            $g->quantity = $quant;
        }

        /// get dop fields
        foreach ($ss_dop_fields as & $sf)
        {
            $g->$sf = intval($db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], $g->incomeId . '_' . $g->barcode, $sf));
        }

        $dpf = 'Общая себестоимость';
        $dpf2 = 'Стоимость единицы товара';
        $g->$dpf += $g->quantity * $g->$dpf2;

        foreach ($ss_dop_fields as & $sf)
        {
            $sf = trim($sf);
            if ($sf == '' || $sf == 'Общая себестоимость' || $sf == 'Средняя себестоимость единицы' || $sf == 'Стоимость единицы товара') continue;

            $g->$dpf += $g->$sf;
        }
        $dpf2 = 'Средняя себестоимость единицы';
        $g->$dpf2 = intval($g->$dpf / $g->quantity);

        if (isset($_GET['rid']) && isset($_GET['bc']))
        {
            if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

        }
        else if (isset($_GET['rid']))
        {
            if ($g->incomeId != $_GET['rid']) continue;

        }
        else
        {

            if (isset($keys_bc2[$g->incomeId]))
            {

                $sums = explode("\n", trim('quantity'));
                foreach ($ss_dop_fields as & $sf)
                {
                    $sf = trim($sf);
                    if ($sf == 'Стоимость единицы товара') continue;

                    $sf2 = 'dp_' . $sf;

                    $sums[] = $sf;
                    $sums[] = $sf2;
                }

                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);

                    $reps[$keys_bc2[$g
                        ->incomeId]]->$fieldsum += $g->$fieldsum;
                }

            }

            if ($last_code != $g->incomeId)
            {
                $last_code = $g->incomeId;
            }
            else
            {
                continue;
            }
        }

        $reps[] = $g;
        $last_key = count($reps) - 1;
        $keys_bc[$g->incomeId] = $last_key;
        $keys_bc2[$g->incomeId] = $last_key;

        $g->incomeId2 = $g->incomeId;
        $g->incomeId = '<a href="./index.php?page=wb&type=7&rid=' . $g->incomeId . '&dt=' . $_GET['dt'] . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->incomeId . '</a>';

    }

    //echo '<pre>';var_dump($keys_bc);var_dump($tpl_rows, $reps);
    $tbl_rows = $reps;

    //---------------------------------------
    // пересчетываем сгруппированную себестоимость по поставкам
    if (!isset($_GET['rid']))
    {

        $reps = [];
        foreach ($tbl_rows as $g)
        {
            $dpf = 'Общая себестоимость';
            $dpf2 = 'Стоимость единицы товара';
            //$g->$dpf += $g->quantity *  $g->$dpf2;
            foreach ($ss_dop_fields as & $sf)
            {
                $sf = trim($sf);
                if ($sf == '' || $sf == 'Общая себестоимость' || $sf == 'Средняя себестоимость единицы' || $sf == 'Стоимость единицы товара') continue;

                $g->$dpf += $g->$sf;
            }
            $dpf2 = 'Средняя себестоимость единицы';
            $g->$dpf2 = intval($g->$dpf / $g->quantity);

            $reps[] = $g;
        }
        $tbl_rows = $reps;

    }
    else
    {

        foreach ($tbl_rows as $g)
        {

            $kkk = $g->incomeId2;

            foreach ($ss_dop_fields as & $sf)
            {
                $sf = trim($sf);
                if ($sf == '' || $sf == 'Общая себестоимость' || $sf == 'Средняя себестоимость единицы') continue;

                $val = $g->$sf;

                $g->$sf = "<input type='text' name='dp[{$kkk}_{$g->barcode}][$sf]' psf='{$sf}' class='form-control dp_{$kkk}_{$g->barcode}' value='" . $val . "'>";

                $sf2 = 'dp_' . $sf;

                $g->$sf2 = $val;

            }

            //$g->save = "<input type=button  onclick='dp_save(this, \"{$kkk}_{$g->barcode}\", \"{$g->incomeId2}_{$g->barcode}\", 0);' class='dp_save_btn btn btn-success' style='display:none;' value='Сохранить'> <script>all_keys_col += '|'+'{$g->incomeId}_{$g->barcode}'; </script>";


            echo "<script>dp_krows.push('{$g->incomeId2}_{$g->barcode}')</script>";
        }

    }

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

    $last_key = - 1;
    //var_dump($tbl_rows);
    foreach ($tbl_rows as $g)
    {
        $g = (object) $g;
       // var_dump($g->incomeId);
        if (isset($_GET['f1']) && isset($_GET['bc']))
        {
            if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

        }
        else if (isset($_GET['f1']))
        {
            if ($g->barcode != $_GET['f1']) continue;

        }
        else
        {
                //var_dump($g['barcode']);
            if (isset($keys_bc[$g->barcode]))
            {

                $sums = explode("\n", trim('
quantity
quantityFull
quantityNotInOrders
inWayToClient
inWayFromClient
Price
'));

                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);

                    $reps[$keys_bc[$g
                        ->barcode]]->$fieldsum += $g->$fieldsum;
                }

            }

            if (isset($keys_bc[$g->barcode])) continue;

        }

        $reps[] = $g;
       // var_dump($g);
        $last_key = count($reps) - 1;
        $keys_bc[$g->barcode] = $last_key;
        $keys_bc2[$g->barcode] = $last_key;

        $g->barcode = '<a href="./index.php?page=wb&type=6&f1=' . $g->barcode . '&dt=' . $_GET['dt'] . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->barcode . '</a>';

    }

    //echo '<pre>';var_dump($keys_bc);var_dump($tpl_rows, $reps);
    $tbl_rows = $reps;

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

    foreach ($tbl_rows as $g)
    {
        $g = (object) $g;

        if ((!$g->isCancel && $_GET['type'] == 3) || (!$g->isCancel && $g->oblast && $_GET['type'] == 10)) continue;
        //if ($g->isCancel && $_GET['type'] == 2) continue;


        if (isset($_GET['f1']))
        {
            if ($g->barcode != $_GET['f1']) continue;

        }
        else
        {
            if (isset($keys_bc[$g->barcode]))
            {

                // $sums = explode("\n", trim('

// quantity
// '));


                    if ($g->isCancel) $reps[$keys_bc[$g
                        ->barcode]]->RED = 1;

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

    foreach ($tbl_rows as $g)
    {
        if (($g->totalPrice > 0 && $_GET['type'] == 4) || ($g->totalPrice > 0 and $_GET['type'] == 10 and ($g->forPay or $g->finishedPrice))) continue;
        //if ($g->totalPrice < 0 && $_GET['type'] == 1) continue;


        if (isset($_GET['f1']))
        {
            if ($g->barcode != $_GET['f1']) continue;

        }
        else
        {

            if (isset($keys_bc[$g->barcode]))
            {

                $sums = explode("\n", trim('
quantity
totalPrice
'));
                if ($g->forPay < 0) $reps[$keys_bc[$g
                    ->barcode]]->RED = 1;

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

//////////////////////////////////////////////////////////////
// Склад - доп поля
if ($_GET['type'] == 6)
{

    $buf = file_get_contents('wb-cache-new/' . $USER['wb_key'] . '-1');
    $buf = explode('@@---@@', $buf);
    $r = $r0 = $buf[1];

    $sales_rows = json_decode($r);

    $buf = file_get_contents('wb-cache-new/' . $USER['wb_key'] . '-2');
    $buf = explode('@@---@@', $buf);
    $r = $r0 = $buf[1];

    $orders_rows = json_decode($r);

    //	var_dump($r, $sales_rows, $orders_rows);


    foreach ($tbl_rows as $g)
    {

        $g->price_min_discount = $g->Price * (100 - $g->Discount) / 100;

        $cnt_refund_sales = $cnt_sales = 0;
        $cnt_refund_sales30 = $cnt_sales30 = 0;

        $cnt_refund_orders = $cnt_orders = 0;
        $cnt_refund_orders30 = $cnt_orders30 = 0;

        $idps = $idps30 = array();

        foreach ($sales_rows as $sale_row)
        {
            if ($sale_row->nmId == $g->nmId)
            {
                $cdt = $sale_row->lastChangeDate;
                //$cdt = $sale_row->date;
                //var_dump($cdt, $g);exit;


                //if (strtotime($cdt) >= time()-60*60*24* 8 )


                if (strtotime($cdt) >= strtotime(date('Y-m-d', time() - 60 * 60 * 24 * 7)))
                {

                    if ($sale_row->totalPrice < 0)
                    {
                        $cnt_refund_sales++;
                    }
                    else
                    {
                        $cnt_sales++;
                        $idps[$sale_row->odid] = 1;
                    }

                }

                if (strtotime($cdt) >= time() - 60 * 60 * 24 * 30)
                {

                    if ($sale_row->totalPrice < 0)
                    {
                        $cnt_refund_sales30++;
                    }
                    else
                    {
                        $cnt_sales30++;

                        $idps30[$sale_row->odid] = 1;

                    }

                }

            }

        }

        foreach ($orders_rows as $sale_row)
        {
            if ($sale_row->nmId == $g->nmId)
            {
                $cdt = $sale_row->lastChangeDate;
                //$cdt = $sale_row->date;
                //$cdt = $g->cancel_dt;


                if (strtotime($cdt) >= strtotime(date('Y-m-d', time() - 60 * 60 * 24 * 7)))
                {

                    if ($sale_row->isCancel)
                    {
                        $cnt_refund_orders++;

                    }
                    else
                    {
                        if (!isset($idps[$sale_row->odid])) $cnt_orders++;
                    }
                }

                if (strtotime($cdt) >= time() - 60 * 60 * 24 * 30)
                {
                    if ($sale_row->isCancel)
                    {
                        $cnt_refund_orders30++;

                    }
                    else
                    {
                        if (!isset($idps30[$sale_row->odid]))

                        $cnt_orders30++;
                    }
                }

            }

        }

        //var_dump($idps);


        $g->refund_7 = intval(($cnt_refund_sales + $cnt_refund_orders) / ($cnt_orders + $cnt_sales) * 100) . '% <br>';
        $g->refund_7 .= ($cnt_refund_sales + $cnt_refund_orders) . ' / ' . ($cnt_orders + $cnt_sales);

        $g->refund_30 = intval(round(($cnt_refund_sales30 + $cnt_refund_orders30) / ($cnt_orders30 + $cnt_sales30) * 100, 2)) . '% <br> ';
        $g->refund_30 .= ($cnt_refund_sales30 + $cnt_refund_orders30) . ' / ' . ($cnt_orders30 + $cnt_sales30);

        // speed_30s Скорость продаж за месяц, шт/день по продажам
        //$g->refund_30s = intval(round(($cnt_refund_sales30+$cnt_refund_orders30) / ($cnt_sales30 + $cnt_orders30) * 100, 2)) . '% <br> ';
        //$g->refund_30s .= ($cnt_refund_sales30+$cnt_refund_orders30).' / '.($cnt_sales30+$cnt_orders30);


        $g->speed_30 = round($cnt_sales30 / 30, 1);
        $g->speed_7 = round($cnt_sales / 7, 1);
        $g->speed_7_order = round($cnt_orders / 7, 1);

        $g->speed = '~ ' . intval(round($g->quantity / ($cnt_sales / 7) , 1));
        //if ($cnt_sales == 0)
        //$g->speed = '~ '.floatval(round($g->quantity / ($cnt_sales30 / 30), 1));
    }

}
////////////////////////////////////////////////////////////////////


//=================================================================================


// Себестоимость
if ($_GET['type'] == 8)
{

    $ss_dop_fields = $USER['dp_list'];
    if (trim($ss_dop_fields) == '') $ss_dop_fields = "Затраты на поиск товара\n Затраты на забор товара\n Затраты на услуги фулфилмента\n Затраты на фото/видео материалы\n Затраты на внутреннюю рекламу\n Затраты на внешнюю рекламу\n Затраты на самовыкупы\n Затраты прочие";

    if (!isset($_GET['f1'])) echo "<input class='btn btn-default' id='btn_pd_lst' value='Редактировать список полей' style='width: 260px;' onclick='$(\"#dop_fields_div\").toggle();$(\"#set_fields_div\").hide(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'> ";

    if (isset($_GET['f1'])) echo "<input class='btn btn-default' id='btn_pd_val'  value='Установить значение поля для всех поставок ' style='width: 360px;' onclick='$(\"#dop_fields_div\").hide();$(\"#set_fields_div\").toggle(); $(\"#btn_pd_lst\").removeClass(\"btn-warning\");$(\"#btn_pd_val\").addClass(\"btn-warning\");'> ";

    echo '<div id="dop_fields_div" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "><img onclick="$(\'#dop_fields_div\').toggle();" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png"> <b>Список Ваших полей для вычета - общие на каждую поставку</b>';
    echo "<textarea class='form-control' style='height: 150px;' id='dop_fileds' >$ss_dop_fields</textarea><td style='width: 10px;'> <input class='btn btn-success' value='Сохранить список полей' onclick='save_list();'> </div>";

    $ss_dop_fields = 'Стоимость единицы товара' . "\n" . $ss_dop_fields;

    $ss_dop_fields = explode("\n", trim($ss_dop_fields));

    foreach ($ss_dop_fields as & $sf)
    {
        $sf = trim($sf);
        if ($sf == '') continue;
        $tbl_keys[$sf] = $sf;
    }

    echo '<br clear=all><div id="set_fields_div" style="float: left; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; jbackground: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "> <img onclick="$(\'#set_fields_div\').toggle();" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png">   <b>Установка значения полей для всех поставок по баркоду</b>';

    echo "<hr><input type=button  onclick='not_reload=1; $(\".col_save_btn\").click(); $(this).fadeOut(); setTimeout(function(){document.location.reload();}, 2000);' class='btn btn-success' value='Сохранить значения'>";

    echo '<table class="items table table-striped" style="width: 700px;margin-top: 20px;"><tr><th>Поле</th><th>Значение</th></tr>';

    foreach ($ss_dop_fields as $k => & $sf)
    {
        echo '<tr><td style="width: 250px;">' . $sf . '</td><td style="width: 100px;">' . "<input type='text' psf='{$sf}' class='form-control' id='adp_{$k}' value=''>" . '</td>' . "<td style='width: 100px;'><input class='col_save_btn btn btn-success' style='display:none;' value='Сохранить значение' onclick='dp_save_col(\"adp_{$k}\", \"{$sf}\");'></td></tr>";
    }
    echo "</table>  </div> <br clear=all>";

    if (isset($_GET['f1'])) $tbl_keys['save'] = '';

    echo '<hr>

<script>

function save_list()
{
	$.post("", {dp_save_list:1, list:$("#dop_fileds").val()}, function (dt){
		document.location.reload();
	});
}


var not_reload = 0;

function dp_save(e, cl, cllst, tp)
{
	lst = $(".dp_" + cl);
	dt = "";
	for (i=0; i<lst.length; i++)
	{
		key_col = $(lst[i]).attr("psf");
		val = $(lst[i]).val();

		dt += key_col + "@" + val + "|";
	}
	console.log(dt);
	if (tp  != 0) {
		cl = cllst;
		if (!confirm("Значения будут сохранены для всех поставок по данному баркоду. Продолжить?")) return false;
	}

//if (not_reload != 1)
$(e).fadeOut();

	$.post("", {save_dops:1, ker_row:cllst, data:dt}, function (res){
		console.log(res);

		if (not_reload != 1) document.location.reload();

		//alert("Данные по поставке сохранены! Для перерасчета - обновите страницу!");
	});
}

var all_keys_col = "";
function dp_save_col(id, col)
{
	lst = $("#"+id).val();
	if (lst.trim() == "") return;

//	alert(lst + " = " + all_keys_col);

	$.post("", {save_dops_col:1, ker_row:all_keys_col, col:col, val:lst}, function (res){
		console.log(res);

		document.location.reload();

		//alert("Данные по поставке сохранены! Для перерасчета - обновите страницу!");

	});
}

</script>

';

    if (isset($_GET['f1']))
    {
        echo "<h4><a href='?page=wb&type=8&dt=" . $_GET['dt'] . "''>Себестоимость</a>  / Артикул <a href='?page=wb&type=8&f1=$_GET[f1]&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a></h4>';

        echo "<br><input type=button  onclick='not_reload=1; $(\".dp_save_btn\").click(); $(this).fadeOut(); setTimeout(function(){document.location.reload();}, 2000);' class='btn btn-success' value='Сохранить все'>";
    }

    $last_key = - 1;
    foreach ($tbl_rows as $kkk => & $g)
    {

        $g->kkk = $kkk;

        // $g->quantity
        $quant = $db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], $g->incomeId . '_' . $g->supplierArticle . '_' . $g->techSize, 'quantity');
        if ($quant !== false)
        {
            $g->quantity = $quant;
        }

        foreach ($ss_dop_fields as & $sf)
        {
            $sf = trim($sf);

            $val = intval($db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], "{$g->incomeId}_{$g->barcode}", $sf));

            $g->$sf = "<input type='text' name='dp[{$kkk}_{$g->barcode}][$sf]' psf='{$sf}' class='form-control dp_{$kkk}_{$g->barcode}' value='" . $val . "'>";

            $sf2 = 'dp_' . $sf;

            $g->$sf2 = $val;

        }

        $g->save = "<input type=button  onclick='dp_save(this, \"{$kkk}_{$g->barcode}\", \"{$g->incomeId}_{$g->barcode}\", 0);' class='dp_save_btn btn btn-success' style='display:none;' value='Сохранить'> <script>all_keys_col += '|'+'{$g->incomeId}_{$g->barcode}'; </script>";

        $stf = "dp_Стоимость единицы товара";

        $g->ss_all = $g->$stf * $g->quantity;

        $sum = 0;
        foreach ($ss_dop_fields as $k => & $sf)
        {
            if ($sf == 'save' || $sf == 'Стоимость единицы товара') continue;
            $sf = trim($sf);
            $sf2 = "dp_" . $sf;
            //echo $sf .' = '.$g->$sf2."<br>\n";
            $sum += $g->$sf2;

        }
        $g->ss_all += $sum;
        $g->ss_one = intval($g->ss_all / $g->quantity);

        if (isset($_GET['f1']) && isset($_GET['bc']))
        {
            if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

        }
        else if (isset($_GET['f1']))
        {
            if ($g->supplierArticle != $_GET['f1']) continue;

        }
        else
        {

            if (isset($keys_bc[$g->supplierArticle]))
            {

                $sums = explode("\n", trim('
quantity
ss_all
'));
                foreach ($ss_dop_fields as & $sf)
                {
                    $sf = trim($sf);
                    if ($sf == 'Стоимость единицы товара') continue;

                    $sf2 = 'dp_' . $sf;

                    $sums[] = $sf;
                    $sums[] = $sf2;
                }

                //print_r($sums);print_r($g);exit;
                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);

                    $reps[$keys_bc[$g
                        ->supplierArticle]]->$fieldsum += $g->$fieldsum;
                }

                $reps[$keys_bc[$g
                    ->supplierArticle]]->save_codes .= "|" . $g->incomeId . '_' . $g->barcode;

                $reps[$keys_bc[$g
                    ->supplierArticle]]->incomeId += 1;;
                $reps[$keys_bc[$g
                    ->supplierArticle]]->incomeId .= ' шт.';

            }

            if (isset($keys_bc[$g->supplierArticle])) continue;

        }

        if (!isset($_GET['f1']))
        {
            $g->save_codes = $g->incomeId . '_' . $g->barcode;

            $g->incomeId = 1 . ' шт.';;
        }

        $reps[] = $g;
        $last_key = count($reps) - 1;
        $keys_bc[$g->supplierArticle] = $last_key;
        $keys_bc2[$g->supplierArticle] = $last_key;

        $g->barcode2 = $g->barcode;

        $g->supplierArticle2 = $g->supplierArticle;
        if (!isset($_GET['f1'])) $g->supplierArticle = '<a href="./index.php?page=wb&type=8&f1=' . $g->supplierArticle . '&dt=' . $_GET['dt'] . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->supplierArticle . '</a>';

    }

    //echo '<pre>';var_dump($keys_bc);var_dump($tpl_rows, $reps);
    $tbl_rows = $reps;

    if (!isset($_GET['f1'])) foreach ($tbl_rows as $g)
    {

        //var_dump($g);exit;
        $g->ss_one = intval($g->ss_all / $g->quantity);

        $u = [];
        $u['user_id'] = $USER['id'];
        $u['barcode'] = $g->barcode;
        $u['art'] = $g->supplierArticle2;
        $u['sebes'] = $g->ss_one;

        if ($db->getOne("SELECT COUNT(*) FROM sebes_vals WHERE user_id=?i and art=?s ", $u['user_id'], $u['art']) == 0)
        {
            $db->query("INSERT INTO sebes_vals SET ?u", $u);
        }
        else
        {
            $db->query("UPDATE sebes_vals SET ?u WHERE user_id=?i and art=?s ", $u, $u['user_id'], $u['art']);

        }

        $g->save = ""; //"<input type=button  onclick='dp_save(\"{$g->kkk}_{$g->barcode2}\", \"{$g->save_codes}\", 1);' class='btn btn-success' value='Сохранить все'>";
        foreach ($ss_dop_fields as & $sf)
        {
            $sf = trim($sf);

            $g->$sf = "<input type='text' name='dp[{$kkk}_{$g->barcode}][$sf]' psf='{$sf}' class='form-control dp_{$kkk}_{$g->barcode}' value='" . $val . "'>";

            $sf2 = 'dp_' . $sf;

            $g->$sf = $g->$sf2;

        }

    }

    // SUMS
    foreach ($tbl_rows as $g)
    {

        // SUMS --------
        foreach ($g as $gk => $gv)
        {
            $ITOGO_SUMS[$gk] += $gv;
        }

    }

}

//=====================================================================================================
// Чистая прибыль
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

//=================================================================================



?>









             <table class="items table table-striped" style="margin: 10px; font-size: 11px;" >
               <thead>
<?php
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
               <tbody>



<div id="grid<?php echo $_GET['type'] ? $_GET['type'] : ''; ?>" class="grid"></div>

<?php

$title = '';
foreach ($tps_res as $key => $value) {
    if ($key == $_GET['type'])
        $title = $value;
}

$fields = array_keys($tbl_keys);
array_unshift($fields, 'image');

$columns[] = (object) [
    'text' => 'Изображение',
    'id' => 'image',
    'dataIndex' =>
    'image',
    'sortable' => false,
    'hideable' => true,
    'width' => 45
];
foreach ($tbl_keys as $k => $str) {
    $column = [
        'text' => mb_ucfirst($str),
        'id' => $k,
        'dataIndex' => $k,
        'sortable' => true,
        'hideable' => true
    ];
    if ('barcode' == $k) {
        $column['width'] = 140;
    }
    if ('number' == $k) {
        $column['width'] = 120;
    }
    if ('date' == $k || 'lastChangeDate' == $k) {
        $column['width'] = 145;
    }
    $columns[] = (object) $column;
}

$data = [];
$data_rows = [];
$tbl_rows = array_reverse($tbl_rows);
if (count($tbl_rows)) {
    foreach ($tbl_rows as $g) {

        if (isset($_GET['f2']) && isset($_GET['f3']) && $g->{$_GET['f2']} <> $_GET['f3']) {
            continue;
        }


        $data_cols = [];

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

        foreach ($sums as $fieldsum)
        {
            $fieldsum = trim($fieldsum);

            $g->$fieldsum = abs($g->$fieldsum);
        }

        $flag = 0;
        $REFUND_COLOR = '';

        if ($_GET['type'] == 1)
        {
            if ($g->forPay < 0 || $g->RED == 1) $REFUND_COLOR = 'danger'; //$flag = 1;

        }

        if ($_GET['type'] == 4 || ($g->totalPrice > 0 and $_GET['type'] == 10 and ($g->forPay or $g->finishedPrice)))
        {
            if ($g->totalPrice > 0) $flag = 1;
        }
     /*   if ($_GET['type'] == 10)
        {
            if ($g->totalPrice > 0) $flag = 1;
            if ($g->isCancel != 1) $flag = 1;
            if ($g->forPay) $PRICE_SUM += $g->forPay * $g->quantity;
            else $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);

        }*/

        if ($_GET['type'] == 2)
        {
            if ($g->isCancel == 1 || $g->RED == 1) $REFUND_COLOR = 'danger'; //$flag = 1;

        }

        if ($_GET['type'] == 3 || ($g->oblast && $_GET['type'] == 10))
        {
            if ($g->isCancel != 1) $flag = 1;
        }

        if ($_GET['type'] == 5)
        {
            $flag = 0;
        }
        // if ($flag == 1) continue;

        // $CNT_SUM += $g->quantity;

        if ($_GET['type'] == 1 || $_GET['type'] == 4 || ($g->totalPrice > 0 and $_GET['type'] == 10 and ($g->forPay or $g->finishedPrice)))
        {
            $PRICE_SUM += $g->forPay * $g->quantity;
        }
        else
        { // ЗАКАЗЫ
            $PRICE_SUM += $g->totalPrice * ((100 - $g->discountPercent) / 100);
        }

        $data_cols['refund_color'] = $REFUND_COLOR;


        // image
        if ($_GET['type'] == 5 || $_GET['type'] == 9 || $_GET['type'] == 7)
        {
            if (isset($g->nmId)) $g->nm_id = $g->nmId;

            $img = 'https://images.wbstatic.net/small/new/' . substr($g->nm_id, 0, -4) . '0000/' . $g->nm_id . '.jpg';

            if (!isset($_GET['rid']))
            {
                $img = '';
            }
            else $img = '<a href="https://www.wildberries.ru/catalog/' . $g->nm_id . '/detail.aspx?targetUrl=MS" target=_blank><img src="' . $img . '" style="height: 40px;"></a>';
        }

        else
        {
            $img = 'https://images.wbstatic.net/small/new/' . substr($g->nmId, 0, -4) . '0000/' . $g->nmId . '.jpg';

            $img = '<a href="https://www.wildberries.ru/catalog/' . $g->nmId . '/detail.aspx?targetUrl=MS" target=_blank><img src="' . $img . '" style="height: 40px;"></a>';
        }

        $data_cols['image'] = $img;


        // other
        foreach ($tbl_keys as $k => $str) {
            if (!isset($g->$k)) $g->$k = '---';
            if (strpos($str, 'дата') !== false) $g->$k = str_replace('T', ' ', $g->$k);

            // delete column in sales and orders
            //if (($_GET['type'] == 1 || $_GET['type'] == 2) && $k == 'lastChangeDate') continue;

            if ($g->$k === true) {
                $g->$k = 1;
            } elseif ($g->$k === false) {
                $g->$k = '';
            }

            $data_cols[$k] = "<a href='?page=wb&type=" . $_GET['type'] . (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "") . "&dt=" . $_GET['dt'] . "&f2=" . $k . "&f3=" . $g->$k . "'>" . $g->$k . "</a>";;
        }

        $data_rows[] = (object) $data_cols;
    }
    $CNT_SUM = count($tbl_rows);
}
$data = $data_rows;

?>

<script>
    var title = '<?php echo $title; ?>';
    var fields = <?php echo json_encode($fields); ?>;
    var data = <?php echo json_encode($data); ?>;
    var columns = <?php echo json_encode($columns); ?>;
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/classic/theme-triton/resources/theme-triton-all.css" rel="stylesheet" />
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/ext-all.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/extjs/6.2.0/classic/locale/locale-ru.js"></script>-->
<script type="text/javascript" src="js/ext-all.js"></script>
<script type="text/javascript" src="js/locale-ru.js"></script>

<script type = "text/javascript">
    Ext.onReady(function() {

        Ext.define('Data', {
            extend: 'Ext.data.Model',
            fields: fields
        });

        store = Ext.create('Ext.data.Store', {
            model: 'Data',
            data: data
        });
        store.load();

        /*var cp = Ext.create('Ext.state.CookieProvider', {
            // path: "/cgi-bin/",
            expires: new Date(new Date().getTime()+(1000*60*60*24*3000)) //3000 days
            // domain: "sencha.com"
        });
        Ext.state.Manager.setProvider(cp);*/
        Ext.state.Manager.setProvider(new Ext.state.LocalStorageProvider());

        grid = Ext.create('Ext.grid.Panel', {
            renderTo: 'grid<?php echo $_GET['type'] ? $_GET['type'] : ''; ?>',
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
            }],
            stateId: 'grid<?php echo $_GET['type'] ? $_GET['type'] : ''; ?>',
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

if ($_GET['type'] == 8)
{

    echo "
<tr class='warning' style='font-size: 14px;' ><td><b>Итого:</b></td>";

    foreach ($tbl_keys as $gk => $str)
    {
        if ($gk == 'save_cost') $gk = 'save_cost2';
        if (in_array($gk, ['incomeId', 'supplierArticle', 'techSize', 'barcode', '', 'save']) !== false)
        {
            $ITOGO_SUMS[$gk] = '';
        }
        echo "<td><b>{$ITOGO_SUMS[$gk]}</b></td>";
    }

    echo "
</tr>";

}

?>



<script>

<?php
if ($_GET['type'] != 5 && $_GET['type'] != 6 && $_GET['type'] != 7 && $_GET['type'] != 8 && $_GET['type'] != 9)
{

?>

$('#stat').html("Суммарное кол-во товаров: <b><?=abs($CNT_SUM); ?></b> | Суммарная цена: <b><?=number_format($PRICE_SUM, 2, '.', ''); ?></b> руб.");

<?php
} ?>


</script>


        </form>


    </div>
</div>
