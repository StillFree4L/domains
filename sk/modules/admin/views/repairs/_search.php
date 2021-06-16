<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RepairsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="repairs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'receipt') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'client') ?>

    <?= $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'service_name') ?>

    <?php // echo $form->field($model, 'equipment') ?>

    <?php // echo $form->field($model, 'serial_id') ?>

    <?php // echo $form->field($model, 'facilities') ?>

    <?php // echo $form->field($model, 'problem') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'money') ?>

    <?php // echo $form->field($model, 'result_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
