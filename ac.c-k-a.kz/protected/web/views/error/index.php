<?php
$this->setTitle(Yii::t("main","Ошибка"), false);
?>

<div class="controller container">
    <div class="action-content">

        <div class="alert alert-danger">
            <h4 class="text-white"><?=Yii::t("main","Ошибка")?></h4>
            <p style="margin-top:0;"><?=$exception->getMessage()?></p>
        </div>

    </div>
</div>