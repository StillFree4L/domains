 
<?php
$this->renderPartial("panel", array(
    "pitems" => $pitems,
    
));
?>

<?php

if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

?>
<table class="forum_table">
<?php

if (!empty($categories))
{
    
    ?>   
    
        <tr>
            <th colspan="2" class="forum_header name">
                <?=t("Раздел")?>
            </th>
            <th colspan="2" class="forum_header ccount2">
                <?=t("Темы")?>
            </th>            
            <th class="forum_header last_m">
                <?=t("Последнее сообщение")?>
            </th>
        </tr>
        
    <?php
    foreach ($categories as $category)    
    {
        $this->renderPartial("category_".$category->type, array(
            "category"=>$category
        ));
    }
    
} else if (!isset($_GET['cat'])) {
    
    ?>
        <div class="alert alert-warning">
            
            <?php
                echo t("Не создано ниодной категории");
            ?>
            
        </div>
    <?php
    
}

if (!empty($themes))
{
    ?>
    <tr>
            <th colspan="2" class="forum_header name">
                <?=t("Тема")?>
            </th>
            <th colspan="1" class="forum_header ccount">
                <?=t("Сообщения")?>
            </th>
            <th colspan="1" class="forum_header ccount">
                <?=t("Просмотры")?>
            </th>
            <th class="forum_header last_m">
                <?=t("Последнее сообщение")?>
            </th>
        </tr>

    <?php
    foreach ($themes as $theme)
    {
        $this->renderPartial("theme", array(
            "theme"=>$theme
        ));
    }

}

?>
</table>

<div class="forum_pager">
       <?php
       if (isset($_GET['cat']))
       {
        $this->widget("application.extensions.Pagination.Pagination", array(
            "model"=>PForumThemes::model(),
            "criteria"=>array("condition"=>"category_id = :tid","params"=>array(":tid"=>$_GET['cat'])),
            "perPage"=>$this->limit,
            "page"=>$page,
            "url"=>"/".Yii::app()->language."/".Yii::app()->controller->id."/index/cat/".$_GET['cat']
        ));
       }
       ?>
</div>
