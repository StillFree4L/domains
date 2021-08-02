<?php
class BaseOptions
{
    private static $_data;
    static function init()
    {
        
        self::$_data = Options::model()->getOptions();
    }

    /*
    public function __set($property, $value){
      return $this->_data[$property] = $value;
    }
    */

    public function __get($property){
      return array_key_exists($property, self::$_data)
        ? self::$_data[$property]
        : null
      ;
    }
}
?>
