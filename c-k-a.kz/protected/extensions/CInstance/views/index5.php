<a class="instance_link" href="<?=$record->instance->getLink()?>" target="<?=$record->instance->target?>">

    <img class="link_image" src="<?=$record->instance->preview?>" />

    <?php
    if (!empty($record->instance->body))
    {
        ?>
        <span class="link_text"><?=$record->instance->body?></span>
        <?php
    }
    ?>

</a>