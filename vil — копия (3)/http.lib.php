<?php

/*
//---------------
// making request - searching for working proxi
http_load_proxy_list('proxy.txt');
do
{
	http_sel_rand_proxy();
	http_del_cookie();	

	$r = http($URL_PARSE);
	
	global $HTTP_CUR_PROXY;var_dump($HTTP_CUR_PROXY);
}
while(strstr($r, 'Realty.View.')===false || strstr($r, 'error-page__error-name')!==false);



//----------------------------
// post headers

	$ps = http($p['phone_ajax'], 1, 
						array('secretFormValue'=>$secretFormValue), 
						array(	'X-Request-Value: '.$XHeader, //'127199414a555248458411710c244f75',
								'X-Requested-With:XMLHttpRequest'
							)
		);


*/

global $HTTP_COOKIE_FILE;
$HTTP_COOKIE_FILE = dirname(__FILE__).'/cookies.dat';

function http_set_cookie_file($fl)
{
  global $HTTP_COOKIE_FILE;
  $HTTP_COOKIE_FILE = $fl;
  //var_dump($HTTP_COOKIE_FILE);
}

function http_new_url($url,$json=null){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($json != null) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$GLOBALS["auth"]));
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
}

function http($url, $post=0, $ps = 0, $headers = 1)
{
  global $login, $password, $domain, $red_book_cms, $UAGENT;

  # ����������� User Agent "�� �����".
  # ���� ��������� � ����� �� ��������, � ����������� �������� *.txt.
  $user_agent = 'Opera/9.62 (Windows NT 6.0; U; ru) Presto/2.1.1';
  if (trim($UAGENT) != '') $user_agent = $UAGENT;

  global $HTTP_COOKIE_FILE;
  $cookies = $HTTP_COOKIE_FILE;
  //echo '<br>'.$cookies;

  # ��������, cURL:
  $red_book_cms = curl_init();

  global $HTTP_CUR_PROXY;
  if (trim($HTTP_CUR_PROXY)!='')
  {
  	curl_setopt($red_book_cms, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
  	curl_setopt($red_book_cms, CURLOPT_PROXY, $HTTP_CUR_PROXY);
  }


  global $HTTP_USER_PASS;
  if (trim($HTTP_USER_PASS) != '')
  {
    curl_setopt($red_book_cms, CURLOPT_USERPWD, $HTTP_USER_PASS);  
  }



  # ������ User Agent ("�������" ������ ������ ������������),
  # ������ �������� �������� - ��������.
  # cURL ����� ����� ���������� ������� �� ����� 10 ������.
  curl_setopt($red_book_cms, CURLOPT_USERAGENT, $user_agent);
  curl_setopt($red_book_cms, CURLOPT_REFERER, $url);

//  curl_setopt($red_book_cms, CURLOPT_TIMEOUT, 3);

//echo " -proxy:$HTTP_CUR_PROXY - ";
curl_setopt($red_book_cms, CURLOPT_CONNECTTIMEOUT, 3); 
//curl_setopt($red_book_cms, CURLOPT_TIMEOUT, 2);
//    curl_setopt($red_book_cms, CURLOPT_NOSIGNAL, 1);
    curl_setopt($red_book_cms, CURLOPT_TIMEOUT_MS, 5000);


//$url = 'http://whoer.net/';


  # ������ � GET-�������� ��� ����������� �� ����� mail.ru:
  curl_setopt($red_book_cms, CURLOPT_URL, $url);

//  curl_setopt($red_book_cms,CURLOPT_ENCODING , "gzip");

  # �� ����� ��������� SSL ���������� � Host SSL �����������
  curl_setopt($red_book_cms, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($red_book_cms, CURLOPT_SSL_VERIFYHOST, false);

  # ��������� ���������� ���������� ��������.
  # ���� �����������, ����� ��������� ��������� �� ����������������.
  curl_setopt($red_book_cms, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($red_book_cms, CURLOPT_FOLLOWLOCATION, true);

  # �������� � ������, cookies:
  curl_setopt($red_book_cms, CURLOPT_COOKIEFILE, $cookies);
  curl_setopt($red_book_cms, CURLOPT_COOKIEJAR, $cookies);

  //var_dump($cookies);

  if ($post == 1)
  {
    //echo "POST ! \n";
    curl_setopt($red_book_cms, CURLOPT_POST, true);
    //if (is_array($ps)) $ps = http_build_query($ps) ;
   /* $query = array('limit' => '1000','offset' => '0','total' => '0');
    $params = array('query' => $query,'supplierID' => 'b541a87c-d482-4161-9f30-5edc1fded445');
    $json2 = array('jsonrpc' => '2.0','params' => $params);
    $ps = json_encode($json2);*/
    curl_setopt($red_book_cms, CURLOPT_POSTFIELDS,$ps);
  }

  $hhs = array('Upgrade-Insecure-Requests: 1');
  if ($headers !== 1)
  {
    $hhs = array_merge($hhs, $headers);
  }
  global $HTTP_EX_HDRS;
  if (isset($HTTP_EX_HDRS))
  {
    $hhs = array_merge($hhs, $HTTP_EX_HDRS);
  }
  //curl_setopt($red_book_cms, CURLOPT_HTTPHEADER, $hhs);
    $authorization = 'Authorization: Bearer '.$GLOBALS["auth"];
  $arr = array('Content-Type: application/json',$hhs, $authorization,'Content-Length: 0');
    curl_setopt($red_book_cms, CURLOPT_HTTPHEADER, $arr);

//  curl_setopt($red_book_cms, CURLOPT_HEADER, 1);
//  curl_setopt($red_book_cms, CURLINFO_HEADER_OUT, true);
  $html = curl_exec($red_book_cms);


  global $HTTP_HDRS;
  $HTTP_HDRS = $headers;

  # �����������:
  curl_close($red_book_cms);
  return
  //$headers."\n\n=========================================\n".
  $html;
}

function http_del_cookie()
{
  global $HTTP_COOKIE_FILE;  
  file_put_contents($HTTP_COOKIE_FILE, '');
	@unlink($HTTP_COOKIE_FILE);
}

function http_load_proxy_list($fl)
{
	$buf = file_get_contents($fl);

	global $HTTP_PROXY_LIST;
	$HTTP_PROXY_LIST = explode("\n", trim($buf));
}

function http_sel_rand_proxy()
{
	global $HTTP_PROXY_LIST;
	global $HTTP_CUR_PROXY;
	$HTTP_CUR_PROXY = trim($HTTP_PROXY_LIST[ rand(0, count($HTTP_PROXY_LIST)-1) ]);
}

function pm($pat, $txt)
{
	preg_match($pat, $txt, $r);
	return $r[1];
}


function http_unurl($url)
{
global $GOM_HDRS;
$g = gom($url);
preg_match("@GET (/.*?) HTTP.*?Host: (.*?)\n@is", $GOM_HDRS, $rr);
$url = 'http://'.trim($rr[2]).trim($rr[1]);
return $url;
}

//кэшир склад старов api
function stock_cache_old(){
    $dir = 'cache';
    $fileName = $dir.'/'.$GLOBALS['auth'].'-stocks_old.txt';
    $http = 'https://suppliers-stats.wildberries.ru/api/v1/supplier/stocks?dateFrom=2000-03-25T21:00:00.000Z&key='.$GLOBALS['wb_key_new'];
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($fileName, '',FILE_APPEND);
    $lines = file($fileName);
    if ($lines[0] == "" || json_decode($lines[1]) == NULL || time() - intval($lines[0]) > 60*10){
        $r = http($http);
        if ($r==''){
            $r = http($http);
        }
        if ($r!='') {
            file_put_contents($fileName, time() . PHP_EOL);
            foreach (json_decode($r) as $rs) {
                file_put_contents($fileName, json_encode($rs).PHP_EOL, FILE_APPEND);
            }
            return $lines;
        }
        return false;
    }else{
        return $lines;
    }
}

function stock_cache($barcode){
    $dir = 'cache';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $fileName = $dir.'/'.$GLOBALS['auth'].'-stocks.txt';
    file_put_contents($fileName, '',FILE_APPEND);
    $lines = file($fileName);
    if ($lines[0] == "" || json_decode($lines[1]) == NULL || time() - intval($lines[0]) > 60*10){
        $r = http('https://suppliers-api.wildberries.ru/api/v2/stocks?skip=0&take=2000');
        if ($r) {
            file_put_contents($fileName, time() . PHP_EOL);
            foreach (json_decode($r)->stocks as $rs) {
                file_put_contents($fileName, json_encode($rs) . PHP_EOL, FILE_APPEND);
            }
        }
        return stock_cache($barcode);
    }else{
        foreach ($lines as $line_num => $line) {
            if (strpos($line, $barcode) or strpos($line, 'can\'t decode supplier key') !== false){
                return json_decode($line);
            }
        }
        $hts = http('https://suppliers-api.wildberries.ru/api/v2/stocks?skip=0&take=1&search=' . $barcode)->stocks;
        if ($hts) {
            foreach ($hts as $ht) {
                return $ht;
            }
        }else{
            return false;
        }
    }
}

function stock_barcode($arr,$col){
    $ra = stock_cache($col->barcode);
    if ($ra) {
            $arr['supplierArticle'] = $ra->article;
            $arr['subject'] = $ra->subject;
            $arr['brand'] = $ra->brand;
            $arr['techSize'] = $ra->size;
            $arr['fbs'] = $ra->stock;
            $arr['quantity'] = '1';//$c->stock;
        return $arr;
    }else{
        return stock_barcode($arr,$col);
    }
}
function status_object($arr,$col){
    if ($col->status == 0) {
        $arr['status'] = 'Новый заказ';
    } elseif ($col->status == 1) {
        $arr['status'] = 'Принял заказ';
    } elseif ($col->status == 2) {
        $arr['status'] = 'Сборочное задание завершено';
    } elseif ($col->status == 3) {
        $arr['status'] = 'Сборочное задание отклонено';
    } elseif ($col->status == 5) {
        $arr['status'] = 'На доставке курьером';
    } elseif ($col->status == 6) {
        $arr['status'] = 'Курьер довез и клиент принял товар';
    } elseif ($col->status == 7) {
        $arr['status'] = 'Клиент не принял товар';
    } elseif ($col->status == 8) {
        $arr['status'] = 'Товар для самовывоза из магазина принят к работе';
    } elseif ($col->status == 9) {
        $arr['status'] = 'Товар для самовывоза из магазина готов к выдаче';
    }
    if ($col->userStatus == 1) {
        $arr['userStatus'] = 'Отмена клиента';
    } elseif ($col->userStatus == 2) {
        $arr['userStatus'] = 'Доставлен';
    } elseif ($col->userStatus == 3) {
        $arr['userStatus'] = 'Возврат';
    } elseif ($col->userStatus == 4) {
        $arr['userStatus'] = 'Ожидает';
    } elseif ($col->userStatus == 5) {
        $arr['userStatus'] = 'Брак';
    }
    if ($col->deliveryType == 1) {
        $arr['deliveryType'] = 'обычная доставка';
    } else {
        $arr['deliveryType'] = 'доставка силами поставщика';
    }
    return $arr;
}

function card_info($array,$barcode,$results,$infos){
    foreach ($results->result->cards as $result){
        $array['category'] = $result->parent;
        foreach ($result->nomenclatures as $nomenclature) {
            if ($nomenclature->vendorCode == $barcode){
                foreach ($infos as $info) {
                    if ($info->nmId == $nomenclature->nmId) {
                        $array['discountPercent'] = $info->discount;
                        $array['promoCodeDiscount'] = $info->promoCode;
                       // $array['priceWithDisc'] = $info->price;
                    }
                }
                $array['nmId'] = $nomenclature->nmId;
                foreach ($nomenclature->variations as $variation) {
                    foreach ($variation->addin as $addin) {
                        foreach ($addin->params as $param) {
                            $array['totalPrice'] = $param->count;
                        }
                    }
                }
             //   $array['date'] = $result->createdAt;
                $array['lastChangeDate'] = $result->updatedAt;
                $array['cancel_dt'] = $nomenclature->updatedAt;
            }
        }
    }
    //var_dump($array);
    return $array;
}

function card_info_stocks($array,$barcode,$results,$infos){
    foreach ($results->result->cards as $result){
        foreach ($result->nomenclatures as $nomenclature) {
            if ($nomenclature->vendorCode == $barcode){
                foreach ($infos as $info) {
                    if ($info->nmId == $nomenclature->nmId) {
                        $array['Discount'] = $info->discount;
                        $array['promoCodeDiscount'] = $info->promoCode;
                        $array['price_min_discount'] = $info->price;
                    }
                }
                $array['nmId'] = $nomenclature->nmId;
                foreach ($nomenclature->variations as $variation) {
                    foreach ($variation->addin as $addin) {
                        foreach ($addin->params as $param) {
                            $array['Price'] = $param->count;
                        }
                    }
                }
                $array['lastChangeDate'] = $result->updatedAt;
                $array['category'] = $result->parent;
            }
        }
    }
    return $array;
}

function orders_object($r){
    $url1 = 'https://suppliers-api.wildberries.ru/card/list';
    $url2 = 'https://suppliers-api.wildberries.ru/public/api/v1/info';
    $wb = json_decode(http('https://suppliers-api.wildberries.ru/api/v2/warehouses'));

    $query = array('limit' => 1000,'offset' => 0,'total' => 0);
    $params = array('query' => $query,'supplierID' => 'b541a87c-d482-4161-9f30-5edc1fded445');
    $jsonDatas  = array('jsonrpc' => '2.0','params' => $params);

    $results = http_new_url($url1,json_encode($jsonDatas));
    $infos = http_new_url($url2);

    $r = $r->orders;
    $arr = array();
    $i = 0;
    foreach ($r as $col) {
        if (($_GET['type'] == 10 and ($col->userStatus == 1 or $col->userStatus == 3 or $col->userStatus == 5)) or ($_GET['type'] == 2)) {
            $arr[$i] = stock_barcode($arr[$i], $col);
            $arr[$i]['number'] = $col->orderId;
            $arr[$i]['date'] = strpos($col->dateCreated, '.') !== FALSE ? strtok($col->dateCreated, '.') : $col->dateCreated;
            $arr[$i]['lastChangeDate'] = strpos($col->dateCreated, '.') !== FALSE ? strtok($col->dateCreated, '.') : $col->dateCreated;
            $arr[$i]['barcode'] = $col->barcode;
            $arr[$i]['finishedPrice'] = substr($col->totalPrice,0,-2);
            if ($_GET['type'] == 10){
                $arr[$i]['orderId'] = $col->orderId;
            }
            $arr[$i]['incomeID'] = '';
            $arr[$i]['oblast'] = $col->officeAddress;
            if ($wb){
                foreach ($wb as $c) {
                    if ($c->id == $col->storeId){
                        $arr[$i]['warehouseName'] = $c->name;
                    }
                }
            }
            $arr[$i] = card_info($arr[$i],$arr[$i]['supplierArticle'],$results,$infos);

            if ($col->userStatus == 1 or $col->userStatus == 3 or $col->userStatus == 5) {
                $arr[$i]['isCancel'] = 1;
            } else {
                $arr[$i]['isCancel'] = 0;
            }
            $arr[$i]['gNumber'] = 0;
            $arr[$i]['odid'] = $col->orderId;
            $arr[$i]['cancel_dt'] = '';
            $arr[$i] = status_object($arr[$i], $col);
            $i++;
        }
       //if ($i>0)break;
    }
    return $arr;
}

function sales_object($r){
    $url1 = 'https://suppliers-api.wildberries.ru/card/list';
    $url2 = 'https://suppliers-api.wildberries.ru/public/api/v1/info';
    $wb = json_decode(http('https://suppliers-api.wildberries.ru/api/v2/warehouses'));

    $query = array('limit' => 1000,'offset' => 0,'total' => 0);
    $params = array('query' => $query,'supplierID' => 'b541a87c-d482-4161-9f30-5edc1fded445');
    $jsonDatas  = array('jsonrpc' => '2.0','params' => $params);

    $results = http_new_url($url1,json_encode($jsonDatas));
    $infos = http_new_url($url2);

    $r = $r->orders;
    $arr = array();
    $i = 0;
    foreach ($r as $col){
        if($col->userStatus==2) {
            $arr[$i] = stock_barcode($arr[$i],$col);
            $arr[$i]['number'] = '';
            $arr[$i]['date'] = strpos($col->dateCreated, '.') !== FALSE ? strtok($col->dateCreated, '.') : $col->dateCreated;
            $arr[$i]['barcode'] = $col->barcode;
            $arr[$i] = card_info($arr[$i],$arr[$i]['supplierArticle'],$results,$infos);
            $arr[$i]['isSupply'] = '';
            $arr[$i]['isRealization'] = '';
            $arr[$i]['orderId'] = $col->orderId;
            if ($wb){
                foreach ($wb as $c) {
                    if ($c->id == $col->storeId){
                        $arr[$i]['warehouseName'] = $c->name;
                    }
                }
            }
            $arr[$i]['countryName'] = $col->officeAddress;
            $arr[$i]['oblastOkrugName'] = $col->deliveryAddressDetails->province;
            $arr[$i]['incomeID'] = '';
            $arr[$i]['regionName'] = $col->officeAddress->street;
            $arr[$i]['saleID'] = '';
            $arr[$i]['spp'] = '';
            $arr[$i]['forPay'] = 0;
            $arr[$i]['finishedPrice'] = substr($col->totalPrice,0,-2);
            $arr[$i]['IsStorno'] = '';
            $arr[$i]['gNumber'] = $col->orderId;
          //  $arr[$i]['nmId'] = $col->pid;
            $arr[$i]['odid'] = $col->orderId;
            $arr[$i] = status_object($arr[$i],$col);
            $i++;
        }
        //if ($i>0)break;
    }
    return $arr;
}

function stocks_object($r){
    $url1 = 'https://suppliers-api.wildberries.ru/card/list';
    $url2 = 'https://suppliers-api.wildberries.ru/public/api/v1/info';
   // $wb = json_decode(http('https://suppliers-api.wildberries.ru/api/v2/warehouses'));

    $query = array('limit' => 1000,'offset' => 0,'total' => 0);
    $params = array('query' => $query,'supplierID' => 'b541a87c-d482-4161-9f30-5edc1fded445');
    $jsonDatas  = array('jsonrpc' => '2.0','params' => $params);

    $results = http_new_url($url1,json_encode($jsonDatas));
    $infos = http_new_url($url2);
    $arr = array();
    $i = 0;
    foreach ($r->stocks as $col){
        $arr[$i]['v'] = 'new';
        $arr[$i]['supplierArticle'] = $col->article;
        $arr[$i]['techSize'] = $col->size;
        $arr[$i]['barcode'] = $col->barcode;
        $arr[$i]['quantity'] = $col->stock;
        $arr[$i] = card_info_stocks($arr[$i],$col->article,$results,$infos);
      //  $arr[$i]['isSupply'] = '';
      //  $arr[$i]['isRealization'] = '';
     //   $arr[$i]['quantityFull'] = '';
     //   $arr[$i]['quantityNotInOrders'] = '';
        $arr[$i]['warehouseName'] = $col->warehouseName;
     //   $arr[$i]['inWayToClient'] = '';
     //   $arr[$i]['inWayFromClient'] = '';
        $arr[$i]['subject'] = $col->subject;
     //   $arr[$i]['daysOnSite'] = '';
        $arr[$i]['brand'] = $col->brand;
      //  $arr[$i]['SCCode'] = '';
        $i++;
    }
    return $arr;
}

function type_object($r){
    if($_GET['type'] == 2 || $_GET['type'] == 10){
        $r = orders_object($r);
        //$r = sales_object($r);
    }elseif($_GET['type'] == 1){
        $r = sales_object($r);
    }elseif($_GET['type'] == 6){
        $r = stocks_object($r);
    }
   // var_dump($r);
    return $r;
}

function http_json($api_url,$v=false){
    $r = http($api_url);
    $wbt = 0;
    while ($r == '')
    {
        $r = http($api_url);
        $wbt++;
        if ($wbt > 10) break;
    }
    if ($v){return type_object(json_decode($r));}
    return json_decode($r);
}

function array_unite($rs=null,$rs_new=null,$rs_sales=null){
    $array = array();
    $date = 0;

    if($_GET['type'] == 1){
        $cdt = 'lastChangeDate';
    }else{
        $cdt = 'date';
    }
    if ($rs) {
        foreach ($rs as $r) {
            $array[] = $r;
            if (strtotime($r->$cdt) > $date){
                $date = strtotime($r->$cdt);
            }
        }
    }
    if ($rs_sales){
        foreach ($rs_sales as $r_sales){
            $array[] = $r_sales;
            if (strtotime($r_sales->$cdt) > $date){
                $date = strtotime($r_sales->$cdt);
            }
        }
    }
    if ($rs_new){
        foreach ($rs_new as $r_new){
            if (strtotime($r_new[$cdt]) > $date or $_GET['type']==6){
                $array[] = (object)$r_new;
            }
        }
    }
    return json_encode($array);
}

//валидация ключа - форма
function api_valid($r_url_new){
    if ($r_url_new){
        if ($r_url_new!=""){
            if ($r_url_new!="invalid token"
                or $r_url_new!='supplier key not found'
               // or (array) $r_url_new['errors'][0] != 'can\'t decode supplier key'
                or $r_url_new!='unauthorized'){
                foreach ($r_url_new->errors as $err){
                    if($err == 'can\'t decode supplier key'){
                        return '<font color="red">не валиден</font>';
                    }
                }
                return '<font color="green">валиден</font>';
            }else{
                return '<font color="red">ключ api новый не валиден</font>';
            }
        }else{
            return '<font color="red">не валиден или нет ответа от сервера</font>';
        }
    }else{
        return '<font color="red">api запрос отсутствует или ключ не валиден</font>';
    }
}
