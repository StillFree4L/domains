<div class="menu_instances_list well well-small" style="min-height:50px;">
<label for="instance_lang"><?=t("Категории")?></label>
<?php

if ($categories) {
    foreach ($categories as $k => $category)
    {

        if (!Menu::model()->hasInstance($group->id, $category->id)) {
            ?>

                <label class="checkbox">
                    <input id="instance_<?=$category->id?>" value="<?=$category->id?>" type="checkbox" name="addInstance[]">
                    <label for="instance_<?=$category->id?>"><?=$category->caption?></label>
                </label>

            <?php
        }       
        
    }

    

}
?>
</div>

