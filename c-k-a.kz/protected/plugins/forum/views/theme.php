<tr class="theme">
    <td class="theme_icon">
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
    </td>
    <td class="theme_name">
        <div class="theme_list_name">
        <a href="<?="/".Yii::app()->language."/".Yii::app()->controller->id."/view/eid/".$theme->id?>" class="category_caption"><?=$theme->name?></a>
        <div class="forum_pager_inline">
            <?php
            $this->widget("application.extensions.Pagination.Pagination", array(
                "model"=>PForumPosts::model(),
                "criteria"=>array("condition"=>"theme_id = :tid","params"=>array(":tid"=>$theme->id)),
                "perPage"=>$this->limit,                
                "url"=>"/".Yii::app()->language."/".Yii::app()->controller->id."/view/eid/".$theme->id,
                "show_pages"=>2
            ))
            ?>
        </div>
        </div>
    </td>
    <td colspan="1" class="category_themes ccount">
        <span><?=$theme->pPostCount?></span>
    </td>
    <td colspan="1" class="category_themes ccount">
        <span><?=$theme->pViewCount?></span>
    </td>
    <td class="category_last_message">
        <span class="lmdate"><?=date('d.m.Y G:i', $theme->lastPost->ts)?></span>
        <span class="lmdate"><?=" ".t('от')." ".$theme->lastPost->author->fio?></span>
    </td>

    <td class="noborder">
    <?php
        $this->renderPartial("panel_items/edit_delete", array(
            "item"=>$theme,
            "edit"=>"editTheme",
            "delete"=>"deleteTheme",
        ));
    ?>
    </td>

</tr>