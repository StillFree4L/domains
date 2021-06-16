<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = 'Ошибка!';
?>

<section id="contact" class="section-bg wow fadeInUp">
    <div class="container">

        <div class="section-header">
            <h3><?= Html::encode($this->title) ?></h3>
            <div class="alert alert-danger" style="text-align: center">
                <?= nl2br(Html::encode($message)) ?>
            </div>
            <p>Вышеупомянутая ошибка произошла во время обработки вашего запроса веб-сервером.</p>
            <p>Свяжитесь с нами, если вы считаете, что это ошибка сервера. Спасибо.</p>
        </div>


    </div>
</section><!-- #contact -->
