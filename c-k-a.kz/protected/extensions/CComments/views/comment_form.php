<div class="comment_form">
<?php

$form = $this->beginWidget("bootstrap.widgets.TbActiveForm", array(
    "type"=>"horizontal",
    "id"=>"commentForm",
));

if (Yii::app()->user->id)
{
    ?>
    <div class="control-group "><label class="control-label" for="user_id"><?=t("Имя")?></label>
        <div class="controls">
    <?php
        echo CHtml::textField("user_id", Yii::app()->user->login, array("disabled"=>true));
    ?>
        </div>
    </div>
    <?php
} else {

    echo $form->textFieldRow($comment, "name", array("class"=>"span4"));

}

echo $form->textAreaRow($comment, "comment", array("class"=>"span5"));

?>
<div class="form-actions">
    <?php echo CHtml::htmlButton('<i class="icon-ok"></i> '.t('Отправить'), array('class'=>'btn btn-small', 'type'=>'submit')); ?>
</div>
<?php

$this->endWidget();

?>
</div>
