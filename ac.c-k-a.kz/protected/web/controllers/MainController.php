<?php

namespace app\controllers;
use app\components\Controller;
use glob\helpers\Common;

/**
 * Class MainController
 * @package app\controllers
 */
class MainController extends Controller {

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), \Yii::$app->urlManager->createUrl("/main/index"));
        return $p;
    }

    public function actionIndex()
    {

        return $this->render("index");
    }

}
?>