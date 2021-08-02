<?php

namespace glob\components;

use glob\behaviors\BackboneRequestBehavior;
use yii;

/**
 * Базовая модель.
 * Class ActiveRecord
 */
class ActiveRecord extends yii\db\ActiveRecord
{

    public $instance = null;

    // Установить true если запись при удалении не удаляется, а помечается state = self::DELETED
    protected static $UPDATE_ON_DELETE = false;    

    // state = 3 - Запись удалена
    const DELETED = 3;

    public $logging = false;   
    private $_oldAttributes = [];
    public $search = "";

    protected $_attributes=[];

    private function logChanges()
    {
        
    }

    public function behaviors()
    {
        return [
            "backboneRequestBehavior" => [
                "class"=> BackboneRequestBehavior::className()
            ]
        ];
    }

    public static function find()
    {
        return new ActiveQuery(get_called_class());
    }


    // Сохраняет предыдущие значения аттрибутов, перед записью в базу
    public function beforeSave($insert)
    {
        if (in_array("ts", $this->attributes()) AND $this->isNewRecord AND empty($this->ts)) {
            $this->ts = time();
        }

        if (in_array("info", $this->attributes()) AND is_array($this->info)) {
            $this->info = json_encode($this->info);
        }

        return parent::beforeSave($insert);
    }
    
    public function afterDelete()
    {
        return parent::afterDelete();
    }
    
    
    /**
     * Методы удаления для записей, которые не нужно удалять, а ставить конолнку state = self::DELETED;
     */
    public function delete($force = false)
    {
        $c = get_class($this);
        // Если переменная true, то не удаляем запись, а только ставим параметр state = self::DELETED;
        if ($c::$UPDATE_ON_DELETE AND !$force) 
        {

            $this->state = self::DELETED;
            return $this->save();
            
        } else {
            return parent::delete();
        }
    }

    public static function deleteAll($condition = '', $params = []) {
        $c = static::className();
        // Если переменная true, то не удаляем запись, а только ставим параметр state = self::DELETED;
        if ($c::$UPDATE_ON_DELETE) 
        {
            return parent::updateAll([
                "state"=>self::DELETED
            ], $condition, $params);
        } else {
            return parent::deleteAll($condition, $params);
        }
    }

    /**
     * Возвращает аттрибуты модели в виде массива
     * @param mixed $models - модель
     * @param array $relations - если нужно обработать связи
     * @return array
     */
    public static function arrayAttributes($models, $relations = [], $fields = [], $allowEmpty = false)
    {

        if (is_array($models)) {
            $result = [];
            foreach ($models as $k=>$m) {

                if (empty($fields)) {
                    $fields = $m->fields();
                }

                $attr = [];
                foreach ($fields as $f) {
                    if (method_exists($m, $f)) {
                        $attr[$f] = $m->$f();
                    } else $attr[$f] = $m->$f;
                }

                foreach ($attr as $name=>$value) {
                    if (!empty($value) OR $allowEmpty) {
                        $result[$k][$name] = is_numeric($value) ? (($value == (int) $value) ? (int) $value : (float) $value) : $value;
                    }
                }
                if (!empty($relations))
                {
                    foreach ($relations as $r=>$v) {
                        if (!is_array($v)) {
                            if (is_object($m->$v) OR is_array($m->$v)) {
                                $result[$k][$v] = self::arrayAttributes($m->$v);
                            } else {
                                $result[$k][$v] = $m->$v;
                            }
                        } else {

                            $_rels = [];
                            if (isset($v['relations'])) {
                                $_rels = $v['relations'];
                            }
                            $r_fields = [];
                            if (isset($v['fields'])) {
                                $r_fields = $v['fields'];
                            }

                            if (!isset($v['relations']) AND !isset($v['fields'])) {
                                $_rels = $v;
                            }

                            $result[$k][$r] = self::arrayAttributes($m->$r, $_rels, $r_fields);
                        }
                    }
                }
            }
            
        } else {

            if (empty($fields)) {
                $fields = $models->fields();
            }

            $attr = [];
            foreach ($fields as $f) {
                if (method_exists($models, $f)) {
                    $attr[$f] = $models->$f();
                } else $attr[$f] = $models->$f;
            }

            foreach ($attr as $name=>$value) {
                if (!empty($value) OR $allowEmpty) {
                    $result[$name] = is_numeric($value) ? (($value == (int) $value) ? (int) $value : (float) $value) : $value;
                }
            }
            if (!empty($relations))
            {
                foreach ($relations as $r=>$v) {
                    if (!is_array($v)) {
                        if (is_object($models->$v) OR is_array($models->$v)) {
                            $result[$v] = self::arrayAttributes($models->$v);
                        } else {
                            $result[$v] = $models->$v;
                        }
                    } else {

                        $_rels = [];
                        if (isset($v['relations'])) {
                            $_rels = $v['relations'];
                        }
                        $r_fields = [];
                        if (isset($v['fields'])) {
                            $r_fields = $v['fields'];
                        }

                        if (!isset($v['relations']) AND !isset($v['fields'])) {
                            $_rels = $v;
                        }

                        $result[$r] = self::arrayAttributes($models->$r, $_rels, $r_fields);
                    }
                }
            }
        }
        return $result;
    }

    public function translate($attribute)
    {
        $t = json_decode($attribute, true);
        if (is_array($t)) {
            if (isset($t[\Yii::$app->language])) {
                return $t[\Yii::$app->language];
            } else if (isset($t['ru'])) {
                return $t['ru'];
            }
        }
        return $attribute;
    }

    public function getFilterCriteria() {
        return new CDbCriteria();
    }

    public function addVisit($targetType) {
        $visit = new Visits();
        $visit->ts = time();
        if (!\Yii::$app->user->isGuest) $visit->user_id = \Yii::$app->user->id;
        $visit->target_id = $this->id;
        $visit->target_type = $targetType;
        $visit->save();
    }

    public function getJInfo()
    {
        if (isset($this->info)) {
            return is_array($this->info) ? $this->info : json_decode($this->info,true);
        }
        return false;
    }

    public function setInfo($name, $value)
    {
        $jInfo = $this->jInfo;
        if (!$jInfo) {
            $jInfo = array();
        }
        $jInfo[$name] = $value;
        $this->info = json_encode($jInfo);
    }

    public function updateInfo()
    {
        $this->save();
    }

    public static function autoComplete($attribute, $query)
    {
        $data = static::find()->filterWhere(["like", $attribute, $query])
            ->distinct(true)->all();

        $result = [
            "query"=>$query,
            "suggestions"=>[]
        ];
        if (!empty($data)) {
            foreach($data as $d) {
                $result['suggestions'][] = $d->{$attribute};
            }
        }
        return $result;
    }

    public function __get($name) {
        if (substr($name, strlen($name) - 4, 4) == "Json") {
            $name = substr($name,0,strlen($name)-4);
            $attr = parent::__get($name);
            return is_array($attr) ? $attr : (json_decode($attr, true) ? json_decode($attr, true) : []);
        }
        return parent::__get($name);
    }

}
?>
