<?php
//поставки

    if (isset($_GET['rid']))
    {
      $templateHTML = "<a style='margin-left:10px;' href='?page=wb&type=7&dt=" . $_GET['dt'] . "''>Все поставки</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=" . $_GET['dt'] . "'>" . $_GET['rid'] . '</a>';

        $postav_keys = 'incomeId номер поставки
number номер УПД
date дата поступления
lastChangeDate дата и время обновления информации в сервисе
supplierArticle ваш артикул
techSize размер
barcode Баркод
quantity кол-во
subject предмет
category категория
brand бренд
dateClose дата принятия (закрытия) у нас
warehouseName название склада
nmId Код WB
status Текущий статус поставки
totalPrice цена из УПД';

        $tbl_keys = make_tbl_keys($postav_keys);
    }
    else
    {

        echo '

       <div class="panel panel-default" >

            <div class="panel-heading">Синхронизация кол-ва товаров с личным кабинетом WB (укажите файл экспортированный из кабинета - например - recieved_goods_3458937.xls)</div>

            <div class="panel-body" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; qbackground: rgb(239, 239, 239);">

               <form role="form" method="post" action="" enctype="multipart/form-data">

		<table style="width: 100%"><tr><td style="width: 400px;">
                     XLSX файл с кол-вом товаров в поставке</td><td>
                     <input type=file name="expfile" style="width: 300px; display: inline-block;" class="form-control" value="" />

		<button type="submit" name="syncxlsx" class="btn btn-success">Синхронизировать кол-во товаров</button></td></tr></table>


               </form>


            </div>
      </div>


';
    }

    $ss_dop_fields = $USER['dp_list'];
    if (trim($ss_dop_fields) == '') {
        $ss_dop_fields = $ss_dop = dop_list('2');
    }
    if (trim($ss_dop_fields) == ''){
        $ss_dop_fields = $ss_dop = "Затраты на поиск товара\n Затраты на забор товара\n Затраты на услуги фулфилмента\n Затраты на фото/видео материалы\n Затраты на внутреннюю рекламу\n Затраты на внешнюю рекламу\n Затраты на самовыкупы\n Затраты прочие";
    }

    // if (!isset($_GET['rid']))
    // {
    echo "<input class='btn btn-default' id='btn_pd_lst' value='Редактировать список полей' style='margin-left:10px;width: 260px;' onclick='$(\"#dop_fields_div\").toggle();$(\"#set_fields_div\").hide(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'> ";

    echo '<div id="dop_fields_div" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "><img onclick="$(\'#dop_fields_div\').toggle();" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png"> <b>Список Ваших полей для вычета - общие на каждую поставку</b>';
    echo "<textarea class='form-control' style='height: 150px;' id='dop_fileds' >$ss_dop_fields</textarea><td style='width: 10px;'> <input class='btn btn-success' value='Сохранить список полей' onclick='save_list();'> ";

    echo '<script async>
