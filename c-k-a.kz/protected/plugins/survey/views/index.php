<?php

if (!empty($surveys)) {

    foreach ($surveys as $k=>$v) {

        ?>

        <div class="survey_main <?=(count($surveys)>0 AND $k != 0)  ? "survey_next" : ""?>">

            <div class="survey_header"><?=$v->name?></div>

            <?php $this->render("survey_body", array(
                "model"=>$v
            )); ?>

        </div>

        <?php

    }

}

?>