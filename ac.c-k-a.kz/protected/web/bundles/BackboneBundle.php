<?php

    namespace app\bundles;

    use yii\web\AssetBundle;

    class BackboneBundle extends AssetBundle
    {
        public $sourcePath = '@webroot/protected/assets/backbone';
        public $css = [

        ];
        public $js = [
            'framework/underscore.js',
            'framework/backbone.js',
            'framework/backbone.syphon.js',
            'components/sync.js',
            'components/model.js',
            'components/collection.js',
            'components/component.js',
            'components/validation.js',
            'components/poll.js',
            'components/item.js',
            'components/controller.js',
            'components/action.js',
            'components/widget.js'
        ];
        public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
        public $depends = [
            'app\bundles\JQueryBundle'
        ];

        public static function registerWidget($view, $name)
        {
            $bundle = self::register($view);
            $view->registerJsFile($bundle->baseUrl."/widgets/".$name.".js", [
                    'depends' => [self::className()],
                    'position'=> \app\components\View::POS_HEAD
            ]);
        }

    }

?>
