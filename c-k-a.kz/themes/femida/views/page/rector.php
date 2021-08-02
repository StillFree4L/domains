<?php

$c = Instances::model()->byLabel("rector_blog")->find();

$this->widget("ext.CInstance.CInstance",array(
    "model"=>$c,
));

$this->widget("application.plugins.question_answer.question_answer");
?>