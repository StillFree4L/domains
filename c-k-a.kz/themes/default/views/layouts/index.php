<?php $this->beginContent('//layouts/before_main'); ?>

<div id="body">

        <div id="body_bg" class="bg"></div>
        <div id="body_content" class="cc">
            

            <div id="left_content">
                <div id="left_menu">
                    <?php $this->widget("ext.CMenu.CMenu", array(
                            "group"=>"left_menu"
                        ));
                    ?>
                </div>

                <?php
                    if (Yii::app()->plugins->survey != null)
                    {
                        ?>
                    <div class="surveys st">
                        <div class="surveys_bg bg"></div>
                        <div class="surveys_content cc">

                        <?php
                        $this->widget("application.plugins.survey.survey", array(
                        ));
                        ?>
                        </div>
                    </div>
                        <?php

                    }
                ?>
                
                <div class="clear"></div>
            </div>

            <div id="main_content" class="column2">
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