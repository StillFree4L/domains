<div>
    <label for="instance_lang"><?=t("Категории ссылок")?></label>
<?php
    $categories = Instances::model()->linkCategories()->findAll();
    $rawData = array();

    if (!empty($categories))
    {

        foreach ($categories as $category)
        {
            ?>
                <label class="checkbox">
                    <input <?=in_array($category->id, $this->model->parentCategories) ? "checked='checked'" : ""?> id="TestForm_checkboxes_<?=$category->id?>" value="<?=$category->id?>" type="checkbox" name="Instances[relations][]">
                    <label for="TestForm_checkboxes_<?=$category->id?>"><?=$category->caption?></label>
                </label>
            <?php
        }

    }


?>
</div>