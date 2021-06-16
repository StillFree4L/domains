<?php
use \yii\helpers\FileHelper;
use yii\helpers\Html;

$this->title = 'Наши сертификаты ';
$this->params['breadcrumbs'][] = ['label' => 'Sertificat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$img=\app\models\Sertificat::find()->all();
?>

<section id="portfolio"  class="section-bg" >
    <div class="container">

        <header class="section-header">
            <h3 class="section-title">Фото</h3>
        </header>

        <?php if (!Yii::$app->user->isGuest): ?>
        <div class="text-center"><?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?></div>
        <?php endif; ?>
        <br><br>
        <div class="row portfolio-container">

            <?php
            foreach ($img as $image):
                ?>

                <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp">
                    <div class="portfolio-wrap">
                        <figure>
                            <?= Html::img('/'.$image->name,['class'=>'img-fluid','alt'=>'','height'=>'100%', 'width'=>'100%']) ?>
                            <?php if (!Yii::$app->user->isGuest): ?>
                            <?= Html::a('<i class="ion ion-eye"></i>',['view', 'id' => $image->id], ['class' => 'link-preview']) ?>
                            <?= Html::a('<i class="ion ion-android-delete"></i>',['delete', 'id' => $image->id], ['class' => 'link-details','data'=>['method'=>'post']]) ?>
                            <?php endif; ?>
                        </figure>
                    </div>
                </div>

            <?php
            endforeach;
            ?>

        </div>

    </div>
</section><!-- #portfolio -->