<?php

if (isset($_GET['Reports_page']))
{
    $page = $_GET['Reports_page'];
}
else
{
    $page = 1;
}

Yii::import("bootstrap.widgets.TbButtonColumn");

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'InstancesList',
    'htmlOptions'=>array(
        "name"=>"instancesList"
    ),
));

function getRowClass($t) {
    $rowClasses = array("1"=>"warning","2"=>"success","3"=>"error");
    return $rowClasses[$t];
}

$columns = array(
            array(
                'class'=>'CCheckBoxColumn',
                'checkBoxHtmlOptions'=>array(
                        'class'=>'instance_checkbox',
                        'name'=>'InstancesL[]'
                ),
                'selectableRows'=>2,
                'cssClassExpression'=>"instance_cell",
            )            
    );

if ($this->defaultColumns) {
    $columns[] = array('name' => 'caption',
                        'header' => t('Заголовок'),
                        //'htmlOptions'=>array('data-gid'=>'aa'),
                );
    $columns[] = array('name'=> 'state',
                        'filter'=>false,
                        'header' => t('Состояние'),
                        'value'=>'$data->stateCaption'
                );
}
$columns = array_merge($columns, $this->columns);

if ($this->defaultColumns) {
    $columns[] =  array('name'=> 'owner_id',
                        'filter'=>false,
                        'header' => t('Автор'),
                        'value'=>'Users::model()->findByPk($data->owner_id)->login'
                    );

    $columns[] = array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'buttons'=>array(
                        "view"=>array("visible"=>'false'),
                        "update"=>array("url"=>"'/admin/".Yii::app()->language."/".Yii::app()->controller->id."/add/iid/'.\$data->id"),
                        "delete"=>array("visible"=>'false')
                    ),
                    'htmlOptions'=>array('style'=>'width: 50px'),
                );
}

$this->widget('frontend.components.ParamBootGridView', array(
    'id' => 'Instances',
    'dataProvider' => $this->model->search(),
    'filter'=>$this->model,
    'template' => "{items}{pager}", //{summary}    
    'extParam' => array(
            'gid' => 'id',
            //'gname' => 'name',
    ),
    'htmlOptions'=>array(
        'class'=>'table-bordered',        
    ),
    'rowCssClassExpression'=>'getRowClass($data->state)',
    'columns' => $columns,
));

$this->endWidget();

?>