<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Repairs */

$this->title = 'Клиент '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Repairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<!--==========================
  View Users Section
============================-->
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="form">
                <form action="" method="post" role="form" class="contactForm">
                    <div class="text-center">
                        <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
                        <?php echo Html::a('Печатать договор', ['report-service', 'id' => $model->id], ['class' => 'btn btn-primary']);?>
                        <?php echo Html::a('Печатать акт', ['report-ready', 'id' => $model->id], ['class' => 'btn btn-primary']);?>
                        <?php echo Html::a('Печатать приложение', ['report-app', 'id' => $model->id], ['class' => 'btn btn-primary']);?>
                    </div><br>
                    <div class="text-center">
                        <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
                    </div><br><br>
                    <div class="form-group">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'receipt',
            'date',
            'client',
            'phone',
            'service_name',
            'equipment',
            'serial_id',
            'facilities',
            'problem',
            'username',
            'money',
            'result_name',
        ],
    ]) ?>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- #contact -->

    <?php
    $gallery=$model->getImages();
    if (!$model->getImage()==null):
    ?>

    <section id="portfolio"  class="section-bg" >
        <div class="container">

            <header class="section-header">
                <h3 class="section-title">Фото</h3>
            </header>

            <div class="row portfolio-container">

                <?php
                foreach ($gallery as $img):
                ?>

                <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp">
                    <div class="portfolio-wrap">
                        <figure>
                            <?= Html::img($img->getUrl('800x600'),['class'=>'img-fluid','alt'=>'']) ?>
                            <a href="<?= $img->getUrl('1200x1200') ?>" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
                        </figure>
                        <div class="portfolio-info">
                            <a href="deleteimg" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview">
                            <h4><?= Html::a('Удалить', ['deleteimg','id' => $model->id,'imgId' => $img->id], ['class' => 'btn btn-primary']) ?></h4>
                        </div>
                    </div>
                </div>

                <?php
                endforeach;
                ?>

            </div>

        </div>
    </section><!-- #portfolio -->
    <?php
    endif;
    ?>
</main>

