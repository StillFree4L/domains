<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Тестирование"), false);
?>

<div class="controller container">
    <div class="action-content">

        <?php
            if (Yii::$app->user->can(\glob\models\Users::ROLE_SUPER)) {
                ?>
                <div class="inline-block">

                    <div class="form-group">
                        <?=\app\helpers\Html::dropDownList("group_id", $filter["group_id"], \yii\helpers\ArrayHelper::map($groups, "id", "grup") , [
                            "class"=>"form-control chosen-list",
                            "empty"=>Yii::t("main","Группа")
                        ]);?>
                    </div>

                </div>

                <?php if ($students) { ?>
                    <div class="inline-block">

                        <div class="form-group">
                            <?= \app\helpers\Html::dropDownList("student_id", $filter["student_id"], \yii\helpers\ArrayHelper::map($students, "id", "fio"), [
                                "class" => "form-control chosen-list",
                                "empty" => Yii::t("main", "Студент")
                            ]); ?>
                        </div>

                    </div>
                <?php
                }
            }

        if ($group) {
        ?>
            <ul class="nav nav-tabs">
                <?php

                for ($i = 1; $i <= 2*$group->course; $i++) {
                    ?>
                    <li role="presentation" class="<?=$i==$filter['s'] ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/tests/index", ["filter"=>array_merge($filter, ["s"=>$i])])?>"><?=Yii::t("main","{s} семестр", ["s"=>$i])?></a></li>
                <?php
                }

                ?>
            </ul>
        <?php
        }

        ?>

        <?php
            if ($tests) {
                ?>
                <h4 style="margin-top:30px;"><?=Yii::t("main","Назначенные тесты за {s} семестр", [
                        "s" => $filter['s']
                    ])?></h4>
                <table class="table table-condensed" >
                <?php

                $types = \glob\models\TestStarted::getTypes($group->form);

                $types_labels = [
                    1 => "primary",
                    2 => "warning",
                    6 => "warning",
                    7 => "warning",
                    8 => "info",
                    4 => "danger",
                    3 => "danger",
                    5 => "primary"
                ];

                foreach ($tests as $test) {
                    ?>
                    <tr>
                        <td><?=$test->dis->dis?></td>
                        <td><?=date('d.m.Y',$test->testdate)?></td>
                        <td><?=$test->qcount?></td>
                        <td><strong class="text-<?=$types_labels[$test->t]?>"><?=$types[$test->t]?></strong></td>
                        <td class="text-right"><?=$test->access ? "" : "<b class='text-danger'>".Yii::t("main","Нет допуска")."</b>"?> <a class="btn btn-<?=$types_labels[$test->t]?> btn-xs " <?=$test->access ? "" : "disabled"?> title="<?=Yii::t("main","Начать тестирование")?>" href="<?=$test->access ? \glob\helpers\Common::createUrl("/tests/start", ["id"=>$test->id]) : ""?>"><i class="fa fa-play-circle"></i></a></td>
                    </tr>
                    <?php
                }
                ?>
                </table>
                <?php
            }
        ?>

        <?php
        if ($passed_tests AND $subjects) {
            ?>
            <h4 style="margin-top:30px;"><?=Yii::t("main","Оценки за {s} семестр", [
                    "s" => $filter['s']
                ])?></h4>

            <?php

                $types = \glob\models\TestStarted::getTypes($group->form);

                ?>

                <table class="table table-condensed">

                    <tr>
                        <th style="vertical-align: middle;"><?=Yii::t("main","Предмет")?></td>
                        <?php foreach ($types as $type => $label) { ?>
                            <th class="text-<?=$types_labels[$type]?>" style="vertical-align: middle; text-align: center;"><?=$types[$type]?></th>
                        <?php } ?>

                    </tr>

                    <?php
                    foreach ($subjects as $subject) { ?>
                        <tr>
                            <td style="vertical-align: middle">
                                <?=$subject->dis?>
                            </td>

                            <?php
                            $subject_tests = array_filter($passed_tests, function($test) use ($subject) {
                                return $test->dis_id == $subject->id;
                            });

                            foreach ($types as $type => $label) {

                                if ($type != 3) {
                                    $inner_tests = array_filter($subject_tests, function ($test) use ($type) {
                                        return $test->t == $type;
                                    });
                                } else {

                                    $inner_tests = [];
                                    $t = new \glob\models\TestStarted();
                                    $t->t = 3;
                                    $t->ball = \glob\models\TestStarted::getMaxBalls($subject_tests, $group->form)[3];
                                    $inner_tests[] = $t;

                                }

                                ?>
                                <td style="vertical-align: middle; <?=($subject->kurs == 0 AND $type == 4) ? "background-color:#eee;" : ""?>">
                                    <?php if ($inner_tests) {
                                        foreach ($inner_tests as $it) {
                                                ?>
                                                <p style="white-space: nowrap;" t="<?=$it->t?>" class="text-center"><?=$it->testdate ? "<span>".date('d.m.Y', $it->testdate)."</span> " : ""?><b class="<?=$it->ballTextColor?>"><?=$it->ball?></b></p>
                                            <?php
                                        }
                                    } else {
                                        if ($subject->kurs == 0 AND $type == 4) {
                                            ?>

                                            <?php
                                        } else {
                                            $tt = new \glob\models\TestStarted();
                                            $tt->t = $type;
                                            ?>
                                            <p t="<?= $tt->t ?>" class="text-center <?= $tt->ballTextColor ?>"><b>0</b>
                                            </p>
                                        <?php
                                        }
                                    } ?>
                                </td>
                            <?php

                            }

                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
        <?php
        }
        ?>

    </div>
</div>