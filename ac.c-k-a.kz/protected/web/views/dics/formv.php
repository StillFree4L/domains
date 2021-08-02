<?php

\app\bundles\ToolsBundle::registerChosen($this);
\Yii::$app->breadCrumbs->addLink(\Yii::t("main","Список словарей"), \glob\helpers\Common::createUrl("/dics/index"));
$this->addTitle(Yii::t("main","Добавление значения"));

?>
<div class="controller container users-controller">
    <div class="action-content">

        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions"=>[
                "action"=>\glob\helpers\Common::createUrl("/dics/addv", \Yii::$app->request->get(null, [])),
                "method"=>"post",
                "id"=>"newDicsvForm"
            ],
        ]);

        ?>

        <div style="margin-top:0;" class="page-header"><h3 class="text-info"><?=Yii::t("main","Основные данные")?></h3></div>

        <div class="form-group" attribute="name">

            <label for="name" class="control-label"><?=Yii::t("main","Значение")?></label>

            <input class="form-control" type="text" placeholder="<?=Yii::t("main","Значение")?>" id="name" value="<?=$dicv->name?>" name="name" />

        </div>

        <div class="form-group" attribute="category">

            <label for="name" class="control-label"><?=Yii::t("main","Категория")?></label>

            <input class="form-control autocomplete" autocomplete-attribute="category" type="text" placeholder="<?=Yii::t("main","Категория")?>" id="category" value="<?=$dicv->category?>" name="category" />

            <p class="help-block"><?=Yii::t("main","Для группировки по категориям. Не обязательное поле")?></p>

        </div>

        <div class="form-group">

            <input type="submit" class="btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />

        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>

    </div>
</div>