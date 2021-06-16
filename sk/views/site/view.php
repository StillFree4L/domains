<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Repairs */

$this->title = 'Сертификат '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Repairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


?>

<section id="facts"  class="wow fadeIn">
    <div class="container">
        <header class="section-header">
            <h3 class="section-title">Фото</h3>
        </header><br>
        <div class="facts-img center-block">
            <?= Html::img('/'.$model->name,['class'=>'img-fluid center-block','alt'=>'','height'=>'1000px', 'width'=>'600px']) ?>
        </div><br><br><br><br>
    </div>
</section><!-- #contact -->