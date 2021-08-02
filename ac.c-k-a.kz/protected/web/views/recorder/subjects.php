<?php

$this->addTitle(Yii::t("main","Список предметов"));

?>

<div class="container controller users-controller">

    <div class="action-content">

        <div class="pull-right">
            <a target="modal" href="<?=\glob\helpers\Common::createUrl("/subjects/add")?>" class="btn btn-success"><?=Yii::t("main","Добавить предмет")?></a>
        </div>

        <div class="page-header" style="margin-top:0;"><h3 class="text-info"><?=Yii::t("main","Предметы")?></h3></div>

        <div style="margin-top:30px;">
            <h4 style="margin:0; margin-bottom:15px;"><?=Yii::t("main","Фильтр")?></h4>
            <form method="get">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group" attribute="name">
                            <input class="form-control" type="text" name="filter[name]" placeholder="<?=$filter->getAttributeLabel("name")?>" value="<?=$filter->name?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group pull-right">
                            <input type="submit" class="btn btn-primary" value="<?=Yii::t("main","Показать")?>" />
                        </div>
                        <div class="form-group pull-right" style="margin-right:10px;">
                            <a class="btn btn-danger" href="<?=\glob\helpers\Common::createUrl("/recorder/subjects")?>"><?=Yii::t("main","Сбросить")?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <table class="subjects table table-bordered table-hover">

            <thead>
            <tr>
                <th><?=Yii::t("main","Название")?></th>
                <th><?=Yii::t("main","Преподаватель")?></th>
                <th><?=Yii::t("main","Кредиты")?></th>
                <th><?=Yii::t("main","Порядок в ведомости")?></th>
                <th><?=Yii::t("main","Гос")?></th>
                <th><?=Yii::t("main","Курсовая")?></th>
                <th><?=Yii::t("main","ГЭК")?></th>
            </tr>
            </thead>

            <tbody style="border-top:0;" class="subjects-body">
            <?php
                foreach ($subjects as $subject) {
                    /* @var $subject \glob\models\Dis */
                    ?>
                    <tr class='subjects-table-row'>
                        <td class="relative">
                            <?=$subject->dis?>
                            <a title="<?=Yii::t("main","Назначение групп/преподавателей")?>" target="modal" href='<?=\glob\helpers\Common::createUrl('/subjects/assign', ["id"=>$subject->id])?>' style='position:absolute; right:31px; top:1px;'  class='edit-order'><i class='fa fa-group'></i></a>
                            <a target="modal" href='<?=\glob\helpers\Common::createUrl('/subjects/add', ["id"=>$subject->id])?>' style='position:absolute; right:17px; top:1px;'  class='edit-order'><i class='fa fa-pencil'></i></a>
                            <a confirm='<?=Yii::t("main","Вы уверены? Все назначенные тесты и вопросы будут утеряны")?>' href='<?=\glob\helpers\Common::createUrl('/subjects/delete', ["id"=>$subject->id])?>' style='position:absolute; right:3px; top:1px;'  class='text-danger edit-order'><i class='fa fa-times'></i></a>
                        </td>
                        <td class="relative"><?=$subject->tea?></td>
                        <td class="relative"><?=$subject->credits?></td>
                        <td class="relative"><?=$subject->position?></td>
                        <td class="relative"><?=$subject->gos ? "<li class='text-success fa fa-check'></li>" : "<li class='text-danger fa fa-times'></li>" ?></td>
                        <td class="relative"><?=$subject->kurs ? "<li class='text-success fa fa-check'></li>" : "<li class='text-danger fa fa-times'></li>" ?></td>
                        <td class="relative"><?=$subject->gek ? "<li class='text-success fa fa-check'></li>" : "<li class='text-danger fa fa-times'></li>" ?></td>
                    </tr>
                    <?php
                }
            ?>
            </tbody>

        </table>

        <?= yii\widgets\LinkPager::widget([
        'pagination' => $pagination,
        ]) ?>

    </div>

</div>