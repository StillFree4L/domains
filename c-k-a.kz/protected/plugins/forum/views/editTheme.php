
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

echo $form->textFieldRow($theme, "name", array('class'=>'span6'));

$parents = PForumCategories::model()->byName()->findAll("type != 2");
$data = CHtml::listData($parents, 'id', 'name');

echo $form->dropDownList($theme, "category_id", $data, array(
    "class"=>"chosen_select",
    "style"=>"width:300px;",
    "data-placeholder"=>t("Родительская категория")
));

?>
    <div class="clear"></div>
</div>

<script language="javascript">
    $(function()
    {
        $("select.chosen_select").chosen({
            no_results_text: "<?=t('Нет результатов')?>"

        });
    })
</script>

<div class="form-actions">
    <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>


<?php

$this->endWidget();

?>
