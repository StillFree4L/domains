<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Repairs */

$this->title = $type;
?>
<!--==========================
  Create Repairs Section
============================-->
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="form">
                <?= $this->render('_form', compact('model','repairs','services')) ?>
            </div>
        </div>
    </section><!-- #contact -->
</main>

