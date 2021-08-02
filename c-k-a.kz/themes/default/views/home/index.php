<?php


$cs = Yii::app()->getClientScript();
$cs->registerCSSFile(Yii::app()->theme->getBaseUrl().'/css/home.css');


?>

<div class="home_page">

    <div class="home_page_1">
    <?php
        
        $home1 = Instances::model()->byLabel("main")->find();
        $this->widget("ext.CInstance.CInstance",array(
            "model"=>$home1,
            "blocks"=>array("body")
        ));

        $home2 = Instances::model()->byLabel("awards")->find();
        $this->widget("ext.CInstance.CInstance",array(
            "model"=>$home2,
            "blocks"=>array("link_caption","preview")
        ));

    ?>
    </div>

    <div class="home_page_2">
        <?php
            if (Yii::app()->plugins->question_answer != null)
            {
                ?>
            <div id="last_question">
                <?php
                $this->widget("application.plugins.question_answer.question_answer", array(
                    "type"=>"last_questions",
                    "limit"=>"3"
                ));
                ?>
            </div>
                <?php

            }
        ?>
    </div>

</div>

