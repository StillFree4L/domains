<?php if (!Yii::$app->request->isAjax) { $this->beginContent('@app/views/layouts/inner.php'); ?> <?php } ?>

<?= $content ?>

<?php if (!Yii::$app->request->isAjax) $this->endContent(); ?>