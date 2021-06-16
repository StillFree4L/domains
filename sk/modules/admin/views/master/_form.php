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
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'role')->dropDownList([
        'Инженер сервиса'=>'Инженер сервиса',
        'Инженер сервиса рем сотовых устр'=>'Монтажник',
        'Инженер СБ'=>'Инженер СБ',
        'Системотехник'=>'Системотехник',
        'Монтажник'=>'Монтажник',
        'Главный инженер'=>'Главный инженер',

    ]) ?>
        </div>

            <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>
    </form>

    <?php ActiveForm::end(); ?>

</div>
