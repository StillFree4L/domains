<div class="instance_page">

    <?php if (in_array("caption",$this->blocks)) { ?>
        <span class="instance_caption">
            <?=$this->model->caption?>

            <?php $this->widget("ext.CAdmin.CAdmin",array(
                        "instance"=>$model
            ))
            ?>

        </span>
    <?php } ?>

    <?php if (in_array("ts",$this->blocks)) { ?>
        <span class="instance_ts">
            <?=date("d.m.Y",$this->model->ts)?>
            
        </span>
    <?php } ?>    

    <span class="instance_preview">
        <?=$this->model->preview?>
    </span>

    <span class="instance_body">
        <?=$this->model->body?>
    </span>

</div>

<?php
$this->widget("ext.CComments.CComments", array(
    "model"=>$this->model
))
?>