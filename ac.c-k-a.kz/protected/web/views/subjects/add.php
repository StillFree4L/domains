<?php

\app\bundles\ToolsBundle::registerChosen($this);
$this->addTitle(Yii::t("main","Добавление предмета"));

?>
<div class="controller container users-controller">
    <div class="cc">
        <div class="action-content">

            <?php
            $f = \app\widgets\EForm\EForm::begin([
                "htmlOptions"=>[
                    "action"=>\glob\helpers\Common::createUrl("/subjects/add", \Yii::$app->request->get(null, [])),
                    "method"=>"post",
                    "id"=>"newSubjectForm"
                ],
            ]);

            ?>

            <div class="form-group" attribute="dis">

                <label for="dis" class="control-label"><?=Yii::t("main","Название предмета")?></label>

                <input class="form-control" type="text" placeholder="<?=Yii::t("main","название")?>" id="dis" value="<?=$model->dis?>" name="dis" />

            </div>

            <div class="form-group" attribute="tea">

                <label for="tea" class="control-label"><?=Yii::t("main","Преподаватель")?></label>

                <input class="form-control" type="text" placeholder="<?=Yii::t("main","ФИО")?>" id="tea" value="<?=$model->tea?>" name="tea" />

            </div>

            <div class="form-group" attribute="credits">

                <label for="credits" class="control-label"><?=Yii::t("main","Кредиты")?></label>

                <input class="form-control" type="number" placeholder="<?=Yii::t("main","Кредиты")?>" id="credits" value="<?=$model->credits?>" name="credits" />

            </div>

            <div class="form-group" attribute="position">

                <label for="position" class="control-label"><?=Yii::t("main","Порядок в ведомости")?></label>

                <input class="form-control" type="number" placeholder="<?=Yii::t("main","Порядок в ведомости")?>" id="position" value="<?=$model->position?>" name="position" />

            </div>

            <div class="form-group" attribute="gos">

                <div class="checkbox">
                    <label for="position" class="control-label">
                        <input type="checkbox" id="gos" <?=$model->gos ? "checked" : ""?> name="gos" />
                        <?=Yii::t("main","Гос")?>
                    </label>
                </div>

            </div>

            <div class="form-group" attribute="kurs">

                <div class="checkbox">
                    <label for="position" class="control-label">
                        <input type="checkbox" id="kurs" <?=$model->kurs ? "checked" : ""?> name="kurs" />
                        <?=Yii::t("main","Курсовая")?>
                    </label>
                </div>

            </div>

            <div class="form-group" attribute="gek">

                <div class="checkbox">
                    <label for="gek" class="control-label">
                        <input type="checkbox" id="gek" <?=$model->gek ? "checked" : ""?> name="gek" />
                        <?=Yii::t("main","ГЭК")?>
                    </label>
                </div>

            </div>

            <div class="form-group">

                <input type="submit" class="btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />

            </div>

            <?php \app\widgets\EForm\EForm::end(); ?>

        </div>
    </div>
</div>