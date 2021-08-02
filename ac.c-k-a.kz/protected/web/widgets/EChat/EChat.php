<?php

namespace app\widgets\EChat;

use app\components\Widget;

class EChat extends Widget
{
    protected $backbone = true;
    /**
     * @var BaseActiveRecord target model
     */
    public function run()
    {
        return $this->render("setTemplates");
    }
}
?>