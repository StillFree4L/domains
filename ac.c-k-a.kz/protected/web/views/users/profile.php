<?php
$this->addTitle(Yii::t("main","Редактирование профиля"));
?>
<div class="controller container platform-controller">
    <div class="action-content">

        <?php
        $f = \app\widgets\EForm\EForm::begin([
            "htmlOptions"=>[
                "action"=>\glob\helpers\Common::createUrl("/users/profile", \Yii::$app->request->get(null, [])),
                "method"=>"post",
                "id"=>"newProfileForm"
            ],
        ]);
        ?>

        <div style="margin-top:0;" class="page-header"><h3 class="text-info"><?=\Yii::t("main","Основные данные")?></h3></div>

        <?php
        echo \app\widgets\EUploader\EUploader::widget([
            "standalone"=>false
        ]);
        echo \app\widgets\ECropper\ECropper::widget([
        ]);
        ?>
        <div>

            <div class="pull-left" style="width:150px;">
                <div class="user-avatar">
                    <div class="image">
                        <script id="profile_uploaded_image_template" type="text/template">
                            <div class='uploaded-image'>
                                <% if (!data.error && data.percent>0 && data.percent < 100) { %>
                                    <div style='margin-top:5px; margin-bottom:5px;' class="progress progress-striped">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <%=data.percent%>%">
                                            <span><%=(Math.ceil(data.loaded/1024)) + "kb" %>/<%=(Math.ceil(data.total/1024)) + "kb"%></span>
                                        </div>
                                    </div>
                                <% } %>
                                <% if (data.response) { %>
                                    <img src="<%=data.response.preview%>" />
                                <% } %>
                            </div>

                        </script>
                        <div class="image-placeholder"><?=\app\helpers\Html::userImg($profile->logoPreview, [
                                'preview'=>'preview'
                            ])?></div>
                        <div style="margin-top:-15px;" class="edit-avatar text-center"><a class="upload-button btn btn-sm btn-danger"><?=\Yii::t("main","Изменить лого")?></a></div>
                        <input style="display:none" type="file" name="avatar" />
                </div>
                </div>
                <input style="display:none;" type='text' name="logo" id="logo" value='<?=json_encode($profile->logo)?>' />
            </div>

            <div style="margin-left:160px;">
                <div class="form-group" attribute="fio">

                    <label for="caption" class="control-label"><?=\Yii::t("main","ФИО")?></label>

                    <input class="form-control" type="text" placeholder="<?=\Yii::t("main","ФИО")?>" id="caption" value="<?=$profile->fio?>" name="fio" />

                </div>
            </div>

            <div class="clearfix"></div>

        </div>

        <div style="margin-top:0;" class="page-header"><h3 class="text-info"><?=\Yii::t("main","Пароль")?></h3></div>

        <div class="form-group" attribute="password">

            <label for="password" class="control-label"><?=Yii::t("main","Пароль")?></label>

            <input class="form-control" type="password" placeholder="<?=Yii::t("main","пароль")?>" id="password" name="password" />

        </div>
        <div class="form-group" attribute="repassword">

            <label for="repassword" class="control-label"><?=Yii::t("main","Повторите пароль")?></label>

            <input class="form-control" type="password" placeholder="<?=Yii::t("main","повторите пароль")?>" id="repassword" name="repassword" />

        </div>

        <div class="clearfix"></div>

        <div class="form-group">

            <input type="submit" class="btn btn-success" value="<?=\Yii::t("main","Сохранить")?>" />

        </div>

        <?php \app\widgets\EForm\EForm::end(); ?>

    </div>
</div>