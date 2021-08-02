<p style="text-align: center"><strong><?=Yii::t("main","ЦЕНТРАЛЬНО-КАЗАХСТАНСКАЯ АКАДЕМИЯ")?></strong></p>
<p style="text-align: center">____________________________________________________</p>
<p style="text-align: center"><strong><?=mb_strtoupper($type, "UTF-8")?></strong></p>
<?php
$smstr = $filter['s'];
$course = $group->course;
$academic_year =  $group->changed_course - ($course - (ceil($smstr / 2)));
if (!$academic_year) {
    $academic_year = date('m') > 9 ? date('Y') : date('Y')-1;
}
?>
<p style="text-align: center"><strong><?=Yii::t("main","Учебный год:")?> <u> <?=$academic_year." - ".($academic_year + 1)?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Семестр:")?> <u> <?=$filter['s']?> </u> </strong></p>
<p style="text-align: center"><strong><?=Yii::t("main","Специальность:")?> <u> <?=$group->specR->spec?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Язык обучения:")?> <u><?=$group->otd == 1 ? Yii::t("main","Русский") : Yii::t("main","Казахский")?></u> </strong></p>
<p style="text-align: center"><strong><?=Yii::t("main","Форма обучения:")?> <u> <?=$group->formR->form?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Курс:")?> <u> <?=$group->course?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Группа:")?> <u> <?=$group->grup?> </u></strong></p>

