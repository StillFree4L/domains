<?php
foreach ($menu as $m)
{

    if (isset($m->instance->caption)) {
    ?>
    <div class="menu_link_div">
    <?php
    echo CHtml::link($m->instance->caption, 
            $m->instance->getLink(),
            array("class"=>"system_font_color menu_link",
                "id"=>"menu_".$m->instance->id)
            );
        if (!empty($m->childs)) {
    ?>

        <div class="menu_link_childs <?=$class?>">
            <span class="arrow"></span>
            <?php $this->render("_menus",array("menu"=>$m->childs, "class"=>"inner")); ?>
        </div>

        <?php
        }
        ?>

    </div>
    <?php
    }
}
?>