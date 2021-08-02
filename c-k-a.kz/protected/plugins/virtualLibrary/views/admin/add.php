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

        ?><label for="PVirtualLibrary_library_name"><?=t("Библиотека")?></label><?php
        $this->widget('bootstrap.widgets.TbTypeahead', array(
            'name'=>'PVirtualLibrary[library_name]',
            'value'=>$model->library_name,
            'options'=>array(
                'source'=>$libraries,
                'items'=>10,
                'matcher'=>"js:function(item) {
                    return ~item.toLowerCase().indexOf(this.query.toLowerCase());
                }",
            ),
            'htmlOptions'=>array("class"=>"span6")
        ));
        
        echo $form->textFieldRow($model, "book_name", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "book_code", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "book_year", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "book_price", array(
            "class"=>"span6"
        )); 
        
        echo $form->textFieldRow($model, "book_lang", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "book_isbn", array(
            "class"=>"span6"
        ));
        
        echo $form->textAreaRow($model, "book_preview", array(
            "class"=>"span6",
            "rows"=>"6",
        ));
        
        echo $form->textFieldRow($model, "pub_view", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "pub_name", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "pub_code", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "pub_dep", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "pub_code", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "book_country", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "pub_city", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "author_name", array(
            "class"=>"span6"
        ));
        
        echo $form->textFieldRow($model, "author_code", array(
            "class"=>"span6"
        ));
        
        ?>
        
        <label for="PVirtualLibrary_book_link"><?=t("ссылка на книгу")?></label>
        <?php
        $this->widget("bootstrap.widgets.input.TbInputVertical", array(
                                'type'=>TbInput::TYPE_TEXT,
                                'form'=>$form,
                                'model'=>$model,
                                'attribute'=>"book_link",
                                "label"=>false,
                                'htmlOptions'=>array("class"=>"span5"),
                        ));
        $this->widget("frontend.extensions.CKFinderButton.CKFinderButton", array(
            "id"=>"PVirtualLibrary_book_link",
            "type"=>"files",
            "filespath"=>Yii::app()->params['uploadDir'],
            "filesurl"=>Yii::app()->baseUrl."/uploaded/",
        ));
        ?>
        
        <?php
    
    ?>

</div>

<div class="form-actions">
    <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>

<?php

$this->endWidget();
?>
