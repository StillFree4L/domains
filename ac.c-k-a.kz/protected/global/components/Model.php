<?php

namespace glob\components;

use glob\behaviors\BackboneRequestBehavior;

class Model extends \yii\base\Model
{
    public function behaviors()
    {
        return [
            "backboneRequestBehavior" => [
                "class"=> BackboneRequestBehavior::className()
            ]
        ];
    }

}
?>