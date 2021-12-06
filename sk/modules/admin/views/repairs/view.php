<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Repairs */

$this->title = 'Клиент '.$model->client;
$this->params['breadcrumbs'][] = ['label' => 'Repairs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
            //'id',
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
            'updated_at',
            'created_at',
        ],
    ]) ?>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- #contact -->

    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3>Услуги</h3>
            </div>
            <div class="form">
                <form action="" method="post" role="form" class="contactForm">
                    <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
                    <?php if(\Yii::$app->user->can('master')):?>
                        <div class="text-center">
                            <?= Html::a('Добавить', ['complete-create','id' => $model->id,'type'=>'Услуги'], ['class' => 'btn btn-success']) ?>
                        </div>
                        <br>
                    <?php endif;?>
                    <?php if($complete): ?>
                        <?php
                    $price=0;
                    $m = $model->id;
                    foreach ($complete as $prices){
                        $price+=$prices->price;
                        }
                        ?>
                        <div class="text-center">Общая цена комплектующих: <?=$price?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                //'id',
                                'name',
                                'number',
                                'price',

                                [
                                    'class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}',
                                    'urlCreator' => function ($action, $model, $key, $index) use ($m) {
                                        if ($action === 'update') {
                                            return Url::to(['complete-update', 'id' => $model->id,'repairs_id'=>$m,'type'=>'Услуги']);
                                        }
                                        if ($action === 'delete') {
                                            return Url::to(['complete-delete', 'id' => $model->id,'repairs_id'=>$m,'type'=>'Услуги']);
                                        }
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3>Материалы</h3>
            </div>
            <div class="form">
                <form action="" method="post" role="form" class="contactForm">
                    <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
                    <?php if(\Yii::$app->user->can('master')):?>
                        <div class="text-center">
                            <?= Html::a('Добавить', ['complete-create','id' => $model->id,'type'=>'Материалы'], ['class' => 'btn btn-success']) ?>
                        </div>
                        <br>
                    <?php endif;?>
                    <?php if($material): ?>
                        <?php
                        $price=0;
                        $m = $model->id;
                        foreach ($material as $prices){
                            $price+=$prices->price;
                        }
                        ?>
                        <div class="text-center">Общая цена комплектующих: <?=$price?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderMaterial,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                //'id',
                                'name',
                                'number',
                                'price',

                                [
                                    'class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}',
                                    'urlCreator' => function ($action, $model, $key, $index) use ($m) {
                                        if ($action === 'update') {
                                            return Url::to(['complete-update', 'id' => $model->id,'repairs_id'=>$m,'type'=>'Материалы']);
                                        }
                                        if ($action === 'delete') {
                                            return Url::to(['complete-delete', 'id' => $model->id,'repairs_id'=>$m,'type'=>'Материалы']);
                                        }
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php
    $gallery=$model->getImages();
    if ($model->getImage()):
    ?>

    <section id="contact" class="section-bg wow fadeInUp" >
        <div class="container">

            <header class="section-header">
                <h3 class="section-title">Фото</h3>
            </header>

            <div class="row portfolio-container" style="margin-bottom: 25%">

                <?php
                foreach ($gallery as $img):
                ?>

                <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp">
                    <div class="portfolio-wrap" style="text-align: center">
                        <figure>
                            <a href="<?= $img->getUrl('1200x1200') ?>" data-lightbox="portfolio" data-title="App 1" class="link-details" title="Preview">
                            <?= Html::img($img->getUrl('800x600'),['class'=>'img-fluid','alt'=>'']) ?>
                            </a>
                        </figure>
                            <div class="portfolio-info">
                                <?= Html::a('Удалить', ['deleteimg','id' => $model->id,'imgId' => $img->id], ['class' => 'btn btn-primary']) ?>
                            </div>
                    </div>
                </div>

                <?php
                endforeach;
                ?>

            </div>

        </div>
    </section>
    <?php
    endif;
    ?>
</main>