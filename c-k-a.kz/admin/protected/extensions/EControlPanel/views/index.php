<?php

foreach ($this->buttons as $name=>$button)
{

    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'size'=>'small',
        'buttons'=>array(
                $button
            ),
        )
    );
}
?>
<div style="margin-bottom:15px;"></div>