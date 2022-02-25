<?php
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
if ($_GET['f1'] and $_GET['type'] == 8) {
    $sebes_keys = 'incomeId Поставки
supplierArticle Ваш артикул
techSize Размер
barcode Баркод
quantity кол-во
subject предмет
category категория
brand бренд
warehouseName название склада
status Текущий статус поставки
ss_all Общая себестоимость с учётом количества
ss_one Общая себестоимость единицы товара';
}elseif (!$_GET['f1'] and $_GET['type'] == 8){
    $sebes_keys = 'incomeId Поставки
supplierArticle Ваш артикул
techSize Размер
barcode Баркод
quantity кол-во
subject предмет
category категория
brand бренд
warehouseName название склада
status Текущий статус поставки
ss_all Общая себестоимость
ss_one Средняя себестоимость единицы';
}

//=============================================
/*
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
*/
$postav_keys = 'incomeId номер поставки
date дата поступления
quantity кол-во
status Текущий статус поставки
warehouseName название склада
subject предмет
category категория
brand бренд
dateClose дата принятия (закрытия) у нас
lastChangeDate дата и время обновления информации в сервисе
number номер УПД
totalPrice цена из УПД
';

//===========================================
/*
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
*/
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
