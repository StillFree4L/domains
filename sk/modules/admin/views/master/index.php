<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\Master;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мастера';
$this->params['breadcrumbs'][] = $this->title;
$result = Master::find()->all();
?>
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="form">
                <form action="" method="post" role="form" class="contactForm">
                    <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
                    <div class="text-center"><?= Html::a('Добавить мастера', ['create'], ['class' => 'btn btn-success']) ?></div><br>
                    <?php if($result != null): ?>
                    <div class="text-center">Сегодня день рождения у мастера
                        <?php foreach ($result as $res)
                        {
                            if(Yii::$app->formatter->asDate($res->date, 'd')==date('d')){
                            echo $res->name;
                            echo '<br> Его специальность ';
                            echo $res->role;
                            }
                        }?>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'date',
            'role',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- #contact -->
</main>
