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

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>array(
        array('label'=>t('Категории'), 'url'=>"/admin/".Yii::app()->language."/categories/index/cat/1", 'active'=>((isset($_GET['cat']) AND $_GET['cat']==1) ? true : false)),
        array('label'=>t('Категории ссылок'), 'url'=>"/admin/".Yii::app()->language."/categories/index/cat/4", 'active'=>((isset($_GET['cat']) AND $_GET['cat']==4) ? true : false)),
    ),
));

$this->widget("ext.EInstance.EInstanceList", array(
    "model"=>$model,
    "columns"=>array(
        array('header' => t('Ссылка'),
                'value'=>'$data->getLink()'
            ),
        array(
            "header"=>t("Метка"),
            "value"=>'$data->label'
        )
    )
));
?>