<?php

namespace app\bundles;

use app\components\View;
use yii\web\AssetBundle;

class ToolsBundle extends AssetBundle
{
    public $sourcePath = '@webroot/protected/assets/tools';
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'app\bundles\JQueryBundle'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    /**
     * @param View $view
     */
    public static function registerJgrowl($view)
    {

        $bundle = self::register($view);
        $view->registerJsFile($bundle->baseUrl."/jgrowl/jquery.jgrowl.min.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jgrowl");
        $view->registerCssFile(self::register($view)->baseUrl."/jgrowl/jquery.jgrowl.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jgrowl_css");
    }

    /**
     * @param View $view
     */
    public static function registerJCrop($view)
    {
        $bundle = self::register($view);

        $view->registerJsFile($bundle->baseUrl."/jcrop/jquery.Jcrop.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jcrop");
        $view->registerCssFile($bundle->baseUrl."/jcrop/jquery.Jcrop.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "jcrop_css");
    }

    /**
     * @param View $view
     */
    public static function registerChosen($view)
    {

        $bundle = self::register($view);

        $view->registerJsFile($bundle->baseUrl."/chosen/chosen.jquery.min.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "chosen");
        $view->registerCssFile($bundle->baseUrl."/chosen/chosen.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "chosen_css");
    }

    /**
     * @param View $view
     */
    public static function registerRange($view)
    {

        $bundle = self::register($view);

        $view->registerJsFile($bundle->baseUrl."/range/js/ion.rangeSlider.min.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "range");
        $view->registerCssFile($bundle->baseUrl."/range/css/ion.rangeSlider.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "range_css");
        $view->registerCssFile($bundle->baseUrl."/range/css/ion.rangeSlider.skinHTML5.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "range_theme");
    }

    /**
     * @param View $view
     */
    public static function registerInlineChoser($view)
    {

        $bundle = self::register($view);

        $view->registerJsFile($bundle->baseUrl."/inline-choser/choser.js", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "chosen");
        $view->registerCssFile($bundle->baseUrl."/inline-choser/choser.css", [
            'position' => $bundle->jsOptions['position'],
            "depends" => $bundle->depends
        ], "chosen_css");
    }

}

?>
