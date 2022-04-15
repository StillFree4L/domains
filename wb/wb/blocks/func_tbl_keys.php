<?php
//наименования столбцов
if ($_GET['type'] == 11){
  $calc_keys = "supplierArticle Артикул
barcode Баркод
subject Предмет
category Категория
brand Бренд
strikethrough_price Зачеркнутая цена ₽
sale_percent Скидка %
sale_total Скидка ₽
totalPrice Розничная цена ₽
stoimost Себестоимость ₽
zatrat Доп расходы ₽
wb_commission Комиссия WB %
cost_wb_commission Комиссия WB ₽
cost_delivery Доставка ₽
cost_amout Возврат ₽
cost_log Логистика ₽
all_costs Все затраты ₽
nalog7 Налоги ₽
ransom Выкупа %
defect Брак %
cost_defect Брак ₽
pribil Маржа ₽
marga ROI %";
}
if (!isset($_GET['rid']) and $_GET['type'] == 9){
    $pribil_keys = 'realizationreport_id Номер отчета
rr_dt Операция дата
quantity Продажи шт
retail_amount Сумма продаж(Возвратов) ₽
storage_cost Хранение ₽
acceptance_fee Платной приемки ₽
other_deductions Прочие удержания ₽
ppvz_for_pay К перечислению Продавцу за реализованный Товар ₽
ppvz_vw Вознаграждение Вайлдберриз (ВВ), без НДС ₽
ppvz_vw_nds НДС с Вознаграждения Вайлдберриз ₽
delivery_amount Доставки шт
return_amount Возвраты шт
delivery_rub Логистика ₽
total_payable Итого к оплате ₽
all_cost Все расходы ₽
nalog7 Налоги ₽
pribil Маржа ₽
marga ROI %
speed_back Скорость возврата инвестиций
ss_one Общая себестоимость ₽';

}elseif (isset($_GET['rid']) and $_GET['type'] == 9){
    $pribil_keys = 'realizationreport_id Номер отчета
suppliercontract_code Договор
rid Уникальный идентификатор позиции заказа
rr_dt Операция дата
rrd_id Номер строки
gi_id номер поставки
subject_name Предмет
nm_id Артикул
brand_name Бренд
sa_name Артикул поставщика
ts_name Размер
barcode Баркод
doc_type_name Тип документа
quantity Продажи шт
retail_price Розничная цена ₽
retail_amount Сумма продаж(Возвратов) ₽
sale_percent Cкидка cогласованная %
commission_percent Комиссия %
office_name Склад
supplier_oper_name Обоснование для оплаты
order_dt Заказ дата
order_dt Продажа дата
shk_id ШК
retail_price_withdisc_rub Розничная цена с учетом согласованной скидки ₽
delivery_amount Доставки шт
return_amount Возвраты шт
delivery_rub Логистика ₽
gi_box_type_name Тип коробов
product_discount_for_report Продуктовый дисконт согласованный %
supplier_promo Промокод
ppvz_spp_prc Постоянного покупателя скидка %
ppvz_kvw_prc_base Размер кВВ без НДС % Базовый
ppvz_kvw_prc Итоговый кВВ без НДС %
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
incomeId Поставки
category категория
status Текущий статус поставки
total_payable Итого к оплате ₽
all_cost Все расходы ₽
nalog7 Налоги ₽
pribil Маржа ₽
marga ROI %
speed_back Скорость возврата инвестиций
ss_one Общая себестоимость ₽';

}

if ($_GET['f1'] and $_GET['type'] == 8) {
    $sebes_keys = 'incomeId Поставки
    date Поступление дата
supplierArticle Ваш артикул
techSize Размер
barcode Баркод
quantity Кол-во шт
subject предмет
category категория
brand бренд
dateClose Принятия (закрытия) у нас дата
lastChangeDate Обновления информации в сервисе дата и время
warehouseName название склада
status Текущий статус поставки
ss_all Общая себестоимость с учётом количества ₽
ss_one Общая себестоимость единицы товара ₽';
}elseif (!$_GET['f1'] and $_GET['type'] == 8){
    $sebes_keys = 'incomeId Поставки
supplierArticle Ваш артикул
barcode Баркод
quantity Кол-во шт
subject предмет
category категория
brand бренд
ss_all Общая себестоимость ₽
ss_one Средняя себестоимость единицы ₽';
}

//=============================================

$postav_keys = 'incomeId номер поставки
date Поступление дата
quantity Кол-во шт
status Текущий статус поставки
warehouseName название склада
subject предмет
category категория
brand бренд
dateClose Принятия (закрытия) у нас дата
lastChangeDate Обновления информации в сервисе дата и время
number номер УПД
totalPrice цена из УПД ₽
';

//===========================================

