<?php

\app\bundles\ToolsBundle::registerChosen($this);
$this->addTitle(Yii::t("main","Назначение групп/преподавателей"));

?>
<div class="controller container users-controller">
    <div class="cc">
        <div class="action-content">

            <div class="page-header" style="margin:0 0 10px 0;">
                <h4 style="margin:0;"><?=Yii::t("main","Группы")?></h4>
            </div>

            <div class="btn-group text-center" data-toggle="buttons" type="danger">
            <?php
                if (!empty($groups)) {
                    foreach ($groups as $g) {
                        ?>
                        <label t="group" aid="<?=$g->id?>" class="btn btn-sm btn-<?=isset($assigned_groups[$g->id]) ? "success active" : "danger"?> btn-g" style="display:inline-block; float:none; margin:3px 1px;  border-radius:0;">
                            <input <?=isset($assigned_groups[$g->id]) ? "checked" : ""?> type="checkbox"> <?=$g->grup?>
                            <input value="<?=isset($assigned_groups[$g->id]) ? $assigned_groups[$g->id]['semestr'] : ""?>" maxlength="1" <?=isset($assigned_groups[$g->id]) ? "" : "disabled"?> style="margin-left:5px; text-align:center; width:20px; display: inline-block; color:#fff; padding:2px;" type="text" class="semestr input-xs form-control" />
                        </label>
                        <?
                    }
                }
            ?>
            </div>

            <div class="page-header" style="margin:0 0 10px 0;">
                <h4 style="margin:0;"><?=Yii::t("main","Преподаватели")?></h4>
            </div>

            <div class="btn-group text-center" data-toggle="buttons" type="danger">
                <?php
                if (!empty($teachers)) {
                    foreach ($teachers as $t) {
                        ?>
                        <label t="teacher" aid="<?=$t->profile->id?>"  class="btn btn-sm btn-<?=in_array($t->profile->id, $assigned_teachers) ? "success active" : "danger"?> btn-g" style="display:inline-block; float:none; margin:3px 1px;  border-radius:0;">
                            <input <?=in_array($t->id, $assigned_teachers) ? "checked" : ""?> type="checkbox"> <?=$t->profile->fio?>
                        </label>
                    <?
                    }
                }
                ?>
            </div>

        </div>
    </div>
</div>