<?php

namespace glob\models\filters;

use glob\components\Model;

class SubjectsFilter extends Model
{
    public $name = null;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            "name" => \Yii::t("main","Название"),
        ];
    }

    /**
     * @param \glob\components\ActiveQuery $query
     * @return \glob\components\ActiveQuery
     */
    public function appendFilter($query) {
        if ($this->name) {
            $s = explode(" ", $this->name);
            $q = "(";
            foreach ($s as $qq) {
                $q .= "dis.dis LIKE '%$qq%' OR dis.dis LIKE '%$qq%' OR ";
            }
            $q = rtrim($q, " OR ").")";
            $query->andWhere($q);
        }
        return $query;
    }

}

?>