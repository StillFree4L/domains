<?php

$import = $this->render("admin/import", array(), true);
$list = $this->render("admin/list", array("model"=>$model), true);

$la = true;
$ia = false;
if (isset($_FILES['import_xml']))
{
    $ia = true;
    $la = false;
}

$this->widget('bootstrap.widgets.TbTabs', array(
    'type'=>'tabs', // 'tabs' or 'pills'
    'tabs'=>array(
        array('label'=>t('Список книг'), 'content'=>$list, 'active'=>$la),
        array('label'=>t('Импорт из файла'), 'content'=>$import, 'active'=>$ia),        
    ),
));

?>