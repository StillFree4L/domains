<?php

namespace app\components;

/**
 * Базовая модель для возвращения в контроллер в JS
 * Class JModel
 */
class JModel
{
    private $_attributes;
    public function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }
        return null;
    }

    public function toJSON()
    {
        return json_encode($this->_attributes);
    }

    public function toArray()
    {
        foreach ($this->_attributes as $key => $attr) {
            return $this->_attributes;
        }
    }
    private function _toArray()
    {

    }

    public function append($attr, $value)
    {
        return $this->_attributes[$attr][] = $value;
    }

}
?>