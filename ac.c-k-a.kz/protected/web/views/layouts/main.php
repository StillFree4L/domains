<?php if (!Yii::$app->request->isAjax) {

    $this->beginContent('@app/views/layouts/index.php');

    $this->render("@app/views/layouts/left_menu.php");

}?>



    <?=$content?>

<?php if (!Yii::$app->request->isAjax) { ?>
<?php $this->endContent();
} ?>