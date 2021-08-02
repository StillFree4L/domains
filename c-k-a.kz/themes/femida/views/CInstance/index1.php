<div class="instance_page">

    <?php if (in_array("caption",$this->blocks)) { ?>
        <span class="instance_caption text_color">
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
                    <a href="<?=$child->instance->getLink()?>" class="instance_caption text_color">
                        <?=$child->instance->caption?>
                        
                        <?php if (in_array("ts",$this->blocks)) { ?>
                        <span class="instance_ts">
                            <?=date("d.m.Y",$child->instance->ts)?>                        
                        </span>
                    <?php } ?>

                        
                    
                    </a>
                <?php } ?>               

                <span class="instance_preview">
                    <?php $this->widget("ext.CAdmin.CAdmin",array(
                            "instance"=>$child->instance
                            ))
                            ?>

                    <?=$child->instance->preview?>
                    <div style="clear:both;"></div>
                </span>

                <div class="instance_separator">
                   <a class="instance_comments text_color" href="<?=$child->instance->getLink()?>"><?=t("Комментарии")."(".$child->instance->cCount.")"?></a>
                   <a class="instance_more text_color" href="<?=$child->instance->getLink()?>"><?=t("Подробнее")?></a>                   
                </div>

                <div style="clear:both;"></div>
            </div>

        <?php
        }

    }
    ?>

</div>


<?php

if (!empty($childs))
{
    $this->render("childs", array(
        "childs"=>$childs,
    ));
}

if ($this->limit == null)
{
    $this->widget("ext.Pagination.Pagination", array(
        "model"=>$pagerModel,
        "page"=>$this->page,
        "perPage"=>Yii::app()->baseOptions->pageSize,
        "url"=>"/".Yii::app()->language."/view/".$model->id,
        "criteria"=>array("condition"=>"p_id = ".$this->model->id)
    ));
}

?>
