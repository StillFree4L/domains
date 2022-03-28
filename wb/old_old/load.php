<?php

require_once('blocks/func_key.php');
require_once('blocks/func_tbl_keys.php');
//------------------------------------------------------------------------------------------

//if (!isset($_GET['dt'])) $_GET['dt'] = date('Y-m-d', time());
//if (!isset($_GET['type'])) $_GET['type'] = 2;

$v_api = ["success"=>false,"message"=>"Error!"];
if (trim($USER['wb_key']) != '')
{
    require_once('blocks/func_api.php');

      if ($buf == "" || json_decode($buf2[1]) == NULL || time() - intval($buf2[0]) > 60*5 || strpos($buf, 'can\'t decode supplier key') !== false)
      {
          if ($api_url or $api_url_sales or $api_url_new){
              if (in_array($_GET['type'], [1,2,10])){$r_url_report =  json_decode(report_cache());}

              if ($api_url){$r_url = http_json($api_url);}
            //  var_dump($r_url);
              if ($api_url_sales){$r_url_sales = http_json($api_url_sales);}
              if ($api_url_new){$r_url_new = http_json($api_url_new,true);}

              if ($r_url or $r_url_sales){
                  $r = array_unite($r_url, $r_url_new, $r_url_sales);
              }

            if ( time() - intval($buf2[0]) > (60*60*24*2) or ($r and !in_array($r,[null,"[]","","can't decode supplier key","unauthorized","invalid token","supplier key not found"]))){
              $r = json_decode($r);
              if ($r_url_report or $r){$r = unity_report($r_url_report, $r);}
            }

              if ($r and !in_array($r,[null,"[]","","can't decode supplier key","unauthorized","invalid token","supplier key not found"])){
                //  $r = json_decode($r);
                //  if ($r_url_report and $r){$r = unity_report($r_url_report, $r);}

                  if (in_array($_GET['type'], [1, 2, 6, 10])) {
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
                      $v_api["message"] = "Data updated successfully!";
                      file_put_contents($fileN, time() . '@@---@@' . json_encode($r), LOCK_EX);
                  }
              }else{$v_api["message"] .= " Data missing or no response from api server.";}
          }
      }else{
          $v_api["success"] = true;
          $v_api["message"] = "Data is available!";
      }
}
echo json_encode($v_api);
