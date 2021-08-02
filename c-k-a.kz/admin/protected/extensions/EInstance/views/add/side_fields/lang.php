<div>
    <label for="instance_lang"><?=t("Язык записи")?></label>
<?php

    foreach (Yii::app()->urlManager->languages as $v)
    {
        $languages[$v] = t($v);
    }

    echo CHtml::dropDownList("instance_lang", "ru", $languages, array("class"=>"div"));

?>
</div>
