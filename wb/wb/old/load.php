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

if (($_POST['key1'] or $_POST['key2'] or $_POST['key3'] or $_GET['return'])
    and (($_POST['key1'] != $lines[0])
        or ($_POST['key2'] != $lines[1])
        or ($_POST['key3'] != $lines[2])
        or ($_GET['return'] != $lines[3]))){
    if ($_POST['key1'] != '' or $_POST['key1'] != null){
        $lines[0] = $_POST['key1'];
    }
    if ($_POST['key2'] != '' or $_POST['key2'] != null){
        $lines[1] = $_POST['key2'];
    }
    if ($_POST['key3'] != '' or $_POST['key3'] != null){
        $lines[2] = $_POST['key3'];
    }
    if ($_GET['return'] != '' or $_GET['return'] != null){
        $lines[3] = $_GET['return'];
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
fbs ФБС
fbo ФБО
fbs_fbo ФБС+ФБО
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
/*
$report_keys = 'realizationreport_id Номер отчета
suppliercontract_code Договор
rr_dt Дата операции
rrd_id Номер строки
gi_id номер поставки
subject_name Предмет
nm_id Артикул
brand_name Бренд
sa_name Артикул поставщика
rid Уникальный идентификатор позиции заказа
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
*/

$report_keys = 'realizationreport_id Номер отчета
suppliercontract_code Договор
rid Уникальный идентификатор позиции заказа
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
quantity Количество продаж
retail_price Цена розничная
retail_amount Сумма продаж(Возвратов)
sale_percent Согласованная скидка
commission_percent Процент комиссии
office_name Склад
supplier_oper_name Обоснование для оплаты
order_dt Даты заказа
sale_dt Дата продажи
shk_id ШК
retail_price_withdisc_rub Цена розничная с учетом согласованной скидки
delivery_amount Кол-во доставок
return_amount Кол-во возвратов
delivery_rub Стоимость логистики
gi_box_type_name Тип коробов
product_discount_for_report Согласованный продуктовый дисконт
supplier_promo Промокод
ppvz_spp_prc Скидка постоянного покупателя
ppvz_kvw_prc_base Размер кВВ без НДС, % Базовый
ppvz_kvw_prc Итоговый кВВ без НДС, %
ppvz_sales_commission Вознаграждение с продаж до вычета услуг поверенного, без НДС
ppvz_for_pay К перечислению Продавцу за реализованный Товар
ppvz_reward Возмещение Расходов услуг поверенного
ppvz_vw Вознаграждение Вайлдберриз (ВВ), без НДС
ppvz_vw_nds НДС с Вознаграждения Вайлдберриз
ppvz_office_id Номер офиса
ppvz_office_name Наименование офиса доставки
ppvz_supplier_id Номер партнера
ppvz_supplier_name Партнер
ppvz_inn ИНН партнера
declaration_number Номер таможенной декларации
bonus_type_name Обоснование штрафов и доплат';

$orders_keys = 'number № заказа
date дата заказа
lastChangeDate дата изменения
supplierArticle артикул
techSize размер
barcode Баркод
fbs ФБС
fbo ФБО
fbs_fbo ФБС+ФБО
quantity кол-во
totalPrice цена до скидки/промо/спп
discountPercent итоговый дисконт
finishedPrice итоговая цена
speed Остатков хватит примерно, дни
speed_7_order Скорость заказов за неделю, шт/день
speed_7 Скорость продаж за неделю, шт/день
speed_30 Скорость продаж за месяц, шт/день
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
fbs ФБС
fbo ФБО
fbs_fbo ФБС+ФБО
quantity кол-во
totalPrice начальная розничная цена
discountPercent скидка на товар
isSupply договор поставки
isRealization договор реализации
orderId Номер заказа
promoCodeDiscount промокод
speed Остатков хватит примерно, дни
speed_7_order Скорость заказов за неделю, шт/день
speed_7 Скорость продаж за неделю, шт/день
speed_30 Скорость продаж за месяц, шт/день
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
fbs ФБС
fbo ФБО
fbs_fbo ФБС+ФБО
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
qualification уточнение
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
    if ($_GET['type'] == 1 or $_GET['type'] == 2){
        $fileN = 'cache/wb-cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'].'-'.$GLOBALS['config_return'];
    }else{
        $fileN = 'cache/wb-cache/' . $GLOBALS['wb_key_new'] . '-' . $_GET['type'];
    }

    if (!file_exists('cache/wb-cache')) {mkdir('cache/wb-cache', 0777, true);}
    file_put_contents($fileN, '', FILE_APPEND);
    $buf = file_get_contents($fileN);
    $buf2 = explode('@@---@@', $buf);

      if ($buf == "" || json_decode($buf2[1]) == NULL || time() - intval($buf2[0]) > 60*5 || strpos($buf, 'can\'t decode supplier key') !== false)
      {
          $ho = false;
          if ($api_url_new){$r_url_new = http_json($api_url_new,true);}else{$r_url_new = null;}

          if ($api_url_sales){
              if ($_GET['type'] == 1 or $_GET['type'] == 2 or $_GET['type'] == 10){$r_url_sales_report =  json_decode(report_cache());}
              if ($api_url_sales){$r_url_sales = http_json($api_url_sales);}
              if ($_GET['type']==10){$ho = true;}
              if ($r_url_sales_report){$r_url_sales = unity_report($r_url_sales_report,$r_url_sales);}
          }else{
              $r_url_sales = null;
          }
          if ($api_url){
              if (($_GET['type'] == 1 or $_GET['type'] == 2) and $_GET['type'] != 6){$r_url_report =  json_decode(report_cache());}
              if ($_GET['type']!=10 and !$ho){$r_url = http_json($api_url);}
              if ($r_url_report){$r_url = unity_report($r_url_report,$r_url);}
           //   $r_url = $r_url_report;
          }else{
              $r_url = null;
          }

          //$r_url_sales = http_json($api_url_sales);
          if (($r_url_new!=null and $r_url_sales!=null and $r_url!=null)
              or ($r_url_new==null and $r_url_sales!=null and $r_url!=null)
              or ($r_url_new!=null and $r_url_sales!=null and $r_url==null)
              or ($r_url_new!=null and $r_url_sales==null and $r_url!=null)){
              $r = array_unite($r_url,$r_url_new,$r_url_sales);
          }else{
              if ($r_url){$r_ur=$r_url;}elseif ($r_url_new){$r_ur=$r_url_new;}elseif ($r_url_sales){$r_ur=$r_url_sales;}
              $r =  json_encode($r_ur);
          }
          $dir = 'cache/stocks';
          $arr = array();
          foreach (json_decode($r) as $g){
            //  var_dump($g['barcode']);
          if ($g->barcode and ($_GET['type']==1 or $_GET['type']==2 or $_GET['type']==6 or $_GET['type']==10)){
              //var_dump($g->fbs);
              if ($g->fbs==null or !$g->fbs) {
                  $fileName = $dir . '/' . $GLOBALS['auth'] . '-stocks.txt';
                  $lines = file($fileName);
                  foreach ($lines as $line_num => $line) {
                      $line = json_decode($line);
                      if ($line->barcode == $g->barcode) {
                          $g->fbs = $line->stock;
                      }
                  }
              }

              if($g->fbo==null or !$g->fbo){
                  $fileName = $dir . '/' . $GLOBALS['auth'] . '-stocks_old.txt';
                  $lines = file($fileName);
                  foreach ($lines as $line_num => $line) {
                      $line = json_decode($line);
                      if ($line->barcode == $g->barcode) {
                              $g->techSize = $line->techSize;
                              $g->fbo += $line->quantity;
                              $g->isSupply = $line->isSupply;
                              $g->isRealization = $line->isRealization;
                              $g->quantityFull = $line->quantityFull;
                              $g->quantityNotInOrders = $line->quantityNotInOrders;
                      }
                  }
              }
              $g->fbs_fbo = $g->fbs + $g->fbo;
          }

          $arr[] = $g;
          }

          if ($_GET['type'] != 5 && $_GET['type'] != 6 && $_GET['type'] != 8 && $_GET['type'] != 9){
              $arr = speed($arr);
          }

          if ($_GET['type'] == 7 or $_GET['type'] == 8) {
              $fileName2 = 'cache/wb-cache/' . $GLOBALS['wb_key_new'] . '-2-on';
              $lines2 = file_get_contents($fileName2);
              $lines2 = explode('@@---@@', $lines2)[1];
              if (json_decode($lines2) == NULL || strpos($lines2, 'can\'t decode supplier key') !== false) {
                  $fileName1 = $dir . '/' . $GLOBALS['auth'] . '-stocks_old.txt';
                  $lines1 = file($fileName1);
                  $i = 0;
                  foreach ($arr as $g) {
                      foreach ($lines1 as $line_num => $line) {
                          if (strpos($line, $g->barcode) or strpos($line, $g->supplierArticle) or strpos($line, 'can\'t decode supplier key') !== false) {
                              $line = json_decode($line);
                              $g->brand = $line->brand;
                              $g->subject = $line->subject;
                              $g->category = $line->category;
                          }
                      }
                      $arr[$i] = $g;
                      $i++;
                  }
              }
              else {
                  $i = 0;
                  foreach ($arr as $g) {
                      foreach (json_decode($lines2) as $item) {
                          if ($item->barcode == $g->barcode or $item->supplierArticle == $g->supplierArticle) {
                              $g->brand = $item->brand;
                              $g->subject = $item->subject;
                              $g->category = $item->category;
                          }
                      }
                      $arr[$i] = $g;
                      $i++;
                  }
              }
          }

          $r = json_encode($arr);

              if ($r != '' && json_decode($r) !== NULL)
              {
                  file_put_contents($fileN, time() . '@@---@@' . $r,LOCK_EX);
              }
      }
}
echo true;