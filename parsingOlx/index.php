<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'phpQuery/phpQuery/phpQuery.php';

$i=1;
$j=0;
$fileName = 'log.txt';
if (!file_exists($fileName)){file_put_contents($fileName, '');}

while($i!=0){
    //$i++;
$doc = phpQuery::newDocument(file_get_contents('https://www.olx.ua/nedvizhimost/kvartiry/?page='.$i));

$data['wrap'] = array();
$data['list'] = array();

$entry = $doc->find('.photo-cell a');

foreach ($entry as $row) {
    if ($i != 0) {
    $ent = pq($row);
    $url = $ent->attr('href');
    $data['wrap'][$j]['url'] = $url;//Ссылка
    if (!strpos(file_get_contents($fileName), $url)) {

        file_put_contents($fileName, PHP_EOL . $url, FILE_APPEND);
        $pagess = phpQuery::newDocument(file_get_contents($url));
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

    } else {
        $i = 0;
        break;
    }
    $j++;
}
}
}
print_r($data['wrap']);

?>