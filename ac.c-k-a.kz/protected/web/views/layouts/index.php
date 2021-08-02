<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <link href='http://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>

        <?php

        \app\bundles\JSTransBundle::register($this);
        \app\bundles\ToolsBundle::registerJgrowl($this);
        \app\bundles\BaseBundle::register($this);
        \app\bundles\FontAwesomeBundle::register($this);
        $this->registerJs("// Some php vars
                BACKBONE_ASSETS = '".Yii::$app->assetManager->getBundle("backbone")->baseUrl."';
                BASE_ASSETS = '".Yii::$app->assetManager->getBundle("base")->baseUrl."';
                DEBUG = ".intval(YII_DEBUG).";
                URL_ROOT = '';
                POLL_URL = '".POLL_URL."';
                TRACKING_CODE = false;
            ", View::POS_HEAD, 'constants');

        $ccontroller = ucfirst($this->context->id)."Controller";
        $this->registerJsFile(Yii::$app->assetManager->getBundle("backbone")->baseUrl."/controllers/{$ccontroller}.js", [
            'depends' => [\app\bundles\BackboneBundle::className()],
            'position'=> View::POS_HEAD
        ]);

        $this->head()

        ?>

    </head>
    <body data-spy="scroll">
    <div class="main-el">
        <?php $this->beginBody() ?>

        <div id="block_land"><?=Yii::t("main","Поверните ваше устройство в альбомный режим")?></div>

        <div class="loading_body">
            <div class="loading-container">
                <div class="loading"></div>
                <div id="loading-text"><?=Yii::t("main","Загрузка")?></div>
            </div>
        </div>

        <?php
        $headers = [
            'main' => 'main',
            'inner' => 'inner',
            "auth" => "auth"
        ]
        ?>

        <div class='wrapper'>

            <div class='header-main hidden-print'>
                <?=$this->render("//layouts/".(in_array($this->context->layout, $headers) ? $headers[$this->context->layout]."_header" : "inner_header"))?>
            </div>

            <div class="inner-el" style="margin-top:-55px;">

                <div class="body clearfix">

                    <div class="controller-content">
                        <?php
                        echo $content;
                        ?>
                    </div>

                    <div class="clearfix"></div>

                </div>

            </div>

        </div>

        <?= Yii::$app->user->isGuest ? "" : \app\widgets\EChat\EChat::widget(); ?>

        <?=$this->render("//layouts/main_footer")?>

    </div>

    <script type="text/template" id="controller_modal_template">
        <div class="modal" id="controller_modal" data-animation="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="controller_modal" aria-hidden="true">
            <div class="modal-dialog <%= data.size ? "modal-" + data.size : "" %>">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><%= data.pageTitle %></h4>
                    </div>
                    <div class="modal-body">
                        <%= html %>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/template" id="controller_template">
        <div class='controller-content'>
            <%= html %>
        </div>
    </script>

    <div class="remove_after_load">
        <script language="javascript">
            $(function() {

                <?php
                $p = $_GET;
                if (isset($p['z'])) {
                    $murl = $p['z'];
                }
                unset($p['z']);
                $baseUrl = Url::to(\yii\helpers\ArrayHelper::merge(["/".$this->context->id."/".$this->context->action->id], $p));

                ?>


                Yii.app = _.extend(new BaseApplication({
                    el : $(".main-el"),
                    innerEl : $(".inner-el"),
                    controllerEl : ".controller-content"
                }), Yii.app);

                // Init current controller
                Yii.app.user = <?=json_encode(Yii::$app->user->identity ? Yii::$app->user->identity->backboneArray() : [])?>;
                <?php if (Yii::$app->params['in_test']) { ?>
                    Yii.app.user.in_test = 1;
                <?php } ?>
                Yii.app.currentController = new <?=$ccontroller?>({
                    el : $(Yii.app.el).find(".controller-content"),
                    loaded : true,
                    <?=Yii::$app->data->isModal ? "noState : true," : ""?>
                    url : '<?=$baseUrl?>',
                    name : '<?=$ccontroller?>',
                    model : <?=json_encode(Yii::$app->response->getModelData())?>
                });
                Yii.app.currentController.load(function(state) {
                    if (state == 1) {
                        Yii.app.pushState(this);
                    }
                    if (state == 2)
                    {
                        if (!Yii.app.currentController.noState) {
                            Yii.app.replaceState(this);
                        }
                    }
                });
                Yii.app.render();
            });
        </script>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>