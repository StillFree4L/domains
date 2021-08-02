<?php 
$url = "/admin/".Yii::app()->language."/".Yii::app()->controller->id."/".Yii::app()->controller->action->id."/l/";

$items = array();
foreach (Yii::app()->urlManager->languages as $l)
{
    $items[] = array('label'=>t($l), 'url'=>$url.$l, 'active'=>($l == $lang ? true : false));            
}

$this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>$items,
)); ?>