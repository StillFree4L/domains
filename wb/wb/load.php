<?php

require_once('blocks/func_key.php');
require_once('blocks/func_tbl_keys.php');

$v_api = ["success"=>false,"message"=>'<font color="red">Нет данных</font>'];

$data_time1 = time();

barOption($link,'forcibly',$data_time1,$USER["id"]);
writeStatus($link,$USER["id"],$_GET['type'],'1',$data_time1);

if (trim($USER['wb_key']) != '')
{

  if($_GET['async']=='on'){
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

    require_once('blocks/func_api.php');

      if ($buf == "" || $_GET['forcibly']=='on' || json_decode($buf2[1]) == NULL || time() - intval($buf2[0]) > 60*60 || strpos($buf, 'can\'t decode supplier key') !== false)
      {
        if ($api_url or $api_url_sales or $api_url_new){

          $urls = array(
              'stock_cache_old'=>'http://wb/wb/load.report.php?type='.$_GET['type'].'&load=stock_cache_old',
              'stock_cache_new'=>'http://wb/wb/load.report.php?type='.$_GET['type'].'&load=stock_cache_new',
          );
          if (in_array($_GET['type'], [1,2,10])){
            $urls['report_cache'] = 'http://wb/wb/load.report.php?type='.$_GET['type'].'&load=report_cache';
          //  $r_url_report =  json_decode(report_cache());
          //  $r_url_report = async_api('report_cache');
          }
          if ($api_url){
            $urls['r_url'] = 'http://wb/wb/load.report.php?type='.$_GET['type'].'&load=r_url';
            //$r_url = http_json($api_url);
          //  $r_url = async_api('r_url');
          }

          if ($api_url_sales){
            $urls['r_url_sales'] = 'http://wb/wb/load.report.php?type='.$_GET['type'].'&load=r_url_sales';
          //  $r_url_sales = http_json($api_url_sales);
          //  $r_url_sales = async_api('r_url_sales');
          }

          if ($api_url_new){
            $urls['r_url_new'] = 'http://wb/wb/load.report.php?type='.$_GET['type'].'&load=r_url_new';
          //  $r_url_new = http_json($api_url_new,true);
          //  $r_url_new = async_api('r_url_new');
          }

          //var_dump($r_url);
          async_api($urls);
          if($tmp['r_url']){
            $r_url=$tmp['r_url'];
          }
          if($tmp['r_url_sales']){
            $r_url_sales=$tmp['r_url_sales'];
          }
          if($tmp['r_url_new']){
            $r_url_new=$tmp['r_url_new'];
          }
          if($tmp['report_cache']){
            $r_url_report=$tmp['report_cache'];
          }

          if ($r_url or $r_url_sales){
            $r = array_unite($r_url, $r_url_new, $r_url_sales);
          }

            if ((time() - intval($buf2[0]) > (60*60*24*2) or ($r and !in_array($r,[null,"[]","","can't decode supplier key","unauthorized","invalid token","supplier key not found"]))) and $r_url_report){
              $r = json_decode($r);
              if ($r_url_report or $r){$r = unity_report($r_url_report, $r);}
            }
            else{
              $r = json_decode($r);
            }
            var_dump($tmp['r_url']);


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

                  if (!in_array($r[0], [null, ""])) {
                      $v_api["success"] = true;
                      $v_api["message"] = '<font color="green">Данные обновлены. <a href="#" style="color: green; text-decoration: revert;" onclick="parent.location.reload(); return false;">Перезагрузите страницу</a></font>';
                      $data_time = time();
                      file_put_contents($fileN, $data_time.'@@---@@'.json_encode($r), LOCK_EX);
                      writeStatus($link,$USER["id"],$_GET['type'],'3',$data_time);
                  }
              }else{
                $v_api["message"] = '<font color="red">Данные отсутствуют или нет ответа от API-сервера. <a href="#" style="color: red; text-decoration: revert;" onclick="parent.location.reload(); return false;">Попробуйте позднее</a></font>';
                writeStatus($link,$USER["id"],$_GET['type'],'0',time());
              }
          }
      }else{
          $v_api["success"] = true;
          $v_api["message"] = '<font color="green">Данные полученны успешно</font>';
          writeStatus($link,$USER["id"],$_GET['type'],'2',time());
      }

}

echo json_encode($v_api);
