<?=$this->render("parts/header", [
    "type" => \glob\models\forms\ReportsForm::getTypes(1),
    "group" => $group,
    "subject" => $subject,
    "filter" => $filter
]);?>
<?php
$overalls = [];

if ($subject->gek) {
?>
<p><?=Yii::t("main","Комиссия:")?></p>
<p><?=Yii::t("main","Председатель ГЭК:")?> ________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Зам. председателя:")?> __________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Члены комиссии:")?> _________________</p>
<?php
}

$date = null;
foreach ($tests as $t) {
    if ($t->t == 1) {
        $date = $t->testdate;
        break;
    }
}

?>
<p style=""><?=Yii::t("main","Дата проведения:")?> <u><?=date('d.m.Y',$date)?></u></p>
<table class="table table-bordered table-condensed">
    <tr>
        <td style="vertical-align: middle;"  tid="1" rowspan="2"><p style='text-align: center;'><span>№</span></p></td>
        <td style="width:12cm; vertical-align: middle;" tid="2" rowspan="2"><p style='text-align: center;'><span><?=Yii::t("main","Ф.И.О обучающегося")?></span></p></td>
        <td style="vertical-align: middle;"  tid="3" rowspan="2"><p style='text-align: center;'><span><?=Yii::t("main","РД")?></span></p></td>
        <td style="vertical-align: middle;"  tid="4" rowspan="2"><p style='text-align: center; word-wrap: break-word;'><span><?=Yii::t("main","Экзамена ционная оценка (в %)")?></span></p></td>
        <td style="vertical-align: middle;"  colspan="4"><p style='text-align: center;'><span><strong><?=Yii::t("main","Итоговая оценка И = РД*0.6 + Э*0.4")?></strong></span></p></td>
    </tr>
    <tr>
        <td style="vertical-align: middle;" rid="[1,2,3,4]"><p style='text-align: center;'><span><?=Yii::t("main","В процентах")?></span></p></td>
        <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=Yii::t("main","Буквенная")?></span></p></td>
        <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=Yii::t("main","В баллах")?></span></p></td>
        <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=Yii::t("main","Традиционная")?></span></p></td>
    </tr>
    <?php
        $i = 1;
        foreach ($students as $student) {
            ?>
            <tr>
                <td style="vertical-align: middle;"  ><p style='text-align: center;'><span><?=$i?></span></p></td>
                <td style="vertical-align: middle;" ><p><?=$student->fio?></p></td>
                <?php
                $student_tests = array_filter($tests, function($test) use ($student) {
                    return $test->ui_id == $student->id AND $test->finished == 1;
                });


                if ($student_tests) {
                    $max = \glob\models\TestStarted::getMaxBalls($student_tests, $group->form);
                    $overall = ($max[3] * 0.6) + ($max[1] * 0.4);
                    $overalls[\glob\models\TestStarted::ballTextColor($overall)] = $overalls[\glob\models\TestStarted::ballTextColor($overall)] ? $overalls[\glob\models\TestStarted::ballTextColor($overall)] + 1 : 1;
                    $overall_a = \glob\models\TestStarted::getDeployedMark($overall);
                } else {
                    $overall_a = \glob\models\TestStarted::getDeployedMark(0);
                    $overalls['no'] = $overalls['no'] ? $overalls['no']+1 : 1;
                }
                ?>
                <td style="vertical-align: middle;"  ><p style='text-align: center;'><span><?=$max[3]?></span></p></td>
                <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=$max[1]?></span></p></td>
                <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=$overall_a['percent']?></span></p></td>
                <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=$overall_a['char']?></span></p></td>
                <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=$overall_a['ball']?></span></p></td>
                <td style="vertical-align: middle;" ><p style='text-align: center;'><span><?=$overall_a['classic']?></span></p></td>
            </tr>
            <?php
            $i++;
        }
    ?>
</table>
<?php if ($subject->gek) { ?>
<p>&nbsp;</p>
<p><?=Yii::t("main","Председатель ГЭК")?> _________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Yii::t("main","Зам. председателя")?> _________ </p>
<p><?=Yii::t("main","Члены комиссии")?> _____________________________________________________________ </p>
<?php } ?>
<?=$this->render("parts/footer", [
    "no_signs" => $subject->gek ? true : false,
    "type" => \glob\models\forms\ReportsForm::getTypes(1),
    "group" => $group,
    "subject" => $subject,
    "filter" => $filter,
    "tests" => $tests,
    "registration" => true,
    "overalls" => $overalls
])?>
