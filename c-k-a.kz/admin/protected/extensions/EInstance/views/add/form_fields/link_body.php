<div>
<label for="Instances_ref"><?=t("Текст")?></label>
<?php
$this->widget("bootstrap.widgets.input.TbInputVertical", array(
        'type'=>TbInput::TYPE_TEXT,
        'form'=>$form,
        'model'=>$this->model,
        'attribute'=>"body",
        "label"=>false,
        'htmlOptions'=>array("class"=>"span5"),
));


?>
</div>