<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>
<form role="form" class="contactForm">
    <div class="form-group">
    <?= $form->field($model, 'status')->label('Статус')->dropDownList([
        '10' => 'Активный',
        '0' => 'Отключен',
    ]); ?>
    </div>
    <div class="form-group">
        <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>

    <?php ActiveForm::end(); ?>

</div>
