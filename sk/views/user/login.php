<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
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
                <?php $form = ActiveForm::begin(); ?>
                <form role="form" class="contactForm">
                    <div class="form-group">
    <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя пользователя'); ?>
                    </div>
                    <div class="form-group">
    <?= $form->field($model, 'password')->passwordInput()->label('Пароль пользователя'); ?>
                    </div>
                    <div class="form-group">
    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->label('Запомнить'); ?>
                    </div>
                    <div class="form-group">
                        <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>
    <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section><!-- #contact -->
</main>
