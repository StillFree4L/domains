<div class="instance_page">


    <?php if (in_array("caption",$this->blocks)) { ?>
            <span class="instance_caption text_color">
            <?=$this->model->caption?>

            <?php $this->widget("ext.CAdmin.CAdmin",array(
                        "instance"=>$model
            ))
            ?>

    <?php }  ?>

        <?php if (in_array("ts",$this->blocks)) { ?>
        <span class="instance_ts">
            <?=date("d.m.Y",$this->model->ts)?>
            </span>

            </span>
        <?php } ?>





    <?php if (in_array("body",$this->blocks)) { ?>
    <span class="instance_body ">
        <?=$this->model->body?>
    </span>
    <?php } ?>

</div>

<?php

if (!empty($childs))
{
    $this->render("childs", array(
        "childs"=>$childs,
    ));
}

if (in_array("comments",$this->blocks)) { ?>
<?php
$this->widget("ext.CComments.CComments", array(
    "model"=>$this->model
));
}
?>