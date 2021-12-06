<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="repairs-form">

    <?php $form = ActiveForm::begin(); ?>
    <form role="form" class="contactForm">
        <div class="form-group">
    <?php
    if(Yii::$app->request->get('type')=='Материалы'){
        echo $form->field($model, 'name')->textInput(['maxlength' => true]);
    }
    else{
        echo $form->field($model, 'name')->dropDownList(ArrayHelper::map($services,'service','service'),['prompt' => 'Укажите услугу...']);
    }
    ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'number')->textInput(['type' => 'number']) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'price')->textInput(['type' => 'number']) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'repairs_id')->dropDownList(ArrayHelper::map($repairs,'id','receipt'),['prompt' => 'Укажите заказ...']) ?>
        </div>
            <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>
    </form>

    <?php ActiveForm::end(); ?>

</div>
