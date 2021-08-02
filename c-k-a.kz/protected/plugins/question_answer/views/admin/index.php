<?php
$buttons = array(
    "actions" => array(
        "label"=>t("Выделенные"),
        'items'=>array(
                array(
                    'label'=>t('Утвердить'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("approve")'
                        )
                    ),
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

$this->widget("ext.EControlPanel.EControlPanel", array(
    "baseButtons"=>null,
    "buttons"=>$buttons));
?>

<div class="question_answer_admin">

<?php
if (PQuestionAnswer::model()->count()>0) {

    $this->render("admin/list", array(
        "question"=>$question,
        "error"=>$error
    ));

} else {

    Yii::app()->user->setFlash('warning', 'Еще не задано ниодного вопроса');
    $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed        
    ));

}
?>

</div>