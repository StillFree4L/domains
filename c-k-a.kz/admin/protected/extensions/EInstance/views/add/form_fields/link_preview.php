<label for="Instances_ref"><?=t("Изображение")?></label>
<?php
$this->widget("bootstrap.widgets.input.TbInputVertical", array(
			'type'=>TbInput::TYPE_TEXT,
			'form'=>$form,
			'model'=>$this->model,
			'attribute'=>"preview",
                        "label"=>false,
			'htmlOptions'=>array("class"=>"span5"),
));
$this->widget("frontend.extensions.CKFinderButton.CKFinderButton", array(
    "id"=>"Instances_preview",
    "type"=>"images",
    "filespath"=>Yii::app()->params['uploadDir'],
    "filesurl"=>Yii::app()->baseUrl."/uploaded/",
));
?>