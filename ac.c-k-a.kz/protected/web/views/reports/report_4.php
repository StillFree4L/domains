<?=$this->render("parts/header_summary", [
    "type" => \glob\models\forms\ReportsForm::getTypes(4),
    "group" => $group,
    "filter" => $filter,
]);
$overalls = [];

$raw_tests = [];
foreach ($tests as $test) {
    if ($test->finished == 1 OR (in_array($test->t, [4,9,10,8]))) {
        if (!$raw_tests[$test->ui_id][$test->dis_id][$test->t]) {
            $raw_tests[$test->ui_id][$test->dis_id][$test->t] = 0;
        }
        if ($test->ball > $raw_tests[$test->ui_id][$test->dis_id][$test->t]) {
            $raw_tests[$test->ui_id][$test->dis_id][$test->t] = $test->ball;
        }
    }
}

function getRD($student, $subject, $group, $raw_tests)
{
    if ($group->form == 1) {
        return floor(($raw_tests[$student->id][$subject->id][6] + $raw_tests[$student->id][$subject->id][7] + $raw_tests[$student->id][$subject->id][9] + $raw_tests[$student->id][$subject->id][10]) / 2);
    } else {
        return floor(($raw_tests[$student->id][$subject->id][2] + $raw_tests[$student->id][$subject->id][8]) / 2);
    }
}

