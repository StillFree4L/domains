<?php

$url = "/".$this->controller->id."/".$_GET['iid'];

if ($pages>1) { ?>
<div class='pages-div' style="margin-top:10px;">
    <?php
    if ($page>($this->show_pages/2)+1) {
    ?>
        <a href='<?= $this->url ?>' <?= ((1 == @$page) ? "class='current-page'" : "") ?>>1</a>
            ...
    <?php } ?>

    <?php
    if (@$page>$this->show_pages/2) {
        $z = $page - $this->show_pages/2;
    } else {
        $z = 1;
    }
    for ($i = $z; $i <= $z+$this->show_pages; $i++) {
        if ($i<=$pages) {
        ?>
            <a href='<?= $this->url."/page/".$i ?>' <?= (($i == @$page) ? "class='current-page'" : "") ?>><?= $i ?></a>
        <?php } }
        if ($page+5<$pages) {
        ?>
            ...
            <a href='<?= $this->url."/page/".$pages ?>' <?= (($pages == @$page) ? "class='current-page'" : "") ?>><?= $pages ?></a>
    <?php } ?>
</div>
<?php } ?>