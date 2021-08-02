<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Отчеты"), false);
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

            <?php if ($filter['t'] != "summary") { ?>
                <div class="inline-block">

                    <div class="form-group">
                        <?=\app\helpers\Html::dropDownList("subject_id", $filter["subject_id"], \yii\helpers\ArrayHelper::map($subjects, "id", "dis") , [
                            "class"=>"form-control chosen-list",
                            "empty"=>Yii::t("main","Предмет")
                        ]);?>
                    </div>

                </div>

                <div class="inline-block">

                    <div class="form-group">
                        <a class="btn btn-primary btn-lg" href="<?=\glob\helpers\Common::createUrl("/reports/index", [
                            "filter" => [
                                "group_id" => $filter['group_id'],
                                "s" => $filter['s'],
                                "t" => "summary"
                            ]
                        ])?>"><?=Yii::t("main","Сводная")?></a>
                    </div>

                </div>
            <?php } else { ?>
                <div class="inline-block">
                    <div class="form-group">
                        <a class="btn btn-danger btn-lg" href="<?=\glob\helpers\Common::createUrl("/reports/index", [
                            "filter" => [
                                "group_id" => $filter['group_id'],
                                "s" => $filter['s']
                            ]
                        ])?>"><?=Yii::t("main","Назад")?></a>
                    </div>
                </div>
            <?php } ?>

        </div>

        <?php
        if (isset($groups[$filter['group_id']])) {

            $group = $groups[$filter['group_id']];

            ?>
            <ul class="nav nav-tabs">
                <?php

                for ($i = 1; $i <= 2*$group->course; $i++) {
                    ?>
                    <li role="presentation" class="<?=$i==$filter['s'] ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/reports/index", ["filter"=>array_merge($filter, ["s"=>$i])])?>"><?=Yii::t("main","{s} семестр", ["s"=>$i])?></a></li>
                <?php
                }

                ?>
            </ul>
        <?php

        }
        ?>

        <div class="students-table" style="margin-top:30px;">

            <?php
                if ((!$filter['group_id'] OR !$filter['subject_id']) AND $filter['t'] != "summary") {
                    ?>
                        <div class="alert alert-warning"><?=Yii::t("main","Выберите группу и предмет")?></div>
                    <?php
                }
            ?>

            <?php
            if (!$filter['group_id'] AND $filter['t'] == "summary") {
                ?>
                <div class="alert alert-warning"><?=Yii::t("main","Выберите группу")?></div>
            <?php
            }
            ?>

            <?php
            if ($tests) {
                if ($filter['t'] != 'summary') {
                    $types = \glob\models\forms\ReportsForm::getTypes();
                    if (!$subjects[$filter['subject_id']]->kurs) {
                        unset($types[3]);
                    }
                    unset($types[4]);
                    foreach ($types as $type => $label) {
                        ?>
                        <div class="report" style="margin-bottom:30px;">
                            <a style="margin-top:5px; margin-left:5px;"
                               href="<?= \glob\helpers\Common::createUrl("/reports/index", ["filter" => $filter, "d" => $type]) ?>"
                               class="pull-left btn btn-info btn-xs hidden-print"><?= Yii::t("main", "Скачать в Microsoft Word") ?></a>
                            <page size="A4">
                                <?php
                                echo $this->render("report_" . $type, [
                                    "filter" => $filter,
                                    "group" => $group,
                                    "subject" => $subjects[$filter['subject_id']],
                                    "students" => $students,
                                    "tests" => $tests
                                ]);
                                ?>
                            </page>
                        </div>
                    <?php
                    }
                } else {
                    if ($subjects) {
                        ?>
                        <div class="report" style="margin-bottom:30px;">
                            <a style="margin-top:5px; margin-left:5px;"
                               href="<?= \glob\helpers\Common::createUrl("/reports/index", ["filter" => $filter, "d" => 4]) ?>"
                               class="pull-left btn btn-info btn-xs hidden-print"><?= Yii::t("main", "Скачать в Microsoft Word") ?></a>
                            <page size="A4" orientation="landscape">
                                <?php
                                echo $this->render("report_4", [
                                    "filter" => $filter,
                                    "group" => $group,
                                    "subjects" => $subjects,
                                    "students" => $students,
                                    "tests" => $tests
                                ]);
                                ?>
                            </page>
                        </div>
                    <?php
                    } else {
                        ?>
                        <div class="alert alert-danger"><?=Yii::t("main","В текущем семестре не назначено ниодного предмета")?></div>
                        <?php
                    }
                }
            } else {
                if ($filter['subject_id'] AND $filter['group_id'] AND !$tests) {
                    ?>
                    <div class="alert alert-danger"><?=Yii::t("main","В текущем семестре по данному предмету нет ни одной оценки")?></div>
                    <?php
                } else {
                    if (!$subjects) {
                        ?>
                        <div class="alert alert-danger"><?=Yii::t("main","В текущем семестре не назначено ниодного предмета")?></div>
                        <?php
                    }
                }
                ?>
            <?php } ?>

        </div>

    </div>
</div>