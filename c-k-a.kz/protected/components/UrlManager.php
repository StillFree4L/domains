<?php

class UrlManager extends CUrlManager
{
	
	public $languages=array('ru','kz','en');
	public $langParam='lang';
	
	
        public function init()
        {

            parent::init();
        }
        
	public function langUrl($route, $params = array(), $ampersand = '&')
	{
		if(isset($params[$this->langParam])){
			$params[$this->langParam] = Yii::app()->language;
		}
		// change only absolute urls
		if(mb_substr($route, 0, 1) == '/'){
			if(!in_array(mb_substr($route, 1, 2), $this->languages)){
				$route = '/' . Yii::app()->language . '/' . ltrim($route, '/');
			}else{
				$route = $this->normalizeUrl(preg_replace('/^\/(ru|en|kz)\//Dui', '/' . Yii::app()->language . '/', $route));
			}
		}
		$url = parent::createUrl($route, $params, $ampersand);
		return $url;
	}
	
	public function createLangUrl($lang = 'ru')
	{
		$uri = $_SERVER['REQUEST_URI'];
		if(!in_array(mb_substr($uri, 1, 2), $this->languages)){
			return '/' . $lang . $uri;
		}else{
			return preg_replace('/^\/(ru|en|kz)\//Dui', '/' . $lang . '/', $uri);
		}
		//return $this->createUrl($this->normalizeUrl($route), $params);
	}
}

?>