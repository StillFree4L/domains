<?php

    namespace app\components;

    use app\bundles\BackboneBundle;

    class Widget extends \yii\base\Widget
    {

        protected $backbone = false;

        const TYPE_STATIC = 0;
        const TYPE_TEMPLATE = 1;

        public $type = self::TYPE_STATIC;
        public $id = null;

        public function init()
        {
            $c = (new \ReflectionClass($this))->getShortName();
            if ($this->backbone)
            {
                BackboneBundle::registerWidget($this->view, $c);
            }

            if ($this->id == null) $this->id = $this->getId();
        }

    }
?>