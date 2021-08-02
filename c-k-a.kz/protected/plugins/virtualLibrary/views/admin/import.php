<?php
if (Yii::app()->user->hasFlash('fieldError')) {
    Yii::app()->user->setFlash('error', Yii::app()->user->getFlash('fieldError'));
    $this->widget('bootstrap.widgets.TbAlert');
}?>
<?php
if (Yii::app()->user->hasFlash('fieldSuccess')) {
    Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSuccess'));
    $this->widget('bootstrap.widgets.TbAlert');
}?>
<div class="">
    <form enctype="multipart/form-data" method="post">
        <label style="display:block; margin:10px; font-weight:bold;" for="import_xml"><?=t("Импорт из файла")?></label>
        <input type="file" name="import_xml" />
            <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Импортировать'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
        
    </form>
</div>