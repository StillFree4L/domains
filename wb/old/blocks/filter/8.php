<?php
//себестоимость

    $ss_dop_fields = $USER['dp_list'];
    if (trim($ss_dop_fields) == '') {
        $ss_dop_fields = $ss_dop = dop_list('2');
    }

    if (trim($ss_dop_fields) == '') $ss_dop_fields = "Затраты на поиск товара\n Затраты на забор товара\n Затраты на услуги фулфилмента\n Затраты на фото/видео материалы\n Затраты на внутреннюю рекламу\n Затраты на внешнюю рекламу\n Затраты на самовыкупы\n Затраты прочие";
    if (!isset($_GET['f1'])) echo "<input class='btn btn-default' id='btn_pd_lst' value='Редактировать список полей' style='margin-left:10px;width: 260px;' onclick='$(\"#dop_fields_div\").toggle();$(\"#set_fields_div\").hide(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'> ";
    if (isset($_GET['f1'])) echo "<input class='btn btn-default' id='btn_pd_val'  value='Установить значение поля для всех поставок ' style='margin-left:10px;width: 360px;' onclick='$(\"#dop_fields_div\").hide();$(\"#set_fields_div\").toggle(); $(\"#btn_pd_lst\").removeClass(\"btn-warning\");$(\"#btn_pd_val\").addClass(\"btn-warning\");'> ";

    echo '<div id="dop_fields_div" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "><img onclick="$(\'#dop_fields_div\').toggle();" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png"> <b>Список Ваших полей для вычета - общие на каждую поставку</b>';
    echo "<textarea class='form-control' style='height: 150px;' id='dop_fileds' >$ss_dop_fields</textarea><td style='width: 10px;'> <input class='btn btn-success' value='Сохранить список полей' onclick='save_list();'> </div>";

    $ss_dop_fields = 'Стоимость единицы товара' . "\n" . $ss_dop_fields;
    $ss_dop_fields = explode("\n", trim($ss_dop_fields));

    foreach ($ss_dop_fields as & $sf)
    {
        $sf = trim($sf);
        if ($sf == '') continue;
        $tbl_keys[ru2Lat($sf)] = $sf;
    }

    echo '<br clear=all><div id="set_fields_div" style="float: left; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "> <img onclick="$(\'#set_fields_div\').toggle();" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png">   <b>Установка значения полей для всех поставок по артикулу</b>';
    echo "<hr><input type=button  onclick='number_update_all();' class='btn btn-success' value='Сохранить значения'>";
    echo '<table class="items table table-striped" style="width: 700px;margin-top: 20px;"><tr><th>Поле</th><th>Значение</th></tr>';

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
	$.post("/wb/update/update.php", {dp_save_list:2, list:$("#dop_fileds").val()}, function (dt){
		document.location.reload();
	});
}

function number_update_all(){
    let input = document.querySelectorAll(\'input.inputValueAll\');
    let len = Ext.select("td.x-grid-cell-ss_all").elements.length;

    let i = 0;
    let j = 0;
    while (i < input.length) {
        if(input[i].value && input[i].value != "" && input[i].value != null && input[i].getAttribute("sf") != ""){
            inputs = document.querySelectorAll("input.inputValue#"+input[i].getAttribute("sf"));
            if (len > 0){
                j = 0;
                while (j < len-1){
                   number_update("Data-"+(j+1),input[i].value,input[i].getAttribute("sf"),inputs[j].getAttribute("incomeId"),inputs[j].getAttribute("supplierArticle"),inputs[j].getAttribute("barcode"))
                    j++;
                }
            }
        }
        i++;
    }
    document.location.reload();
    setTimeout(function () {document.location.reload();}, 1000);
}
</script>

';

    if (isset($_GET['f1']))
    {
      $templateHTML = "<a href='?page=wb&type=8&dt=" . $_GET['dt'] . "''>Себестоимость</a>  / Артикул <a href='?page=wb&type=8&f1=$_GET[f1]&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a>';
    }

    if (!$_GET['f1']){
        $correct_lines = file_read('7');
    }

    $sums = explode("\n", trim('Стоимость единицы товара
        '.$ss_dop));

    $last_key = - 1;
    foreach ($tbl_rows as $kkk => & $g)
    {
        foreach ($sums as $fieldsum)
        {
            $fieldsum = ru2Lat(trim($fieldsum));
            if ($correct_lines){
                foreach ($correct_lines as $keys => $correct_line) {
                    if ($g->incomeId == $correct_line->incomeId
                        and $g->supplierArticle == $correct_line->supplierArticle
                        and $g->barcode == $correct_line->barcode) {
                        foreach ($correct_line as $key => $datumm) {
                            if ($key == $fieldsum) {
                                    $g->$fieldsum = intval($g->quantity) * intval($datumm);
                            }
                        }
                    }
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
