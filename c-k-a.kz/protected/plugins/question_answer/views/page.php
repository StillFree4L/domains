<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'questionModal',
        "autoOpen"=>$error,
        "htmlOptions"=>array(
        ))); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?=t("Задать вопрос")?></h4>
</div>

<?php
    $form = $this->beginWidget("bootstrap.widgets.TbActiveForm", array(
    "type"=>"vertical",
    "id"=>"questionForm",
    ));
?>

<div class="modal-body" style="margin-right:15px;">

    <div>
        <?php

        echo $form->textFieldRow($question, "name", array("class"=>"div"));
        echo $form->textFieldRow($question, "email", array("class"=>"div"));

        echo $form->textAreaRow($question, "question", array("rows"=>"8", "class"=>"div"));

        ?>
    </div>

</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>t("Задать"),
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


if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('warning', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

?>


<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>t("Задать вопрос"),
    'size'=>'small',
    'htmlOptions'=>array(
        'data-toggle'=>'modal',
        'data-target'=>'#questionModal',
    ),
)); ?>

<a name="questions"></a>

<div class="commentsHeader"><?=t("Вопросы")?></div>
<div class="commentslist">

    <?php
        if (!empty($questions)) {
        foreach ($questions as $qquestion)
        {
            ?>

                <div class="comment">
                    <a name="question_<?=$qquestion->id?>"></a>
                    <span class="date system_font_color"><?=date("d.m.Y",$qquestion->ts)?></span><span class="user"><?=$qquestion->name?></span>

                    <div class="comment_text">
                        <?=$qquestion->question?>
                    </div>

                    <?php if (!empty($qquestion->answer))
                    {
                        ?>


                    <span class="comment_answer_header"><?=t("Ответ:")?></span>
                    <div class="comment_answer">
                        <?=$qquestion->answer?>
                    </div>

                        <?php
                    }
                    ?>

                </div>

            <?php
        }
        }
    ?>

</div>