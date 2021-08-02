<?php if (!Yii::$app->request->isAjax) $this->beginContent('@app/views/layouts/index.php'); ?>

<?=$content?>

<?php if (!Yii::$app->request->isAjax) { ?>
    <?php $this->endContent();
} ?>