?>
<p>&nbsp;</p>
<table class="table table-bordered table-condensed table-mini">
    <tr>
        <td colspan="2" rowspan="2" tid="1" style="width:6cm; vertical-align: middle;"><p style="text-align: center"><?=Yii::t("main","Ф.И.О. студента")?></p></td>
        <td colspan="4" style="vertical-align: middle;"><p style="text-align: center"><strong><?=Yii::t("main","GPA")?></strong></p></td>
        <?php
        $count = 0;
        foreach ($subjects as $subject) {
            if ($subject->kurs) {
                $count++;
            }
            $count++;
        }
        ?>
        <td colspan="<?=$count?>" style="vertical-align: middle;"><p style="text-align: center"><strong><?=Yii::t("main","Дисциплина")?></strong></p></td>
    </tr>
    <tr style="height:8cm">
        <td style="padding-right:20px;" rid="[1]" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>"><div class="relative"><p class="rotate" style='line-height:normal; text-align: center; position:absolute; width:7.8cm; top:0; left:0px;'><?=Yii::t("main","GPA итог (в %)")?></p></div></td>
        <td style="padding-right:20px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>"><div class="relative"><p class="rotate" style='line-height:normal; text-align: center; position:absolute; width:7.8cm; top:0; left:0px;'><?=Yii::t("main","GPA Итог (Буквенная)")?></p></div></td>
        <td style="padding-right:20px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>"><div class="relative"><p class="rotate" style='line-height:normal; text-align: center; position:absolute; width:7.8cm; top:0; left:0px;'><?=Yii::t("main","GPA Итог (Балл)")?></p></div></td>
        <td style="padding-right:20px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>"><div class="relative"><p class="rotate" style='line-height:normal; text-align: center; position:absolute; width:7.8cm; top:0; left:0px;'><?=Yii::t("main","GPA Итог (Традиционная)")?></p></div></td>
        <?php
            $credits = 0;
            foreach ($subjects as $subject) {
                if ($subject->kurs) {
                    ?>
                    <td style="padding-right:25px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>"  ><div class="relative"><p class="rotate" style='line-height:normal; text-align: center; position:absolute; width:7.8cm; top:0; left:0px;'><strong><?=$subject->dis.Yii::t("main","(курс.)")?></strong></p></div></td>
                <?php
                }
                $credits += $subject->credits;
                ?>
                <td style="padding-right:20px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>"  ><div class="relative"><p class="rotate" style='line-height:normal; text-align: center; position:absolute; width:7.8cm; top:0; left:0px;'><?=$subject->dis?></p></div></td>
                <?php
            }
        ?>
    </tr>
    <tr>
        <td colspan="2" style="vertical-align: middle;"><p style="text-align: right"><?=Yii::t("main","Количество кредитов:")?> <?=$credits?></p></td>
        <td style="vertical-align: middle;"><p style="text-align: center;"></p></td>
        <td style="vertical-align: middle;"><p style="text-align: center;"></p></td>
        <td style="vertical-align: middle;"><p style="text-align: center;"></p></td>
        <td style="vertical-align: middle;"><p style="text-align: center;"></p></td>
        <?php
        foreach ($subjects as $subject) {
            if ($subject->kurs) {
                ?>
                <td></td>
                <?php
            }
                ?>
                <td style="vertical-align: middle;"><p style="text-align: center"><?= $subject->credits ?></p></td>
            <?php
        }
        ?>
    </tr>
    <?php
    $i = 1;
    $overalls = [];
    foreach ($students as $student) {
        ?>
        <tr>
            <td  style="vertical-align: middle;" ><p style='text-align: center;'><?=$i?></p></td>
            <td style="vertical-align: middle;"><p style='text-align: center;'><?=$student->fio?></p></td>
            <?php
                $gpaPerc = 0;
                $gpaBall = 0;
                foreach ($subjects as $subject) {
                    $rd = getRd($student, $subject, $group, $raw_tests);
                    $overall_mark = floor($rd*0.6 + (intval($raw_tests[$student->id][$subject->id][1])*0.4));
                    $gpaPerc += ($overall_mark*$subject->credits);
                    $gpaBall += (\glob\models\TestStarted::getDeployedMark($overall_mark)['ball']*$subject->credits);
                }
                $GPAovm = round($gpaPerc/$credits);
                $GPA = \glob\models\TestStarted::getDeployedMark($GPAovm);
                $GPA['ball'] = round($gpaBall/$credits, 2);
            ?>
            <td style="vertical-align: middle;"><p style="text-align: center;"><?=$GPA['percent']?></p></td>
            <td style="vertical-align: middle;"><p style="text-align: center;"><?=$GPA['char']?></p></td>
            <td style="vertical-align: middle;"><p style="text-align: center;"><?=$GPA['ball']?></p></td>
            <td style="vertical-align: middle;"><p style="text-align: center;"><?=$GPA['classic']?></p></td>
            <?php
            foreach ($subjects as $subject) {

                $rd = getRd($student, $subject, $group, $raw_tests);
                if ($raw_tests[$student->id][$subject->id][1]) {
                    $overall_mark = floor($rd * 0.6 + (intval($raw_tests[$student->id][$subject->id][1]) * 0.4));
                    $overalls[0][$subject->id][\glob\models\TestStarted::ballTextColor($overall_mark)] = $overalls[0][$subject->id][\glob\models\TestStarted::ballTextColor($overall_mark)] ? $overalls[0][$subject->id][\glob\models\TestStarted::ballTextColor($overall_mark)] + 1 : 1;
                } else {
                    $overall_mark = 0;
                    $overalls[0][$subject->id]["no"] = $overalls[0][$subject->id]["no"] ? $overalls[0][$subject->id]["no"] + 1 : 1;
                }
                if ($subject->kurs) {
                    $kurs = intval($raw_tests[$student->id][$subject->id][4]);
                    $overalls[1][$subject->id][\glob\models\TestStarted::ballTextColor($kurs)] = $overalls[1][$subject->id][\glob\models\TestStarted::ballTextColor($kurs)] ? $overalls[1][$subject->id][\glob\models\TestStarted::ballTextColor($kurs)]+1 : 1;
                    ?>
                    <td style="vertical-align: middle;"><p style="text-align: center"><strong><?=$kurs?></strong></p></td>
                    <?php
                }

                ?>
                <td style="vertical-align: middle;"><p style="text-align: center"><?=$overall_mark?></p></td>
                <?php

            }?>
        </tr>
        <?php
        $i++;
    }

    $summaries = [
        "text-success" => Yii::t("main","отлично"),
        "text-primary" => Yii::t("main","хорошо"),
        "text-warning" => Yii::t("main","удовлетворительно"),
        "text-danger" => Yii::t("main","неудовлетворительно"),
        "no" => Yii::t("main","Неявка")
    ];

    ?>
    <tr>
        <td colspan="12" style="border-width:0;"></td>
    </tr>
    <?php

    foreach ($summaries as $s => $label) {
    ?>
        <tr>
            <td colspan="6" style="border-width:0; vertical-align: middle;"><p style="text-align: right;"><?=$label?></p></td>
            <?php
                foreach ($subjects as $subject) {
                    if ($subject->kurs) {
                        ?>
                        <td style="vertical-align: middle;"><p style="text-align: center"><strong><?=$overalls[1][$subject->id][$s]?></strong></p></td>
                    <?php
                    }
                    ?>
                    <td style="vertical-align: middle;"><p style="text-align: center"><?=$overalls[0][$subject->id][$s]?></p></td>
                    <?php
                }
            ?>
        </tr>
    <?php
    } ?>
</table>
<p>&nbsp;</p>
<p><?=Yii::t("main","Декан __________________________")?></p>