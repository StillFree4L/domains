<?php $this->beginContent('//layouts/before_main'); ?>

<div id="body">

        <div id="body_bg" class="bg"></div>
        <div id="body_content" class="cc">
            

            <div id="main_content" class="column1">
                <div id="main_content_bg" class="bg"></div>
                <div id="main_content_inner" class="cc">
                    <?php /* $this->widget('frontend.extensions.bootstrap.widgets.TbBreadcrumbs', array(
                    'homeLink'=>"<a href='/'>".t('Главная')."</a>",
                    'links'=>Yii::app()->breadCrumbs->getLinks(),
                    )); */ ?>
                    <div id="page_body">
                        <?php echo $content; ?>
                    </div>
                <div class="clear"></div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    
</div>

<!-- page -->
<?php $this->endContent(); ?>