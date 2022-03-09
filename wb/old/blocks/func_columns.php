<?php

//переименование
$renames  = ['Stoimosty_edinicy_tovara' => 'Стоимость товара',
    'Srednyaya_sebestoimosty_edinicy'=>'Средняя себестоимость'];

//не скрывать в отчетах реализаций
$hidden = ['realizationreport_id','rr_dt','return_amount','delivery_amount','quantity','retail_amount','ppvz_vw','ppvz_vw_nds'
    ,'storage_cost','acceptance_fee','other_deductions','ppvz_for_pay','delivery_rub','storage_cost', 'acceptance_fee',
    'other_deductions','total_payable'];

//убрать столбец
$cancel = ['save'];

//ширина столбца
$width = ['barcode'=>140,'number'=>120,'date'=>145,'lastChangeDate'=>145];

//шаблон столбца
$tpl = [5=>'rid={realizationreport_id}',
    7=>'rid={incomeId}&dt='.$_GET['dt'],
    8=>'f1={supplierArticle}&dt='.$_GET['dt'],
    9=>'rid={realizationreport_id}',
];

$tpl_input = [
    5=>"realizationreport_id='{realizationreport_id}' onblur=\"number_update('{id}',this.value,this.id,{realizationreport_id})\"",
    7=>"hidden='false' incomeId='".$_GET['rid']."' barcode='{barcode}' supplierArticle='{supplierArticle}' onblur=\"number_update('{id}',this.value,this.id,".$_GET['rid'].",'{supplierArticle}',{barcode})\"",
    8=>"idd='{id}' hidden='false' incomeId='{incomeId}' barcode='{barcode}' supplierArticle='{supplierArticle}' onblur=\"number_update('{id}',this.value,this.id,{incomeId},'{supplierArticle}',{barcode})\"",
    9=>"hidden='false' realizationreport_id='{realizationreport_id}' onblur=\"number_update('{id}',this.value,this.id,{realizationreport_id})\"",
];
$tpl_a = [
    'Obschaya_sebestoimosty_edinicy_tovara'=>'inputSum',
    'Obschaya_sebestoimosty_s_uchetom_kolichestva'=>'inputSumKol',
    'quantity'=>'inputKol',
    'ss_one'=>'inputSum',
    'ss_all'=>'inputSumKol',
];
$tpl_a_div = [
    7 =>"incomeId='" . $_GET['rid'] . "' barcode='{barcode}' supplierArticle='{supplierArticle}'",
    8 =>"incomeId='{incomeId}' barcode='{barcode}' supplierArticle='{supplierArticle}'",
];

//редактируемые ячейки
$or = ['storage_cost','acceptance_fee','other_deductions'];
$ss_dops = explode("\n",$ss_dop);
$ss_dopp[] = 'Stoimosty_edinicy_tovara';

//константы для ссылок
$f1 = (isset($_GET['f1']) ? "&f1={$_GET['f1']}" : (isset($_GET['rid']) ? "&f1={$_GET['rid']}" : "")); //(isset($_GET['f1']) ? "&f1={$_GET['f1']}" : "");

foreach ($ss_dops as $ss_d) {
    $ss_d = ru2Lat(trim($ss_d));
    $ss_dopp[] = $ss_d;
}

foreach ($tbl_keys as $k => $str){
    //переименование
    if (in_array($_GET['type'],[7,8]) and !$_GET['rid'] and !$_GET['f1'] and array_key_exists($k,$renames)){
        $str = $renames[$k];
    }
    //убрать столбец
    if (in_array($k,$cancel)){continue;}
    //столбцы
    $column = [
        'text' => '<span data-qtip="'.mb_ucfirst($str).'">'.mb_ucfirst($str).'</span>',
        'id' => $k,
        'dataIndex' => $k,
        'sortable' => true,
        'hideable' => true,
        'useNull' => true,
        'defaultValue' => '---',
        'xtype'=>'templatecolumn',
    ];
    //не скрывать в отчетах реализаций
    if ((in_array($k,$hidden) and $_GET['type'] == 5 and !$_GET['rid']) or ($_GET['type'] == 5 and $_GET['rid'])){
        $column['hidden'] = false;
    }elseif($_GET['type'] == 5 and !$_GET['rid']){
        $column['hidden'] = true;
    }
    //ширина столбца
    if (array_key_exists($k,$width)){
        $column['width'] = $width[$k];
    }
    //редактируемые ячейки
    if ((in_array($_GET['type'],[5,9]) and $k=='realizationreport_id') or ($_GET['type'] == 7 and $k=='incomeId') or ($_GET['type'] == 8 and $k=='supplierArticle') ){
        $column['tpl'] = "<a href='?page=wb&type=".$_GET['type'].'&'.$tpl[$_GET['type']].'\'><img '.(in_array($_GET['type'],[8,9]) ? 'hidden' : '').' height="20px" style="vertical-align: middle;" src="https://v1.iconsearch.ru/uploads/icons/gnomeicontheme/24x24/stock_list_bullet.png">{'.$k.'}</a>';
    }
    elseif (!$_GET['rid'] and !in_array($k,$or) and in_array($_GET['type'],[5])){
        $column['tpl'] = "<a href='?page=wb&type=".$_GET['type'].$f1."&".$tpl[$_GET['type']]."'>{".$k."}</a>";
    }
    elseif (!$_GET['rid'] and (!in_array(ru2Lat($k),$ss_dopp) or !$_GET['rid'])
        and in_array($_GET['type'],[7])){

        $column['tpl'] = "<a href='?page=wb&type=".$_GET['type'].$f1."&".$tpl[$_GET['type']]."'>{".$k."}</a>";
    }
    elseif (!$_GET['f1'] and (!in_array(ru2Lat($k),$ss_dopp) or !$_GET['f1'])
        and (($k != 'ss_one' and $k != 'ss_all'and $k!="quantity" or !$_GET['f1']) and $_GET['type']==8)
        and in_array($_GET['type'],[8])){

        $column['tpl'] = "<a href='?page=wb&type=".$_GET['type'].$f1."&".$tpl[$_GET['type']]."'>{".$k."}</a>";
    }
    elseif (!$_GET['rid'] and !in_array($k,$or) and in_array($_GET['type'],[9])){

        $column['tpl'] = "<a href='?page=wb&type=".$_GET['type'].$f1."&".$tpl[$_GET['type']]."'>{".$k."}</a>";
    }
    elseif (in_array($k,$or) or ((($_GET['f1'] and $_GET['type']==8) or ($_GET['rid'] and $_GET['type']==7))
            and in_array(ru2Lat($k),$ss_dopp))){

        $column['tpl'] = "<input type=\"text\" id='$k' ".$tpl_input[$_GET['type']]." class='inputValue' onkeyup=\"this.value = this.value.replace(/[^^0-9\.]/g,'');\" value='{".$k."}'>";
    }
    elseif (($_GET['type']==7 and array_key_exists($k,$tpl_a) and $_GET['rid']) or ($_GET['type']==8 and $_GET['f1'])){

        $column['tpl'] = "<a href='?page=wb&type=".$_GET['type'].$f1."&dt=".$_GET['dt']."&f2=".$k."&f3={".$k."}'><div class='".$tpl_a[$k]."' ".$tpl_a_div[$_GET['type']].">{" . $k . "}</div></a>";
    }
    else{
        $column['tpl'] = "<a href='?page=wb&type=".$_GET['type'].$f1."&dt=" . $_GET['dt']."&f2=".$k."&f3={".$k."}'>{".$k."}</a>";
    }
    $columns[] = (object) $column;
}