<div class="lastThemes lthemes">
    <span class="themes_header"><?=$this->label?></span>
<?php
if (!empty($themes))
{
    foreach ($themes as $theme)
    {
        ?>
            <div class="ltheme">
                <div class="ltheme_m">
                    <?php
        
                        if ($theme->state == "2")
                        {
                            $title = t("Тема закрыта");
                            $icon = "2";
                        } else {
                            $icon = "1";

                            if (Yii::app()->user->id) {

                                $title = t("Нет непрочитанных сообщений");

                                if (PForumThemeViews::model()->exists("theme_id = :tid AND user_id = :uid AND ts >= :ts",array(
                                    ":tid"=>$theme->id,
                                    ":uid"=>Yii::app()->user->id,
                                    ":ts"=>$theme->pForumPosts[0]->ts
                                ))) {
                                    $title = t("Непрочитанные сообщения");
                                    $icon = "4";
                                }

                            }                

                        }

                    ?>
                    <span rel="tooltip" title="<?=$title?>" class="theme_icon icon_<?=$icon?>"></span>
                    <div style="clear:both;"></div>
                    <span class="ltheme_m">
                        <?=$theme->pPostCount?>
                    </span>                    
                </div>
                <div class="ltheme_r">
                    <div>
                        <a href="<?="/".Yii::app()->language."/forum/view/eid/".$theme->id?>" class="ltheme_r"><?=$theme->name?></a>
                        <a href="<?="/".Yii::app()->language."/forum/index/cat/".$theme->category_id?>" class="ltheme_r_cat"><?=$theme->category->name?></a>                        
                    </div>                    
                </div>
                <div style="clear:both;"></div>
            </div>
        <?php
    }
}
?>
</div>
