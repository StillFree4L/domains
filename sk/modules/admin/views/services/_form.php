<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Master */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="repairs-form">

    <?php $form = ActiveForm::begin(); ?>
    <form role="form" class="contactForm">

        <div class="form-group">
    <?= $form->field($model, 'service')->textInput(['maxlength' => true]) ?>
        </div>

            <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>
    </form>

    <?php ActiveForm::end(); ?>

</div>
