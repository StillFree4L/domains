<?php

function getSurveyBody($model)
{
    $path = Yii::getPathOfAlias("survey.views");
    ob_start();
    include($path."/survey_body.php");
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}


$buttons = array(
    "actions" => array(
        "label"=>t("Выделенные"),
        'items'=>array(
                array(
                    'label'=>t('Активировать'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("activate")'
                        )
                    ),
                array(
                    'label'=>t('Деактивировать'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("deactivate")'
                        )
                    ),
                array(
                    'label'=>t('Сбросить голоса'),
                    'url'=>'#',
                    'itemOptions'=>array(
                            'onclick'=>'submitInstances("reset")'
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

$buttons[] = array(
                  "label"=>t("Добавить опрос"),
                  "url"=>"/admin/".Yii::app()->language."/".Yii::app()->controller->id."/survey/act/add"
                );

$this->widget("ext.EControlPanel.EControlPanel", array(
    "baseButtons"=>null,
    "buttons"=>$buttons));
?>

<div class="question_answer_admin">

<?php

if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

if (PSurvey::model()->count()>0) {

    $this->render("admin/list", array(
        "question"=>$question,
        "error"=>$error
    ));

} else {

    Yii::app()->user->setFlash('warning', 'Ниодного опроса не составлено');
    $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
    ));

}
?>

</div>