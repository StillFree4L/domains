<?php

\app\bundles\ToolsBundle::registerChosen($this);
\Yii::$app->breadCrumbs->addLink(\Yii::t("main","Список словарей"), \glob\helpers\Common::createUrl("/dics/index"));
$this->addTitle(Yii::t("main","Добавление словаря"));

?>
<div class="controller container users-controller">
    <div class="action-content">

        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions"=>[
                "action"=>\glob\helpers\Common::createUrl("/dics/add", \Yii::$app->request->get(null, [])),
                "method"=>"post",
                "id"=>"newDicsForm"
            ],
        ]);

        echo \app\widgets\EUploader\EUploader::widget([
            "standalone" => true
        ]);

        ?>

        <div style="margin-top:0;" class="page-header"><h3 class="text-info"><?=Yii::t("main","Основные данные")?></h3></div>

        <div class="form-group" attribute="name">

            <label for="name" class="control-label"><?=Yii::t("main","Название")?></label>

            <input class="form-control" type="text" placeholder="<?=Yii::t("main","Название")?>" id="name" value="<?=$dic->name?>" name="name" />

            <p class="help-block"><?=Yii::t("main","Латинницей")?></p>

        </div>

        <div class="form-group" attribute="description">

            <label for="description" class="control-label"><?=Yii::t("main","Краткое описание")?></label>

            <textarea class="form-control" rows="3" placeholder="<?=Yii::t("main","Краткое описание")?>" id="description" name="description"><?=$dic->description?></textarea>

        </div>

        <script id="attached_file_template" type="text/template">
            <div class='uploaded-file'>
                <% if (!data.error && data.percent>0 && data.percent < 100) { %>
                    <div style='margin-top:5px; margin-bottom:5px;' class="progress progress-striped">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <%=data.percent%>%">
                            <span><%=(Math.ceil(data.loaded/1024)) + "kb" %>/<%=(Math.ceil(data.total/1024)) + "kb"%></span>
                        </div>
                    </div>
                <% } %>
            </div>
        </script>

        <div class="form-group">

            <div class="uploader">
                <h4 style="margin:30px 0; text-decoration: underline;" class=""><?=Yii::t("main","Загрузить значения из Excel")?> <a style="margin-left:10px;" class="btn btn-default upload-button"><?=Yii::t("main","Прикрепить")?></a></h4>

                <input style="display:none" type="file" name="file" />
                <div class="uploaded-loader"></div>
                <div class="uploaded-list">

                </div>
            </div>

            <input class="form-control" type="text" name="excel_file" id="excel_file" value="" />

        </div>

        <div class="form-group">

            <input type="submit" class="btn btn-success" value="<?=Yii::t("main","Сохранить")?>" />

        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>

    </div>
</div>