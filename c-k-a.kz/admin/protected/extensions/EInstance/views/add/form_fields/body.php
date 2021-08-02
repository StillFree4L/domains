<div style="margin-top:30px;">
    <?php
    $body = '';    
    ob_start();
    $this->widget('frontend.extensions.editor.CKkceditor',array(
        "model"=>$this->model,                # Data-Model
        "attribute"=>'body',         # Attribute in the Data-Model
        "height"=>'550',
        "width"=>'558px',
        'config'=>$config,
        "filespath"=>Yii::app()->params['uploadDir'],
        "filesurl"=>Yii::app()->baseUrl."/uploaded/",
    ) );
    $body = ob_get_contents();
    ob_end_clean();

    $tabs = array(
            array('label'=>t("Текст"), 'active'=>true, 'content'=>$body),
        );

    if (isset($this->baseFieldsOptions['body']['ref']) AND $this->baseFieldsOptions['body']['ref']) {

        $ref = '';
        ob_start();
        echo $form->textFieldRow($this->model, "ref", array("class"=>"span5"));
        $this->widget("frontend.extensions.CKFinderButton.CKFinderButton", array(
            "id"=>"Instances_ref",
            "type"=>"files",
            "filespath"=>Yii::app()->params['uploadDir'],
            "filesurl"=>Yii::app()->baseUrl."/uploaded/",
        ));
        $ref = ob_get_contents();
        ob_end_clean();
        
        $tabs[] = array('label'=>t("Ссылка"), 'content'=>$ref);

    }

    ?>

    <?php $this->widget('bootstrap.widgets.TbTabs', array(
        'type'=>'tabs',
        'placement'=>'above', // 'above', 'right', 'below' or 'left'
        'tabs'=>$tabs,
        'htmlOptions'=>array("class"=>"smallfont")
    )); ?>

</div>