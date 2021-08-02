<?php
if (!empty($childs)) {
foreach ($childs as $child)
{
    ?>
        <a class="instance_child" href="<?=$child->instance->getLink()?>"><?=$child->instance->caption?></a>
    <?php
}
}
?>
