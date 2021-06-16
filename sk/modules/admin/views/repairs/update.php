<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Repairs */

$this->title = 'Изменить пользователя: '.$model->client;
$this->params['breadcrumbs'][] = ['label' => 'Repairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<!--==========================
  Update Users Section
============================-->
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="form">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
            </div>
        </div>
    </section><!-- #contact -->
</main>
