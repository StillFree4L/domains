<?php

for ($i=1; $i < 12; $i++) {
  if($i==3 or $i==4)continue;

  $url = 'http://wb/wb/load.php?type='.$i.'&async=on&forcibly=on';
  //$url = 'http://wb/wb/load.php?type='.$i.'&forcibly=on';

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);

  curl_setopt($curl, CURLOPT_USERAGENT, 'Opera/9.62 (Windows NT 6.0; U; ru) Presto/2.1.1');
  curl_setopt($curl, CURLOPT_USERPWD, 'admin:123pass');
  curl_setopt($curl, CURLOPT_TIMEOUT, 1);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
  curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
  curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);

  curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);

  $data = curl_exec($curl);

  if (!curl_errno($curl)) {
    $info = curl_getinfo($curl);
    echo 'Прошло ', $info['total_time'], ' секунд во время запроса к ', $info['url'], "<br/>",$data, "<br/>";
  }

}
