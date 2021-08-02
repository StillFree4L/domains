<div class="left-menu">

    <div class="text-center list-group">
        <a style="border:0;" class="list-group-item <?=$this->context->id == "users" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/users/profile")?>">
            <div class="circle va">
                <i class="fa fa-user vam"></i>
            </div>
            <p><?=Yii::t("main","Профиль")?></p>
        </a>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_STUDENT)) { ?>
            <a style="border:0;" class="list-group-item <?=$this->context->id == "tests" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/tests")?>">
                <div class="circle va">
                    <i class="fa fa-list vam"></i>
                </div>
                <p><?=Yii::t("main","Тестирование")?></p>
            </a>
        <?php } ?>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_TEACHER)) { ?>
            <a style="border:0;" class="list-group-item <?=$this->context->id == "journal" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/journal")?>">
                <div class="circle va">
                    <i class="fa fa-calendar vam"></i>
                </div>
                <p><?=Yii::t("main","Журнал")?></p>
            </a>
        <?php } ?>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_REGISTRATION)) { ?>
            <a style="border:0;" class="list-group-item <?=$this->context->id == "reports" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/reports")?>">
                <div class="circle va">
                    <i class="fa fa-user vam"></i>
                </div>
                <p><?=Yii::t("main","Отчеты")?></p>
            </a>
        <?php } ?>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_REGISTRATION)) { ?>
            <a style="border:0;" class="list-group-item <?=$this->context->id == "recorder" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/recorder")?>">
                <div class="circle va">
                    <i class="fa fa-suitcase vam"></i>
                </div>
                <p><?=Yii::t("main","Офис-регистратор")?></p>
            </a>
        <?php } ?>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_ADMIN)) { ?>
            <!--<a style="border:0;"  class="list-group-item <?=$this->context->id == "users" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/users")?>"><?=Yii::t("main","Пользователи")?></a>-->
        <?php } ?>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_OTDELCADROV)) { ?>
            <a style="border:0;" class="list-group-item <?=$this->context->id == "employees" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/employees")?>">
                <div class="circle va">
                    <i class="fa fa-group vam"></i>
                </div>
                <p><?=Yii::t("main","Картотека сотрудников")?></p>
            </a>
        <?php } ?>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_SELECTION_COMITET)) { ?>
            <a style="border:0;" class="list-group-item <?=$this->context->id == "students" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/students")?>">
                <div class="circle va">
                    <i class="fa fa-group vam"></i>
                </div>
                <p><?=Yii::t("main","Картотека студентов")?></p>
            </a>
        <?php } ?>
        <a style="border:0;" class="list-group-item <?=$this->context->id == "cases" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/cases")?>">
            <div class="circle va">
                <i class="fa fa-newspaper-o vam"></i>
            </div>
            <p><?=Yii::t("main","Учебные материалы")?></p>
        </a>
        <?php if (Yii::$app->user->can(\glob\models\Users::ROLE_SUPER)) { ?>
            <a style="border:0;" class="list-group-item <?=$this->context->id == "dics" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/dics")?>">
                <div class="circle va">
                    <i class="fa fa-file-o vam"></i>
                </div>
                <p><?=Yii::t("main","Словари")?></p>
            </a>
            <a style="border:0;"  class="list-group-item <?=$this->context->id == "options" ? "active" : ""?>" target="_full" href="<?=\glob\helpers\Common::createUrl("/options")?>">
                <div class="circle va">
                    <i class="fa fa-sliders vam"></i>
                </div>
                <p><?=Yii::t("main","Параметры")?></p>
            </a>
        <?php } ?>
    </div>

</div>
