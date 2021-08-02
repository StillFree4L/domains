<?php

namespace app\components;

use yii\helpers\Html;

class Response extends \yii\web\Response
{



    public function redirect($url, $options = [], $statusCode=302)
    {
        if (\Yii::$app->request->isAjax) {
            $this->format = \yii\web\Response::FORMAT_JSON;
            $this->data = [
                "redirect"=>$url,
                "target"=>isset($options['target']) ? $options['target'] : null,
                "full"=>isset($options['full']) ? $options['full'] : false,
                'scroll'=>isset($options['scroll']) ? $options['scroll'] : true
            ];
            $this->send();

        } else {
            parent::redirect($url,$statusCode, false);
        }
    }

    public function prepare()
    {
        if (\Yii::$app->request->isAjax) {

            if ($this->format != \yii\web\Response::FORMAT_JSON) {
                $this->format = \yii\web\Response::FORMAT_JSON;
            } else {
                return parent::prepare();
            }

            $data = [];
            $data['html'] = $this->data;
            $data['model'] = $this->getModelData();
            $this->data = $data;
            if (YII_DEBUG) {
                $this->data['logger'] = [];
            }

        }
        return parent::prepare();
    }

    public function getModelData()
    {
        $flashes = \Yii::$app->session->getAllFlashes(true);
        if ($flashes) {
            \Yii::$app->data->flash = $flashes;
        }
        return \Yii::$app->data->toArray();
    }

}

?>