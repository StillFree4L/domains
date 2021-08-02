<?php

if (!isset($lvl))
{
    $lvl = 0;
}

$lvl++;
foreach ($menu as $k=>$m)
{

    if (isset($m->instance->caption)) {
    ?>
    <div class="menu_link_div lvl_<?=$lvl?> <?=$k==(count($menu)-1) ? "m_last" : "" ?>">
    <?php
    echo CHtml::link($m->instance->caption, 
            $m->instance->getLink(),
            array("class"=>"system_font_color menu_link st",
                "id"=>"menu_".$m->instance->id)
            );
        if (!empty($m->childs)) {
    ?>

        <div class="menu_link_childs <?=$class?>">
            <span class="arrow"></span>
            <?php $this->render("_menus",array("menu"=>$m->childs, "class"=>"inner", "lvl"=>$lvl)); ?>
        </div>

        <?php
        }
        ?>

    </div>
    <?php
    }
}
?>