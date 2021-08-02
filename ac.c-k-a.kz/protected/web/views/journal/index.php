<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Журнал"), false);
?>

<div class="controller container">
    <div class="action-content">

        <div>

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
                    <?=\app\helpers\Html::dropDownList("group_id", $filter["group_id"], \yii\helpers\ArrayHelper::map($groups, "id", "grup") , [
                        "class"=>"form-control chosen-list",
                        "empty"=>Yii::t("main","Группа")
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
                    <li role="presentation" class="<?=$i==$filter['s'] ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/journal/index", ["filter"=>array_merge($filter, ["s"=>$i])])?>"><?=Yii::t("main","{s} семестр", ["s"=>$i])?></a></li>
                    <?php
                }

                ?>
                </ul>
                <?php

            }
        ?>

        <div class="students-table" style="margin-top:15px;">

            <?php if ($students) {

                $types = \glob\models\TestStarted::getTypes($group->form);
                if (!$subjects[$filter['subject_id']]->kurs) {
                    unset($types[4]);
                }

                ?>

                <div class="row">
                    <div class="col-xs-9">
                        <div class="form-group">
                            <input class="form-control text-center" type="text" value="<?=date('d.m.Y')?>" name="date" id="date" style="width:150px;" />
                            <p class="help-block text-muted"><?=Yii::t("main","При выставлении баллов будет устанавливаться данная дата. Если вы хотите поставить оценку за другое число, измените дату")?></p>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered">

                    <tr>
                        <th style="vertical-align: middle; text-align: center;"><?=Yii::t("main","ФИО")?></td>
                        <?php foreach ($types as $type => $label) { ?>
                           <th style="vertical-align: middle; text-align: center;"><?=$label?></th>
                        <?php } ?>

                    </tr>

                <?php
                foreach ($students as $student) { ?>
                    <tr>
                        <td style="vertical-align: middle">
                            <?=$student->fio?>
                        </td>

                        <?php
                        $student_tests = array_filter($tests, function($test) use ($student) {
                            return $test->ui_id == $student->id;
                        });



                        foreach ($types as $type => $label) {

                            if ($type != 3) {
                                $inner_tests = array_filter($student_tests, function ($test) use ($type) {
                                    return $test->t == $type;
                                });
                            } else {

                                $inner_tests = [];
                                $t = new \glob\models\TestStarted();
                                $t->t = 3;
                                $t->ball = \glob\models\TestStarted::getMaxBalls($student_tests, $group->form)[3];
                                $inner_tests[] = $t;

                            }

                            ?>
                            <td style="vertical-align: middle">
                                <?php if ($inner_tests) {
                                    foreach ($inner_tests as $it) {
                                        if ($it->canEdit) {
                                        ?>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <p style="padding:4px 0; margin-bottom:0;" class="pull-right"><label style="margin-bottom:0;" class="control-label" for="<?=$it->id?>"><?=date('d.m.Y', $it->testdate)?></label></p>
                                                </div>
                                                <div class="col-xs-6">
                                                    <input t="<?=$it->t?>" id="<?=$it->id?>" value="<?=$it->ball?>" type="text" class="mark-input form-control input-sm text-center <?=$it->ballTextColor?>"/>
                                                </div>
                                            </div>
                                        <?php
                                        } else {
                                            ?>
                                            <p t="<?=$it->t?>" class="text-center"><?=$it->testdate ? "<span>".date('d.m.Y', $it->testdate)."</span> " : ""?><b class="<?=$it->ballTextColor?>"><?=$it->ball?></b></p>
                                            <?php
                                        }
                                    }
                                } else {
                                    $tt = new \glob\models\TestStarted();
                                    $tt->t = $type;
                                    if ($tt->canEdit) {
                                        ?>
                                            <p><input t="<?=$tt->t?>" ui_id="<?=$student->id?>" class="form-control text-center input-sm mark-input <?=$tt->ballTextColor?>" value="0" /></p>
                                        <?php
                                    } else {
                                        ?>
                                        <p t="<?=$tt->t?>" class="text-center <?=$tt->ballTextColor?>"><b>0</b></p>
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

            <?php } ?>

        </div>

    </div>
</div>