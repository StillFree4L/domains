<?php

/*
 * Return json encoded data to client
 */

function get_arr($args)
{
	$out = array();
	foreach($args as $arg){
		if(!is_array($arg)){
			$arg = array('text'=>t($arg));
		}
		$out = @array_merge($out,$arg);
	}
	return $out;
}

function r_json($out)
{
	print json_fix_cyr(json_encode(get_arr($out)));
	Yii::app()->end();
}

function r_json_ok()
{
	$out = get_arr(func_get_args());
	$out['status'] = 'ok';
	print json_fix_cyr(json_encode($out));
	Yii::app()->end();
}

function r_json_warn()
{
	$out = get_arr(func_get_args());
	$out['status'] = 'warn';
	print json_fix_cyr(json_encode($out));
	Yii::app()->end();
}

function r_json_err()
{
	$out = get_arr(func_get_args());
	$out['status'] = 'err';
	print json_fix_cyr(json_encode($out));
	Yii::app()->end();
}
function json_fix_cyr($json_str)
{
	if(is_object($json_str) || is_array($json_str)){
		foreach($json_str as &$v){
			$v = json_fix_cyr($v);
		}
		return $json_str;
	}else{
		$cyr_chars = array(
            '\u0430' => 'а', '\u0410' => 'А',
            '\u0431' => 'б', '\u0411' => 'Б',
            '\u0432' => 'в', '\u0412' => 'В',
            '\u0433' => 'г', '\u0413' => 'Г',
            '\u0434' => 'д', '\u0414' => 'Д',
            '\u0435' => 'е', '\u0415' => 'Е',
            '\u0451' => 'ё', '\u0401' => 'Ё',
            '\u0436' => 'ж', '\u0416' => 'Ж',
            '\u0437' => 'з', '\u0417' => 'З',
            '\u0438' => 'и', '\u0418' => 'И',
            '\u0439' => 'й', '\u0419' => 'Й',
            '\u043a' => 'к', '\u041a' => 'К',
            '\u043b' => 'л', '\u041b' => 'Л',
            '\u043c' => 'м', '\u041c' => 'М',
            '\u043d' => 'н', '\u041d' => 'Н',
            '\u043e' => 'о', '\u041e' => 'О',
            '\u043f' => 'п', '\u041f' => 'П',
            '\u0440' => 'р', '\u0420' => 'Р',
            '\u0441' => 'с', '\u0421' => 'С',
            '\u0442' => 'т', '\u0422' => 'Т',
            '\u0443' => 'у', '\u0423' => 'У',
            '\u0444' => 'ф', '\u0424' => 'Ф',
            '\u0445' => 'х', '\u0425' => 'Х',
            '\u0446' => 'ц', '\u0426' => 'Ц',
            '\u0447' => 'ч', '\u0427' => 'Ч',
            '\u0448' => 'ш', '\u0428' => 'Ш',
            '\u0449' => 'щ', '\u0429' => 'Щ',
            '\u044a' => 'ъ', '\u042a' => 'Ъ',
            '\u044b' => 'ы', '\u042b' => 'Ы',
            '\u044c' => 'ь', '\u042c' => 'Ь',
            '\u044d' => 'э', '\u042d' => 'Э',
            '\u044e' => 'ю', '\u042e' => 'Ю',
            '\u044f' => 'я', '\u042f' => 'Я',
            '\r' => '',
            '\n' => '<br />',
            '\t' => ''
		);
		foreach ($cyr_chars as $cyr_char_key => $cyr_char) {
			$json_str = str_replace($cyr_char_key, $cyr_char, $json_str);
		}
		return $json_str;
	}
}

/**
 * Translate function
 * 
 * @param string $text for translate
 * @param int $num if used plural types of string
 * @param string $context
 */
function t($text, $num = 0, $context = 'app')
{
	return	Yii::t($context, $text, $num);
}


/**
 * Register jgrowl js and css files
 *
 * @return void
 */
function registerJGrowl()
{
	static $registered = false;
	if(!$registered){
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile(CHtml::asset('js/jquery.jgrowl.min.js'));
		$cs->registerCSSFile(CHtml::asset('css/jquery.jgrowl.css'));
		$registered = true;
	}
}

/**
 * Register blockui jquery plugin file
 *
 * @return void
 */
function registerBlockUI()
{
	static $registered = false;
	if(!$registered){
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile(CHtml::asset('js/jquery.blockui.min.js'));
		$registered = true;
	}
}



