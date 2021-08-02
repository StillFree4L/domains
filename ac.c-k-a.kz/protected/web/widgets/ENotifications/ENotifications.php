<?php

namespace app\widgets\ENotifications;

use app\components\Widget;

class ENotifications extends Widget
{
    public $backbone = true;

    public function run()
    {

        return $this->render("templates");

    }

}
?>