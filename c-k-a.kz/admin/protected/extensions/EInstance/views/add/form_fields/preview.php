<div>
<label for="preview"><?=t("Превью")?></label>
<?php

    $this->widget('frontend.extensions.editor.CKkceditor',array(
        "model"=>$this->model,                # Data-Model
        "attribute"=>'preview',         # Attribute in the Data-Model
        "height"=>'200',
        "width"=>'558px',
        'config'=>$config,
        "filespath"=>Yii::app()->params['uploadDir'],
        "filesurl"=>"/uploaded/",
    ) );

?>
</div>