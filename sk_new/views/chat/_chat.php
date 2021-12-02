<?php
/**
 * @var \yii\web\View $this
 * @var \app\models\Message $message
 * @var \yii\db\ActiveQuery $messagesQuery
 */
?>


<?= $this->render('grup-list', compact('messagesQuery')) ?>
            <div class="mesgs">
                <div class="msg_history">

<?php \yii\widgets\Pjax::begin([
    'id' => 'list-messages',
    'enablePushState' => false,
    'formSelector' => false,
    'linkSelector' => false
]) ?>

<?= $this->render('_list', compact('messagesQuery')) ?>

<?php \yii\widgets\Pjax::end() ?>
</div>
          <div class="type_msg">
            <div class="input_msg_write">
<?php \yii\widgets\ActiveForm::begin(['options' => ['class' => 'pjax-form']]) ?>
<?= \yii\bootstrap\Html::activeTextarea($message, 'text',['class'=>'write_msg']) ?>

<?= \yii\helpers\Html::submitButton('<i class="fa fa-paper-plane-o" aria-hidden="true"></i>',['class'=>'msg_send_btn']) ?>

<?php \yii\widgets\ActiveForm::end() ?>
            </div>
          </div>
            </div>


