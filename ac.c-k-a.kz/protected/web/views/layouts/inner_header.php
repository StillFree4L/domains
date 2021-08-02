<header class="inner-header" style="margin-bottom:60px;">

    <div class="top-header bg-primary" style="height:60px;">



        <div class="container relative">

            <a class="logo" href="<?=\glob\helpers\Common::createUrl("/main/index")?>"></a>

            <?php if (\Yii::$app->params['in_test']) { ?>
                <div>
                    <div style="margin-top:19.5px; color:#fff;">
                        <? echo app\widgets\EAuth\EAuth::widget(); ?>

                    </div>
                </div>
            <?php } else { ?>
                <div class="row">

                    <div class="col-xs-6">

                        <div class="dropdown pull-left" style="margin-top:13px;">
                            <button style="box-shadow: none;" class="btn btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <?=Yii::t("main","Меню")?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <li class="<?=$this->context->id == "users" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/users/profile")?>"><?=Yii::t("main","Профиль")?></a></li>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_STUDENT)) { ?>
                                    <li class="<?=$this->context->id == "tests" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/tests")?>"><?=Yii::t("main","Тестирование")?></a></li>
                                <?php } ?>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_TEACHER)) { ?>
                                    <li class="<?=$this->context->id == "journal" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/journal")?>"><?=Yii::t("main","Журнал")?></a></li>
                                <?php } ?>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_REGISTRATION)) { ?>
                                    <li class="<?=$this->context->id == "reports" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/reports")?>"><?=Yii::t("main","Отчеты")?></a></li>
                                <?php } ?>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_REGISTRATION)) { ?>
                                    <li class="<?=$this->context->id == "recorder" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/recorder")?>"><?=Yii::t("main","Офис-регистратор")?></a></li>
                                <?php } ?>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_ADMIN)) { ?>
                                    <!--<li class="<?=$this->context->id == "users" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/users")?>"><?=Yii::t("main","Пользователи")?></a></li>-->
                                <?php } ?>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_OTDELCADROV)) { ?>
                                    <li class="<?=$this->context->id == "employees" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/employees")?>"><?=Yii::t("main","Картотека сотрудников")?></a></li>
                                <?php } ?>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_SELECTION_COMITET)) { ?>
                                    <li class="<?=$this->context->id == "students" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/students")?>"><?=Yii::t("main","Картотека студентов")?></a></li>
                                <?php } ?>
                                <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_SUPER)) { ?>
                                    <li class="<?=$this->context->id == "dics" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/dics")?>"><?=Yii::t("main","Словари")?></a></li>
                                    <li class="<?=$this->context->id == "options" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/options")?>"><?=Yii::t("main","Параметры")?></a></li>
                                <?php } ?>
                                <li class="<?=$this->context->id == "cases" ? "active" : ""?>"><a target="_full" href="<?=\glob\helpers\Common::createUrl("/cases")?>"><?=Yii::t("main","Учебные материалы")?></a></li>
                            </ul>

                        </div>

                    </div>

                    <div class="col-xs-6">
                        <div style="margin-left:60px; margin-top:19.5px; color:#fff;" class="pull-left">
                            <? echo app\widgets\EAuth\EAuth::widget(); ?>

                        </div>
                        <?php if (!\Yii::$app->user->isGuest) { ?> <a href="<?=\yii\helpers\Url::to(["/auth/logout"])?>" class="btn btn-danger pull-left btn-xs" style="margin-left:10px; margin-top:22px;" ><?=Yii::t("main","выйти")?></a></span> <?php } ?>

                    </div>
                </div>
            <?php } ?>
        </div>

    </div>

</header>
