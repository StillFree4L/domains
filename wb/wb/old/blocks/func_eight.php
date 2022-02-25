<?php
if ($_GET['type'] == 8)
{
    $ss_dop_fields = $USER['dp_list'];
    if (trim($ss_dop_fields) == '') {
        $dp_save_list = 2;
        if($dp_save_list){
            $ss_dop_contents = json_decode(file_get_contents('update/json/list.json'));
            if ($ss_dop_contents != '') {
                foreach ($ss_dop_contents as $ss_dop_content) {
                    if ($ss_dop_content->dp_save_list == $dp_save_list) {
                        $ss_dop_fields = $ss_dop = $ss_dop_content->list;
                    }
                }
            }
        }
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

    //echo "<hr><input type=button  onclick='not_reload=1; $(\".col_save_btn\").click(); $(this).fadeOut(); setTimeout(function(){document.location.reload();}, 2000);' class='btn btn-success' value='Сохранить значения'>";
    echo "<hr><input type=button  onclick='number_update_all();' class='btn btn-success' value='Сохранить значения'>";

    echo '<table class="items table table-striped" style="width: 700px;margin-top: 20px;"><tr><th>Поле</th><th>Значение</th></tr>';

    foreach ($ss_dop_fields as $k => & $sf)
    {
        $sf2 = ru2Lat(trim($sf));
        echo '<tr><td style="width: 250px;">'.$sf.'</td><td style="width: 100px;"><input type="text" sf="'.$sf2.'" class="form-control inputValueAll" onkeyup="this.value = this.value.replace(/[^^0-9\.]/g,\'\');"></td></tr>';
        //echo '<tr><td style="width: 250px;">' . $sf . '</td><td style="width: 100px;">' . "<input type='text' psf='{$sf}' class='form-control' id='adp_{$k}' value=''>" . '</td>' . "<td style='width: 100px;'><input class='col_save_btn btn btn-success' style='display:none;' value='Сохранить значение' onclick='dp_save_col(\"adp_{$k}\", \"{$sf}\");'></td></tr>";
    }
    echo "</table>  </div> <br clear=all>";

    if (isset($_GET['f1'])) $tbl_keys['save'] = '';

    echo '<hr>
<script>

function save_list()
{
	$.post("/wb/update/update.php", {dp_save_list:'.$dp_save_list.', list:$("#dop_fileds").val()}, function (dt){
		document.location.reload();
	});
}

function number_update_all(){
    var input = document.querySelectorAll(\'input.inputValueAll\');
    var inputval = "";
    
    var i = 0;
    var j = 0;
    while (i < input.length) {
        if(input[i].value != "" && input[i].value != null){
            j = 0
            inputval = document.querySelectorAll("#"+input[i].getAttribute("sf"));
            //console.log(inputval);
        while (j < inputval.length) {
            if(inputval[j].getAttribute("incomeid") != null && inputval[j].getAttribute("incomeid") != ""){
                number_update(inputval[j].getAttribute(\'idd\'),input[i].value,input[i].getAttribute(\'sf\'),inputval[j].getAttribute(\'incomeid\'),inputval[j].getAttribute(\'supplierarticle\'),inputval[j].getAttribute(\'barcode\'));
            }
            j++
            
        }
        }
        i++;
    }
    setTimeout(function () {document.location.reload();}, 2000);
}
</script>

';

    if (isset($_GET['f1']))
    {
        echo "<h4 style='margin-left:10px;'><a href='?page=wb&type=8&dt=" . $_GET['dt'] . "''>Себестоимость</a>  / Артикул <a href='?page=wb&type=8&f1=$_GET[f1]&dt=" . $_GET['dt'] . "'>" . $_GET['f1'] . '</a></h4>';

        // echo "<br><input type=button  onclick='not_reload=1; $(\".dp_save_btn\").click(); $(this).fadeOut(); setTimeout(function(){document.location.reload();}, 2000);' class='btn btn-success' value='Сохранить все'>";
    }

    if (!$_GET['f1']){
        $correct_lines = json_decode(file_get_contents('update/json/8.json'));
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
                                if (is_numeric($g->quantity) and is_numeric($datumm)) {
                                    $g->$fieldsum = $g->quantity * $datumm;
                                }
                                //if ($g->supplierArticle=='6х2,5спб112' and $fieldsum=='Zatraty_na_poisk_tovara'){var_dump($g->Zatraty_na_poisk_tovara);}
                            }
                        }
                    }
                }
            }
        }

        $g->kkk = $kkk;

        // $g->quantity
        /* $quant = $db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], $g->incomeId . '_' . $g->supplierArticle . '_' . $g->techSize, 'quantity');
         if ($quant !== false)
         {
             $g->quantity = $quant;
         }

         foreach ($ss_dop_fields as & $sf)
         {
             $sf = trim($sf);

             $val = intval($db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], "{$g->incomeId}_{$g->barcode}", $sf));

             $g->$sf = "<input type='text' name='dp[{$kkk}_{$g->barcode}][$sf]' psf='{$sf}' class='form-control dp_{$kkk}_{$g->barcode}' value='" . $val . "'>";

             $sf2 = 'dp_' . $sf;

             $g->$sf2 = $val;

         }
 */
        $g->save = "<input type=button  onclick='dp_save(this, \"{$kkk}_{$g->barcode}\", \"{$g->incomeId}_{$g->barcode}\", 0);' class='dp_save_btn btn btn-success' style='display:none;' value='Сохранить'> <script>all_keys_col += '|'+'{$g->incomeId}_{$g->barcode}'; </script>";

        $stf = "dp_Стоимость единицы товара";

        $g->ss_all = $g->$stf * $g->quantity;

        $sum = 0;
        foreach ($ss_dop_fields as $k => & $sf)
        {
            if ($sf == 'save' || $sf == 'Стоимость единицы товара') continue;
            $sf = trim($sf);
            $sf2 = "dp_" . $sf;
            //echo $sf .' = '.$g->$sf2."<br>\n";
            $sum += $g->$sf2;

        }
        $g->ss_all += $sum;
        $g->ss_one = intval($g->ss_all / $g->quantity);

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
                    // if ($sf == 'Стоимость единицы товара') continue;

                    $sf2 = 'dp_' . $sf;

                    $sums[] = $sf;
                    $sums[] = $sf2;
                }

                //print_r($sums);print_r($g);exit;
                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);
                    $reps[$keys_bc[$g->supplierArticle]]->$fieldsum += $g->$fieldsum;
                }

                $reps[$keys_bc[$g->supplierArticle]]->save_codes .= "|" . $g->incomeId . '_' . $g->barcode;

                $reps[$keys_bc[$g->supplierArticle]]->incomeId += 1;;
                // $reps[$keys_bc[$g->supplierArticle]]->incomeId .= ' шт.';

            }

            if (isset($keys_bc[$g->supplierArticle])) continue;

        }

        if (!isset($_GET['f1']))
        {
            $g->save_codes = $g->incomeId . '_' . $g->barcode;

            $g->incomeId = 1 /*. ' шт.'*/;
        }

        $reps[] = $g;
        $last_key = count($reps) - 1;
        $keys_bc[$g->supplierArticle] = $last_key;
        $keys_bc2[$g->supplierArticle] = $last_key;

        $g->barcode2 = $g->barcode;
        $g->supplierArticle2 = $g->supplierArticle;
        // if (!isset($_GET['f1'])) $g->supplierArticle = '<a href="./index.php?page=wb&type=8&f1=' . $g->supplierArticle . '&dt=' . $_GET['dt'] . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->supplierArticle . '</a>';

    }

    //echo '<pre>';var_dump($keys_bc);var_dump($tpl_rows, $reps);

    $tbl_rows = $reps;

    // echo '<pre>';var_dump($tbl_rows);
    if (!isset($_GET['f1'])) foreach ($tbl_rows as $g)
    {

        //var_dump($g);exit;
        $g->ss_one = intval($g->ss_all / $g->quantity);

        $u = [];
        $u['user_id'] = $USER['id'];
        $u['barcode'] = $g->barcode;
        $u['art'] = $g->supplierArticle2;
        $u['sebes'] = $g->ss_one;

        /*if ($db->getOne("SELECT COUNT(*) FROM sebes_vals WHERE user_id=?i and art=?s ", $u['user_id'], $u['art']) == 0)
        {
            $db->query("INSERT INTO sebes_vals SET ?u", $u);
        }
        else
        {
            $db->query("UPDATE sebes_vals SET ?u WHERE user_id=?i and art=?s ", $u, $u['user_id'], $u['art']);

        }*/

        $g->save = ""; //"<input type=button  onclick='dp_save(\"{$g->kkk}_{$g->barcode2}\", \"{$g->save_codes}\", 1);' class='btn btn-success' value='Сохранить все'>";
        /*foreach ($ss_dop_fields as & $sf)
        {
            $sf = trim($sf);

            $g->$sf = "<input type='text' name='dp[{$kkk}_{$g->barcode}][$sf]' psf='{$sf}' class='form-control dp_{$kkk}_{$g->barcode}' value='" . $val . "'>";

            $sf2 = 'dp_' . $sf;

            $g->$sf = $g->$sf2;

        }*/

    }
    //echo '<pre>'; var_dump($tbl_rows);
    // SUMS
    /*foreach ($tbl_rows as $g)
    {

        // SUMS --------
        foreach ($g as $gk => $gv)
        {
            //if (is_numeric($gv)){
                $ITOGO_SUMS[$gk] += $gv;
           // }
        }

    }*/

}