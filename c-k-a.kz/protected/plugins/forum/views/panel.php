<div class="forum_panel">

    <?php
    if (!empty($pitems))
    {
        foreach ($pitems as $item)
        {
            $this->renderPartial("panel_items/".$item);
        }
    }
    ?>
    
</div>
