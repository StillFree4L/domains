<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
$on = User::find()->where('status=10')->count();
$off = User::find()->where('status=0')->count();
?>
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
                    <div class="text-center"><?= Html::a('Регистрация', ['/user/signup'], ['class' => 'btn btn-success']) ?>
                        <?= Html::a('Выйти', ['/user/logout'], ['class' => 'btn btn-success']) ?></div>
                    <br>
                    <div class="text-center">Активных пользователей: <?=$on ?></div>
                    <div class="text-center">Отключенных пользователей: <?=$off ?></div>
                    <div class="form-group">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                    'attribute'=>'username',
                'label'=>'Логин',
            ],
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
                'attribute'=>'status',
                'label'=>'Статус',
                'value'=>function($model){
        switch ($model->status){
            case 0;return 'Отключен';
            case 10;return 'Активен';
        }return null;
                }
            ],
            [
                'attribute'=>'updated_at',
                'format'=>'datetime',
                'label'=>'Дата изменения',
            ],
            [
                'attribute'=>'created_at',
                'format'=>'datetime',
                'label'=>'Дата создания',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- #contact -->
</main>
