<?php

namespace app\components;

class BreadCrumbs extends \yii\base\Component
{
    private $breadcrumbs = [];
    public function init() {
        
    }
    public function getLinks()
    {
        return $this->breadcrumbs;
    }
    public function addLink($header, $url = null, $options = [])
    {
        $this->breadcrumbs[] = [
            "header"=>$header,
            "url"=>$url,
            "options"=>$options,
        ];
    }
    public function clearLinks()
    {
        $this->breadcrumbs = [];
    }
}
?>
