<div class="instance_page">

    <span class="instance_caption">
    <?php if (in_array("caption",$this->blocks)) { ?>
        
            <?=$this->model->caption?>

            <?php $this->widget("ext.CAdmin.CAdmin",array(
                        "instance"=>$model
            ))
            ?>
        
    <?php }  ?>
    </span>

    <?php if (in_array("ts",$this->blocks)) { ?>
        <span class="instance_ts">
            <?=date("d.m.Y",$this->model->ts)?>
        </span>
    <?php } ?>

    
    <span class="instance_body">
        <?=$this->model->body?>
    </span>

</div>

<?php
$this->widget("ext.CComments.CComments", array(
    "model"=>$this->model
))
?>