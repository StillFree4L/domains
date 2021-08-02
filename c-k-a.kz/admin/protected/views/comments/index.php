<?php
$buttons = array(
    "actions" => array(
        "label"=>t("Выделенные"),
        'items'=>array(
                array(
                    'label'=>t('Удтвердить'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("approve_comments")'
                        )
                    ),
                array(
                    'label'=>t('Удалить'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("delete_comments")'
                        )
                    ),                
        )
        )
);

$this->widget("ext.EControlPanel.EControlPanel", array(
    "buttons"=>$buttons,
    "baseButtons"=>null));

$this->widget("ext.EInstance.EInstanceList", array(
    "model"=>$model,
    "defaultColumns"=>false,
    "columns"=>array(
        array(
            "header"=>t("Имя"),
            "name"=>"name"
        ),
        array(
            "header"=>t("Комментарий"),
            "name"=>"comment"
        ),
        array("header"=>t("Запись"),
            "type"=>"raw",
            "value"=>'"<a href=\"".$data->instance->getLink()."#comment".$data->id."\">".$data->instance->caption."</a>"'
            ),
        array(
            "header"=>t("Статус"),
            "filter"=>false,
            "name"=>"stateCaption"
        )
    )
));
?>