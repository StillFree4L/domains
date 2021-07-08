<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\Repairs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;

$result = Repairs::find()->orderBy('id DESC')->one();
?>
<!--==========================
  Repairs Section
============================-->
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="form">
                <form action="" method="post" role="form" class="contactForm">
                    <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />

                    <div class="text-center"><?= Html::a('Создать заказ', ['create'], ['class' => 'btn btn-success']) ?></div><br>
                    <?php if($result != null): ?>
                    <div class="text-center">Поледний заказ пришел от <?=$result->client ?>  на сумму <?=$result->money ?> </div>
                    <?php endif; ?>
                    <div class="form-group">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {
            return ['class' => $model->result_name === 'Завершен' ? 'table-success' : ($model->result_name === 'Отказ от услуг' ? 'table-danger' : 'table-warning')];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'receipt',
            'date',
            'client',
            //'phone',
            'service_name',
            //'equipment',
            //'serial_id',
            //'facilities',
            //'problem',
            'username',
            'money',
            'result_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- #contact -->
</main>
