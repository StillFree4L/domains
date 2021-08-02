<?php
$buttons = array(
    "actions" => array(
        "label"=>t("Выделенные"),
        'items'=>array(
                array(
                    'label'=>t('Удалить'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("delete")'
                        )
                    ),
                
        )
        )
);

$buttons[] = array(
                  "label"=>t("Добавить книгу"),
                  "url"=>"/admin/".Yii::app()->language."/".Yii::app()->controller->id."/virtualLibrary/act/add"
                );

$this->widget("ext.EControlPanel.EControlPanel", array(
    "baseButtons"=>null,
    "buttons"=>$buttons));


if (Yii::app()->user->hasFlash('fieldSubmitted')) {
            Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
            $this->widget('bootstrap.widgets.TbAlert');
        }

if (isset($_GET['PVirtualLibrary_page']))
{
    $page = $_GET['PVirtualLibrary_page'];
}
else
{
    $page = 1;
}

Yii::import("bootstrap.widgets.TbButtonColumn");
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'BooksList',
    'htmlOptions'=>array(
        "name"=>"BooksList"
    ),
));

$columns = array(
            array(
                'class'=>'CCheckBoxColumn',
                'checkBoxHtmlOptions'=>array(
                        'class'=>'book_checkbox',
                        'name'=>'Books[]'
                ),
                'selectableRows'=>2,
                'cssClassExpression'=>"book_cell",
            )
    );

$columns[] =  array('name'=> 'book_name',
        'header' => t('Название'),        
    );

$columns[] =  array('name'=> 'book_code',
        'header' => t('Код'),
    );

$columns[] =  array('name'=> 'book_year',
        'header' => t('год'),        
    );

$columns[] = array(
    'class'=>'bootstrap.widgets.TbButtonColumn',
    'buttons'=>array(
        "view"=>array("visible"=>'false'),
        "update"=>array("url"=>"'/admin/".Yii::app()->language."/".Yii::app()->controller->id."/virtualLibrary/act/add/book/'.\$data->id"),
        "delete"=>array("visible"=>'false')
    ),
    'htmlOptions'=>array('style'=>'width: 50px'),
);

//$dataProvider = new CActiveDataProvider(PVirtualLibrary::model());

$this->widget('frontend.components.ParamBootGridView', array(
    'id' => 'PVirtualLibrary',
    'dataProvider' => $model->search(),
    'filter'=>$model,
    'template' => "{items}{pager}", //{summary}    
    'extParam' => array(
            'gid' => 'id',
            //'gname' => 'name',
    ),
    'type'=>'striped bordered',
    'columns' => $columns,
    'htmlOptions'=>array(
        "style"=>"padding-top:0;"
    )
));

$this->endWidget();

?>