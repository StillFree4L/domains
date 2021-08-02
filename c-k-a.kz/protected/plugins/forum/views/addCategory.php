

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

$types = array(
    "1" => t("Категория"),
    "2" => t("Раздел")
);

echo $form->dropDownList($category, "type", $types, array("class"=>"span4"));
echo $form->textFieldRow($category, "name", array('class'=>'span6'));

$parents = PForumCategories::model()->byName()->findAll();
$data = array(""=>"");
$data += CHtml::listData($parents, 'id', 'name');

echo $form->dropDownList($category, "parent_id", $data, array(
    "class"=>"chosen_select",
    "style"=>"width:300px;",
    "data-placeholder"=>t("Родительская категория")
));   
echo "<div style='margin-top:15px;'></div>";
echo $form->checkBoxRow($category, "can_add_themes", array("style"=>''));

?>
<script language="javascript">
    $(function()
    {
        $("select.chosen_select").chosen({
            no_results_text: "<?=t('Нет результатов')?>"

        });
    })
</script>
   
    <div class="clear"></div>
</div>

<div class="form-actions">
    <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>


<?php

$this->endWidget();

?>
