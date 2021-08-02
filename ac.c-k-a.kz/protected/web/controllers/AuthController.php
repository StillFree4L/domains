<?php

namespace app\controllers;

use glob\components\ActiveRecord;
use glob\helpers\Common;
use glob\models\Users;
use yii\filters\AccessControl;

class AuthController extends \app\components\Controller
{

    public $layout = "auth";
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'actions' => ['logout'],
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }

    public function actionLogin()
    {

        $user = new Users();
        $user->scenario = "login";
        if (\Yii::$app->request->post('Users'))
        {

            $user->login = \Yii::$app->request->post('Users')['login'];
            $user->password = \Yii::$app->request->post('Users')['password'];
            $user->rememberMe = \Yii::$app->request->post('Users')['rememberMe'];

            $result = [];
            $error = false;
            if ($user->validateAndLogin()) {
                return $this->renderJSON([
                    "redirect"=>\Yii::$app->user->identity->home,
                    "full"=>true
                ]);
            } else {
                return $this->renderJSON($user->getErrors(), true);
            }

        }

        $onlyLogin = false;
        if (\Yii::$app->request->get('t') == "cp") {
            $onlyLogin = true;
        }
        \Yii::$app->data->size = "sm";

        \Yii::$app->data->user = ActiveRecord::arrayAttributes($user, [], ["login", "password"], true);
        \Yii::$app->data->rules = $user->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $user->attributeLabels();


        return $this->render("login", [
            "onlyLogin"=>$onlyLogin,
            "user"=>$user,
        ]);

    }
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        \Yii::$app->response->redirect("/auth/login", [
            "full"=>true,
        ]);
    }

}

?>