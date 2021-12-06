<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
$params = [
        'prompt' => 'Выберите роль пользователя...'
    ];
?>
<!--==========================
  Authorization Section
============================-->
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="form">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <form role="form" class="contactForm">
                    <div class="form-group">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя пользователя'); ?>
                    </div>
                    <div class="form-group">
            <?= $form->field($model, 'email')->label('E-mail пользователя'); ?>
                    </div>
                    <div class="form-group">
            <?= $form->field($model, 'password')->passwordInput()->label('Пароль пользователя'); ?>
                    </div>
                    <div class="form-group">
            <?= $form->field($model, 'role')->dropDownList(yii\helpers\ArrayHelper::map($roles,'name','description'),$params); ?>
            </div>
                    <div class="form-group">
                        <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>