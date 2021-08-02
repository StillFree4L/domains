<?php

$this->addTitle(Yii::t("main","Список параметров"));

?>

<div class="container controller users-controller">

    <div class="action-content">

        <div class="page-header" style="margin-top:0;"><h2 class="text-info"><?=Yii::t("main","Параметры")?></h2></div>

        <script type="text/template" id="option_template">
            <div class="option-item">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <% if (data.model.get("id")) { %>
                                <p style="padding:12px 10px 12px 0;" class="text-right"><%=data.model.get("name")%> = </p>
                            <% } else { %>
                                <input type="text" class="form-control name-input" name="name" value="<%=data.model.get("name")%>" placeholder="<?=Yii::t("main","Уникальное имя")?>" />
                            <% } %>
                        </div>
                    </div>
                    <div class="col-xs-5">
                        <input type="text" class="form-control value-input" name="value" value='<%=data.model.get("value")%>' placeholder="<?=Yii::t("main","Значение")?>" />
                    </div>
                    <div class="col-xs-1 ">
                        <a class="btn btn-info commit" style="<%=data.model.get("id") ? "display:none;" : ""%>"><i class="fa fa-pencil"></i></a>
                    </div>

                </div>
            </div>
        </script>

        <div class="options-list">



        </div>

        <a style="margin-top:15px;" class="btn btn-primary add-option"><?=Yii::t("main","Добавить параметр")?></a>

    </div>

</div>