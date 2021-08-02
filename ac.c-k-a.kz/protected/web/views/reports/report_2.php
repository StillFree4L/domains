<?=$this->render("parts/header", [
    "type" => \glob\models\forms\ReportsForm::getTypes(2),
    "group" => $group,
    "subject" => $subject,
    "filter" => $filter
]);
$date = null;
foreach ($tests as $t) {
    if ($t->t == 2 OR $t->t == 6 OR $t->t == 7) {
        $date = $t->testdate;
        break;
    }
}

?>
<p style=""><?=Yii::t("main","Дата:")?> <u><?=date('d.m.Y',$date)?></u></p>
<table class="table table-bordered table-condensed">
    <tr style="height:3.2cm;">
        <td style="vertical-align: middle;" ><p style='text-align: center;'>№</p></td>
        <td style="vertical-align: middle; width:12cm;"><p style='text-align: center;'><?=Yii::t("main","Ф.И.О обучающегося")?></p></td>
        <?php
        $rd_types = \glob\models\TestStarted::getRDTypes($group->form);
        $types = \glob\models\TestStarted::getTypes($group->form);
        foreach ($rd_types as $rd_t) {
        ?>
            <td style="padding-right:30px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>" ><div class="relative"><p class="rotate" style='text-align: center; position:absolute; width:3cm; top:0; left:0px;'><?=$types[$rd_t]?></p></div></td>
        <?php } ?>
        <td style="padding-right:30px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>" ><div class="relative"><p class="rotate" style='text-align: center; position:absolute; width:3cm; top:0; left:0px;'><?=$types[3]?></p></div></td>
        <td style="padding-right:30px;" rotate="<?=\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR?>"  ><div class="relative"><p class="rotate" style='text-align: center; position:absolute; width:3cm; top:0; left:0px;'><?=Yii::t("main","РД, с учетом аппеляции")?></p></div></td>
    </tr>
    <?php
    $i = 1;
    $overalls = [];
    foreach ($students as $student) {
        ?>
        <tr>
            <td style="vertical-align: middle;" ><p style='text-align: center;'><?=$i?></p></td>
            <td style="vertical-align: middle;"><p><?=$student->fio?></p></td>
            <?php
            $student_tests = array_filter($tests, function($test) use ($student) {
                return $test->ui_id == $student->id AND $test->finished == 1;
            });

            if ($student_tests) {
                $max = \glob\models\TestStarted::getMaxBalls($student_tests, $group->form);
                $overalls[\glob\models\TestStarted::ballTextColor($max[3])] = $overalls[\glob\models\TestStarted::ballTextColor($max[3])] ? $overalls[\glob\models\TestStarted::ballTextColor($max[3])] + 1 : 1;
            }

            foreach ($rd_types as $rd_t) {
            ?>
            <td style="vertical-align: middle;" ><p style='text-align: center;'><?=$max[$rd_t]?></p></td>
            <?php } ?>
            <td style="vertical-align: middle;"><p style='text-align: center;'><?=$max[3]?></p></td>
            <td ><p></p></td>
        </tr>
        <?php
        $i++;
    }
    ?>
</table>
<?=$this->render("parts/footer", [
    "type" => \glob\models\forms\ReportsForm::getTypes(2),
    "group" => $group,
    "subject" => $subject,
    "filter" => $filter,
    "tests" => $tests,
    "registration" => true,
    "overalls" => $overalls
])?>

