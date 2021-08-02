<?php

$this->render("index".$this->model->type, array(
    "model"=>$model,
    "records"=>$records,
    "pagerModel"=>$pagerModel,
));

?>
