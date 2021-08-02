<?php
if ($this->checkAccess("closeTheme"))
{
    $this->widget("bootstrap.widgets.TbButton", array(
        "label"=>t("Закрыть тему"),
        "icon"=>"remove",
        'size'=>'small',
        "url"=>"/".Yii::app()->language."/".Yii::app()->controller->id."/closeTheme/".(isset($_GET['eid']) ? "eid/".$_GET['eid'] : "")
    ));
}
?>
