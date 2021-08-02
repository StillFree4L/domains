<?php $this->beginContent('//layouts/before_main'); ?>

<div id="body">

        <div id="body_bg" class="bg"></div>
        <div id="body_content" class="cc">
            

            <div id="left_content">
                <div id="left_menu">
                    <?php $this->widget("ext.MyMenu.MyMenu", array(
                            "group"=>"faks",
                            "label"=>true
                        ));
                    ?>
                    <?php $this->widget("ext.MyMenu.MyMenu", array(
                            "group"=>"left_menu"
                        ));
                    ?>
                </div>

                <?php
                    if (Yii::app()->plugins->survey != null)
                    {

                        $surveys = $this->widget("application.plugins.survey.survey", array(
                        ), true);

                        if (!empty($surveys)) {
                            ?>
                            <div class="surveys st">
                                <div class="surveys_bg bg"></div>
                                <div class="surveys_content cc">

                                    <?php
                                    echo $surveys
                                    ?>
                                </div>
                            </div>
                        <?php
                        }
                    }
                ?>
                
                <div class="clear"></div>
            </div>

            <div id="main_content" class="column2">
                <div id="main_content_bg" class="bg"></div>
                <div id="main_content_inner" class="cc">
                    <?php $this->widget('frontend.extensions.bootstrap.widgets.TbBreadcrumbs', array(
                    'homeLink'=>"<a href='/'>".t('Главная')."</a>",
                    'links'=>Yii::app()->breadCrumbs->getLinks(),
                    ));  ?>
                    <div id="page_body">
                        <?php echo $content; ?>

                        <div style="clear:both"></div>
                    </div>

                    <?php
                    /*
                        if (Yii::app()->plugins->forum != null AND Yii::app()->controller->id != "forum")
                        {
                        ?>
                            <div class="forum_last_messages">

                                    <?php $this->widget("forum.ForumLastThemes", array("label"=>t("Новые темы на форуме"))); ?>
                                    <?php $this->widget("forum.ForumHotThemes", array("label"=>t("Обсуждаемые темы на форуме"))); ?>
                                <div style="clear:both"></div>
                            </div>
                        <?php
                        } */
                        ?>


                <div style="clear:both"></div>
                </div>
            </div>

            <div style="clear:both"></div>
        </div>
    
</div>

<!-- page -->
<?php $this->endContent(); ?>