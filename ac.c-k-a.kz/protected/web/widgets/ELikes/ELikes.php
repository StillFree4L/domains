<?php

namespace app\widgets\ELikes;

use app\components\Widget;

class ELikes extends Widget
{
    public $backbone = true;
    public function run()
    {
        static $loaded;
        if (!$loaded) {
            $loaded = true;
            return $this->render("templates");
        }

    }

}
?>