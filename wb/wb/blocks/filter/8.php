<?php
//себестоимость

    $ss_dop_fields = $USER['dp_list'];
    if (trim($ss_dop_fields) == '') {
      $result = mysqli_query($link, 'SELECT * FROM `list` WHERE `userId`='.$USER["id"].' limit 1');
      foreach ($result as $key => $value) {
        $ss_dop_fields = $ss_dop = $value['list'];
      }
    }

    if (trim($ss_dop_fields) == '') $ss_dop_fields = "Затраты на поиск товара\n Затраты на забор товара\n Затраты на услуги фулфилмента\n Затраты на фото/видео материалы\n Затраты на внутреннюю рекламу\n Затраты на внешнюю рекламу\n Затраты на самовыкупы\n Затраты прочие";
    if (!isset($_GET['f1'])) echo "<input class='btn btn-default' id='btn_pd_lst' type='button' style='margin-left:10px;width: 35px;' onclick='$(\"#dop_fields_div\").toggle();$(\"#set_fields_div\").hide(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'> ";
    if (isset($_GET['f1'])) echo "<input class='btn btn-default' id='btn_pd_val' style='margin-left:10px;width: 40px;' onclick='$(\"#dop_fields_div\").hide();$(\"#set_fields_div\").toggle(); $(\"#btn_pd_lst\").removeClass(\"btn-warning\");$(\"#btn_pd_val\").addClass(\"btn-warning\");'> ";

    echo '<div id="dop_fields_div" title="Редактируемые столбцы" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "><img onclick="$(\'#dop_fields_div\').toggle();$(\'#btn_pd_lst\').removeClass(\'btn-warning\');" style="width: 20px; cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png"> Список Ваших полей для вычета - общие на каждую поставку';
    echo "<textarea class='form-control' style='margin-top: 10px; margin-bottom: 10px;height: 150px;' id='dop_fileds' >$ss_dop_fields</textarea><td style='width: 10px;'> <input style='width: 200px;' class='btn btn-success' value='Сохранить список полей' onclick='save_list();'> </div>";

    $ss_dop_fields = 'Стоимость единицы товара' . "\n" . $ss_dop_fields;
    $ss_dop_fields = explode("\n", trim($ss_dop_fields));
    $ss_dom_lat=[];

    foreach ($ss_dop_fields as & $sf)
    {
        $sf = trim($sf);
        $ss_dom_lat[]=ru2Lat($sf);
        if ($sf == '') continue;
        $tbl_keys[ru2Lat($sf)] = $sf;
    }

    echo '<br clear=all><div id="set_fields_div" style="float: left; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "> <img onclick="$(\'#set_fields_div\').toggle();$(\'#btn_pd_val\').removeClass(\'btn-warning\');" style="width: 20px;cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png"> Установка значения полей для всех поставок по артикулу';
    echo "<input type=button style='margin-left: 20px;'  onclick='number_update_all();' class='btn btn-success' value='Сохранить значения'>";
    echo '<table class="items table table-striped" style="width: 700px;margin-top: 20px;"><tr><th style="font-weight: normal;">Поле</th><th style="font-weight: normal;">Значение</th></tr>';

    foreach ($ss_dop_fields as $k => & $sf)
    {
        $sf2 = ru2Lat(trim($sf));
        echo '<tr><td style="width: 250px;">'.$sf.'</td><td style="width: 100px;"><input type="text" sf="'.$sf2.'" class="form-control inputValueAll" onkeyup="this.value = this.value.replace(/[^^0-9\.]/g,\'\');"></td></tr>';
    }
    echo "</table>  </div> <br clear=all>";

    if (isset($_GET['f1'])) $tbl_keys['save'] = '';

    echo '
<script async>

function save_list()
{
	$.post("/wb/update.php", {list: String($("#dop_fileds").val())}, function (dt){
		document.location.reload();
	});
}

