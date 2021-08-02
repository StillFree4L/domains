<tr class="category_1">
    <td colspan="2" class="category_name">
        <a href="<?="/".Yii::app()->language."/".Yii::app()->controller->id."/index/cat/".$category->id?>" class="category_caption"><?=$category->name?></a>
    </td>
    <td colspan="2" class="category_themes ccount">
        <span><?=$category->pThemesCount()?></span>
    </td>
    <td class="category_last_message">
        
    </td>
    
    <td class="noborder">
    <?php
        $this->renderPartial("panel_items/edit_delete", array(
            "item"=>$category,
            "edit"=>"addCategory",
            "delete"=>"deleteCategory",
        ));
    ?>
    </td>
    
</tr>