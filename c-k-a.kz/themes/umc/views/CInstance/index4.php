
    <?php

    if (!empty($records)) {

        foreach ($records as $child)
        {
        ?>

            <a class="instance_link" href="<?=$child->instance->getLink()?>" target="<?=$child->instance->target?>">

                <img class="link_image" src="<?=$child->instance->preview?>" />

                <?php
                if (!empty($this->body))
                {
                    ?>
                    <span class="link_text"><?=$child->instance->body?></span>
                    <?php
                }
                ?>
                
            </a>

        <?php
        }

    }
    ?>

