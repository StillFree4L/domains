<?=$this->render("parts/header", [
    "type" => \glob\models\forms\ReportsForm::getTypes(3),
    "group" => $group,
    "subject" => $subject,
    "filter" => $filter
]);


$date = null;
foreach ($tests as $t) {
    if ($t->t == 4) {
        $date = $t->testdate;
        break;
    }
}

?>
<p style=""><?=Yii::t("main","Дата:")?> <u><?=date('d.m.Y',$date)?></u></p>
    <table class="table table-bordered table-condensed">
        <tr>
            <td style="vertical-align: middle;" tid="1" rowspan="2"><p style='text-align: center;'>№</p></td>
            <td style="vertical-align:middle; width:12cm; text-align: left;" tid="2" rowspan="2"><p style='text-align: left;'><?=Yii::t("main","Ф.И.О обучающегося")?></p></td>
            <td style="vertical-align: middle;" colspan="3"><p style='text-align: center;'><strong><?=Yii::t("main","Оценка")?></strong></p></td>
            <td style="vertical-align: middle;" ><p style='text-align: center;'><?=Yii::t("main","Подпись тьютора")?></p></td>
            <td style="vertical-align: middle;" ><p style='text-align: center;'><?=Yii::t("main","Примечание")?></p></td>
        </tr>
        <tr>
            <td style="vertical-align: middle;"  rid="[1,2]"><p style='text-align: center;'><?=Yii::t("main","%")?></p></td>
            <td style="vertical-align: middle;" ><p style='text-align: center;'><?=Yii::t("main","Цифровой эквивалент")?></p></td>
            <td style="vertical-align: middle;" ><p style='text-align: center;'><?=Yii::t("main","Буквенный эквивалент")?></p></td>
            <td style="vertical-align: middle;" ><p></p></td>
            <td style="vertical-align: middle;" ><p></p></td>
        </tr>
        <?php
        $i = 1;
        foreach ($students as $student) {
            ?>
            <tr>
                <td style="vertical-align: middle;" ><p style='text-align: center;'><?=$i?></p></td>
                <td style="vertical-align: middle;"><p style='text-align: left;'><?=$student->fio?></p></td>
                <?php
                $student_tests = array_filter($tests, function($test) use ($student) {
                    return $test->ui_id == $student->id;
                });

                $max[4] = 0;
                if ($student_tests) {
                    foreach ($student_tests as $it) {
                        if ($it->ball > $max[$it->t]) {
                            $max[$it->t] = $it->ball;
                        }
                    }
                }

                $overall_a = \glob\models\TestStarted::getDeployedMark($max[4]);
                ?>
                <td style="vertical-align: middle;"><p style='text-align: center;'><?=$overall_a['percent']?></p></td>
                <td style="vertical-align: middle;"><p style='text-align: center;'><?=$overall_a['ball']?></p></td>
                <td style="vertical-align: middle;"><p style='text-align: center;'><?=$overall_a['char']?></p></td>
                <td style="vertical-align: middle;"><p></p></td>
                <td style="vertical-align: middle;"><p></p></td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </table>
<?=$this->render("parts/footer", [
    "type" => \glob\models\forms\ReportsForm::getTypes(3),
    "group" => $group,
    "subject" => $subject,
    "filter" => $filter,
    "tests" => $tests,
    "registration" => false
])?>