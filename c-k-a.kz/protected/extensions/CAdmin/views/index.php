<?php

if ($instance->canEdit())
{
    $this->widget("bootstrap.widgets.TbButton", array(
        "type"=>"link",
        "size"=>"small",
        "icon"=>"pencil",
        "url"=>$instance->getLink(true),
        "htmlOptions"=>array(
            "title"=>t("Редактировать")
        )
    ));
}
?>