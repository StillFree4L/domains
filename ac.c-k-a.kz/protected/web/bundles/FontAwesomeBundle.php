<?php

namespace app\bundles;

use yii\web\AssetBundle;

class FontAwesomeBundle extends AssetBundle
{
    public $sourcePath = '@webroot/protected/assets/font_awesome';
    public $css = [
        'css/font-awesome.min.css'
    ];
}

?>
