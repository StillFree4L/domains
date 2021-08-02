<?php

$data = $results->getData();

if (!empty($data)) {
foreach ($data as $k=>$v)
{

    ?>
    <div class="instance_record">

                <a href="<?=$v->getLink()?>" class="instance_caption text_color">
                    <?=$v->caption?>

                                           

                    <span class="instance_ts">
                        <?=date("d.m.Y",$v->ts)?>                        
                        <?=$v->typeCaption?>
                    </span>

                    



                </a> 

            <div style="clear:both;"></div>
        </div>

    <?php

}
} else {

    ?>
    <div class="alert alert-info">
        <?=t("Ничего не найдено")?>
    </div>
    <?php

}

?>