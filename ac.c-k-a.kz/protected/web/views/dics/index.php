<?php

$this->addTitle(Yii::t("main","Список словарей"));

?>

<div class="container controller users-controller">

    <div class="action-content">

        <div class="pull-right">
            <a target="modal" href="<?=\glob\helpers\Common::createUrl("/dics/add")?>" class="btn btn-success"><?=Yii::t("main","Добавить справочник")?></a>
        </div>

        <div class="page-header" style="margin-top:0;"><h2 class="text-info"><?=Yii::t("main","Справочники")?></h2></div>

        <div class="form-group" style="margin-top:30px;">
            <div class="row">
                <div class="col-xs-4" style="padding-right:3px;">
                    <input placeholder="<?=Yii::t("main","Поиск словаря")?>" type="text" value="<?=$filter->s?>" class="find-input form-control autocomplete" autocomplete-attribute="info" />
                </div>
                <div class="col-xs-1" style="padding-left:0;">
                    <a class="find-button btn btn-default"><?=Yii::t("main","Найти")?></a>
                </div>
            </div>
        </div>

        <div class="row">

            <?php if (!empty($dics)) {
                foreach ($dics as $dic) {
                ?>
                    <div class="col-xs-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?=$dic->name?>
                                <div class="pull-right">
                                    <a target="modal" href="<?=\glob\helpers\Common::createUrl("/dics/addv", ["did"=>$dic->id])?>" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>
                                    <a target="modal" href="<?=\glob\helpers\Common::createUrl("/dics/add", ["id"=>$dic->id])?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                                    <a confirm="<?=Yii::t("main","Вы уверены? Данный словарь может использоваться в системе и его удаление приведет к фатальным последствиям")?>" target="modal" href="<?=\glob\helpers\Common::createUrl("/dics/delete", ["id"=>$dic->id])?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>

                            <?php if ($dic->description) { ?>
                            <div class="panel-body">
                                <span class="text-muted"><?=$dic->description?></span>
                            </div>
                            <?php } ?>

                            <ul class="list-group">
                            <?php if (!empty($dic->values)) {
                                foreach ($dic->values as $value) {
                                ?>
                                    <li class="list-group-item"><?=$value->name?>
                                        <div class="pull-right">
                                            <a target="modal" href="<?=\glob\helpers\Common::createUrl("/dics/addv", ["id"=>$value->id])?>" class="btn-link text-info"><i class="fa fa-pencil"></i></a>
                                            <a confirm="<?=Yii::t("main","Вы уверены? Данное значение может использоваться в системе и его удаление приведет к фатальным последствиям")?>"  target="modal" href="<?=\glob\helpers\Common::createUrl("/dics/deletev", ["id"=>$value->id])?>" class="btn-link text-danger"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </li>
                                <?php
                                }
                            } ?>
                            </ul>

                        </div>
                    </div>
                <?php
                }
            } ?>

        </div>

    </div>

</div>