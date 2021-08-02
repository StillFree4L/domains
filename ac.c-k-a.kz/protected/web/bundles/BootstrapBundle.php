<?php
namespace app\bundles;

use yii\web\AssetBundle;

/**
 * Class BootstrapBundle
 * @package app\bundles
 * @configuration http://getbootstrap.com/customize/?id=4a3a25795c9c6758361c
 */
class BootstrapBundle extends AssetBundle
{
    public $sourcePath = '@webroot/protected/assets/bootstrap';
    public $css = [
        'css/bootstrap.css',
        'css/bootstrap-datepicker.css'
    ];
    public $js = [
        'js/bootstrap.js',
        'js/moment.js',
        'js/bootstrap-datepicker.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [
        'app\bundles\JQueryBundle'
    ];

    public static function registerTimePicker($view)
    {

        $bundle = self::register($view);
        $view->registerJsFile($bundle->baseUrl."/js/bootstrap-timepicker.min.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jgrowl");
        $view->registerCssFile(self::register($view)->baseUrl."/css/bootstrap-timepicker.min.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jgrowl_css");
    }

}

?>
