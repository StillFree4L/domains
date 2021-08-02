<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'questionModal',
        "autoOpen"=>$error,
        "htmlOptions"=>array(
        ))); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?=t("Ответить")?></h4>
</div>

<?php
    $form = $this->beginWidget("bootstrap.widgets.TbActiveForm", array(
    "type"=>"vertical",
    "id"=>"answerModal",
    ));
?>

<div class="modal-body" style="margin-right:15px;">

    <div>
        <?php

        echo CHtml::hiddenField("PQuestionAnswer[id]","");
        echo $form->textAreaRow($question, "answer", array("rows"=>"8", "class"=>"div"));

        ?>
    </div>

</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>t("Ответить"),
        'buttonType'=>'submit',
    )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>t("Закрыть"),
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),
    )); ?>
</div>

<?php $this->endWidget(); ?>

<?php $this->endWidget(); ?>

<?php

if (isset($_GET['PQuestionAnswer_page']))
{
    $page = $_GET['PQuestionAnswer_page'];
}
else
{
    $page = 1;
}

Yii::import("bootstrap.widgets.TbButtonColumn");
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'QuestionsList',
    'htmlOptions'=>array(
        "name"=>"QuestionsList"
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
                        'class'=>'question_checkbox',
                        'name'=>'Questions[]'
                ),
                'selectableRows'=>2,
                'cssClassExpression'=>"question_cell",
            )
    );

$columns[] =  array('name'=> 'name',
        'header' => t('Спрашивает'),        
    );

$columns[] =  array('name'=> 'email',
        'header' => t('Email'),
    );

$columns[] =  array('name'=> 'question',
        'header' => t('Вопрос'),
        'type'=>'raw',
        'value'=>'"<a onclick=\"setInputs(\'$data->id\')\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#questionModal\">".$data->question."</a>"'
    );

$columns[] =  array('name'=> 'state',
        'header' => t('Состояние'),
        'value'=>'$data->stateCaption'
    );


$dataProvider = new CActiveDataProvider(PQuestionAnswer::model());

$this->widget('frontend.components.ParamBootGridView', array(
    'id' => 'PQuestionAnswer',
    'dataProvider' => $dataProvider,
    'template' => "{items}{pager}", //{summary}    
    'extParam' => array(
            'gid' => 'id',
            //'gname' => 'name',
    ),
    'type'=>'striped bordered',
    'rowCssClassExpression'=>'getRowClass($data->state)',
    'columns' => $columns,
    'htmlOptions'=>array(
        "style"=>"padding-top:0;"
    )
));

$this->endWidget();

?>