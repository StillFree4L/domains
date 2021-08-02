<p style="text-align: center"><strong><?=Yii::t("main","Министерство образования и науки Республики Казахстан")?></strong></p>
<p style="text-align: center"><strong><?=Yii::t("main","ЦЕНТРАЛЬНО-КАЗАХСТАНСКАЯ АКАДЕМИЯ")?></strong></p>
<p>&nbsp;</p>
<p style="text-align: center"><strong><?=mb_strtoupper($type, "UTF-8")?></strong></p>
<?php
    $smstr = $filter['s'];
    $course = $group->course;
    $academic_year =  $group->changed_course - ($course - (ceil($smstr / 2)));
    if (!$academic_year) {
        $academic_year = date('m') > 9 ? date('Y') : date('Y')-1;
    }
?>
<p style="text-align: center"><strong><?=$academic_year." - ".($academic_year + 1)." ".Yii::t("main","уч. год")?></strong></p>
<p>&nbsp;</p>
<p><?=Yii::t("main","Факультет:")?> <u> <?=$group->fakR->fak?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Специальность:")?> <u> <?=$group->specR->spec?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Форма обучения:")?> <u> <?=$group->formR->form?> </u></p>
<p><?=Yii::t("main","Группа:")?> <u> <?=$group->grup?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","курс:")?> <u> <?=$group->course?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","семестр:")?> <u> <?=$filter['s']?> </u></p>
<p><?=Yii::t("main","Дисциплина:")?> <u> <?=$subject->dis?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Количество кредитов:")?> <u> <?=$subject->credits?> </u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Ф.И.О. тьютора:")?> <u> <?=$subject->tea?> </u></p>
