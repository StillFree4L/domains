<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'phpQuery/phpQuery/phpQuery.php';

function curl_content($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; WinNT; en; rv:1.0.2) Gecko/20030311 Beonex/0.8.2-stable');
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefiles);
//curl_setopt($ch, CURLOPT_NOBODY,true);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefiles);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
    $html=curl_exec($ch);
    return $html;
    unset($url);
}

$i=1;
$j=0;
//$fileName = 'log.txt';
//if (!file_exists($fileName)){file_put_contents($fileName, '');}

while($i!=0){
    //$i++;
    $html=curl_content('https://www.olx.ua/nedvizhimost/kvartiry/?page='.$i);
    //$doc = phpQuery::newDocument(file_get_contents($html));
    $doc = phpQuery::newDocument($html);

    $data['wrap'] = array();
    $data['list'] = array();

    $entry = $doc->find('.photo-cell a');

    foreach ($entry as $row) {
        if ($i != 0) {
            $ent = pq($row);
            $url = $ent->attr('href');
            $data['wrap'][$j]['url'] = $url;
    //if (!strpos(file_get_contents($fileName), $url)) {

        //file_put_contents($fileName, PHP_EOL . $url, FILE_APPEND);
            $htm=curl_content($url);
        $pagess = phpQuery::newDocument($htm);
        $data['wrap'][$j]['name'] = $pagess->find('.css-r9zjja-Text')->text();//Название
        $data['wrap'][$j]['description'] = $pagess->find('.css-g5mtbi-Text')->text();//Описание
        $data['wrap'][$j]['prime'] = substr($pagess->find('.css-okktvh-Text')->text(),0,-2);//Цена
        $pages = $pagess->find('ol.css-2tdfce li');
        foreach ($pages as $ro) {
            $data['list'][] = pq($ro)->text();
        }
        $data['wrap'][$j]['city'] = substr(strstr(end($data['list']), ' - '),2);//Город
        $data['wrap'][$j]['id'] = substr(strstr($pagess->find('a.css-lo5evj-BaseStyles')->attr('href'), 'id='),3);//ID
        $pages = $pagess->find('.swiper-wrapper img');//Картинки
        foreach ($pages as $ro) {
            $link = pq($ro)->parents('.adPhotos-swiperSlide');
            if((pq($ro)->attr('src'))){$data['wrap'][$j]['image'][] = pq($ro)->attr('src');}
            else{$data['wrap'][$j]['image'][] = pq($ro)->attr('data-src');}
        }

    /*} else {
        $i = 0;
        break;
    }*/
            echo "<pre>";
            print_r($data['wrap'][$j]);
            echo "/<pre>";
    $j++;

        }
    }
}

//print_r($data['wrap']);

?>