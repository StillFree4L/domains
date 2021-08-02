<?php
class PluginLoader
{
    public $_plugins = array();
    public function init()
    {
        
        $plugins = Plugins::model()->findAll();

        foreach ($plugins as $k=>$v)
        {

            $this->_plugins[$v->uniq_name] = $v;

            $path = Yii::getPathOfAlias("frontend.plugins.".$v->uniq_name);
            if (file_exists($path."/".$v->uniq_name."_init.php"))
            {
                Yii::import("frontend.plugins.".$v->uniq_name.".".$v->uniq_name."_init");
                $class = $v->uniq_name."_init";                
                $this->_plugins[$v->uniq_name]->init = new $class();
                $this->_plugins[$v->uniq_name]->init->init();
            }

        }

    }

    public function __get($property){
      return array_key_exists($property, $this->_plugins)
        ? $this->_plugins[$property]
        : null
      ;
    }

}
?>
