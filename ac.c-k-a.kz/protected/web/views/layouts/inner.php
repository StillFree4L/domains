<?php if (!Yii::$app->request->isAjax) { $this->beginContent('@app/views/layouts/index.php'); ?> <?php } ?>

    <?php if (!$this->context->isModal) { ?>

        <div style="margin:0; display:flex; align-items:stretch;">
            <div class="main-menu col-xs-1" >
                <?php
                echo $this->render("@app/views/layouts/left_menu.php");
                ?>
            </div>
            <div class="main-content col-xs-10">

                <div class="container hidden-print">
                    <div class="clearfix">
                        <ol class="breadcrumb" id="main_breadcrumbs" style="margin:15px 0;">
                            <?php
                            foreach (Yii::$app->breadCrumbs->getLinks() as $l) {
                                ?><li <?=\yii\helpers\Html::renderTagAttributes($l['options'])?>>
                                <?php if ($l['url']) { ?>
                                    <a href='<?=$l['url']?>'><?=$l['header']?></a>
                                <?php } else { ?>
                                    <span class="text-muted"><?=$l['header']?></span>
                                <?php } ?>
                                </li><?php
                            }
                            ?>
                        </ol>
                    </div>
                </div>

                <?= $content ?>
            </div>
        </div>
    <?php } else { ?>
        <?= $content ?>
    <?php } ?>

<?php if (!Yii::$app->request->isAjax) $this->endContent(); ?>