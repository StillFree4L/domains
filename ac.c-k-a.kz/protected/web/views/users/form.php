<?php

\app\bundles\ToolsBundle::registerChosen($this);
\Yii::$app->breadCrumbs->addLink(\Yii::t("main","Список пользователей"), \glob\helpers\Common::createUrl("/users/index"));
$this->addTitle(Yii::t("main","Добавление пользователя"));

?>
<div class="controller container users-controller">
    <div class="cc">
        <div class="action-content">

            <?php
            $f = \app\widgets\EForm\EForm::begin([
                "htmlOptions"=>[
                    "action"=>\glob\helpers\Common::createUrl("/users/add", \Yii::$app->request->get(null, [])),
                    "method"=>"post",
                    "id"=>"newUserForm"
                ],
            ]);

            ?>

            <div style="margin-top:0;" class="page-header"><h3 class="text-info"><?=Yii::t("main","Основные данные")?></h3></div>

            <div class="form-group" attribute="login">

                <label for="login" class="control-label"><?=Yii::t("main","Логин")?></label>

                <input class="form-control" type="text" placeholder="<?=Yii::t("main","логин")?>" id="login" value="<?=$user->login?>" name="login" />

            </div>

            <div class="form-group" attribute="fio">

                <label for="fio" class="control-label"><?=Yii::t("main","ФИО")?></label>

                <input class="form-control" type="text" placeholder="<?=Yii::t("main","ФИО")?>" id="fio" value="<?=$user->fio?>" name="fio" />

            </div>

            <div class="form-group" attribute="role_id">

                <label for="roleName" class="control-label"><?=Yii::t("main","Роль")?></label>

                <?=\yii\helpers\Html::dropDownList("role_id", $user->role_id, \glob\models\Users::getRoles(), [
                    "class"=>"form-control"
                ]);?>

            </div>

            <div class="form-group" attribute="password">

                <label for="password" class="control-label"><?=Yii::t("main","Пароль")?></label>

                <input class="form-control" type="password" placeholder="<?=Yii::t("main","пароль")?>" id="password" name="password" />

            </div>
            <div class="form-group" attribute="repassword">

                <label for="repassword" class="control-label"><?=Yii::t("main","Повторите пароль")?></label>

                <input class="form-control" type="password" placeholder="<?=Yii::t("main","повторите пароль")?>" id="repassword" name="repassword" />

            </div>

            <div class="form-group">

                <input type="submit" class="btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />

            </div>

            <?php \app\widgets\EForm\EForm::end(); ?>

        </div>
    </div>
</div>