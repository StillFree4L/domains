<?php

namespace app\bundles;

use yii\web\AssetBundle;

class BaseBundle extends AssetBundle
{
    public $sourcePath = '@webroot/protected/assets/base';
    public $css = [
        'css/base.css',
        'css/loading.css'
    ];
    public $js = [
        'js/common.js',
        'js/base.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'app\bundles\JQueryBundle',
        'app\bundles\BootstrapBundle',
        'app\bundles\UrlManagerBundle',
        'app\bundles\BackboneBundle'
    ];
}

?>
