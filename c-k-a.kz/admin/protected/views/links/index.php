<?php
$buttons = array(
    "actions" => array(
        "label"=>t("Выделенные"),
        'items'=>array(
                array(
                    'label'=>t('Востановить'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("restore")'
                        )
                    ),
                array(
                    'label'=>t('Удалить'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("delete")'
                        )
                    ),
                array(
                    'label'=>t('Удалить(безвозвратно)'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("forse_delete")'
                        )
                    ),
        )
        )
);

$this->widget("ext.EControlPanel.EControlPanel", array("buttons"=>$buttons));
$this->widget("ext.EInstance.EInstanceList", array(
    "model"=>$model,
    "columns"=>array(        
        array(
            "header"=>t("Категории"),
            "value"=>'implode(", ",$data->parentCategoriesCaptions)'
        )
    )
));
?>