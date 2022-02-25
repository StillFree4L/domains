<?php
if ($_GET['type'] == 7)
{
    if (isset($_GET['rid']))
    {
        echo "<h4 style='margin-bottom:10px;'><a style='margin-left:10px;' href='?page=wb&type=7&dt=" . $_GET['dt'] . "''>Все поставки</a>  / Поставка № <a href='?page=wb&type=7&rid=$_GET[rid]&dt=" . $_GET['dt'] . "'>" . $_GET['rid'] . '</a></h4>';

        //echo "<br><input style='position:absolute;margin-left:280px;' id='dop_filedsSave' type=button  onclick='dp_save(this);' class='btn btn-success' value='Сохранить все'>";

        /*        $postav_keys = 'incomeId номер поставки
        ///number номер УПД
        date дата поступления
        lastChangeDate дата и время обновления информации в сервисе
        supplierArticle ваш артикул
        techSize размер
        barcode Баркод
        quantity кол-во

        ///dateClose дата принятия (закрытия) у нас
        ///warehouseName название склада
        ///nmId Код WB
        ///status Текущий статус поставки';
        */
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

        echo '
<script>

var dp_krows = [];

var not_reload = 0;

function dp_save(e)
{


var dt = "";
for(j=0;j < dp_krows.length; j++)
{
	cl = dp_krows[j];

	lst = $(".dp_" + cl);

	for (i=0; i<lst.length; i++)
	{
		key_col = $(lst[i]).attr("psf");
		val = $(lst[i]).val();

		dt += cl+ "@" +key_col + "@" + val + "|";
	}
}

	//console.log(dt);return;

	$(e).fadeOut();

	$.post("", {save_dops_new:1, ker_row:"", data:dt}, function (res){
		//console.log(res);

		document.location.reload();

		//alert("Данные по поставке сохранены! Для перерасчета - обновите страницу!");
	});
}


</script>

';

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
    if (trim($ss_dop_fields) == ''){
        $ss_dop_fields = $ss_dop = "Затраты на поиск товара\n Затраты на забор товара\n Затраты на услуги фулфилмента\n Затраты на фото/видео материалы\n Затраты на внутреннюю рекламу\n Затраты на внешнюю рекламу\n Затраты на самовыкупы\n Затраты прочие";
    }

    // if (!isset($_GET['rid']))
    // {
    echo "<input class='btn btn-default' id='btn_pd_lst' value='Редактировать список полей' style='margin-left:10px;width: 260px;' onclick='$(\"#dop_fields_div\").toggle();$(\"#set_fields_div\").hide(); $(\"#btn_pd_lst\").addClass(\"btn-warning\");$(\"#btn_pd_val\").removeClass(\"btn-warning\");'> ";

    echo '<div id="dop_fields_div" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px; background: #efefef; display: none; padding: 10px; margin: 10px; border: 1px solid #ccc; "><img onclick="$(\'#dop_fields_div\').toggle();" style="cursor:pointer; float: right;" src="https://v1.iconsearch.ru/uploads/icons/bnw/32x32/fileclose.png"> <b>Список Ваших полей для вычета - общие на каждую поставку</b>';
    echo "<textarea class='form-control' style='height: 150px;' id='dop_fileds' >$ss_dop_fields</textarea><td style='width: 10px;'> <input class='btn btn-success' value='Сохранить список полей' onclick='save_list();'> ";

    echo '<script>
function save_list()
{
	$.post("/wb/update/update.php", {dp_save_list:'.$dp_save_list.', list:$("#dop_fileds").val()}, function (dt){
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
        $correct_lines = json_decode(file_get_contents('update/json/7.json'));
    }

    $last_key = - 1;
    foreach ($tbl_rows as $g)
    {

        // $g->quantity
        //$quant = $db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], $g->incomeId . '_' . $g->supplierArticle . '_' . $g->techSize, 'quantity');
        /*  if ($quant !== false)
          {
              $g->quantity = $quant;
          }*/
        /*
                /// get dop fields
                foreach ($ss_dop_fields as & $sf)
                {
                    $g->$sf = intval($db->getOne("SELECT value FROM ss_dops WHERE user_id=?i and key_row=?s and key_col=?s", $USER['id'], $g->incomeId . '_' . $g->barcode, $sf));
                }*/
        $sums = explode("\n", trim('Стоимость единицы товара
        '.$ss_dop));

        foreach ($sums as $fieldsum)
        {
            $fieldsum = ru2Lat(trim($fieldsum));

            foreach ($correct_lines as $keys => $correct_line) {
                if ($g->incomeId == $correct_line->incomeId
                    and $g->supplierArticle == $correct_line->supplierArticle
                    and $g->barcode == $correct_line->barcode) {
                    foreach ($correct_line as $key => $datumm) {
                        if ($key==$fieldsum and is_numeric($g->quantity) and is_numeric($datumm)) {
                            $g->$fieldsum = $g->quantity*$datumm;
                            //if ($g->incomeId=='6051704' and $fieldsum=='Zatraty_na_poisk_tovara'){var_dump($g->Zatraty_na_poisk_tovara);}
                        }
                    }
                }
            }
        }
        //var_dump($g);


        if($_GET['rid']){
            $dpf = 'Общая себестоимость с учётом количества';
        }else{
            $dpf = 'Общая себестоимость';
        }

        //$dpf = 'Общая себестоимость';
        $dpf2 = 'Стоимость единицы товара';
        $g->$dpf += $g->quantity * $g->$dpf2;

        foreach ($ss_dop_fields as & $sf)
        {
            $sf = trim($sf);
            if ($sf == '' || $sf == 'Общая себестоимость' || $sf=='Общая себестоимость с учётом количества' || $sf == 'Средняя себестоимость единицы' || $sf == 'Общая себестоимость единицы товара' || $sf == 'Стоимость единицы товара') continue;

            $g->$dpf += $g->$sf;
        }
        if($_GET['rid']){
            $dpf2 = 'Общая себестоимость единицы товара';
        }else{
            $dpf2 = 'Средняя себестоимость единицы';
        }

        $g->$dpf2 = intval($g->$dpf / $g->quantity);

        if (isset($_GET['rid']) && isset($_GET['bc']))
        {
            if ($g->incomeId != $_GET['rid'] || $g->barcode != $_GET['bc']) continue;

        }
        else if (isset($_GET['rid']))
        {
            if ($g->incomeId != $_GET['rid']) continue;
        }
        else
        {

            if (isset($keys_bc2[$g->incomeId]))
            {
                $sums = explode("\n", trim('quantity
                totalPrice'));
                // $sums[] = 'Stoimosty_edinicy_tovaraquantity';
                foreach ($ss_dop_fields as & $sf)
                {
                    $sf = trim($sf);
                    //  if ($sf == 'Стоимость единицы товара') continue;
                    $sf2 = 'dp_' . $sf;
                    $sums[] = ru2Lat($sf);
                    $sums[] = ru2Lat($sf2);
                }
                // echo '<pre>';var_dump($sums);
                // ..if ($g->incomeId=='6051704' and $fieldsum=='Zatraty_na_poisk_tovara'){echo '<pre>';var_dump($reps[$keys_bc2[$g->incomeId]]->$fieldsum);}

                foreach ($sums as $fieldsum)
                {
                    $fieldsum = trim($fieldsum);
                    $reps[$keys_bc2[$g->incomeId]]->$fieldsum += $g->$fieldsum;
                }
                /*$sums = explode("\n", trim('Стоимость единицы товара
        '.$ss_dop));
                if ($g->incomeId=='6051704'){var_dump($reps[$keys_bc2[$g->incomeId]]);}


                foreach ($sums as $fieldsum)
                {
                    $fieldsum = ru2Lat(trim($fieldsum));

                    $reps[$keys_bc2[$g->incomeId]]->$fieldsum += $g->$fieldsum;


                }*/
            }
            if ($last_code != $g->incomeId)
            {
                $last_code = $g->incomeId;
            }
            else
            {
                continue;
            }
        }
        if ($g->date){
            $g->date = date('d.m.Y',strtotime($g->date));
        }
        if ($g->dateClose){
            $g->dateClose = date('d.m.Y',strtotime($g->dateClose));
        }

        $reps[] = $g;
        $last_key = count($reps) - 1;
        $keys_bc[$g->incomeId] = $last_key;
        $keys_bc2[$g->incomeId] = $last_key;

        $g->incomeId2 = $g->incomeId;
        //$g->incomeId = '<a href="./index.php?page=wb&type=7&rid=' . $g->incomeId . '&dt=' . $_GET['dt'] . '"><img height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">' . $g->incomeId . '</a>';

    }

    //echo '<pre>';var_dump($keys_bc);var_dump($tpl_rows, $reps);
    $tbl_rows = $reps;

    //---------------------------------------
    // пересчетываем сгруппированную себестоимость по поставкам
    if (!isset($_GET['rid']))
    {

        $reps = [];
        foreach ($tbl_rows as $g)
        {
            if($_GET['rid']){
                $dpf = 'Общая себестоимость с учётом количества';
            }else{
                $dpf = 'Общая себестоимость';
            }
            //$dpf = 'Общая себестоимость';
            $dpf2 = 'Стоимость единицы товара';
            //$g->$dpf += $g->quantity *  $g->$dpf2;
            foreach ($ss_dop_fields as & $sf)
            {
                $sf = trim($sf);
                if ($sf == '' || $sf == 'Общая себестоимость' || $sf=='Общая себестоимость с учётом количества' || $sf == 'Средняя себестоимость единицы' || $sf == 'Общая себестоимость единицы товара' || $sf == 'Стоимость единицы товара') continue;

                $g->$dpf += $g->$sf;
            }
            if($_GET['rid']){
                $dpf2 = 'Общая себестоимость единицы товара';
            }else{
                $dpf2 = 'Средняя себестоимость единицы';
            }

            $g->$dpf2 = intval($g->$dpf / $g->quantity);

            $reps[] = $g;
        }
        $tbl_rows = $reps;

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

            //$g->save = "<input type=button  onclick='dp_save(this, \"{$kkk}_{$g->barcode}\", \"{$g->incomeId2}_{$g->barcode}\", 0);' class='dp_save_btn btn btn-success' style='display:none;' value='Сохранить'> <script>all_keys_col += '|'+'{$g->incomeId}_{$g->barcode}'; </script>";

            echo "<script>dp_krows.push('{$g->incomeId2}_{$g->barcode}')</script>";
        }
    }
}