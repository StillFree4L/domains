<?php $this->beginContent('//layouts/before_main'); ?>

<div id="body" class="middle">
    
    <div id="left_content">
        <div id="left_menu">
            <?php $this->widget("ext.CMenu.CMenu", array(
                    "group"=>"left_menu"
                ));
            ?>
        </div>
        <div class="clear"></div>
    </div>

    <div id="main_content" class="column2">
        <?php $this->widget('frontend.extensions.bootstrap.widgets.TbBreadcrumbs', array(
        'homeLink'=>"<a href='/'>".t('Главная')."</a>",
        'links'=>Yii::app()->breadCrumbs->getLinks(),
        )); ?>

        <?php echo $content; ?>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>

<!-- page -->
<?php $this->endContent(); ?>