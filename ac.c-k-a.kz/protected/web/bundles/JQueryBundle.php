<?php

namespace app\bundles;

use yii\web\AssetBundle;

class JQueryBundle extends AssetBundle
{
    public $sourcePath = '@webroot/protected/assets/jquery';
    public $css = [
        'css/jquery-ui.min.css'
    ];
    public $js = [
        'js/jquery-2.1.4.min.js',
        'js/jquery-ui.min.js',
        'js/jquery.autocomplete.min.js',
        'js/jquery.sticky.js',
        'js/jquery.masked.js',
        'http://code.jquery.com/jquery-migrate-1.0.0.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

}

?>
