<?php $this->beginContent('//layouts/main'); ?>

<div id="main_bg">  
<div id="page">
    
    
        
        <div id="header_new">

            <div class="header_site_name">
                <div class="header_site_name_bg bg"></div>
                <div class="header_site_name_content cc">
                    <?=t(Yii::app()->baseOptions->system_name)?>
                </div>
                </div>

            <a style="top:auto; bottom:-33px;" href="/<?=Yii::app()->language?>" id="site_logo_<?=Yii::app()->language?>" class="site_logo"></a>
            
            <div id="header_new_bg" class="bg"></div>

            <div id="header_content" class="cc">

                <div class="search_and_lang span pull-right st" style="position:relative; z-index:1;">
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
                                    "action"=>"/".Yii::app()->language."/page/search",
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
                                echo CHtml::link($imghtml, "/page/site_map", array("class"=>"map_link"));

                            ?>
                        </div>

                    </div>


                    </div>
                </div>

                <?php
                    $c = Instances::model()->byLabel("news_feed")->find();
                    $ids = array_keys(InstanceRelations::model()->findAll([
                        "condition" => "p_id = :rid",
                        "params" => [
                            ":rid" => $c->id
                        ],
                        "select" => "r_id",
                        "index" => "r_id"
                    ]));
                    $cr = new CDbCriteria();
                    $cr->addInCondition("id", $ids);
                    $cr->order = "id DESC";
                    $news = Instances::model()->find($cr);

                ?>

                <div class="clearfix"></div>
                <div class="news-feed" style="z-index:0; margin:10px auto 90px auto; display: none; position:relative; width:980px; padding:10px; border: 1px solid #355565; box-shadow: 0px 1px 5px #666;">
                    <div id="main_content_bg" class="bg"></div>
                    <div class="cc">
                        <div class="instance_record">
                            <a href="<?=$news->getLink()?>" class="instance_caption text_color">
                                <?=$news->caption?>
                            </a>
                        <span class="instance_preview">
                            <?=$news->preview?>
                            <div style="clear:both;"></div>
                        </span>
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <script language="javascript">
                    $(function() {
                        $(".news-feed").find("span.instance_caption").remove();
                        $(".news-feed").find(".instance_separator").remove();
                        $(".news-feed").find("img").css({
                            width : "auto",
                            height : "auto",
                            maxWidth:"370px",
                            maxHeight : "280px",
                            width : "50%"
                        }).addClass("pull-right");
                        $(".news-feed").find(".instance_record").prepend($(".news-feed").find("img"));
                        $(".news-feed").find(".instance_preview").css({
                            margin : 0
                        });
                        $(".news-feed").find(".instance_preview").prepend($(".news-feed").find(".instance_caption")).addClass("pull-left");
                        $(".news-feed").find(".instance_preview").css({
                            width:"50%"
                        });
                        $(".news-feed").find(".instance_preview").find("p").css({
                            maxWidth:"370px"
                        });
                        $(".news-feed").find(".instance_caption").css({
                            paddingRight : 0
                        });
                        $(".news-feed").show();
                        $(".news-feed").find(".instance_record").append("<div class='clearfix'></div>");
                        $(".news-feed").find(".instance_record").css({
                            marginTop : 0
                        });
                        $(".news-feed").find(".instance_ts").hide();
                    })
                </script>

            </div>
            
            
        </div>

        <div id="femida_menus">
                <div id="femida_menus_bg" class="bg"></div>
                <div id="femida"></div>
                <div id="femida_menus_content" class="cc">
                <?php $this->widget("frontend.extensions.MyMenu.MyMenu", array(
                    "group"=>"main_menu"
                ));
                ?>

                <?php 
                $this->widget("frontend.extensions.MyMenu.MyMenu", array(
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
                        $banners = Instances::model()->byLabel("employeers_0")->find();
                        $this->widget("ext.CInstance.CInstance",array(
                            "model"=>$banners,
                            "limit"=>999,
                            "template"=>"employees"
                        ));
                        ?>

                    </div>
                    
                    <div class="bottom_banners">

                        <?php
                        $banners = Instances::model()->byLabel("employeers_1")->find();
                        $this->widget("ext.CInstance.CInstance",array(
                            "model"=>$banners,
                            "limit"=>999,
                            "template"=>"employees"
                        ));
                        ?>

                    </div>
                    
                    <div class="bottom_banners">

                        <?php
                        $banners = Instances::model()->byLabel("employeers_2")->find();
                        $this->widget("ext.CInstance.CInstance",array(
                            "model"=>$banners,
                            "limit"=>999,
                            "template"=>"employees"
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
<link rel="stylesheet" href="https://help.edu.kz/static/widget/app.css">
<vue-widget domain="https://help.edu.kz"></vue-widget>
<script defer src="https://help.edu.kz/static/widget/app.js"></script>
<?php $this->endContent(); ?>