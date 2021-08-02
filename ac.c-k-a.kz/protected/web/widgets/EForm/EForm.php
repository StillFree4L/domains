<?php

    namespace app\widgets\EForm;

    use yii\helpers\Html;

    class EForm extends \app\components\Widget
    {

        protected $backbone = true;
        public $htmlOptions = [];

        public function init()
        {
            parent::init();
            echo Html::beginForm($this->htmlOptions['action'], $this->htmlOptions['method'], $this->htmlOptions);
        }

        public function run()
        {

            echo Html::endForm();

        }

    }
?>