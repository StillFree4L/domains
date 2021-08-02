<?php

namespace app\controllers;

use glob\components\ActiveRecord;
use app\components\Controller;
use glob\helpers\Common;
use glob\models\Dics;
use glob\models\DicValues;
use glob\models\FilterForm;
use glob\models\Options;
use glob\models\Users;
use yii\filters\AccessControl;

class OptionsController extends Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), \glob\helpers\Common::createUrl("/main/index"));
        return $p;
    }

    public function actionIndex()
    {

        $options = Options::find()->all();

        \Yii::$app->data->options = ActiveRecord::arrayAttributes($options, [], [], true);
        return $this->render("index", [
            "options" => $options
        ]);

    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Users::ROLE_ADMIN],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?', '@']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }


}

?>