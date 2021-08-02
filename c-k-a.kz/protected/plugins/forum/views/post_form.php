<div class="post_form">
<?php

if ($this->checkAccess("addPost")) {

    if ($theme->state == '1' OR $this->checkAccess("addPostAdmin")) {
        $form = $this->beginWidget("bootstrap.widgets.TbActiveForm", array(

        ));

        $path = Yii::getPathOfAlias("application.extensions.editor.source.ckeditor");
        $config = array(
            "toolbar" => array
            (
                array( 'Source'),
                array( 'Undo','Redo' ),
                array( 'Bold','Italic','Underline','-','RemoveFormat' ),
                array( 'Link','Unlink'),
                array( 'Image','Flash','Smiley','quote'),        
                array( 'Maximize', 'ShowBlocks')
            ),
            "extraPlugins"=> "quote",
            
        );

        ?>
        <label class="required" for="preview"><?=t("Сообщение")?> <span class="required">*</span></label>
        <?php
        $this->widget('application.extensions.editor.CKkceditor',array(
                "model"=>$post,                # Data-Model
                "attribute"=>'post',         # Attribute in the Data-Model
                "height"=>'200',
                "width"=>'715px',
                'config'=>$config,
            ) );
        if ($post->getError("post")) {
        ?>
        <span class="help-block error"><?=$post->getError("post");?></span>
        <?php
        }

        ?>
        <div class="form-actions">
            <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Отправить'), array('class'=>'btn btn-primary btn-small', 'type'=>'submit')); ?>
        </div>
        <?php

        $this->endWidget();
    } else {
        ?>
        <div class="alert alert-warning"><?=t("Тема закрыта")?></div>
        <?php
    }

} else {
    ?>
    <div class="alert alert-warning"><?=t("Только зарегистрированные пользователи могут оставлять сообщения")?></div>
    <?php
}
?>
</div>