<?php
use \yii\helpers\FileHelper;
use yii\helpers\Html;

$this->title = 'Сертификаты';
$this->params['breadcrumbs'][] = ['label' => 'Sertificat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<main id="main">
    <section id="contact" class="section-bg wow fadeInUp">
        <div class="container">
            <div class="section-header">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <?php if(\Yii::$app->user->can('admin')): ?>
                <div class="text-center"><?= Html::a('Добавить сертификат', ['create'], ['class' => 'btn btn-success']) ?></div><br>
            <?php endif; ?>
            <div class="row portfolio-container" style="margin-bottom: 25%">
                <?php
                foreach($models as $model){
                    foreach($model->getImages() as $img){
                        ?>
                        <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp">
                            <div class="portfolio-wrap" style="text-align: center">
                                <figure>
                                    <a href="<?= $img->getUrl('1200x1200') ?>" data-lightbox="portfolio" data-title="App 1" class="link-details" title="Preview">
                                        <?= Html::img($img->getUrl(),['class'=>'img-fluid','alt'=>$model->name,'height'=>'100%', 'width'=>'100%']) ?>
                                    </a>

                                </figure>
                                <?php if(\Yii::$app->user->can('admin')): ?>
                                    <div class="portfolio-info">
                                        <?= Html::a('Удалить',['delete', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <br/>
        </div>
    </section>
</main>