<?php

if($_GET['async']=='on'){
set_time_limit(0);
  while(ob_get_level()) ob_end_clean();
  header('Connection: close');
  ignore_user_abort();
  ob_start();
  echo('Ожидайте');
  $size = ob_get_length();
  header("Content-Length: $size");
  ob_end_flush();
  flush();
}

require_once('blocks/func_key.php');
require_once('blocks/func_tbl_keys.php');

$v_api = ["success"=>false,"message"=>'<font color="red">Нет данных</font>'];

$data_time1 = time();

barOption($link,'forcibly',$data_time1,$USER["id"]);
writeStatus($link,$USER["id"],$_GET['type'],'1',$data_time1);
mysqli_close($link);
    require_once('blocks/func_api.php');

      if ($buf == "" || $_GET['forcibly']=='on' || json_decode($buf2[1]) == NULL || time() - intval($buf2[0]) > 60*60 || strpos($buf, 'can\'t decode supplier key') !== false)
      {
      //  var_dump($api_url.' - '.$api_url_sales.' - '.$api_url_new);
        if ($api_url or $api_url_sales or $api_url_new){

          $urls_name = [];

          if($GLOBALS['wb_key_new']){
            $urls_name[]='stock_cache_old';
          }

          if($GLOBALS['auth']){
            $urls_name[]='stock_cache_new';
          }

          if (in_array($_GET['type'], [1,2,10]) && $GLOBALS['wb_key_new']){
            $urls_name[] = 'report_cache';
          //  $r_url_report =  json_decode(report_cache());
          //  $r_url_report = async_api('report_cache');
          }
          if ($api_url && $GLOBALS['wb_key_new']){
            $urls_name[] = 'r_url';
            //$r_url = http_json($api_url);
          //  $r_url = async_api('r_url');
          }

          if ($api_url_sales && $GLOBALS['wb_key_new']){
            $urls_name[] = 'r_url_sales';
          //  $r_url_sales = http_json($api_url_sales);
          //  $r_url_sales = async_api('r_url_sales');
          }

          if ($api_url_new && $GLOBALS['auth']){
            $urls_name[] = 'r_url_new';
          //  $r_url_new = http_json($api_url_new,true);
          //  $r_url_new = async_api('r_url_new');
          }
            //var_dump($urls);
          if (in_array($_GET['type'], [1,2,10,6]) && $GLOBALS['wb_key_new']){
            $urls_name[] = 'card_list';
            $urls_name[] = 'v1_info';
            $urls_name[] = 'warehouses';
          }

          //$urls_name = ['stock_cache_old','stock_cache_new','report_cache','r_url','r_url_sales','r_url_new','card_list','v1_info','warehouses'];

      /*    $urls = [];
          foreach ($urls_name as $k => $v) {
            $urls["http://".$_SERVER[HTTP_HOST]."/wb/load.report.php?type=".$_GET['type']."&load=".$v]=$v;
          }
*/
          //var_dump($urls);

          $tmp = async_api($urls_name);

        //  var_dump($tmp);

          if($tmp['r_url'] && $api_url){
            $r_url=$tmp['r_url'];
          }
          if($tmp['r_url_sales'] && $api_url_sales){
            $r_url_sales=$tmp['r_url_sales'];
          }
          if($tmp['report_cache'] && in_array($_GET['type'], [1,2,10])){
            $r_url_report=$tmp['report_cache'];
          }
          if($tmp['card_list'] && in_array($_GET['type'], [1,2,10,6])){
            $card_list=$tmp['card_list'];
          }
          if($tmp['v1_info'] && in_array($_GET['type'], [1,2,10])){
            $v1_info=$tmp['v1_info'];
          }
          if($tmp['warehouses'] && in_array($_GET['type'], [1,2,10])){
            $warehouses=$tmp['warehouses'];
          }
          if($tmp['r_url_new'] && $api_url_new){
            $r_url_new=$tmp['r_url_new'];
            $r_url_new=type_object($r,$card_list,$v1_info,$warehouses);
          }

          if ($r_url or $r_url_sales){
            $r = array_unite($r_url, $r_url_new, $r_url_sales);
          }
          $r = json_decode($r);


            if ((time() - intval($buf2[0]) > (60*60*24*2) or ($r and !in_array($r,[null,"[]","","can't decode supplier key","unauthorized","invalid token","supplier key not found"]))) and $r_url_report){
              if ($r_url_report or $r){$r = unity_report($r_url_report, $r);}
            }

              if ($r and !in_array($r,[null,"[]","","can't decode supplier key","unauthorized","invalid token","supplier key not found"])){

                  if ($r_url_report and $r){$r = unity_report($r_url_report, $r);}

                  if (in_array($_GET['type'], [1, 2, 6, 10])) {
                    //  stock_cache_old();
                    //  stock_cache_new();
                      $r = arr_fbs_fbo($r);
                  }
                  if (in_array($_GET['type'], [1, 2, 6, 10])) {
                      $r = speed_fbo_fbs($r);
                  }
                  if (in_array($_GET['type'], [7, 8, 9])) {
                      $r = arr_postav($r);
                  }
                  if (in_array($_GET['type'], [9])) {
                      $r = sebes_pribil($r);
                  }

                    if($r){
                      $v_api["success"] = true;
                      $v_api["message"] = '<font color="green">Данные обновлены. <a href="#" style="color: green; text-decoration: revert;" onclick="parent.location.reload(); return false;">Перезагрузите страницу</a></font>';
                      $data_time = time();
                      writeStatus($link,$USER["id"],$_GET['type'],'3',$data_time);
                      file_put_contents($fileN, $data_time.'@@---@@'.json_encode($r), LOCK_EX);
                    }else{
                      $v_api["message"] = '<font color="red">Данные отсутствуют или нет ответа от API-сервера. <a href="#" style="color: red; text-decoration: revert;" onclick="parent.location.reload(); return false;">Попробуйте позднее</a></font>';
                      writeStatus($link,$USER["id"],$_GET['type'],'0',time());
                    }
              }else{
                $v_api["message"] = '<font color="red">Данные отсутствуют или нет ответа от API-сервера. <a href="#" style="color: red; text-decoration: revert;" onclick="parent.location.reload(); return false;">Попробуйте позднее</a></font>';
                writeStatus($link,$USER["id"],$_GET['type'],'0',time());
              }
          }else{
            $v_api["message"] = '<font color="red">Данные отсутствуют или нет ответа от API-сервера. <a href="#" style="color: red; text-decoration: revert;" onclick="parent.location.reload(); return false;">Попробуйте позднее</a></font>';
            writeStatus($link,$USER["id"],$_GET['type'],'0',time());
          }
      }else{
          $v_api["success"] = true;
          $v_api["message"] = '<font color="green">Данные полученны успешно</font>';
          writeStatus($link,$USER["id"],$_GET['type'],'2',time());
      }

mysqli_close($link);
echo json_encode($v_api);

die();
