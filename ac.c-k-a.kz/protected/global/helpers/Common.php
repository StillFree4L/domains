<?php

namespace glob\helpers;

use glob\models\Regions;
use yii\helpers\Url;

class Common {

    public static function getDefaultRegion()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"), true);
        $r = Regions::find()->andWhere([
            "name"=>$details['city']
        ])->one();
        return $r ? $r->id : 18;
    }

    public static function createUrl($route, $params = []) {
        array_unshift($params , $route);
        return Url::to($params);
    }

    public static function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public static function multiplier($value, $words = array())
    {
        if ($value % 10 == 1 AND ($value<10 OR $value>20)) return $words[0];
        else if ($value % 10 > 1 AND $value % 10 < 5 AND ($value<10 OR $value>20)) return $words[1];
        else return $words[2];
    }

    /**
     * Filters "{asdasd,asdasd}" column into asdasd by language;
     */
    public static function __lf($field, $l = null)
    {
        if ($l == null) $l = \Yii::$app->languageSetter->getLN();
        $f = trim($field,"{}");
        $fs = explode(",",$f);
        return isset($fs[$l-1]) ? trim($fs[$l-1],"\"") : $f;
    }

    public static function translitIt($str)
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
        );
        return strtr($str,$tr);
    }

    public static function getUserHash()
    {
        return md5(\Yii::$app->user->id.SECRET_WORD);
    }

    public static function isJSON($s) {
        return preg_match('/^[\[\{]\"/', $s);
    }

    public static function destroy_dir($dir) {
        if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $file) {
            if ($file == '.' || $file == '..') continue;
            if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                chmod($dir . DIRECTORY_SEPARATOR . $file, 0777);
                if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) return false;
            };
        }
        return rmdir($dir);
    }

    public static function includePhpQuery()
    {
        require_once(dirname(__FILE__) . "/phpQuery.php");
    }

    public static function days_in_month($month, $year)
    {
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    public static function get_fcontent( $url,  $javascript_loop = 0, $timeout = 5 ) {
        $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

        //$cookie = tempnam ("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt( $ch, CURLOPT_URL, $url );
        //curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
        //curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_ENCODING, "" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        $content = curl_exec( $ch );
        $response = curl_getinfo( $ch );
        curl_close ( $ch );

        if ($response['http_code'] == 301 || $response['http_code'] == 302) {
            ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

            if ( $headers = get_headers($response['url']) ) {
                foreach( $headers as $value ) {
                    if ( substr( strtolower($value), 0, 9 ) == "location:" )
                        return get_url( trim( substr( $value, 9, strlen($value) ) ) );
                }
            }
        }

        if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
            return get_url( $value[1], $javascript_loop+1 );
        } else {
            return $content;
        }
    }

    public static function getimg($url) {
        if (strpos($url, 'files.marikz.com') !== false) {
            return file_get_contents("/var/www/v-31349/data/www/files.marikz.com/".str_replace("http://files.marikz.com/","",$url));
        } else {
            $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
            $headers[] = 'Connection: Keep-Alive';
            $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
            $user_agent = 'php';
            $process = curl_init($url);
            curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($process, CURLOPT_HEADER, 0);
            curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
            $return = curl_exec($process);
            curl_close($process);
            return $return;
        }
    }

}


?>
