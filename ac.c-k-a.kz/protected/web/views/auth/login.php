<?php
$this->setTitle(Yii::t("main","Авторизация"));
?>

<div class="action-content" style="margin-top:30px;">
    <div class="auth-form text-center container">

        <div class=row">
            <div class="inline-block col-lg-5 col-md-6 col-sm-8 col-xs-10">
                <?php

                $form = app\widgets\EForm\EForm::begin([
                    "htmlOptions"=>[
                        "action"=>\yii\helpers\Url::to(array_merge(["/auth/login"])),
                        "method"=>"post",
                        "class"=>"",
                        "id"=>"loginForm",
                    ],
                ]);

                ?>

                <div class="text-left">
                    <h3 style="margin-top:0px;"><?=Yii::t("main","Войти в систему")?></h3>

                    <div class="form-group" attribute="email">
                            <input class="form-control" name="login" placeholder="<?=$user->getAttributeLabel("login")?>" />
                    </div>

                    <div class="form-group" attribute="password">
                        <input type="password" class="form-control" name="password" placeholder="<?=$user->getAttributeLabel("password")?>" />
                    </div>

                    <div class="form-group" attribute="rememberMe">
                        <div class="checkbox">
                            <label for="LoginForm_rememberMe">
                                <input id="rememberMe" name="rememberMe" type="checkbox"> <?=Yii::t("main","Запомнить")?>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <button type="submit" class="btn btn-primary"><?=Yii::t("main","Войти")?></button>
                    </div>
                </div>

                <?php app\widgets\EForm\Eform::end(); ?>
            </div>
        </div>

    </div>
</div>