<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Офис-регистратор. Допуски"), false);
?>

<div class="controller container">
    <div class="action-content">

        <div>

            <div class="form-group">
                <?=\app\helpers\Html::dropDownList("student_id", $filter["student_id"], \yii\helpers\ArrayHelper::map($students, "id", "fio_with_group") , [
                    "class"=>"form-control chosen-list",
                    "empty"=>Yii::t("main","Студент")
                ]);?>
            </div>

        </div>

        <div>
        <?php
        if (isset($filter['student_id'])) {
            ?>
            <ul class="nav nav-tabs">
                <?php
                for ($i = 1; $i <= 2*$group->course; $i++) {
                    ?>
                    <li role="presentation" class="<?=$i==$filter['s'] ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/recorder/access", ["filter"=>array_merge($filter, ["s"=>$i])])?>"><?=Yii::t("main","{s} семестр", ["s"=>$i])?></a></li>
                <?php
                }

                ?>
            </ul>
        <?php
        }
        ?>
        </div>


        <table class="table table-bordered table-condensed" style="margin-top:15px;">
        <?php
            if (!empty($subjects) AND isset($filter['s'])) {

                ?>
                <tr>
                    <td><?=Yii::t("main","Предмет")?></td>

                    <?php foreach (\glob\models\TestStarted::getAssignTypes($group->form) as $type => $label) { ?>
                        <td>
                            <label>
                                <input type="checkbox" class="check-all" t="<?=$type?>" />
                                <?=$label?>
                            </label>
                        </td>
                    <?php } ?>

                </tr>
                <?php

                foreach ($subjects as $s) {

                    $subject_tests = array_filter($tests, function($test) use ($s) {
                        return $test->dis_id == $s->id;
                    });

                    ?>
                    <tr>
                        <td><?=$s->dis?></td>

                        <?php foreach (\glob\models\TestStarted::getAssignTypes($group->form) as $type => $label) {

                            $t_tests = array_merge(array_filter($subject_tests, function($test) use ($type) {
                                return $test->t == $type;
                            }));
                            $t_test = $t_tests[count($t_tests)-1];
                            ?>
                            <td><input <?=$t_test->access==1 ? "checked" : ""?> style="margin-right:0;" class="check-access"  type="checkbox" t="<?=$type?>" id="check_<?=$s->id?>" value="<?=$s->id?>" /></td>
                        <?php } ?>

                    </tr>
                    <?php

                }

                ?>
                <?php

            }
        ?>
        </table>

        <div class="form-grop">
            <a class="btn btn-primary save-access"><?=Yii::t("main","Сохранить")?></a>
        </div>

    </div>
</div>