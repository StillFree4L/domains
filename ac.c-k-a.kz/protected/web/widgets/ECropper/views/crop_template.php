<?php
    app\bundles\ToolsBundle::registerJCrop($this);
?>

<div id="cropper_modal" class="modal fade">
    <div class="modal-dialog">
        <div  class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h3 class="crop_crop"><?= Yii::t("main","Вы можете обрезать картинку если хотите"); ?></h3>

                <h3 class="crop_preview"><?= Yii::t("main","Выберите миниатюру {miniature}", [
                    "{miniature}"=>"<span class='miniature_placeholder'></span>",
                ]); ?></h3>

            </div>
            <div  rel="popover"
                  data-placement="right"
                  data-trigger='manual'
                  data-content="asd"
                  data-animation="false"
                  data-html="true"
                  data-title="false" class="modal-body">

            </div>
            <div class="modal-footer">
                <a class="btn btn-success sendAfterCrop"><?=Yii::t("main","Сохранить")?></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
