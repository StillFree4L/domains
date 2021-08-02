<div style="margin-top:20px;"></div>
<div class="menu_instances_list well well-small" style="min-height:50px;" accept="page_item">
<label for="instance_lang"><?=t("Страницы")?></label>

<?php

if ($pages) {
    foreach ($pages as $k => $page)
    {

        if (!Menu::model()->hasInstance($group->id, $page->id)) {
        ?>
            <label class="checkbox">
                <input id="instance_<?=$page->id?>" value="<?=$page->id?>" type="checkbox" name="addInstance[]">
                <label for="instance_<?=$page->id?>"><?=$page->caption?></label>
            </label>
        <?
        }

    }
}
?>
</div>