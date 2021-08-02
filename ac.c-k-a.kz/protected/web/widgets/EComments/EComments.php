<?php

namespace app\widgets\EComments;

use app\components\Widget;

class EComments extends Widget
{

    const comments = 1;
    const chat = 2;

    protected $backbone = true;

    public $template = EComments::comments;
    public function run()
    {
        return $this->render("setTemplates");
    }
}
?>