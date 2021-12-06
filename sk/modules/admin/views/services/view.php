<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Master */

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = ['label' => 'Результат', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
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
                        <?= Html::a('Изменить услугу', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'service',
            'updated_at',
            'created_at',
        ],
    ]) ?>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- #contact -->
</main>


