
    <?php

    if (!empty($records)) {

        foreach ($records as $child)
        {

            ?>



                <a style="cursor:pointer" rel='popover' data-placement="top" data-trigger="hover" data-content="<?=isset($child->instance->body) ? $child->instance->body : ""?>" title="<?=$child->instance->caption?>" class="instance_link instance_employee" target="<?=$child->instance->target?>">

                    <img style="max-height:140px;" class="employee_image" src="<?=$child->instance->preview?>" />

                </a>

            <?php

        }

    }
    ?>

