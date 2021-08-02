<?php

\app\bundles\ToolsBundle::registerChosen($this);
$this->addTitle(Yii::t("main","Назначение групп"));

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
                        <label t="group" aid="<?=$g->id?>"  class="btn btn-sm btn-<?=in_array($g->id, $assigned_groups) ? "success active" : "danger"?> btn-g" style="display:inline-block; float:none; margin:3px 1px;  border-radius:0;">
                            <input <?=in_array($g->id, $assigned_groups) ? "checked" : ""?> type="checkbox"> <?=$g->grup?>
                        </label>
                        <?
                    }
                }
            ?>
            </div>

        </div>
    </div>
</div>