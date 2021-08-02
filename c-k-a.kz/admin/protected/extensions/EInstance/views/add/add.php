<?php

if (count(Instances::model()->categories()->findAll())<1 AND $this->type!="1") {
    Yii::app()->user->setFlash('warning', t("Сначало добавьте категории. ")."<a href='/admin/categories/add'>".t("Добавить категорию")."</a>");
    $this->widget('bootstrap.widgets.TbAlert');
}

if (count(Instances::model()->linkCategories()->findAll())<1 AND ($this->type=="5")) {
    Yii::app()->user->setFlash('warning', t("Сначало добавьте категории. ")."<a href='/admin/categories/add'>".t("Добавить категорию")."</a>");
    $this->widget('bootstrap.widgets.TbAlert');
}

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
<div class="span6" style="margin-left:0;">

<div class="form-actions">
<?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>

<?php

$config = array(
    "toolbar" => array
    (
        array( 'Source','-','Templates' ),
        array( 'Undo','Redo' ),
        array( 'Bold','Italic','Underline','-','RemoveFormat' ),
        array( 'NumberedList','BulletedList','-','Outdent','Indent',
        '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ),
        array( 'Link','Unlink','Anchor' ),
        array( 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ),
        array('Format'),
        array( 'TextColor','BGColor' ),
        array( 'Maximize', 'ShowBlocks','-','About' )
    )
);

foreach ($this->baseFields as $chunk)
{
    $this->render("add/form_fields/".$chunk, array(
        "form"=>$form,
        "config"=>$config
    ));
}

?>

    <div class="clear"></div>
</div>

<?php
if (!empty($this->sideFields)) {
?>
<div class="span" style="width:185px; margin-left:20px;">
<?php

foreach ($this->sideFields as $chunk)
{
    ?>
    <div class="well" style="margin-top:24px; padding:10px;">
    <?php
    $this->render("add/side_fields/".$chunk, array(
        "form"=>$form,
    ));
    ?>
    </div>
    <?php
}

?>
    <div class="clear"></div>
</div>
<?php
}
?>

<div class="clear"></div>

<div class="form-actions">
    <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>



<?php

$this->endWidget();

?>
