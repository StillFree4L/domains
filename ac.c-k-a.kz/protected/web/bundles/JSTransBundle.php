<?php

namespace app\bundles;

use yii\web\View;
use yii\helpers\Json;
use yii\web\AssetBundle;

class JSTransBundle extends AssetBundle
{
    public $sourcePath = '@webroot/protected/assets/jstrans';
    public $css = [];
    public $js = [
        'JsTrans.min.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public function registerAssetFiles($view) {

        parent::registerAssetFiles($view);

        $this->registerScript($view);

    }


    public function registerScript($view)
    {
        // set default language
        $defaultLanguage = \Yii::$app->language;
        // create arrays from params
        $categories = ["app", "main"];
        $languages = \Yii::$app->urlManager->languages;

        // publish assets folder
        $hash = substr(md5(implode($categories)), 0, 10);
        $dictionaryFile = "dictionary-{$hash}.js";

        // generate dictionary file if not exists or YII DEBUG is set
        if (!file_exists($this->basePath . '/' . $dictionaryFile) || YII_DEBUG) {
            // declare config (passed to JS)
            $config = ['language' => $defaultLanguage];

            // base folder for message translations
            $messagesFolder = \Yii::getAlias("@app/messages");

            // loop message files and store translations in array
            $dictionary = [];
            foreach ($languages as $lang) {
                if (!isset($dictionary[$lang])) $dictionary[$lang] = [];

                foreach ($categories as $cat) {
                    $messagefile = $messagesFolder . '/' . $lang . '/' . $cat . '.php';
                    if (file_exists($messagefile)) $dictionary[$lang][$cat] = array_filter(require($messagefile));
                }
            } //asd

            // save config/dictionary
            $data = 'Yii.translate.config=' . Json::encode($config) . ';' .
                'Yii.translate.dictionary=' . Json::encode($dictionary);


            // save to dictionary file
            if(!file_put_contents($this->basePath . '/' . $dictionaryFile, $data))
                \Yii::log('Error: Could not write dictionary file, check file permissions', 'trace', 'jstrans');
        }

        // publish library and dictionary
        if (file_exists($this->basePath . '/' . $dictionaryFile)) {
            $view->registerJsFile($this->baseUrl . '/' . $dictionaryFile, [
                "position"=>View::POS_HEAD
            ]);
        } else {
            \Yii::log('Error: Could not publish dictionary file, check file permissions', 'trace', 'jstrans');
        }

    }

}

?>
