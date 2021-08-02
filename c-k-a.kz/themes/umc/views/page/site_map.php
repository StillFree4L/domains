<div class="site_map">
    
<?php
$this->layout = "//layouts/index_1column";

$cs = Yii::app()->getClientScript();
$cs->registerCSSFile(Yii::app()->theme->getBaseUrl().'/css/site_map.css');

$site_map = Instances::model()->byLabel("site_map")->find();
Yii::app()->breadCrumbs->addLink($site_map->caption);
?>    
    <?php

$menuGroups = MenuGroups::model()->findAll();

foreach ($menuGroups as $group)
{

    $menu = Menu::model()->byGroup($group->id)->top()->findAll();

    if ($menu)
    {
        
        foreach ($menu as $m)
        {

            ?>
            <div class="menu_parent">
            <?php

            $lvl = 1;
            $this->renderPartial("site_map/_menu", array(
                "menu"=>$m,
                "class"=>"parent system_font_color lvl$lvl",
                "lvl"=>$lvl,
            ));

            ?>
            </div>
            <?php

        }       

    }

}

?>
</div>