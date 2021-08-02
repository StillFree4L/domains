<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (!Yii::$app->user->isGuest): ?>
      <?php if (\Yii::$app->user->can('roleAdmin')): ?>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
  <?php endif; ?>
<?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'username',
            'email:email',
            'role',
            'date:date',
            [
              'label' => 'Дата добавления',
              'attribute' => 'created_at',
              'format'=>'datetime',
      'visible' => \Yii::$app->user->can('roleAdmin') ? true : false
    ],
          [
            'label' => 'Дата изменения',
            'attribute' => 'updated_at',
            'format'=>'datetime',
    'visible' => \Yii::$app->user->can('roleAdmin') ? true : false
  ],
            'about',
      ],
    ]) ?>

</div>
