<?php
/**
 * CHtml class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * CLangHtml is a static class that provides a collection of helper methods for creating HTML views.
 */
class CLangHtml extends CHtml
{

	/**
	 * Generates a hyperlink tag.
	 * @param string $text link body. It will NOT be HTML-encoded. Therefore you can pass in HTML code such as an image tag.
	 * @param mixed $url a URL or an action route that can be used to create a URL.
	 * See {@link normalizeUrl} for more details about how to specify this parameter.
	 * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
	 * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
	 * @return string the generated hyperlink
	 * @see normalizeUrl
	 * @see clientChange
	 */
	public static function link($text,$url='#',$htmlOptions=array())
	{
		//Yii::log(print_r($url, true));
		
		if(is_array($url) && !isset($url['lang'])){
			$url['lang'] = Yii::app()->language;
		}
		if($url!=='')
			$htmlOptions['href']=self::normalizeUrl($url);
		
		//Yii::log($htmlOptions['href']);
		
		self::clientChange('click',$htmlOptions);
		return self::tag('a',$htmlOptions,$text);
	}

}
