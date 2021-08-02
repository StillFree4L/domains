<?php

namespace app\controllers;

use yii\base\Controller;
use yii\filters\AccessControl;


class BackboneController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['request'],
                    ],
                ],
            ],
        ];
    }

    public function actionRequest()
    {

        $method = $_SERVER['REQUEST_METHOD'];

        $v = [];
        eval('$v = \Yii::$app->request->'.(!in_array($method,["DELETE","PUT","POST"]) ? "get()" : "post()").";");
        if (isset($v))
        {
            $m = 'glob\models\\'.$v['yModel'];
            $model = new $m();
            return $this->$method($model, $v[(new \ReflectionClass($model))->getShortName()]);
        } else {
            return $this->renderJSON(["noModel" => [Yii::t("main","Не указана модель")]], true);
        }
    }

    public function GET($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("get",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
    }

    public function POST($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("insert",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
    }

    public function PUT($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("update",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
    }

    public function DELETE($model, $attributes = [])
    {
        /* @var $model BaseActiveRecord */
        $r = $model->backboneRequest("delete",$attributes);
        if ($r !== false)
        {
            return $this->renderJSON($r);
        } else {
            $errors = $model->getErrors();
            return $this->renderJSON($errors, true);
        }
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



}
?>