function number_update_all(){
  let map = store.data.map,ss_dops = '.json_encode($ss_dom_lat).',all = {},i=0,j=0,
    allInp = document.querySelectorAll("input.inputValueAll"),ij=0;
  for (var ma in map){
    j=0;
      while(j<allInp.length){
        if(allInp[j].value!=""){
          all[ij]={incomeId: map[ma].data.incomeId, supplierArticle: map[ma].data.supplierArticle, barcode: map[ma].data.barcode, name: allInp[j].getAttribute("sf"), value: allInp[j].value};
        }
        j++;
        ij++;
      }
    i++;
  }
  $.post("/wb/update.php?all=8", {all:all}, function (dt){
	   setTimeout(function () {document.location.reload();}, 1000);
	});
}
</script>

';

    if (isset($_GET['f1']))
    {
      $templateHTML = "<a href='?page=wb&type=8&dt=" . $_GET['dt'] . "''>Себестоимость</a>  / Артикул <a href='?page=wb&type=8&f1=$_GET[f1]&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a>';
    }

    if($_GET['type']==8 and !$_GET['f1']){
      $result = mysqli_query($link, 'SELECT * FROM `goods` WHERE `userId`='.$USER["id"].' and `type`=7');

    /*  if ($result == false) {
        print(mysqli_error($link));
      }*/
    }

    /*if (!$_GET['f1']){
        $correct_lines = file_read('7');
    }*/

    $sums = explode("\n", trim('Стоимость единицы товара
        '.$ss_dop));

    $last_key = - 1;
    foreach ($tbl_rows as $kkk => & $g)
    {
      if(!$_GET['f1']){
          foreach ($result as $key => $value) {
          if($g->incomeId == $value['incomeId'] and $g->supplierArticle ==$value['supplierArticle'] and $g->barcode == $value['barcode']){
          //  var_dump($value);
            $tmp = $value['name'];
            $g->$tmp = intval((int)$g->quantity * (int)$value['value']);
          }
        }
      }

        $g->kkk = $kkk;

        if (isset($_GET['f1']) && isset($_GET['bc']))
        {
            if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;
        }
        else if (isset($_GET['f1']))
        {
            if ($g->supplierArticle != $_GET['f1']) continue;
        }
        else
        {
            if (isset($keys_bc[$g->supplierArticle]))
            {
                $sums = explode("\n", trim('
quantity
ss_all
'));
                foreach ($ss_dop_fields as & $sf)
                {
                    $sf = ru2Lat(trim($sf));
                    $sums[] = $sf;
                }


                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);
                    $reps[$keys_bc[$g->supplierArticle]]->$fieldsum += $g->$fieldsum;

                }

                $reps[$keys_bc[$g->supplierArticle]]->incomeId += 1;;

            }

            if (isset($keys_bc[$g->supplierArticle])) continue;

        }

        if (!isset($_GET['f1']))
        {
            if (!isset($_GET['f2'])){
                $g->incomeId = 1;
            }
        }
        if ($g->date){
            $g->date = date('d.m.Y',strtotime($g->date));
        }
        if ($g->dateClose){
            $g->dateClose = date('d.m.Y',strtotime($g->dateClose));
        }
        if ($g->lastChangeDate){
            $g->lastChangeDate = date('d.m.Y H:i:s',strtotime($g->lastChangeDate));
        }

        $reps[] = $g;
        $last_key = count($reps) - 1;
        $keys_bc[$g->supplierArticle] = $last_key;
        $keys_bc2[$g->supplierArticle] = $last_key;

        $g->barcode2 = $g->barcode;
        $g->supplierArticle2 = $g->supplierArticle;
    }

    $tbl_rows = $reps;

    if (!isset($_GET['f1'])) foreach ($tbl_rows as $g)
    {
        $g->ss_one = intval($g->ss_all / $g->quantity);
        $u = [];
        $u['user_id'] = $USER['id'];
        $u['barcode'] = $g->barcode;
        $u['art'] = $g->supplierArticle2;
        $u['sebes'] = $g->ss_one;
    }
