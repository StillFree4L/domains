<div id="menu_builder_main">
    <div id="menu_container">
        <?php
        if (in_array("groups",$this->templates))
        {
            $this->render("groups", array(
                "group"=>$group,
            ));
        }
        if (!empty($this->group_id) AND in_array("menu", $this->templates))
        {

            $this->render("menu", array(
                "group"=>$group,
                "menu"=>$menu,
                "categories"=>$categories,
                "pages"=>$pages,
            ));

        }

        ?>
    </div>
</div>