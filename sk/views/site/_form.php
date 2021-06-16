<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Master */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="repairs-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <form role="form" class="contactForm">
        <div class="form-group">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'imageFile')->fileInput() ?>
        </div>

            <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>
    </form>

    <?php ActiveForm::end(); ?>

</div>
