<?php $this->beginContent('//layouts/main'); ?>

<div id="main_bg">  
<div id="page">
    
    
        
        <div id="header">

            <span class="header_site_name st">
                    <?=t(Yii::app()->baseOptions->system_name)?>
                </span>

            <a href="/<?=Yii::app()->language?>" id="site_logo_<?=Yii::app()->language?>" class="site_logo"></a>
            
            <div id="header_bg" class="bg"></div>

            <div id="header_content" class="cc">

                

            <div class="search_and_lang span pull-right st">
                <div class="search_and_lang_bg bg"></div>
                <div class="search_and_lang_content cc">  
                <div class="auth">

                <?php
                    if (isset(Yii::app()->user->id))
                    {
                        ?>
                            <span class="username"><?=Yii::app()->user->fio?></span> (<a class="logout" href="/admin/authentication/logout"><?=t('Выйти')?></a>)
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
                                "action"=>"/".Yii::app()->language."/search",
                                "type"=>"search",
                                "id"=>"searchForm",
                            ));

                    echo $form->textFieldRow(Instances::model(), 'search_string', array('style'=>'width:130px; margin-right:3px;', 'class'=>'input-medium', 'prepend'=>'<i class="icon-search"></i>'));
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
            </div>
            
            
        </div>

        <div id="femida_menus">
                <div id="femida_menus_bg" class="bg"></div>
                <div id="femida"></div>
                <div id="femida_menus_content" class="cc">
                <?php $this->widget("ext.CMenu.CMenu", array(
                    "group"=>"main_menu"
                ));
                ?>

                <?php 
                $this->widget("ext.CMenu.CMenu", array(
                    "group"=>"main_menu_right"
                ));
                ?>
                </div>
            </div>     
        
        
        <div>
            <?=$content?>     
            <div style="clear:both;"></div>
        </div>
        
        <div id="bottom_banners">
            
                <div id="bottom_banners_bg" class="bg"></div>
                <div id="bottom_banners_content" class="cc">

                    <div class="bottom_banners">

                        <?php
                        $banners = Instances::model()->byLabel("employees")->find();
                        $this->widget("ext.CInstance.CInstance",array(
                            "model"=>$banners,
                            "limit"=>999,
                        ));
                        ?>

                    </div>

                </div>
        </div>

        <div id="footer">
            <div id="footer_bg" class="bg"></div>
            <div id="footer_content" class="cc">
                <div id="femida_footer"></div>

                <div class="footer_c st">
                    <span>© 2012</span>
                    <?=t(Yii::app()->baseOptions->system_name)?>
                    <br>
                    <span>При использовании материалов ссылка на источник обязательна</span>
                </div>

            </div>
        </div>
    
</div>
</div>

<?php $this->endContent(); ?>