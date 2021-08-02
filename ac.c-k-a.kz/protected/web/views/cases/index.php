<?php
\app\bundles\ToolsBundle::registerChosen($this);
$this->setTitle(Yii::t("main","Кейсы"), false);

echo \app\widgets\EUploader\EUploader::widget([
    "standalone"=>false
]);

?>

<div class="controller container">
    <div class="action-content">

        <div class="row">
            <!--<div class="col-xs-8">
                <div class="form-group">
                    <? /*\app\helpers\Html::dropDownList("subject_id", $filter["subject_id"], \yii\helpers\ArrayHelper::map($subjects, "id", "dis") , [
                        "class"=>"form-control chosen-list",
                        "empty"=>Yii::t("main","Предмет")
                    ]);*/ ?>
                </div>
            </div>-->

            <div class="col-xs-4">
                <div class="form-group">
                    <?=\app\helpers\Html::dropDownList("type", $filter["type"], $types , [
                        "class"=>"form-control chosen-list",
                        "empty"=>Yii::t("main","Тип")
                    ]);?>
                </div>
            </div>
        </div>

        <?php
        /* if ($teachers) {
            ?>
            <div class="form-group">
                <?=\app\helpers\Html::dropDownList("teacher_id", $filter["teacher_id"], \yii\helpers\ArrayHelper::map($teachers, "id", "fio") , [
                    "class"=>"form-control chosen-list",
                    "empty"=>Yii::t("main","Преподаватель")
                ]);?>
            </div>
            <?php
        } */
        ?>

        <?php if (!empty($filter['type'])) { ?>

        <ul class="nav nav-tabs" style="margin-top:15px;">
            <?php
            for ($i = 1; $i <= 8; $i++) {
                ?>
                <li role="presentation" class="<?=$i==$filter['s'] ? "active" : ""?>"><a href="<?=\glob\helpers\Common::createUrl("/cases/index", ["filter"=>array_merge($filter, ["s"=>$i])])?>"><?=Yii::t("main","{s} семестр", ["s"=>$i])?></a></li>
            <?php
            }

            ?>
        </ul>

        <?php } ?>

        <?php if (!empty($filter['type']) AND !empty($filter['s'])) { ?>

        <div class="files-table" style="margin-top:15px;">

            <div class="upload-case">

                <div style="" class="add-case"><a class="upload-button btn btn btn-danger"><?=\Yii::t("main","Добавить ".$types[$filter['type']])?></a></div>
                <script id="upload_case_template" type="text/template">
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
                <input style="display:none" type="file" name="case_file" />
                <input style="display:none;" type='text' name="logo" id="logo" value='<?=json_encode($profile->logo)?>' />
                <div class="uploaded-case-container">

                </div>
            </div>

            <script id="uploaded_case_template" type="text/template">
                <div class='uploaded-file'>
                    <div class="form-group">
                        <h3 class="relative">
                            <a target="_blank" style="cursor:pointer;" href="<%=data.model.get("infoJson").url%>"><%=data.model.get("infoJson").name%></a>
                            <% if (data.model.get("teacher_id") == <?=Yii::$app->user->identity->profile->id ? Yii::$app->user->identity->profile->id : 0?> || '<?=Yii::$app->user->can(\glob\models\Users::ROLE_ADMIN)?>' == '1') { %>
                            <a title="<?=Yii::t("main","Назначение групп/преподавателей")?>" target="modal" href='<%=Yii.app.createUrl('/cases/assign', {"id":data.model.get("id")})%>' style='font-size:28px; position:absolute; right:36px; top:3px;'  class='edit-order'><i class='fa fa-group'></i></a>
                            <a style="font-size:66px;" class="close">&times;</a>
                            <% } %>
                        </h3>
                    </div>
                </div>
            </script>

            <div class="cases-list">
            </div>

        </div>

        <?php } ?>

    </div>
</div>