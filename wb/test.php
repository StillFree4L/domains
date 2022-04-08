<?php

$urls = array(
    'http://wb/wb/load.report.php?type=1&load=stock_cache_old',
    'http://wb/wb/load.report.php?type=1&load=stock_cache_new',
    'http://wb/wb/load.report.php?type=1&load=report_cache',
    'http://wb/wb/load.report.php?type=2&load=report_cache',
    'http://wb/wb/load.report.php?type=5&load=report_cache',
    'http://wb/wb/load.report.php?type=10&load=report_cache',
);
$url = 'http://wb/wb/load.report.php?type=1&load=stock_cache_new';
/*
foreach ($urls as $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    echo curl_exec($ch);
    curl_close($ch);
}*/

$multi = curl_multi_init();
$channels = array();


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_multi_add_handle($multi, $ch);

    $channels[$url] = $ch;


$active = null;
do {
    $mrc = curl_multi_exec($multi, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);

while ($active && $mrc == CURLM_OK) {
    if (curl_multi_select($multi) == -1) {
        continue;
    }

    do {
        $mrc = curl_multi_exec($multi, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
}

foreach ($channels as $k=>$channel) {
    $tmp = curl_multi_getcontent($channel);
  //  echo '<br/>';
    curl_multi_remove_handle($multi, $channel);
}
echo $tmp;

curl_multi_close($multi);

?>
