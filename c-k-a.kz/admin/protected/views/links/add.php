<?php
$this->widget("ext.EInstance.EInstanceAdd", array(
    "model"=>$model,
    "type"=>"5",
    "baseFields"=>array(
        "label",
        "caption",
        "link_preview",
        "link_body",
        "link_ref",        
    ),    
    "sideFields"=>array(
        "link_params",
        "link_categories"
    )
));
?>