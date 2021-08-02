<?php

namespace app\components;

use glob\helpers\Common;
use glob\models\TestStarted;
use glob\models\Users;
use yii;
use yii\filters\AccessControl;

class Controller extends yii\base\Controller
{

    public $layout = "inner";
    public $isModal = false;

    /**
     * Определяем базовые константы АССЕТОВ
     * @param CAction $action
     * @return bool
     */
    public function beforeAction($action)
    {
        \Yii::$app->data->controller = $this->id;
        \Yii::$app->data->action = $action->id;
        \Yii::$app->data->size = "lg";
        if (\Yii::$app->request->post('target')=="modal") {
            \Yii::$app->data->isModal = true;
            $this->isModal = true;
        }

        if ($this->id != "error") {
            if (\Yii::$app->user->identity->role_id == Users::ROLE_STUDENT) {
                if (!($this->id == "tests" AND in_array($action->id, ["process", "finish"]))) {
                    $tests = TestStarted::findStarted()->all();
                    if ($tests) {
                        \Yii::$app->response->redirect(Common::createUrl("/tests/process", ["id" => $tests[0]->id]));
                        return false;
                    }
                }
            }
        }

        \Yii::$app->view->on(yii\web\View::EVENT_BEFORE_RENDER, function () {

            if (file_exists(\Yii::$app->assetManager->getBundle("base")->basePath."/css/controllers/{$this->id}.css"))
            {
                $this->getView()->registerCssFile(\Yii::$app->assetManager->getBundle("base")->baseUrl."/css/controllers/{$this->id}.css", [
                    'depends' => [
                        \app\bundles\BaseBundle::className()
                    ],
                    'position'=> yii\web\View::POS_HEAD
                ]);
            }
        });

        return parent::beforeAction($action);
    }



    /**
     * Возвращает JSON результат для аякс запросов
     * @param $data
     * @param mixed $code
     */
    public function renderJSON($data, $code = false)
    {
        if ($code === true) {
            \Yii::$app->response->statusCode = 500;
        } else if ($code === false) {
            \Yii::$app->response->statusCode = 200;
        } else {
            \Yii::$app->response->statusCode = $code;
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
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
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }
    
}
?>