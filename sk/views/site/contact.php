<?php

use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

    $this->title = 'Контакты';
?>
<!--==========================
  Contact Section
============================-->
<section id="contact" class="section-bg wow fadeInUp">
    <div class="container">

        <div class="section-header">
            <h3>Контакты</h3>
            <p>Свяжитесь с сотрудниками SK, чтобы задать вопрос онлайн или о том, как воспользоваться нашими услугами</p>
        </div>

        <div class="row contact-info">

            <div class="col-md-4">
                <div class="contact-address">
                    <i class="ion-ios-location-outline"></i>
                    <h3>Адрес</h3>
                    <address>Проспект Н. Абдирова 47/1 офис 63, РК</address>
                    <address>Вход В Офис, С Торца Напротив 9-Ти Этажного Дома 47/2</address>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-phone">
                    <i class="ion-ios-telephone-outline"></i>
                    <h3>Телефоный номер</h3>
                    <p><a href="tel:87059102200">8 (705) 910-22-00</a></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-email">
                    <i class="ion-ios-email-outline"></i>
                    <h3>E-mail</h3>
                    <p><a href="mailto:Sk@Statuskrg.Kz">Sk@Statuskrg.Kz</a></p>
                    <p><a href="mailto:Statuskaraganda@Gmail.Com">Statuskaraganda@Gmail.Com</a></p>
                </div>
            </div>

        </div>

        <div class="form">
<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
    <div class="alert alert-success">Сообщение отправлено. Спасибо!</div>
<?php else: ?>
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
            <form action="" method="post" role="form" class="contactForm">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label('Имя') ?>
                    </div>
                    <div class="form-group col-md-6">
                        <?= $form->field($model, 'email') ?>
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'subject') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'body')->textarea(['rows' => 4]) ?>
                </div>
                <div class="form-group">
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
                ]) ?>
                </div>
                <div class="text-center"><?= Html::submitButton('Отправить', [ 'name' => 'contact-button']) ?></div>
            </form>
            <?php ActiveForm::end(); ?>
<?php endif; ?>
        </div>

    </div>
</section><!-- #contact -->
