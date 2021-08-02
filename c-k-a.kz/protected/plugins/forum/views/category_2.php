<tr class="category_2">
    <td colspan="5" class="category_name">
        <span class="category_caption"><?=$category->name?></span>
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
<?php
    if (!empty($category->pCategoryChilds)) {

        foreach ($category->pCategoryChilds as $child) {

            $this->renderPartial("category_".$child->type, array(
                "category"=>$child
            ));

        } 
    }
?>