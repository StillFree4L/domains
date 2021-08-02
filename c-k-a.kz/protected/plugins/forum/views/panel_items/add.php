
<?php

$buttons = array();
if ($this->checkAccess("addCategory"))
{
    $buttons[] = array(
        "label"=>t("Добавить категорию"),
        "icon"=>"plus",
        "url"=>"/".Yii::app()->language."/".Yii::app()->controller->id."/addCategory/".(isset($_GET['cat']) ? "cat/".$_GET['cat'] : "")
    );
}

if ($this->checkAccess("addTheme") AND isset($_GET['cat']) AND (PForumCategories::model()->findByPk($_GET['cat'])->can_add_themes == 1 OR $this->checkAccess("addThemeAdmin")))
{
    $buttons[] = array(
        "label"=>t("Добавить тему"),
        "icon"=>"plus white",
        "type"=>"primary",
        "url"=>"/".Yii::app()->language."/".Yii::app()->controller->id."/addTheme/".(isset($_GET['cat']) ? "cat/".$_GET['cat'] : "")
    );
}

$this->widget('bootstrap.widgets.TbButtonGroup', array(
        'size'=>'small',
        'buttons'=>$buttons
        
        )
    );
?>