<?php if (!Yii::$app->request->isAjax) { $this->beginContent('@app/views/layouts/inner.php'); ?> <?php } ?>

<div class="container" style="margin-bottom:30px;">
    <ul class="nav nav-tabs">
        <li class="<?=$this->context->action->id == "tests" ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/recorder/tests")?>"><?=Yii::t("main","Назначение тестов")?></a></li>
        <li class="<?=$this->context->action->id == "subjects" ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/recorder/subjects")?>"><?=Yii::t("main","Предметы")?></a></li>
        <li class="<?=$this->context->action->id == "access" ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/recorder/access")?>"><?=Yii::t("main","Допуски")?></a></li>
    </ul>
</div>

<?= $content ?>

<?php if (!Yii::$app->request->isAjax) $this->endContent(); ?>