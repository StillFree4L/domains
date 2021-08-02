<?php

namespace glob\components;

class LanguageSetter
{
    public $langs = [
        "ru"=>1,
        "kz"=>2,
        "en"=>3,
    ];
    public function init()
    {        
        
        $lparam = \Yii::$app->urlManager->langParam;
        
        if(isset($_GET[$lparam]) AND in_array($_GET[$lparam], \Yii::$app->urlManager->languages)) {

            \Yii::$app->language = $_GET[$lparam];
            \Yii::$app->user->setState($lparam, $_GET[$lparam]);
            $cookie = new CHttpCookie($lparam, $_GET[$lparam]);
            $cookie->expire = time() + (60*60*24*365); // (1 year)
            \Yii::$app->request->cookies[$lparam] = $cookie;

            \Yii::$app->request->redirect(\Yii::$app->request->urlReferrer);
            
        }
        else if (\Yii::$app->user->hasState($lparam) AND in_array(\Yii::$app->user->hasState($lparam),\Yii::$app->urlManager->languages))
            \Yii::$app->language = \Yii::$app->user->getState($lparam);
        else if(isset(\Yii::$app->request->cookies[$lparam]) AND in_array(\Yii::$app->request->cookies[$lparam],\Yii::$app->urlManager->languages))
            \Yii::$app->language = \Yii::$app->request->cookies[$lparam]->value;
        
    }
    public function getLN($lang = null)
    {
        if ($lang == null) $lang = \Yii::$app->language;
        return $this->langs[$lang];        
    }
}
?>
