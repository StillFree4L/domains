
<?php

if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

if (Yii::app()->user->hasFlash('error')) {
    Yii::app()->user->setFlash('error', Yii::app()->user->getFlash('error'));
    $this->widget('bootstrap.widgets.TbAlert');
}

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'htmlOptions'=>array(),
));

?>

<div class="well well-small">

<?php

$config = array(
    "toolbar" => array
    (
        array( 'Source'),
        array( 'Undo','Redo' ),
        array( 'Bold','Italic','Underline','-','RemoveFormat' ),
        array( 'Link','Unlink','Anchor' ),
        array( 'Image','Flash','Smiley'),        
        array( 'TextColor'),
        array( 'Maximize', 'ShowBlocks','-','About' )
    )
);

echo $form->textFieldRow($theme, "name", array('class'=>'span6'));

?><label class="required" for="preview"><?=t("Сообщение темы")?> <span class="required">*</span></label><?php
$this->widget('application.extensions.editor.CKkceditor',array(
        "model"=>$theme,                # Data-Model
        "attribute"=>'post',         # Attribute in the Data-Model
        "height"=>'200',
        "width"=>'558px',
        'config'=>$config,        
    ) );
if ($theme->getError("post")) {
?>
<span class="help-block error"><?=$theme->getError("post")?></span>
<?php
}
?>
    <div class="clear"></div>
</div>

<div class="form-actions">
    <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>


<?php

$this->endWidget();

?>
