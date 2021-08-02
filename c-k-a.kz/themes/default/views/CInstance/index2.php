<div class="instance_page">

    <?php if (in_array("caption",$this->blocks)) { ?>
        <span class="instance_caption text_color">
            <?=$this->model->caption?>

            <?php $this->widget("ext.CAdmin.CAdmin",array(
                        "instance"=>$model
            ))
            ?>
            
            <?php if (in_array("ts",$this->blocks)) { ?>
        <span class="instance_ts">
            <?=date("d.m.Y",$this->model->ts)?>
            
        </span>
    <?php } ?>  
        </span>
    <?php } else if (in_array("link_caption",$this->blocks)) { ?>
        <a href="<?=$this->model->getLink()?>" class="instance_caption text_color">
            <?=$this->model->caption?>

            <?php $this->widget("ext.CAdmin.CAdmin",array(
                        "instance"=>$model
            ))
            ?>

            <?php if (in_array("ts",$this->blocks)) { ?>
        <span class="instance_ts">
            <?=date("d.m.Y",$this->model->ts)?>
        </span>
    <?php } ?>
        </a>
    <?php }
    
    if (in_array("preview",$this->blocks)) { ?>
    <span class="instance_body">
        <?=$this->model->preview?>
    </span>
    <?php } ?>

    <?php if (in_array("body",$this->blocks)) { ?>
    <span class="instance_body">
        <?=$this->model->body?>
    </span>
    <?php } ?>

</div>

<?php if (in_array("comments",$this->blocks)) { ?>
<?php
$this->widget("ext.CComments.CComments", array(
    "model"=>$this->model
));
}
?>
