<?php
if ($menu->instance) {
?>
<div class="site_map_menu_div">
    <a href="<?=$menu->instance->getLink()?>" class="<?=$class?> site_map_menu_link "><?=$menu->instance->caption?></a>
</div>
<?php
$lvl++;
if (!empty($menu->childs))
{
    foreach ($menu->childs as $m)
    {
        $this->renderPartial("site_map/_menu", array(
            "menu"=>$m,
            "class"=>"child lvl$lvl",
            "lvl"=>$lvl
        ));
    }
}
}
?>


