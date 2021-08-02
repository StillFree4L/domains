<label for="Instances_ref"><?=t("URL")?></label>
<?php
$this->widget("bootstrap.widgets.input.TbInputVertical", array(
			'type'=>TbInput::TYPE_TEXT,
			'form'=>$form,
			'model'=>$this->model,
			'attribute'=>"ref",
                        "label"=>false,
			'htmlOptions'=>array("class"=>"span5"),
		));
$this->widget("frontend.extensions.CKFinderButton.CKFinderButton", array(
    "id"=>"Instances_ref",
    "type"=>"files",
    "filespath"=>Yii::app()->params['uploadDir'],
    "filesurl"=>Yii::app()->baseUrl."/uploaded/",
));
?>