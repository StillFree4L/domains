<?php

namespace glob\components;

use glob\models\Platforms;

class ActiveQuery extends \yii\db\ActiveQuery {

    public $alias = null;
    public function init()
    {
        $modelClass = $this->modelClass;
        $tableName = $modelClass::tableName();
        $this->alias = $tableName;
        parent::init();
    }

    public function byDateDesc()
    {
        return $this->orderBy($this->alias.".ts DESC");
    }

    public function signed()
    {
        return $this->andWhere($this->alias.".state != ".ActiveRecord::DELETED);
    }

    public function notDeleted()
    {
        return $this->andWhere($this->alias.".state != ".ActiveRecord::DELETED);
    }

    public function byPk($value)
    {
        $model = $this->modelClass;
        $pks = $model::primaryKey();

        $condition = [];
        if (!is_array($value)) {
            $condition[$this->alias.".".$pks[0]] = $value;
        } else {
            foreach ($value as $k=>$v) {
                if (isset($pks[$k])) {
                    $condition[$this->alias.".".$pks[$k]] = $v;
                }
            }
        }

        return $this->andWhere($condition);
    }

}

?>