<?php

    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'optionsForm',
    'type'=>'horizontal',
    'htmlOptions'=>array('class'=>'well')
    ));

    if (!empty($options))
    {
        foreach ($options as $option)
        {
            ?>

            <div class="control-group ">
                <label class="control-label" for="Options_<?=$option->name?>"><?=$option->caption?></label>
                <div class="controls">
                    <input value="<?=$option->value?>" name="Options[<?=$option->name?>]" id="Options_<?=$option->name?>" type="text">
                </div>
            </div>

            <?php
        }
    }

    ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'small', 'type'=>'primary', 'label'=>t('Сохранить'))); ?>
    </div>

    <?php
    
    $this->endWidget();

?>