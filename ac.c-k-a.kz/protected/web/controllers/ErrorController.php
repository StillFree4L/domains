<?php

namespace app\controllers;

use app;
use yii\filters\AccessControl;

class ErrorController extends app\components\Controller {
    public $layout = "inner";
    public function actionIndex()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('index', ['exception' => $exception]);
        }
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@','?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }

}
?>