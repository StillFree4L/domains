<table cellspacing="0" style="width: 600px;font-size: 14px;font-family: 'Times New Roman';color: black;text-align: justify; margin: auto;">

    <tr>
        <td colspan="6" style="font-weight: bold;">Приложение 1</td>
    </tr>

    <br><br>
    <tr style="font-weight: bold;text-align: center;">
        <td colspan="6" style="border: 1px solid #000;border-top: 3px solid #000;border-left: 3px solid #000;border-bottom: 2px solid #000;text-align: center;">Результат устранения неисправности:</td>
    </tr>
    <tr>
        <td colspan="6" style="border: 1px solid #000;border-top: 1px solid #000;border-left: 3px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $model->result_name ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border-top: 3px solid #000;"></td>
    </tr>

    <br><br>
    <tr style="font-weight: bold;text-align: center;">
        <td colspan="6" style="border: 1px solid #000;border-top: 3px solid #000;border-left: 3px solid #000;border-bottom: 2px solid #000;text-align: center;">Ремонт и материалы:</td>
    </tr>

    <tr style="font-weight: bold;text-align: center;">
        <td colspan="3" style="border: 1px solid #000;border-left: 3px solid #000;border-bottom: 2px solid #000;">Наименование ремонта</td>
        <td style="border: 1px solid #000;border-bottom: 2px solid #000;">Кол-во</td>
        <td style="border: 1px solid #000;border-bottom: 2px solid #000;">Цена</td>
        <td style="border: 1px solid #000;border-bottom: 2px solid #000;">Сумма</td>
    </tr>
    <?php
    $price=0;$i=0;
    foreach ($completes as $complete){
        $price+=$complete->price;
    }
    foreach ($materials as $material){
        $price+=$material->price;
    }
    ?>
    <?php foreach ($materials as $material){$i++;?>
        <tr>
            <td colspan="3" style="border: 1px solid #000;border-top: 1px solid #000;border-left: 3px solid #000;border-bottom: 0px solid #000;"><?= $material->name ?></td>
            <td style="border: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $material->number ?></td>
            <td style="border: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $material->price ?></td>
            <td style="border: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $price ?></td>
        </tr>
    <?php }?>
    <?php foreach ($completes as $complete){$i++;?>
        <tr>
            <td colspan="3" style="border: 1px solid #000;border-top: 1px solid #000;border-left: 3px solid #000;border-bottom: 0px solid #000;"><?= $complete->name ?></td>
            <td style="border: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $complete->number ?></td>
            <td style="border: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $complete->price ?></td>
            <td style="border: 1px solid #000;border-bottom: 0px solid #000;text-align: center;"><?= $price ?></td>
        </tr>
    <?php }?>
    <tr style="font-weight: bold;text-align: center;">
        <td style="border-bottom: 1px solid #000;border-top: 3px solid #000;border-left: 3px solid #000;text-align: center;"></td>
        <td style="border-bottom: 1px solid #000;border-top: 3px solid #000;text-align: center;"></td>
        <td style="border-bottom: 1px solid #000;border-top: 3px solid #000;text-align: center;"></td>
        <td style="border-bottom: 1px solid #000;border-top: 3px solid #000;text-align: center;"></td>
        <td style="border: 1px solid #000;border-left: 2px solid #000;border-top: 3px solid #000;text-align: center;">Итог:</td>
        <td style="border: 1px solid #000;border-top: 3px solid #000;text-align: center;"><?= $price ?></td>
    </tr>
    <tr>
        <td colspan="6" style="border-top: 2px solid #000;"></td>
    </tr>

    <br><br>
    <tr>
        <td colspan="6"><?= $model->client ?></td>
    </tr>
    <tr>
        <td colspan="6">Оборудование после диагностики и ремонта</td>
    </tr>

    <tr>
        <td colspan="6">Получил : <?= $model->client ?> __________________________________________________ </td>
    </tr>

    <br><br>
    <tr>
        <td colspan="6">ТОО "Статус Караганда",Республика Казахстан, г. Караганда, пр. Н.Абдирова, дом № 47/1, к.63, тел.: 8-705-910-22-00</td>
    </tr>
    <tr>
        <td colspan="6">Выполнил : <?=$model->username?>______________________________________</td>
    </tr>


</table>
