<div>
    <label for="Instances_ts"><?=t("Дата публикации")?></label>
<?php
    
    if (!empty($this->model->ts) AND is_numeric($this->model->ts))
    {
        $ts = date("d.m.Y", $this->model->ts);
    } else {
        $ts = date("d.m.Y");
    }

    echo CHtml::textField("Instances[ts]", $ts, array(
        "id"=>"Instances_ts",
        "style"=>"width:90px"
    ))

?>
</div>
