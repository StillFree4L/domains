<? if (!$no_signs) { ?>
<p>&nbsp;</p>
<p><?=Yii::t("main","Тьютор:")?> _________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Декан:")?> _________ <?php if ($registration) { ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Офис регистратор:")?> _________
<?php } ?>
</p>
<?php } ?>

<?php if ($overalls) {s
    ?>
<p><strong><?=Yii::t("main","Итого:")?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?=Yii::t("main","отлично")?> <u> 
    <?=intval($overalls["text-success"])?> </u> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=Yii::t("main","хорошо")?> <u> <?=intval($overalls["text-primary"])?> </u> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=Yii::t("main","удовлетворительно")?> <u> 
        <?=intval($overalls["text-warning"])?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=Yii::t("main","неудовлетворительно")?> <u> <?=intval($overalls["text-danger"])?> </u> 
        <?php if (isset($overalls["no"])) { ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?=Yii::t("main","неявка")?> <u> <?=intval($overalls["no"])?> </u> <?php } ?>
        </p>
<?php } ?>
<p><strong><?=Yii::t("main","Примечание:")?></strong></p>
<p><?=Yii::t("main","1) Преподаватель ответственен за подсчет итоговой оценки")?></p>
<table class="table table-bordered table-condensed">
<tr>
    <td style="width:8cm;"><p style='text-align:center'><?=Yii::t("main","Рейтинг")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","0-49")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","50-54")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","55-59")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","60-64")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","65-69")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","70-74")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","75-79")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","80-84")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","85-89")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","90-94")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","95-100")?></p></td>
</tr>
<tr>
    <td style="width:8cm;"><p style='text-align:center'><?=Yii::t("main","Балл")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","0")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","1")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","1.33")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","1.67")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","2")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","2.33")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","2.67")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","3")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","3.33")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","3.67")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","4")?></p></td>
</tr>
<tr>
    <td style="width:8cm;"><p style='text-align:center'><?=Yii::t("main","Буквенный эквивалент")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","F")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","D")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","D+")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","C-")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","C")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","C+")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","B-")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","B")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","B+")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","A-")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","A")?></p></td>
</tr>
<tr>
    <td style="width:8cm;"><p style='text-align:center'><?=Yii::t("main","Оценка")?></p></td>
    <td><p style='text-align:center'><?=Yii::t("main","Неуд.")?></p></td>
    <td colspan="4"><p style='text-align:center'><?=Yii::t("main","Удовлетворительно")?></p></td>
    <td colspan="4"><p style='text-align:center'><?=Yii::t("main","Хорошо")?></p></td>
    <td colspan="2"><p style='text-align:center'><?=Yii::t("main","Отлично")?></p></td>
</tr>
</table>
<p><?=Yii::t("main","2) Внесение изменений и корректив в рейтинговую ведомость не допускается")?></p>
<p><?=Yii::t("main","3) Члены апеляционной комиссии подписывают ведомость в случае проведения апелляции")?></p>