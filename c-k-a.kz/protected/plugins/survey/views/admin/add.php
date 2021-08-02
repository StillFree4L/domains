<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'htmlOptions'=>array(),
));

?>

<div class="well well-small">

    <?php

        if (Yii::app()->user->hasFlash('fieldError')) {
            Yii::app()->user->setFlash('error', Yii::app()->user->getFlash('fieldError'));
            $this->widget('bootstrap.widgets.TbAlert');
        }

        if (Yii::app()->user->hasFlash('fieldSubmitted')) {
            Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
            $this->widget('bootstrap.widgets.TbAlert');
        }


        echo $form->textFieldRow($survey, "name", array(
            "class"=>"span6"
        ));

    ?>

    <label><?=t("Варианты опроса")?></label>

    <div class="survey_variants">
    <?php

    if (isset($variants))
    {
        foreach ($variants as $k=>$v)
        {
            echo "<div class='variant'>";
            if (!empty($v->id))
            {
               echo CHtml::hiddenField("PSurveyVariants[$k][id]", $v->id);
            }
            echo CHtml::textField("PSurveyVariants[$k][name]",$v->name, array(
                "class"=>"span5"
            ));
            $this->widget("bootstrap.widgets.TbButton", array(
                "type"=>"warning",
                "icon"=>"remove white",
                "htmlOptions"=>array(
                    "onclick"=>"deleteVariant(this);",
                    "style"=>"margin-bottom:10px; margin-left:10px;"
                ),
            ));
            echo "</div>";
        }
    }
    ?>
    </div>
    <?php

    $this->widget("bootstrap.widgets.TbButton", array(
        "type"=>"primary",        
        "icon"=>"plus white",
        "htmlOptions"=>array(
            "onclick"=>"addVariant();"
        ),
    ))
    ?>

</div>

<div class="form-actions">
    <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>

<?php

$this->endWidget();
?>
