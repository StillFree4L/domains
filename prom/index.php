<?php

require_once 'PHPExcel-1.8/Classes/PHPExcel.php';

$pExcel = PHPExcel_IOFactory::load('doc.xlsx');
$results = array();
// Цикл по листам Excel-файла
foreach ($pExcel->getWorksheetIterator() as $worksheet) {
    // выгружаем данные из объекта в массив
    $tables[] = $worksheet->toArray();
}


$list = 1;
// Цикл по листам Excel-файла
foreach($tables as $table) {
    $j = 0;
    // Цикл по строкам
    foreach($table as $row) {
        $catalog = array();
        $i = 1;
        // Цикл по колонкам
        foreach( $row as $col ) {
            if($i == 2 and $j>0 and $list == 1){
                $results[$j]['id']=$j;
                $results[$j]['name_product']=trim($col);
            }elseif($i == 4 and $j>0 and $list == 1){
                preg_match("'<strong>(.*?)</strong>'si", trim($col), $match);
                $results[$j]['description']=strip_tags($match[1]);
            }elseif($i == 15 and $j>0 and $list == 1){
                $results[$j]['number_group']=trim($col);
            }elseif($i == 16 and $j>0 and $list == 1 and trim($col) != 'Корневая группа'){
                $results[$j]['name_group']=trim($col);
            }elseif($i == 23 and $j>0 and $list == 1){
                $results[$j]['id_subsection']=trim($col);
            }elseif($i == 25 and $j>0 and $list == 1){
                $results[$j]['manufacturer']=trim($col);
            }
            if($i < 5 and $j>0 and $list == 3){
                if ($i == 3 and !is_null($col)){$h = $col;}
                if($i == 4){
                    if (is_null($col)){$col = $h;}
                    $output = preg_split( "/ /", mb_strtolower(trim($col)));
                    $catalog['synonym']=substr($output[0],0,-2);
                    $catalog[]=trim($col);
                }else{
                    $catalog[]=trim($col);
                }
            }
            if($i == 6 and $j>0 and $list == 3){
                foreach($results as $result){
                    if($result['id_subsection']==$col){
                        $results[$result['id']]['catalog'] = $catalog;
                    }
                }
            }
            $i++;
        }
        $j++;
    }
    $list++;
}

$fp = @fopen("1synmaster.txt", "r");
$array = array();
if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
        $array[] = $buffer;
    }
    if (!feof($fp)) {
        echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
    }
    fclose($fp);
}

foreach($array as $arr){
    foreach($results as $result){
        if($results[$result['id']]['catalog']['synonym']) {
            $pos = strpos($arr, $results[36]['catalog']['synonym']);
            if ($pos !== false) {
                $results[36]['synonyms'][] = trim($arr);
            }
        }
    }
}

foreach($results as $result){
    if($result['name_product']){
        $results[$result['id']]['search'] .= $result['name_product'];
    }
    if($result['manufacturer']){
        $results[$result['id']]['search'] .= ','.$result['manufacturer'];
    }
    if($result['description']){
        $results[$result['id']]['search'] .= ','.$result['description'];
    }
    if($result['name_group']){
        $results[$result['id']]['search'] .= ','.$result['name_group'];
    }
    if($result['catalog']){
        foreach($result['catalog'] as $catalog){
            $results[$result['id']]['search'] .= ','.$catalog;
        }
    }
    if($result['synonyms']){
        foreach($result['synonyms'] as $synonym){
            $results[$result['id']]['search'] .= ','.str_replace("|", ",", trim($synonym));
        }
    }
}

$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();
$j = 2;
foreach($results as $result){
    $aSheet->setCellValue('C'.$j,$result['search']);
    $j++;
}
$pExcel->removeSheetByIndex(2);

$objWriter = PHPExcel_IOFactory::createWriter($pExcel, 'Excel2007');
$objWriter->save('simple.xlsx');

?>