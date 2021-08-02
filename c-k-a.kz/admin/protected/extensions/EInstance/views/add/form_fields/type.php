<div>
    <label for="instance_lang"><?=t("Тип категории")?></label>
<?php

    $types = array(
        "1"=>t("Категория"),
        "4"=>t("Категория ссылок")
    );
    echo $form->dropDownList($this->model, "type", $types, array("class"=>"span4"));

?>
</div>
