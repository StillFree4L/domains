<table cellspacing="0" style="width: 600px;font-size: 14px;font-family: 'Times New Roman';color: black;color: black;text-align: justify; margin: auto;">
    <tr>
        <td colspan="6" style="border-bottom: 3px solid #000;font-size: 20px;font-weight: bold;">Акт № <?= $model->receipt ?> от <?= $model->date ?>.</td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr><br>
    <tr>
        <td>Исполнитель:</td>
        <td colspan="5" style="font-weight: bold;">ТОО "СТАТУС КАРАГАНДА"</td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr><br>
    <tr>
        <td>Заказчик:</td>
        <td colspan="5" style="font-weight: bold;"><?= $model->client ?></td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr><br>
    <tr style="font-weight: bold;text-align: center;">
        <td style=" border: 1px solid #000;border-top: 3px solid #000;border-left: 3px solid #000;border-bottom: 2px solid #000;">№</td>
        <td style="border: 1px solid #000;border-top: 3px solid #000;border-bottom: 2px solid #000;">Наименование работ, услуг</td>
        <td style="border: 1px solid #000;border-top: 3px solid #000;border-bottom: 2px solid #000;">Кол-во</td>
        <td style="border: 1px solid #000;border-top: 3px solid #000;border-bottom: 2px solid #000;">Ед.</td>
        <td style="border: 1px solid #000;border-top: 3px solid #000;border-bottom: 2px solid #000;">Цена</td>
        <td style="border: 1px solid #000;border-top: 3px solid #000;border-right: 3px solid #000;border-bottom: 2px solid #000;">Сумма</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000;border-top: 1px solid #000;border-left: 3px solid #000;border-bottom: 0px solid #000;">1</td>
        <td style="border: 1px solid #000;border-top: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $model->service_name ?></td>
        <td style="border: 1px solid #000;border-top: 1px solid #000;border-bottom: 0px solid #000;text-align: center;">1</td>
        <td style="border: 1px solid #000;border-top: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"></td>
        <td style="border: 1px solid #000;border-top: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $model->money ?></td>
        <td style="border: 1px solid #000;border-top: 1px solid #000;border-right: 3px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $model->money ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border-top: 3px solid #000;"></td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr><br><br>
    <tr>
        <td colspan="5" style="font-weight: bold;text-align: right;">Итого:</td>
        <td style="font-weight: bold;text-align: right;"><?= $model->money ?></td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight: bold;text-align: right;">В том числе НДС:</td>
        <td style="font-weight: bold;text-align: right;"><?= (int)(($model->money)/8.3) ?></td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr><br><br><br>
    <tr>
        <td colspan="6">Всего оказано услуг 1, на сумму <?= $model->money ?> ТГ</td>
    </tr>
    <tr height="15px;">
        <td colspan="6"></td>
    </tr><br>
    <tr>
        <td colspan="6">Вышеперечисленные услуги выполнены полностью и в срок. Заказчик претензий по объему, качеству и срокам оказания услуг не имеет.</td>
    </tr>
    <tr height="50px;">
        <td colspan="6">____________________________________________________________________</td>
    </tr>
    <br><br><br>
    <tr>
        <td colspan="2" style="font-weight: bold;">ИСПОЛНИТЕЛЬ</td>
        <td></td>
        <td colspan="4" style="font-weight: bold;">ЗАКАЗЧИК</td>
    </tr>
    <tr>
        <td colspan="2">ТОО "СТАТУС КАРАГАНДА"</td>
        <td></td>
        <td colspan="4"><?= $model->client ?></td>
    </tr>
    <tr height="100px;">
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2" style="border-bottom: 1px solid #000;"></td>
        <td></td>
        <td colspan="4" style="border-bottom: 1px solid #000;"></td>
    </tr>
</table>