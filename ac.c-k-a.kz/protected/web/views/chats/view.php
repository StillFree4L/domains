<?php
$this->addTitle(Yii::t("main","Чаты"));
$this->registerJsFile(Yii::$app->assetManager->getBundle("jQuery")->baseUrl."/js/jquery.timers.js");
?>
<div class="container controller chats-controller">

    <div class="action-content">

        <?php echo \app\widgets\EComments\EComments::widget([
            "template"=>\app\widgets\EComments\EComments::chat
        ]) ?>

    </div>

</div>