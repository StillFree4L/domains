<?php

class BaseActiveRecord extends CActiveRecord
{

    protected $multilang = false;
    protected $duplicate = false;

    private static $_events = array();

    public function tableName($name)
    {

        if ($this->multilang) {
            return $name."_".Yii::app()->language;
        }
        return $name;
    }

    public function primaryKey()
    {
        return "id";
    }

    public function getInfo()
    {

    }

    public function beforeValidate()
    {
        if (!empty($this->ts) AND !is_numeric($this->ts)) $this->ts = strtotime($this->ts);
        return parent::beforeValidate();
    }
    public function beforeSave()
    {        
        if ($this->isNewRecord AND empty($this->ts)) {
            $this->ts = time();
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {

        if ($this->multilang AND !$this->duplicate)
        {
            $langs = Yii::app()->UrlManager->languages;
            $_orig = Yii::app()->language;
            foreach ($langs as $lang)
            {
                Yii::app()->language = $lang;
                if ($lang != $_orig) {
                    $id = $this->id;
                    $attributes = $this->attributes;
                    $m = new $this();
                    $m->refreshMetaData();
                    $new = $m->resetScope()->findByPk($id);

                    if (!$new) {
                            $new = new $this();
                            $new->refreshMetaData();
                            $new->duplicate = true;
                            $new->attributes = $attributes;
                            $new->id = $id;
                            $new->save();
                    }
                }

            }
            Yii::app()->language = $_orig;

        }
        parent::afterSave();
    }

    public function afterDelete()
    {
        if ($this->multilang AND !$this->duplicate)
        {

            $langs = Yii::app()->UrlManager->languages;
            $_orig = Yii::app()->language;
            foreach ($langs as $lang)
            {
                Yii::app()->language = $lang;
                if ($lang != $_orig) {
                    $id = $this->id;
                    $m = new $this();
                    $m->refreshMetaData();
                    $new = $m->resetScope()->findByPk($id);
                    if ($new) {
                            $new->duplicate = true;
                            $new->delete();
                    }
                }

            }
            Yii::app()->language = $_orig;

        }
        parent::afterDelete();
    }

    public function afterFind()
    {
        $this->callEvents("afterFind");
        parent::afterFind();
    }

    private function callEvents($event)
    {
        
        if (isset(self::$_events[get_class($this)]) AND !empty(self::$_events[get_class($this)]))
        {
            foreach (self::$_events[get_class($this)] as $h => $e)
            {
                
                if (isset($e[$event]))
                {
                    
                    $func = $e[$event];
                    call_user_func(array($h,$func),$this);
                }
            }
        }
    }

    public function addEventHandler($handler, $event, $callback)
    {
        if (!isset(self::$_events[get_class($this)][$handler][$event]))
        {
            self::$_events[get_class($this)][$handler][$event] = $callback;
            
        }
    }

}

?>
