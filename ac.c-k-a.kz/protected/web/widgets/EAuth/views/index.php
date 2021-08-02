<div class="cabinet-block clearfix">
    <?php

    if (\Yii::$app->user->isGuest) {

        ?>
        <div style="margin-top:-6px;">
            <a target="modal" href="<?=\yii\helpers\Url::to(["/auth/login", "ref"=>Yii::$app->request->url])?>" class="btn btn-primary btn-sm"><?=Yii::t("main","Войти")?></a>
        </div>

    <?php

    } else {

        ?>

        <div class="pull-left profile-block">

            <?php
            echo app\widgets\EProfile\EProfile::widget([
                "model"=>\Yii::$app->user->identity,
                "logout"=>true
            ]);
            ?>

        </div>

    <?php

    }

    ?>

</div>