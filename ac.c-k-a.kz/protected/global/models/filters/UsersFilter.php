<?php

namespace glob\models\filters;

use glob\components\Model;

class UsersFilter extends Model
{
    public $fio = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['fio'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $p = new UsersFilter();
        return [
            "fio" => \Yii::t("main","ФИО"),
        ];
    }

    /**
     * @param \glob\components\ActiveQuery $query
     * @return \glob\components\ActiveQuery
     */
    public function appendFilter($query) {
        if ($this->fio) {
            $s = explode(" ", $this->fio);
            $q = "(";
            foreach ($s as $qq) {
                $q .= "users_info.last_name LIKE '%$qq%' OR users_info.first_name LIKE '%$qq%' OR ";
            }
            $q = rtrim($q, " OR ").")";
            $query->andWhere($q);
        }
        return $query;
    }

}

?>