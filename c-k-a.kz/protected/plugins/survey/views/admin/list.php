<?php
if (isset($_GET['PSurvey_page']))
{
    $page = $_GET['PSurvey_page'];
}
else
{
    $page = 1;
}

Yii::import("bootstrap.widgets.TbButtonColumn");
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'SurveyList',
    'htmlOptions'=>array(
        "name"=>"SurveyList"
    ),
));

function getRowClass($a)
{
    $c = array("0"=>"warning", "1"=>"success");
    return $c[$a];
}
function getActiveT($a)
{
    $c = array("0"=>t("Нет"), "1"=>t("Да"));
    return $c[$a];
}

$columns = array(
            array(
                'class'=>'CCheckBoxColumn',
                'checkBoxHtmlOptions'=>array(
                        'class'=>'question_checkbox',
                        'name'=>'Surveys[]'
                ),
                'selectableRows'=>2,
                'cssClassExpression'=>"survey_cell",
            )
    );

$columns[] =  array('name'=> 'name',
        'header' => t('Заголовок опроса'),
    );

$columns[] =  array(
        'header' => t('Опрос'),
        'type'=>'raw',
        'value'=>'getSurveyBody($data)'
    );

$columns[] =  array(
        'header' => t('Активен'),
        'value'=>'getActiveT($data->active)'
    );

$columns[] = array(
    'class'=>'bootstrap.widgets.TbButtonColumn',
    'buttons'=>array(
        "view"=>array("visible"=>'false'),
        "update"=>array("url"=>"'/admin/".Yii::app()->language."/".Yii::app()->controller->id."/survey/act/add/survey/'.\$data->id"),
        "delete"=>array("visible"=>'false')
    ),
    'htmlOptions'=>array('style'=>'width: 50px'),
);


$dataProvider = new CActiveDataProvider(PSurvey::model());

$this->widget('frontend.components.ParamBootGridView', array(
    'id' => 'PSurvey',
    'dataProvider' => $dataProvider,
    'template' => "{items}{pager}", //{summary}    
    'extParam' => array(
            'gid' => 'id',
            //'gname' => 'name',
    ),
    'type'=>'striped bordered',
    'rowCssClassExpression'=>'getRowClass($data->active)',
    'columns' => $columns,
    'htmlOptions'=>array(
        "style"=>"padding-top:0;"
    )
));

$this->endWidget();

?>