<?php

namespace app\widgets\EUploader;

use app\components\Widget;

class EUploader extends Widget
{
    public $backbone = true;

    public $standalone = true;

    public function run()
    {

        return $this->render('setTemplates');

    }

}
?>