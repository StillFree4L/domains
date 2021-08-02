<div class="left_menu">

    <div id="left_menu_bg" class="bg"></div>

    <div id="left_menu_content" class="cc">
        <?php
        
        if ($this->label!==false)
        {
            if ($this->label === true)
                $label = t($group->caption); else $label = $this->label;
                
                ?>
                    <div class='menu_header st'>
                        <?=$label?>
   
                    </div>
                <?php
                
        }
        
        $this->render("_menus",array("menu"=>$menu,"class"=>"inner"));
        ?>
    </div>

</div>