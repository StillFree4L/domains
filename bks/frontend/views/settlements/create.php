<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Settlements */

$this->title = 'Create Settlements';
$this->params['breadcrumbs'][] = ['label' => 'Settlements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="settlements-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
