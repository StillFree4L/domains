<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Тест завершен"), false);
?>

<div class="controller container">
    <div class="action-content">

        <h3><?=Yii::t("main","Тест завершен. Вы набрали: {ball}%", [
                "ball" => "<span class='".$test->ballTextColor."'>".$test->ball."</span>"
            ])?></h3>

        <a target="_full" class="btn btn-primary" href="<?=\glob\helpers\Common::createUrl("/tests")?>"><?=Yii::t("main","Вернуться")?></a>

    </div>
</div>