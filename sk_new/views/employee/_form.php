<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['type'=>'email']) ?>

    <?= $form->field($model, 'role')->dropDownList([
        'Инженер сервиса'=>'Инженер сервиса',
        'Инженер сервиса рем сотовых устр'=>'Инженер сервиса рем сотовых устр',
        'Инженер СБ'=>'Инженер СБ',
        'Системотехник'=>'Системотехник',
        'Монтажник'=>'Монтажник',
        'Главный инженер'=>'Главный инженер',
        'Генеральный директор'=>'Генеральный директор',
        'Менеджер'=>'Менеджер',
        'Бугалтер'=>'Бугалтер',
    ],['prompt' => 'Укажите услугу...']) ?>

    <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'about')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
