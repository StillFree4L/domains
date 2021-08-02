<?php

\app\bundles\BackboneBundle::registerWidget($this, "EDisplayDate");
$this->addTitle(Yii::t("main","Список сотрудников"));

?>

<div class="container controller users-controller">

    <div class="action-content">

        <div class="pull-right">
            <a href="<?=\glob\helpers\Common::createUrl("/users/add")?>" class="btn btn-success"><?=Yii::t("main","Добавить сотрудника")?></a>
        </div>

        <div class="page-header" style="margin-top:0;"><h2 class="text-info"><?=Yii::t("main","Сотрудники")?></h2></div>

        <div style="margin-top:30px;">
            <h3 style="margin:0; margin-bottom:15px;"><?=Yii::t("main","Фильтр")?></h3>
            <form method="get">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group" attribute="fio">
                            <input class="form-control" type="text" name="filter[fio]" placeholder="<?=$filter->getAttributeLabel("fio")?>" value="<?=$filter->fio?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group pull-right">
                            <input type="submit" class="btn btn-primary" value="<?=Yii::t("main","Показать")?>" />
                        </div>
                        <div class="form-group pull-right" style="margin-right:10px;">
                            <a class="btn btn-danger" href="<?=\glob\helpers\Common::createUrl("/users/index")?>"><?=Yii::t("main","Сбросить")?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script type="text/template" id="users_row_template">
            <% var bg = ""; if (data.state == <?=\glob\components\ActiveRecord::DELETED?>) bg = "bg-danger"; %>
            <tr class='users-table-row'>
                <td class='<%=bg%>'><%=data.fio%></td>
                <td class='relative <%=bg%>'><%=date('d.m.Y',data.ts)%>
                    <% if (data.state != <?=\glob\components\ActiveRecord::DELETED?>) { %>
                    <a href='<%=Yii.app.createUrl('/employees/add', {"id":data.id})%>' style='position:absolute; right:17px; top:1px;'  class='edit-order'><i class='fa fa-pencil'></i></a>
                    <a confirm='<?=Yii::t("main","Вы уверены?")?>' href='<%=Yii.app.createUrl('/employees/delete', {"id":data.id})%>' style='position:absolute; right:3px; top:1px;'  class='text-danger edit-order'><i class='fa fa-times'></i></a>
                    <% } %>
                </td>
            </tr>
        </script>

        <table class="users table table-bordered table-hover">

            <thead>
            <tr>
                <th><?=Yii::t("main","ФИО")?></th>
                <th><?=Yii::t("main","Дата создания")?></th>
            </tr>
            </thead>

            <tbody style="border-top:0;" class="users-body">

            </tbody>

        </table>

        <?= yii\widgets\LinkPager::widget([
        'pagination' => $pagination,
        ]) ?>

    </div>

</div>