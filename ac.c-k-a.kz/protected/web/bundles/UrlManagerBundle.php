<?php

namespace app\bundles;

use yii\web\View;
use yii\helpers\Json;
use yii\web\AssetBundle;

class UrlManagerBundle extends AssetBundle
{
    public $sourcePath = '@webroot/protected/assets/urlmanager';
    public $css = [];
    public $js = [
        'PHPJS.dependencies.js',
        'Yii.UrlManager.js',
        'Yii.UrlManager.definitions.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public function registerAssetFiles($view) {

        if (RELOAD_ASSETS) {
            $this->compileJS();
        }
        parent::registerAssetFiles($view);



    }


    public function compileJS()
    {
        $urlManager = \Yii::$app->urlManager;
        $managerVars = get_object_vars($urlManager);
        $managerVars['urlFormat'] = $urlManager->enablePrettyUrl ? "path" : false;

        $config = include (\Yii::getAlias("@webroot/protected/config/web.php"));
        $managerVars['rules'] = $config['components']['urlManager']['rules'];

        foreach ($managerVars['rules'] as $pattern => $route) {
            //Ignore custom URL classes
            if(is_array($route) && isset($route['class'])) {
                unset($managerVars['rules'][$pattern]);
            }
        }

        $encodedVars = Json::encode($managerVars);

        $baseUrl = \Yii::$app->getRequest()->getBaseUrl();
        $scriptUrl = \Yii::$app->getRequest()->getScriptUrl();
        $hostInfo = \Yii::$app->getRequest()->getHostInfo();

        $dir = \Yii::getAlias($this->basePath);
        file_put_contents($dir."/".$this->js[count($this->js)-1], "$(function() {
                Yii = Yii || {}; Yii.app = $.extend(Yii.app, {scriptUrl: '{$scriptUrl}',baseUrl: '{$baseUrl}',
                hostInfo: '{$hostInfo}'});
                Yii.app.urlManager = new UrlManager({$encodedVars});
                Yii.app.createUrl = function(route, params, ampersand)  {
                return this.urlManager.createUrl(route, params, ampersand);};
            });");


    }

}

?>
