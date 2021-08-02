<?php

namespace app\helpers;

class Html extends \yii\helpers\Html
{

    public static function img($src, $options = [])
    {

        if (!$src) $src = "";
        if (!is_array($src))
        {
            $src_a = json_decode($src, true);
            if (is_array($src_a))
            {
                if (isset($options['preview'])) {
                    $src = $src_a[$options['preview']];
                } else {
                    $src = $src_a['url'];
                }
            } else {

            }
        } else {
            if (isset($options['preview'])) {
                $src = $src[$options['preview']];
            } else {
                $src = $src['url'];
            }

        }

        if (!isset($options['onerror']))
        {
            $options['onerror'] = "this.src='".\Yii::$app->assetManager->getBundle('base')->baseUrl."/img/default.png'";
        }

        return parent::img($src, $options);

    }

    public static function userImg($src, $options = [])
    {

        $options['src'] = $src;
        if (!isset($options['onerror']))
        {
            $options['onerror'] = "this.src='".\Yii::$app->assetManager->getBundle('base')->baseUrl."/img/default_ava_0.png'";
        }

        return static::tag('img', '', $options);

    }

    public static function dropDownList($name, $selection = null, $items = [], $options = [])
    {
        if (isset($options['empty'])) {
            $items = [""=>$options['empty']] + $items;
        }
        return parent::dropDownList($name, $selection, $items, $options);
    }

    public static function encode($content, $doubleEncode = true)
    {
        if (substr($content, 0, 2) == "<%") {
            return $content;
        }
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, \Yii::$app ? \Yii::$app->charset : 'UTF-8', $doubleEncode);
    }

}

?>