function save_list()
{
	$.post("/wb/update/update.php", {dp_save_list:2, list:$("#dop_fileds").val()}, function (dt){
		document.location.reload();
	});
}
</script>';
    echo "</div>";
    //  }

    if($_GET['rid']){
        $ss_dop_fields = "Общая себестоимость с учётом количества\nОбщая себестоимость единицы товара\nСтоимость единицы товара\n" . $ss_dop_fields;
    }else{
        $ss_dop_fields = "Общая себестоимость\nСредняя себестоимость единицы\nСтоимость единицы товара\n" . $ss_dop_fields;
    }

    $ss_dop_fields = explode("\n", trim($ss_dop_fields));

    $s_dop = explode("\n", 'quantity
    totalPrice');

    foreach ($ss_dop_fields as & $sf)
    {
        $sf = trim($sf);
        if ($sf == '') continue;
        $tbl_keys[ru2Lat($sf)] = $sf;
    }

    if (!$_GET['rid']){
        $correct_lines = file_read('7');
    }

if($tbl_rows){
    $last_key = -1;
    foreach ($tbl_rows as $g) {
        $sums = explode("\n", trim('Стоимость единицы товара
        ' . $ss_dop));

        foreach ($sums as $fieldsum) {
            $fieldsum = ru2Lat(trim($fieldsum));

            foreach ($correct_lines as $keys => $correct_line) {
                if ($g->incomeId == $correct_line->incomeId
                    and $g->supplierArticle == $correct_line->supplierArticle
                    and $g->barcode == $correct_line->barcode) {
                    foreach ($correct_line as $key => $datumm) {
                        if ($key == $fieldsum and is_numeric($g->quantity) and is_numeric($datumm)) {
                            $g->$fieldsum = $g->quantity * $datumm;
                        }
                    }
                }
            }
        }

        if ($_GET['rid']) {
            $dpf = 'Общая себестоимость с учётом количества';
        } else {
            $dpf = 'Общая себестоимость';
        }

        $dpf2 = 'Стоимость единицы товара';
        $g->$dpf += $g->quantity * $g->$dpf2;

        foreach ($ss_dop_fields as & $sf) {
            $sf = trim($sf);
            if ($sf == '' || $sf == 'Общая себестоимость' || $sf == 'Общая себестоимость с учётом количества' || $sf == 'Средняя себестоимость единицы' || $sf == 'Общая себестоимость единицы товара' || $sf == 'Стоимость единицы товара') continue;

            $g->$dpf += $g->$sf;
        }
        if ($_GET['rid']) {
            $dpf2 = 'Общая себестоимость единицы товара';
        } else {
            $dpf2 = 'Средняя себестоимость единицы';
        }

        $g->$dpf2 = intval($g->$dpf / $g->quantity);

        if (isset($_GET['rid']) && isset($_GET['bc'])) {
            if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

        } else if (isset($_GET['rid'])) {
            if ($g->incomeId != $_GET['rid']) continue;
        } else {

            if (isset($keys_bc2[$g->incomeId])) {
                $sums = explode("\n", trim('quantity
                totalPrice'));
                foreach ($ss_dop_fields as & $sf) {
                    $sf = trim($sf);
                    $sf2 = 'dp_' . $sf;
                    $sums[] = ru2Lat($sf);
                    $sums[] = ru2Lat($sf2);
                }

                foreach ($sums as $fieldsum) {
                    $fieldsum = trim($fieldsum);
                    $reps[$keys_bc2[$g->incomeId]]->$fieldsum += $g->$fieldsum;
                }
            }
            if ($last_code != $g->incomeId) {
                $last_code = $g->incomeId;
            } else {
                continue;
            }
        }
        if ($g->date) {
            $g->date = date('d.m.Y', strtotime($g->date));
        }
        if ($g->dateClose) {
            $g->dateClose = date('d.m.Y', strtotime($g->dateClose));
        }

        $reps[] = $g;
        $last_key = count($reps) - 1;
        $keys_bc[$g->incomeId] = $last_key;
        $keys_bc2[$g->incomeId] = $last_key;

        $g->incomeId2 = $g->incomeId;

    }
}

if($reps){
    $tbl_rows = $reps;
}

    //---------------------------------------
    // пересчетываем сгруппированную себестоимость по поставкам
    if (!isset($_GET['rid']))
    {

        if($tbl_rows){
            $reps = [];
            foreach ($tbl_rows as $g) {
                if ($_GET['rid']) {
                    $dpf = 'Общая себестоимость с учётом количества';
                } else {
                    $dpf = 'Общая себестоимость';
                }
                //$dpf = 'Общая себестоимость';
                $dpf2 = 'Стоимость единицы товара';
                //$g->$dpf += $g->quantity *  $g->$dpf2;
                foreach ($ss_dop_fields as & $sf) {
                    $sf = trim($sf);
                    if ($sf == '' || $sf == 'Общая себестоимость' || $sf == 'Общая себестоимость с учётом количества' || $sf == 'Средняя себестоимость единицы' || $sf == 'Общая себестоимость единицы товара' || $sf == 'Стоимость единицы товара') continue;

                    $g->$dpf += $g->$sf;
                }
                if ($_GET['rid']) {
                    $dpf2 = 'Общая себестоимость единицы товара';
                } else {
                    $dpf2 = 'Средняя себестоимость единицы';
                }

                $g->$dpf2 = intval($g->$dpf / $g->quantity);

                $reps[] = $g;
            }
        }
        if($reps){
            $tbl_rows = $reps;
        }

    }
    else
    {
        foreach ($tbl_rows as $g)
        {
            $kkk = $g->incomeId2;
            foreach ($ss_dop_fields as & $sf)
            {
                $sf = trim($sf);
                if ($sf == '' || $sf == 'Общая себестоимость' || $sf=='Общая себестоимость с учётом количества' || $sf == 'Средняя себестоимость единицы' || $sf == 'Общая себестоимость единицы товара') {
                    continue;
                }

                $val = $g->$sf;

                $g->$sf = "<input type='text' name='dp[{$kkk}_{$g->barcode}][$sf]' psf='{$sf}' class='form-control dp_{$kkk}_{$g->barcode}' value='" . $val . "'>";

                $sf2 = 'dp_' . $sf;

                $g->$sf2 = $val;

            }
        }
    }
