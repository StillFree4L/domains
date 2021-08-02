<?php $this->beginContent('//layouts/main'); ?>

<div id="page">
    <div id="wrapper">
        <div id="header" class="middle">

            <div class="pull-left">
            <a href="/" class="logo span">

                <span class="site_name system_font_color">
                    <?=t(Yii::app()->baseOptions->system_name)?>
                </span>                

            </a>
            
            </div>
            
            

            <div class="search_and_lang span pull-right">

                <div class="auth">

                <?php
                    if (isset(Yii::app()->user->id))
                    {
                        ?>
                            <span class="username"><?=Yii::app()->user->fio?></span>(<a class="logout" href="/admin/authentication/logout"><?=t('Выйти')?></a>)
                        <?php
                    } else {
                        ?>
                            <a class="logout" href="/admin/authentication/login"><?=t('Войти')?></a> / <a class="logout" href="/admin/authentication/registration"><?=t('Регистрация')?></a>
                        <?php
                    }
                ?>

                </div>
                
                <div class="search">
                    <?php
                    $form = $this->beginWidget("bootstrap.widgets.TbActiveForm",
                            array(
                                "type"=>"search",
                                "id"=>"searchForm",
                            ));

                    echo $form->textFieldRow(Instances::model(), 'search_string', array('style'=>'margin-right:3px;', 'class'=>'input-medium', 'prepend'=>'<i class="icon-search"></i>'));
                    $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>t('Найти')));

                    $this->endWidget();
                    ?>
                </div>

                <div class="lang_sitemap">

                    <div class="lang span" style="margin-left:0;">
                        <?php
                            $this->widget("ext.ELanguagePicker.ELanguagePicker", array("type"=>"inline"));
                        ?>
                    </div>

                    <div class="map span pull-right">
                        <?php

                            // Home icon
                            $imghtml=CHtml::image(Yii::app()->theme->getBaseUrl()."/images/home.png");
                            echo CHtml::link($imghtml, "/", array("class"=>"map_link"));

                            $imghtml=CHtml::image(Yii::app()->theme->getBaseUrl()."/images/message.png");
                            echo CHtml::link($imghtml, "mailto:".Yii::app()->baseOptions->admin_mail."?subject=Feedback", array("class"=>"map_link"));

                            $imghtml=CHtml::image(Yii::app()->theme->getBaseUrl()."/images/map.png");
                            echo CHtml::link($imghtml, "/site_map", array("class"=>"map_link"));

                        ?>
                    </div>

                </div>
                
                

            </div>


        </div>
        <!-- header -->
        <div id="menu_content">
            <?php $this->widget("ext.CMenu.CMenu", array(
                "group"=>"main_menu"
            ));
            ?>
        </div>

       
        <?php echo $content; ?>
       
        <div id="bottom_banners">
            <div class="bottom_banners middle">

                <?php
                $banners = Instances::model()->byLabel("banners")->find();
                $this->widget("ext.CInstance.CInstance",array(
                    "model"=>$banners,
                    "limit"=>999,
                ));
                ?>

            </div>
        </div>

        <div id="footer">

            <div>
                <span>© 2012</span>
                <a href="http://cit-orleu.kz">ТОО "Центр инновацонных технологий "Өрлеу"</a>
                <br>
                <span>При использовании материалов ссылка на источник обязательна</span>
            </div>

        </div>
    <!-- footer -->
    </div>
</div>
<!-- page -->
<?php $this->endContent(); ?>