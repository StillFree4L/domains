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
        $authorization = 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2Nlc3NJRCI6ImY5MmY5Y2NmLTE5ZmUtNDQ4Ny04NmI3LWRjMmM3NjgzNGVjZSJ9.KdO7hVoY5hNmAciFrcK8zfxr6blu8AEe-qNx8IrfsDM';
        curl_setopt($red_book_cms, CURLOPT_HTTPHEADER, array($hhs, $authorization));

//  curl_setopt($red_book_cms, CURLOPT_HEADER, 1);
//  curl_setopt($red_book_cms, CURLINFO_HEADER_OUT, true);

  $html = curl_exec($red_book_cms);
  $headers = curl_getinfo($red_book_cms, CURLINFO_HEADER_OUT);


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

function stock_barcode($arr,$col){
    $ra = json_decode(http('https://suppliers-api.wildberries.ru/api/v2/stocks?skip=0&take=1&search='.$col->barcode));
    if ($ra != null) {
        foreach ($ra->stocks as $c) {
            $arr['supplierArticle'] = $c->article;
            $arr['subject'] = $c->subject;
            $arr['brand'] = $c->brand;
            $arr['techSize'] = $c->size;
            $arr['quantity'] = $c->stock;
        }
        $arr['warehouseName'] = $ra->total;
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

function orders_object($r,$type){
    $r = $r->orders;
    $arr = array();
    $i = 0;
    foreach ($r as $col) {
        if (($type == 10 and ($col->userStatus == 1 or $col->userStatus == 3 or $col->userStatus == 5)) or ($type == 2)) {
            $arr[$i] = stock_barcode($arr[$i], $col);
            $arr[$i]['number'] = 0;
            $arr[$i]['date'] = $col->dateCreated;
            $arr[$i]['lastChangeDate'] = $col->dateCreated;
            $arr[$i]['barcode'] = $col->barcode;
            $arr[$i]['totalPrice'] = substr($col->totalPrice, 0, -2);
            $arr[$i]['discountPercent'] = 0;
            $arr[$i]['incomeID'] = 0;
            $arr[$i]['oblast'] = '';
            $arr[$i]['officeAddress'] = $col->officeAddress;
            $arr[$i]['category'] = '';
            if ($col->userStatus == 1) {
                $arr[$i]['isCancel'] = true;
            } else {
                $arr[$i]['isCancel'] = false;
            }
            $arr[$i]['gNumber'] = 0;
            $arr[$i]['nmId'] = '';
            $arr[$i]['odid'] = $col->orderId;
            $arr[$i]['cancel_dt'] = '';
            $arr[$i] = status_object($arr[$i], $col);
            $i++;
        }
    }
    return (object) $arr;
}

function sales_object($r){
    $r = $r->orders;
    $arr = array();
    $i = 0;
    foreach ($r as $col){
        if($col->userStatus==2) {
            $arr[$i] = stock_barcode($arr[$i],$col);
            $arr[$i]['number'] = '';
            $arr[$i]['date'] = $col->dateCreated;
            $arr[$i]['lastChangeDate'] = $col->dateCreated;
            $arr[$i]['barcode'] = $col->barcode;
            $arr[$i]['totalPrice'] = '';
            $arr[$i]['discountPercent'] = '';
            $arr[$i]['isSupply'] = '';
            $arr[$i]['isRealization'] = '';
            $arr[$i]['orderId'] = $col->orderId;
            $arr[$i]['promoCodeDiscount'] = '';
            $arr[$i]['countryName'] = $col->officeAddress;
            $arr[$i]['oblastOkrugName'] = $col->deliveryAddressDetails->province;
            $arr[$i]['incomeID'] = '';
            $arr[$i]['regionName'] = $col->officeAddress->street;
            $arr[$i]['saleID'] = '';
            $arr[$i]['spp'] = '';
            $arr[$i]['forPay'] = '';
            $arr[$i]['finishedPrice'] = substr($col->totalPrice,0,-2);
            $arr[$i]['priceWithDisc'] = '';
            $arr[$i]['category'] = '';
            $arr[$i]['IsStorno'] = '';
            $arr[$i]['gNumber'] = $col->orderId;
            $arr[$i]['nmId'] = '';
            $arr[$i]['odid'] = $col->orderId;
            $arr[$i] = status_object($arr[$i],$col);
            $i++;
        }
    }
    return (object) $arr;
}

function stocks_object($r,$dt1){
    $arr = array();
    $i = 0;
    $h = 0;
    $ra = '';
    foreach ($r->stocks as $col){
        $arr[$i]['supplierArticle'] = $col->article;
        $arr[$i]['techSize'] = $col->size;
        $arr[$i]['barcode'] = $col->barcode;
        $arr[$i]['quantity'] = $col->stock;
        $arr[$i]['isSupply'] = '';
        $arr[$i]['isRealization'] = '';
        $arr[$i]['quantityFull'] = '';
        $arr[$i]['quantityNotInOrders'] = '';
        $arr[$i]['warehouseName'] = $col->warehouseName;
      //  $arr[$i]['name'] = $col->name;
        $arr[$i]['inWayToClient'] = '';
        $arr[$i]['inWayFromClient'] = '';
        $arr[$i]['nmId'] = '';
        $arr[$i]['subject'] = $col->subject;
        $arr[$i]['category'] = '';
        $arr[$i]['daysOnSite'] = '';
        $arr[$i]['brand'] = $col->brand;
        $arr[$i]['SCCode'] = '';
        $arr[$i]['Price'] = '';
        $arr[$i]['Discount'] = '';
        $i++;
    }
    return (object) $arr;
}
