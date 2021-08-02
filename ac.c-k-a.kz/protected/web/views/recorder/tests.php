<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Офис-регистратор"), false);
?>

<div class="controller container">
    <div class="action-content">

        <div>
            <div class="inline-block">

                <div class="form-group">
                    <?=\app\helpers\Html::dropDownList("group_id", $filter["group_id"], \yii\helpers\ArrayHelper::map($groups, "id", "grup") , [
                        "class"=>"form-control chosen-list",
                        "empty"=>Yii::t("main","Группа")
                    ]);?>
                </div>

            </div>

            <div class="inline-block">

                <div class="form-group">
                    <?=\app\helpers\Html::dropDownList("subject_id", $filter["subject_id"], \yii\helpers\ArrayHelper::map($subjects, "id", "dis") , [
                        "class"=>"form-control chosen-list",
                        "empty"=>Yii::t("main","Предмет")
                    ]);?>
                </div>

            </div>
        </div>

        <?php
        if (isset($groups[$filter['group_id']])) {

            $group = $groups[$filter['group_id']];

            ?>
            <ul class="nav nav-tabs">
                <?php

                for ($i = 1; $i <= 2*$group->course; $i++) {
                    ?>
                    <li role="presentation" class="<?=$i==$filter['s'] ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/recorder/tests", ["filter"=>array_merge($filter, ["s"=>$i])])?>"><?=Yii::t("main","{s} семестр", ["s"=>$i])?></a></li>
                <?php
                }

                ?>
            </ul>
        <?php

        }
        ?>

        <?php
        if ($students AND $filter['subject_id']) {

            $types_labels = [
                1 => "primary",
                2 => "warning",
                6 => "warning",
                7 => "warning",
                5 => "primary"
            ];

            ?>

        <div style="margin-top:30px;">
            <div class="inline-block">
                <div class="form-group">
                    <input style="width:182px;" class="form-control text-center" type="text" placeholder="<?=Yii::t("main","Дата тестирования")?>" name="date" id="date" />
                </div>
            </div>
            <div class="inline-block" style="margin-left:10px;">
                <div class="form-group">
                    <?=\app\helpers\Html::dropDownList("t", "", \glob\models\TestStarted::getAssignTypes($group->form), [
                        "class" => "form-control text-center"
                    ])?>
                </div>
            </div>
            <div class="inline-block" style="margin-left:10px;">
                <div class="form-group">
                    <a class="btn btn-success btn-set-test"><?=Yii::t("main","Назначить")?></a>
                </div>
            </div>
            <div class="inline-block" style="margin-left:10px;">
                <div class="form-group">
                    <p><b><?=Yii::t("main","Допуск")?></b></p>
                </div>
            </div>
            <div class="inline-block" style="margin-left:10px;">
                <div class="form-group">
                    <a class="btn btn-primary btn-set-access" access="1"><?=Yii::t("main","Дать")?></a>
                </div>
            </div>
            <div class="inline-block" style="margin-left:5px;">
                <div class="form-group">
                    <a class="btn btn-danger btn-set-access" access="0"><?=Yii::t("main","Снять")?></a>
                </div>
            </div>
        </div>

        <div style="margin-top:0px;">
            <div class="inline-block">
                <div class="form-group text-right">
                    <p style="padding:3px 23px 0px 23px; border: none; border-radius: 0; -webkit-appearance: none;-webkit-box-shadow: inset 0 -1px 0 #dddddd; box-shadow: inset 0 -1px 0 #dddddd; font-size: 16px;" class="form-control"><?=Yii::t("main","Вопросы: ")?></p>
                </div>
            </div>
            <div class="inline-block">
                <div class="form-group">
                    <input style="width:60px;" class="form-control text-center" type="text" value="25" name="qcount" id="qcount" />
                </div>
            </div>
        </div>

        <div class="students-table" style="margin-top:30px;">

            <?php
            if ((!$filter['group_id'] OR !$filter['subject_id'])) {
                ?>
                <div class="alert alert-warning"><?=Yii::t("main","Выберите группу и предмет")?></div>
            <?php
            }
            ?>

            <table class="table table-bordered">

                <tr>

                    <th style="width:30px; border-right:0;"><input type="checkbox" class="check-all" style="margin-right:0;"  /></th>

                    <th class="text-center" style="border-left:0;"><?=Yii::t("main","ФИО")?></th>
                    <?php foreach (\glob\models\TestStarted::getAssignTypes($group->form) as $type => $label) { ?>
                        <th  class="text-center"><?=$label?></th>
                    <?php } ?>
                </tr>

                <?php foreach ($students as $student) {

                    $student_tests = array_filter($tests, function($test) use ($student) {
                        return $test->ui_id == $student->id;
                    });

                    ?>

                    <tr>

                        <td style="width:30px; border-right:0;"><input style="margin-right:0;" class="check-student"  type="checkbox" uid="<?=$student->id?>" id="check_<?=$student_id?>" /></td>

                        <td style="border-left:0;">
                            <label style="margin-bottom:0;"for="check_<?=$student_id?>"><?=$student->fio?></label>
                        </td>

                        <?php foreach (\glob\models\TestStarted::getAssignTypes($group->form) as $type => $label) {

                            $inner_tests = array_filter($student_tests, function($test) use ($type) {
                                return $test->t == $type;
                            });

                            ?>
                            <td>
                                <?php
                                    foreach ($inner_tests as $it) {
                                        ?>
                                        <p t="<?=$it->t?>"><i style="margin-right:5px;" title="<?=$it->access == 1 ? Yii::t("main","Допуск") : Yii::t("main","Не допуск")?>" class="fa <?=$it->access == 1 ? "fa-check text-success" : "fa-warning text-warning"?>"></i><?=$it->testdate ? "<span>".date('d.m.Y', $it->testdate)."</span> " : ""?><b class="<?=$it->ballTextColor?>"><?=$it->ball?></b></p>
                                        <?php
                                    }
                                ?>
                            </td>
                        <?php } ?>

                    </tr>

                <?php } ?>
            </table>


        </div>

        <?php } ?>

    </div>
</div>