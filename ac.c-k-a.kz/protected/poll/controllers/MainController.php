<?php

namespace app\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;

class MainController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            //'class' => HttpBearerAuth::className()
            'class' => QueryParamAuth::className()
        ];
        return $behaviors;
    }

    public function actionIndex()
    {

        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);

        $post = \Yii::$app->request->post();

        if (isset($post)) {

            $stop = false;
            $response = array();
            $i = 1;

            $response = array();
            while (!$stop) {

                foreach ($post as $n=>$v) {

                    if (isset($v['yModel'])) {
                        $m = '\glob\models\\'.$v['yModel'];
                        $model = new $m();
                        /* @var $model \glob\components\ActiveRecord */
                        if ($r = $model->backboneRequest("poll",$v))
                        {
                            if (!empty($r['data'])) {
                                $response[$n] = $r;
                            }

                        } else {
                            $errors = $model->getErrors();
                            $response[$n] = array(
                                "error"=>$errors
                            );
                        }
                    }

                }

                if (!empty($response) OR $i>25) {
                    $stop = true;
                }

                $i++;
                sleep(1);

            }

            return $this->renderJSON($response);

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