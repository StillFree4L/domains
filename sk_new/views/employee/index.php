<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php if (!Yii::$app->user->isGuest): ?>
  <?php if (\Yii::$app->user->can('roleAdmin')): ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Employee'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>
  <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'username',
            'email:email',
            'role',
            'date:date',
            //'about',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn',
            'visibleButtons' => ['update' => \Yii::$app->user->can('roleAdmin'),
            'delete' => \Yii::$app->user->can('roleAdmin')]],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
