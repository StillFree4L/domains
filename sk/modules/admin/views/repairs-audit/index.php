<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--==========================
  Repairs-audit Section
============================-->
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="form">
                <form action="" method="post" role="form" class="contactForm">
                    <div class="form-group">
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                    </div>
                    <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
                    <div class="form-group">
    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'changed_on',
            'operation',
            'receipt',
            //'date',
            //'client',
            //'phone',
            'service_name',
            //'equipment',
            //'serial_id',
            //'facilities',
            //'problem',
            //'username',
            //'money',
            'result_name',

            ['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],
    ]); ?>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- #contact -->
</main>
