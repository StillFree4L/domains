<?php 
$url = "/admin/".Yii::app()->language."/".Yii::app()->controller->id."/";
$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>array(
        array('label'=>t("Перевод сайта"), 'url'=>$url."site", 'active'=>(Yii::app()->controller->action->id == "site" ? true : false)),
        array('label'=>t("Перевод админки"), 'url'=>$url."admin", 'active'=>(Yii::app()->controller->action->id == "admin" ? true : false)),        
    ),
)); ?>