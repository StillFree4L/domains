<?php
$this->renderPartial("menu");
$this->renderPartial("langs", array(
    "lang"=>$lang
));

$form = $this->beginWidget("bootstrap.widgets.TbActiveForm", array(
    
));

$i = 0;
?>
<div class="words_page">
<?php

if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

if (Yii::app()->user->hasFlash('fieldError')) {
    Yii::app()->user->setFlash('error', Yii::app()->user->getFlash('fieldError'));
    $this->widget('bootstrap.widgets.TbAlert');
}

foreach ($words as $k=>$v)
{
    $i++;
    ?>

<div i="<?=$i?>" class="word">
    <input type="text" name="words[<?=$i?>][key]" class="key_word" value='<?=  htmlspecialchars(stripslashes($k))?>' /> <span class="word_equals">=</span> <input type="text" name="words[<?=$i?>][value]" class="value_word" value='<?=htmlspecialchars(stripslashes($v))?>' /><a title="<?=t("Удалить")?>" class="word_delete icon icon-remove"></a>
</div>

    <?php
    
}
?>
</div>
<div class="form-actions">
    <?php echo CHtml::link('<i class="icon-plus icon-white"></i> '.t("Добавить слово"), "", array('class'=>'add_word btn btn-primary btn-small')); ?>
    <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
</div>
<?php

$this->endWidget();

?>
