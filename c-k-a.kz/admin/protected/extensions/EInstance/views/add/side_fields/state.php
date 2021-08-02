<div>
    <label for="instance_lang"><?=t("Состояние")?></label>
<?php

    $states = Instances::model()->instanceStates();

    echo $form->dropDownList($this->model, "state", $states, array("class"=>"div"));

?>
</div>
