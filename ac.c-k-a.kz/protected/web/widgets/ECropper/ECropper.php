<?php

namespace app\widgets\ECropper;

use app\components\Widget;

class ECropper extends Widget
{
    public $backbone = true;

    public function run()
    {
        return $this->render("crop_template");
    }
}
?>