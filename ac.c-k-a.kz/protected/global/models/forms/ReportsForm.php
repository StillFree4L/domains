<?php

namespace glob\models\forms;
use glob\components\Model;

/**
 * Model for login users.
 * @property string $email
 * @property string $password
 * @property bool $rememberMe
 * @property array $disabled - if some fields must me read only
 */
class ReportsForm extends Model
{

    public static function getTypes($type = null)
    {

        $types = [
            1 => \Yii::t("main","Экзаменационная ведомость"),
            2 => \Yii::t("main","Рубежно-рейтинговая ведомость"),
            3 => \Yii::t("main","Ведомость защиты курсовой работы"),
            4 => \Yii::t("main","Сводная ведомость успеваемости")
        ];

        return $type ? $types[$type] : $types;

    }

    public static function getTypeWordSettings($type)
    {

        $all = [
            "marginTop" => 567,
            "marginBottom" => 567,
            "marginLeft" => 567,
            "marginRight" => 567
        ];

        $types = [
            4 => [
                "orientation" => "landscape",

            ]
        ];
        return $types[$type] ? array_merge($all, $types[$type]) : $all;
    }



}

