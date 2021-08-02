<?php $this->beginContent('//layouts/before_main'); ?>

<div class="middle" style="padding-left:30px; padding-top:10px;">

    <?php $this->widget('frontend.extensions.bootstrap.widgets.TbBreadcrumbs', array(
        'homeLink'=>"<a href='/'>".t('Главная')."</a>",
        'links'=>Yii::app()->breadCrumbs->getLinks(),
        )); ?>

</div>

<div id="body" class="middle">

    

    <div id="main_content">        

        <?php echo $content; ?>
    </div>

    <div class="clear"></div>
</div>

<!-- page -->
<?php $this->endContent(); ?>