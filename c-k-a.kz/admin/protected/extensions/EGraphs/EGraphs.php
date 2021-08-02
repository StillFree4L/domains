<?php

class EGraphs extends CWidget
{
    var $type, $graph_uniq_name, $data, $settings="", $width="500", $height="250", $file = true;
    var $baseUrl;
    public function run()
    {
        
        // Instance.js
        $assets = dirname(__FILE__).'/charts';
        $this->baseUrl = Yii::app()->assetManager->publish($assets,false,-1,false);
        Yii::app()->clientScript->registerScriptFile($this->baseUrl . "/".$this->type . '/swfobject.js', CClientScript::POS_HEAD);        
        
        $this->data = preg_replace("/[\r\n]/", "", $this->data);
        $this->data = preg_replace("/\"/", "\'", $this->data);

        $this->settings = preg_replace("/\n/", "", $this->settings);
        $this->settings = preg_replace("/\"/", "\'", $this->settings);

        if ($this->settings == "") {
            $str = 'so.addVariable("settings_file", "' . $this->baseUrl . '/' . $this->type . '/am' . $this->type . '_settings.xml");';
        } else {
            $str = 'so.addVariable("' . ($this->file ? 'settings_file' : 'chart_settings') . '", "' . $this->settings . '");';
        }

        $this->render("chart", array(
            "str"=>$str
        ));
        
    }
    
}
?>