$sklad_keys = 'lastChangeDate Обновления информации в сервисе дата и время
category Категория
brand Бренд
subject Предмет
barcode Баркод
supplierArticle Артикул
nmId Код WB
techSize Размер
fbs ФБС, остаток шт
fbo ФБО, остаток шт
fbs_fbo ФБС+ФБО, остаток шт
quantity Доступное для продажи шт
quantityNotInOrders не в заказе шт
quantityFull полное шт
upush Упущенная выручка ₽
speed Остатков хватит примерно, дни
speed_7_order заказов за неделю шт/день
speed_7 продаж за неделю шт/день
speed_30 продаж за месяц шт/день
inWayToClient к клиенту шт
inWayFromClient от клиента шт
refund_7 за неделю возврат %
refund_30 за месяц возврат %
Price Цена ₽
Discount Дисконт %
price_min_discount Цена после вычета дисконта ₽
warehouseName Склад
daysOnSite на сайте, дни
isSupply Договор поставки
isRealization Договор реализации
SCCode код контракта
';
//---------------------------------------------------------------------

$report_keys = 'realizationreport_id Номер отчета
suppliercontract_code Договор
rid Уникальный идентификатор позиции заказа
rr_dt Операция дата
rrd_id Номер строки
gi_id номер поставки
subject_name Предмет
nm_id Артикул
brand_name Бренд
sa_name Артикул поставщика
ts_name Размер
barcode Баркод
doc_type_name Тип документа
quantity Продажи шт
retail_price Розничная цена ₽
retail_amount Сумма продаж(Возвратов) ₽
sale_percent Согласованная скидка %
commission_percent Комиссия %
office_name Склад
supplier_oper_name Обоснование для оплаты
order_dt Заказ дата
order_dt Продажа дата
shk_id ШК
retail_price_withdisc_rub Розничная цена с учетом согласованной скидки
delivery_amount Доставки шт
return_amount Возвраты шт
delivery_rub Логистика ₽
gi_box_type_name Тип коробов
product_discount_for_report Продуктовый дисконт согласованный %
supplier_promo Промокод
ppvz_spp_prc Постоянного покупателя скидка %
ppvz_kvw_prc_base Размер кВВ без НДС % Базовый
ppvz_kvw_prc Итоговый кВВ без НДС %
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
date заказ дата
lastChangeDate изменения дата
supplierArticle артикул
techSize размер
barcode Баркод
fbs ФБС, остаток шт
fbo ФБО, остаток шт
fbs_fbo ФБС+ФБО, остаток шт
quantity Кол-во шт
totalPrice цена до скидки/промо/спп ₽
discountPercent дисконт итоговый %
finishedPrice цена итоговая ₽
speed Остатков хватит примерно, дни
speed_7_order за неделю заказов  шт/день
speed_7 за неделю продаж шт/день
speed_30 за месяц продаж шт/день
warehouseName склад
oblast область
incomeID номер поставки
odid ид.позиции заказа
nmId Код WB
subject предмет
category категория
brand бренд
isCancel отменен
cancel_dt отмена заказа дата
status статус
userStatus статус клиента
deliveryType тип доставки
';

$sales_keys = 'number № документа
date продажи дата
lastChangeDate изменения дата
supplierArticle артикул
techSize размер
barcode Баркод
fbs ФБС, остаток шт
fbo ФБО, остаток шт
fbs_fbo ФБС+ФБО, остаток шт
quantity Кол-во шт
totalPrice начальная розничная цена ₽
discountPercent скидка на товар %
isSupply поставки договор
isRealization реализации договор
orderId Номер заказа
promoCodeDiscount промокод
speed Остатков хватит примерно, дни
speed_7_order за неделю заказов  шт/день
speed_7 за неделю продаж шт/день
speed_30 за месяц продаж шт/день
warehouseName склад
countryName страна
oblastOkrugName округ
regionName регион
incomeID номер поставки
saleID ид. продажи/возврата
odid ид. позиции заказа
spp постоянного покупателя скидка %
forPay к перечислению поставщику ₽
finishedPrice фактическая цена ₽
priceWithDisc цена, от которой считается вознаграждение поставщика ₽
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
lastChangeDate изменения дата
supplierArticle артикул
techSize размер
barcode Баркод
fbs ФБС, остаток шт
fbo ФБО, остаток шт
fbs_fbo ФБС+ФБО, остаток шт
quantity Кол-во шт
totalPrice цена до скидки/промо/спп ₽
discountPercent дисконт %
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
spp постоянного покупателя скидка %
forPay к перечислению поставщику ₽
finishedPrice фактическая цена ₽
priceWithDisc цена, от которой считается вознаграждение поставщика ₽
nmId код WB
subject предмет
category категория
brand бренд
IsStorno сторнирована
isCancel отменен
cancel_dt отмены заказа дата
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
if($calc_keys){
  $calc_keys = make_tbl_keys($calc_keys);
}

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    function mb_ucfirst($string) {
        $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
        return $string;
    }
}
