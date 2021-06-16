<?php

use app\models\Results;
use app\models\Master;
use app\models\Services;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Repairs */
/* @var $form yii\widgets\ActiveForm */
?>
<?php

$service = Services::find()->asArray()->all();
$master = Master::find()->asArray()->all();
$result = Results::find()->asArray()->all();
$itemsService = ArrayHelper::map($service,'service','service');
$itemsMaster = ArrayHelper::map($master, 'name','name','role');
$itemsResult = ArrayHelper::map($result,'result','result');
$paramsService = ['prompt' => 'Укажите услугу...'];
$paramsMaster = ['prompt' => 'Укажите мастера...'];
$paramsResult = ['prompt' => 'Укажите результат...'];

?>

<div class="repairs-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <form role="form" class="contactForm">
        <div class="form-group">
    <?= $form->field($model, 'receipt')->textInput() ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'client')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'service_name')->dropDownList($itemsService,$paramsService) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'equipment')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'serial_id')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'facilities')->textarea(['rows' => 3]) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'problem')->textarea(['rows' => 3]) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'username')->dropDownList($itemsMaster,$paramsMaster) ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'money')->textInput() ?>
        </div>
        <div class="form-group">
    <?= $form->field($model, 'result_name')->dropDownList($itemsResult,$paramsResult) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'gallery[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
        </div>
            <div class="text-center"><?= Html::submitButton('Сохранить') ?></div>
    </form>

    <?php ActiveForm::end(); ?>

</div>
