<div class="instance_page">

    <?php if (in_array("caption",$this->blocks)) { ?>
        <span class="instance_caption">
            <?=$model->caption?>

            <?php $this->widget("ext.CAdmin.CAdmin",array(
                "instance"=>$model
            ))
            ?>
        </span>
    <?php } ?>

    <?php

    if (!empty($records)) {
        
        foreach ($records as $child)
        {
        ?>

            <div class="instance_record">

                <?php if (in_array("caption",$this->blocks)) { ?>
                    <a href="<?=$child->instance->getLink()?>" class="instance_caption">
                        <?=$child->instance->caption?>
                    </a>
                <?php } ?>

                <?php if (in_array("ts",$this->blocks)) { ?>
                    <span class="instance_ts">
                        <?=date("d.m.Y",$child->instance->ts)?>

                        <?php $this->widget("ext.CAdmin.CAdmin",array(
                        "instance"=>$model
                        ))
                        ?>

                    </span>
                <?php } ?>

                

                <span class="instance_preview">
                    <?=$child->instance->preview?>
                    <div style="clear:both;"></div>
                </span>

                <div class="instance_separator">
                    
                </div>

                <div style="clear:both;"></div>
            </div>

        <?php
        }

    }
    ?>

</div>
