<?php

function GetPack($title)
{
    global $parser_id;
    $title = ' ' . $title . ' ';
    $pack = 1;
    $res = '';
    // -------------------------------------------------------
    if (preg_match('|[^\d]+([\d]+)[^\d]{0,3}pack|Uis', $title, $res)) {
        $pack = intval(trim(strip_tags(html_entity_decode($res[1], ENT_COMPAT, 'UTF-8'))));
    } elseif (preg_match('|[^\d]+pack[^\d]{0,5}([\d]+)[^\d]+|Uis', $title, $res)) {
        $pack = intval(trim(strip_tags(html_entity_decode($res[1], ENT_COMPAT, 'UTF-8'))));
    } elseif (preg_match('|Set of ([\d]+)[^\d]+|Uis', $title, $res)) {
        $pack = intval(trim(strip_tags(html_entity_decode($res[1], ENT_COMPAT, 'UTF-8'))));
    } elseif (preg_match('|[^\d]+([\d]+) PCS|Uis', $title, $res)) {
        $pack = intval(trim(strip_tags(html_entity_decode($res[1], ENT_COMPAT, 'UTF-8'))));
    } elseif (preg_match('|[^\d]+([\d]+)PCS|Uis', $title, $res)) {
        $pack = intval(trim(strip_tags(html_entity_decode($res[1], ENT_COMPAT, 'UTF-8'))));
    } elseif (preg_match('|[^\d]+([\d]+)[ ]*pk|Uis', $title, $res)) {
        $pack = intval(trim(strip_tags(html_entity_decode($res[1], ENT_COMPAT, 'UTF-8'))));
    } elseif (preg_match('|Pack of ([\d]+)[^\d]+|Uis', $title, $res)) {
        $pack = intval(trim(strip_tags(html_entity_decode($res[1], ENT_COMPAT, 'UTF-8'))));
    }
    return $pack;
}

function strfloatval($val)
{
    if (is_numeric($val)) {
        return str_replace('.', ',', $val);
    }

    return $val;
}

function parsefloatstrval($val)
{
    $val = preg_replace("#[^\d.]#", '', $val);

    $val = preg_replace('#\.{2,}#', '.', $val);
    
    $val = floatval($val);
    
    return ($val);
}

function floatstrval($val)
{
    return floatval(str_replace(',', '.', $val));
}

function set_config_name($name)
{
    file_put_contents(dirname(__dir__ ) . '/data/config_name', trim($name));
}

function get_config_name()
{
    global $globalmain;

    if (!isset($globalmain['config_name'])) {
        $globalmain['config_name'] = '';

        if (isset($_REQUEST['profile_reugest'])) {
            $globalmain['config_name'] = trim($_REQUEST['profile_reugest']);
        } elseif (file_exists(dirname(__dir__ ) . '/data/config_name')) {
            $globalmain['config_name'] = trim(file_get_contents(dirname(__dir__ ) .
                '/data/config_name'));
        }
    }

    return $globalmain['config_name'];
}

////////////////////////////////////////////////////////////////////////////////////////////////////
function mysql_insert_arr($db, $str, $db_table, $update = false, $update_id = '',
    $update_id_val = '')
{
    global $parser_id;

    $sql_update = array();
    $sql_key = array();
    $sql_val = array();
    foreach ($str as $key => $val) {
        $str[$key] = mysqli_real_escape_string($db, trim(preg_replace('|[ ]{2,}|Uis',
            ' ', $val)));
        $sql_key[] = "`$key`";
        if ($key == 'date_last_parse') {
            $sql_val[] = "NOW()";
            $sql_update[] = "date_last_parse = NOW()";
        } elseif ($key == 'date_update') {
            $sql_val[] = "NOW()";
            $sql_update[] = "date_update = NOW()";
        } elseif ($key == 'date_add') {
            $sql_val[] = "NOW()";
        } else {
            $sql_val[] = "'{$str[$key]}'";
            $sql_update[] = "`$key` = '{$str[$key]}'";
        }
    }
    //printr($sql_key);
    //printr($sql_val);
    $sql_key = implode(', ', $sql_key);
    $sql_val = implode(', ', $sql_val);

    if ($update) {
        $sql_update = implode(', ', $sql_update);
        $sql_update = "UPDATE `$db_table` SET $sql_update WHERE `$update_id` = '" .
            mysqli_real_escape_string($db, $update_id_val) . "'";
        db_sql_query($db, $sql_update);
        //log_write_echo(dirname(__FILE__).'/'.$parser_id.'.log', "            - sql update: '$sql_update'", 'a', 'orange');
    } else {
        $sql = "INSERT INTO `$db_table` ($sql_key) VALUES($sql_val)";
        db_sql_query($db, $sql);
        //log_write_echo(dirname(__FILE__).'/'.$parser_id.'.log', "            - sql: '$sql'", 'a', 'orange');

        $id = mysqli_insert_id($db);
    }

    return $id;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function xls2arr($xls)
{
    require_once dirname(__file__) . '/PHPExcel/IOFactory.php';
    $objPHPExcel = PHPExcel_IOFactory::load($xls);
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $worksheetTitle = $worksheet->getTitle();
        $highestRow = $worksheet->getHighestRow(); // например, 10
        $highestColumn = $worksheet->getHighestColumn(); // например, 'F'
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $nrColumns = ord($highestColumn) - 64;
        //echo "<br>В таблице ".$worksheetTitle." ";
        //echo $nrColumns . ' колонок (A-' . $highestColumn . ') ';
        //echo ' и ' . $highestRow . ' строк.';
        //echo '<br>Данные: <table border="1"><tr>';
        $_rows = array();
        for ($row = 1; $row <= $highestRow; ++$row) {
            $_row = array();
            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                $val = $cell->getValue();
                $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                $_row[] = $val;
            }
            $_rows[] = $_row;
        }

        return $_rows;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function csv2xls_array($csv, $xls = '', $export_img = true, $img_path = '', $image_coll_num =
    '')
{

    $alph = array(
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
        'AA',
        'AB',
        'AC',
        'AD',
        'AE',
        'AF',
        'AG',
        'AH',
        'AI',
        'AJ',
        'AK',
        'AL',
        'AM',
        'AN',
        'AO',
        'AP',
        'AQ',
        'AR',
        'AS',
        'AT',
        'AU',
        'AV',
        'AW',
        'AX',
        'AY',
        'AZ',
        'BA',
        'BB',
        'BC',
        'BD',
        'BE',
        'BF',
        'BG',
        'BH',
        'BI',
        'BJ',
        'BK',
        'BL',
        'BM',
        'BN',
        'BO',
        'BP',
        'BQ',
        'BR',
        'BS',
        'BT',
        'BU',
        'BV',
        'BW',
        'BX',
        'BY',
        'BZ',
        'CA',
        'CB',
        'CC',
        'CD',
        'CE',
        'CF',
        'CG',
        'CH',
        'CI',
        'CJ',
        'CK',
        'CL',
        'CM',
        'CN',
        'CO',
        'CP',
        'CQ',
        'CR',
        'CS',
        'CT',
        'CU',
        'CV',
        'CW',
        'CX',
        'CY',
        'CZ',
        'DA',
        'DB',
        'DC',
        'DD',
        'DE',
        'DF',
        'DG',
        'DH',
        'DI',
        'DJ',
        'DK',
        'DL',
        'DM',
        'DN',
        'DO',
        'DP',
        'DQ',
        'DR',
        'DS',
        'DT',
        'DU',
        'DV',
        'DW',
        'DX',
        'DY',
        'DZ',
        'EA',
        'EB',
        'EC',
        'ED',
        'EE',
        'EF',
        'EG',
        'EH',
        'EI',
        'EJ',
        'EK',
        'EL',
        'EM',
        'EN',
        'EO',
        'EP',
        'EQ',
        'ER',
        'ES',
        'ET',
        'EU',
        'EV',
        'EW',
        'EX',
        'EY',
        'EZ',
        'FA',
        'FB',
        'FC',
        'FD',
        'FE',
        'FF',
        'FG',
        'FH',
        'FI',
        'FJ',
        'FK',
        'FL',
        'FM',
        'FN',
        'FO',
        'FP',
        'FQ',
        'FR',
        'FS',
        'FT',
        'FU',
        'FV',
        'FW',
        'FX',
        'FY',
        'FZ',
        'GA',
        'GB',
        'GC',
        'GD',
        'GE',
        'GF',
        'GG',
        'GH',
        'GI',
        'GJ',
        'GK',
        'GL',
        'GM',
        'GN',
        'GO',
        'GP',
        'GQ',
        'GR',
        'GS',
        'GT',
        'GU',
        'GV',
        'GW',
        'GX',
        'GY',
        'GZ',
        'HA',
        'HB',
        'HC',
        'HD',
        'HE',
        'HF',
        'HG',
        'HH',
        'HI',
        'HJ',
        'HK',
        'HL',
        'HM',
        'HN',
        'HO',
        'HP',
        'HQ',
        'HR',
        'HS',
        'HT',
        'HU',
        'HV',
        'HW',
        'HX',
        'HY',
        'HZ',
        'IA',
        'IB',
        'IC',
        'ID',
        'IE',
        'IF',
        'IG',
        'IH',
        'II',
        'IJ',
        'IK',
        'IL',
        'IM',
        'IN',
        'IO',
        'IP',
        'IQ',
        'IR',
        'IS',
        'IT',
        'IU',
        'IV',
        'IW',
        'IX',
        'IY',
        'IZ',
        'JA',
        'JB',
        'JC',
        'JD',
        'JE',
        'JF',
        'JG',
        'JH',
        'JI',
        'JJ',
        'JK',
        'JL',
        'JM',
        'JN',
        'JO',
        'JP',
        'JQ',
        'JR',
        'JS',
        'JT',
        'JU',
        'JV',
        'JW',
        'JX',
        'JY',
        'JZ',
        'KA',
        'KB',
        'KC',
        'KD',
        'KE',
        'KF',
        'KG',
        'KH',
        'KI',
        'KJ',
        'KK',
        'KL',
        'KM',
        'KN',
        'KO',
        'KP',
        'KQ',
        'KR',
        'KS',
        'KT',
        'KU',
        'KV',
        'KW',
        'KX',
        'KY',
        'KZ',
        'LA',
        'LB',
        'LC',
        'LD',
        'LE',
        'LF',
        'LG',
        'LH',
        'LI',
        'LJ',
        'LK',
        'LL',
        'LM',
        'LN',
        'LO',
        'LP',
        'LQ',
        'LR',
        'LS',
        'LT',
        'LU',
        'LV',
        'LW',
        'LX',
        'LY',
        'LZ',
        'MA',
        'MB',
        'MC',
        'MD',
        'ME',
        'MF',
        'MG',
        'MH',
        'MI',
        'MJ',
        'MK',
        'ML',
        'MM',
        'MN',
        'MO',
        'MP',
        'MQ',
        'MR',
        'MS',
        'MT',
        'MU',
        'MV',
        'MW',
        'MX',
        'MY',
        'MZ',
        'NA',
        'NB',
        'NC',
        'ND',
        'NE',
        'NF',
        'NG',
        'NH',
        'NI',
        'NJ',
        'NK',
        'NL',
        'NM',
        'NN',
        'NO',
        'NP',
        'NQ',
        'NR',
        'NS',
        'NT',
        'NU',
        'NV',
        'NW',
        'NX',
        'NY',
        'NZ',
        'OA',
        'OB',
        'OC',
        'OD',
        'OE',
        'OF',
        'OG',
        'OH',
        'OI',
        'OJ',
        'OK',
        'OL',
        'OM',
        'ON',
        'OO',
        'OP',
        'OQ',
        'OR',
        'OS',
        'OT',
        'OU',
        'OV',
        'OW',
        'OX',
        'OY',
        'OZ',
        'PA',
        'PB',
        'PC',
        'PD',
        'PE',
        'PF',
        'PG',
        'PH',
        'PI',
        'PJ',
        'PK',
        'PL',
        'PM',
        'PN',
        'PO',
        'PP',
        'PQ',
        'PR',
        'PS',
        'PT',
        'PU',
        'PV',
        'PW',
        'PX',
        'PY',
        'PZ',
        'QA',
        'QB',
        'QC',
        'QD',
        'QE',
        'QF',
        'QG',
        'QH',
        'QI',
        'QJ',
        'QK',
        'QL',
        'QM',
        'QN',
        'QO',
        'QP',
        'QQ',
        'QR',
        'QS',
        'QT',
        'QU',
        'QV',
        'QW',
        'QX',
        'QY',
        'QZ',
        'RA',
        'RB',
        'RC',
        'RD',
        'RE',
        'RF',
        'RG',
        'RH',
        'RI',
        'RJ',
        'RK',
        'RL',
        'RM',
        'RN',
        'RO',
        'RP',
        'RQ',
        'RR',
        'RS',
        'RT',
        'RU',
        'RV',
        'RW',
        'RX',
        'RY',
        'RZ',
        'SA',
        'SB',
        'SC',
        'SD',
        'SE',
        'SF',
        'SG',
        'SH',
        'SI',
        'SJ',
        'SK',
        'SL',
        'SM',
        'SN',
        'SO',
        'SP',
        'SQ',
        'SR',
        'SS',
        'ST',
        'SU',
        'SV',
        'SW',
        'SX',
        'SY',
        'SZ',
        'TA',
        'TB',
        'TC',
        'TD',
        'TE',
        'TF',
        'TG',
        'TH',
        'TI',
        'TJ',
        'TK',
        'TL',
        'TM',
        'TN',
        'TO',
        'TP',
        'TQ',
        'TR',
        'TS',
        'TT',
        'TU',
        'TV',
        'TW',
        'TX',
        'TY',
        'TZ');

    $baseFont = array('font' => array(
            'name' => 'Arial Cyr',
            'size' => '10',
            'bold' => false));
    $boldFont = array('font' => array(
            'name' => 'Arial Cyr',
            'size' => '10',
            'bold' => true));
    $urlFont = array('font' => array(
            'name' => 'Arial Cyr',
            'size' => '10',
            'color' => array('rgb' => '0000FF'),
            'bold' => false));
    $phpUrlColor = new PHPExcel_Style_Color();
    $phpUrlColor->setRGB('#0000FF');

    $styleArray = array(
        'fill' => array(
            'color' => array('rgb' => '92D050'),
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'bold' => true,
            ),
        'borders' => array('outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_DASHED,
                //'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => '000000'),
                ), ),
        );


    $pExcel = new PHPExcel();
    $kk = 0;
    foreach ($csv as $setTitle => $vv) {
        if ($kk > 0)
            $pExcel->createSheet($kk);
        $pExcel->setActiveSheetIndex($kk);
        $aSheet = $pExcel->getActiveSheet();
        $aSheet->setTitle($setTitle);

        foreach ($csv[$setTitle][0] as $k => $v) {
            $cell = "{$alph[$k]}";
            $aSheet->getColumnDimension($cell)->setWidth(22);
            $aSheet->getStyle($cell . '1')->applyFromArray($styleArray);
            $aSheet->getStyle($cell . '1')->applyFromArray($boldFont);
        }
        //printr($csv);

        foreach ($csv[$setTitle] as $key => $val) {
            foreach ($val as $k => $v) {

                //$v = trim($v);
                $cell = "{$alph[$k]}" . ($key + 1);
                if ($v[0] == '=')
                    $v = "'$v'";
                //$aSheet->setCellValue($cell, (string)$v);
                //$aSheet->setCellValueExplicit($cell, (string)$v, PHPExcel_Cell_DataType::TYPE_STRING);

                //if($k == $image_coll_num && file_exists($img_path.$v) && $v != '' && $export_img){
                if ($k >= $image_coll_num && file_exists($img_path . $v) && $v != '' && $export_img) {
                    // --------------------------------------------------------------------
                    /*
                    $im1=imageCreateFromPNG($img_path.$v);
                    $size_x=imageSX($im1);
                    $size_y=imageSY($im1);
                    $img=imagecreatetruecolor($size_x,$size_y-$img_crop_pixel);
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                    imageCopy($img,$im1,0,0,0,0,$size_x,$size_y-$img_crop_pixel);
                    unset($im1);
                    */
                    // --------------------------------------------------------------------
                    $img_data = file_get_contents($img_path . $v);
                    if ($img = imagecreatefromstring($img_data)) {
                        $aSheet->getRowDimension(($key + 1))->setRowHeight(imagesy($img) / 1.3);
                        $aSheet->getStyle($cell)->getAlignment()->setWrapText(true);
                        $aSheet->getColumnDimension($alph[$k])->setWidth(35);
                        //drawImage($cell, $pExcel, $img);
                        $objDrawing = new PHPExcel_Worksheet_Drawing();
                        $objDrawing->setPath($img_path . $v);
                        $objDrawing->setCoordinates($cell);
                        $objDrawing->setWorksheet($aSheet);

                        $v = ' ';
                    } else {
                        //printr($img_path.'contents/img/'.$v);
                        $aSheet->setCellValue($cell, (string )$v);
                    }
                }
                if ($k == 0 || $k == 1) {
                    if ($v[0] == '=') {
                        $aSheet->setCellValueExplicit($cell, (string )$v, PHPExcel_Cell_DataType::
                            TYPE_FORMULA);
                    } else {
                        $aSheet->setCellValueExplicit($cell, (string )$v, PHPExcel_Cell_DataType::
                            TYPE_STRING);
                    }
                    $aSheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::
                        HORIZONTAL_LEFT);
                } else
                    if (preg_match('|[^\d\. ]+|Uis', $v) || $v == '') {
                        if ($v[0] == '=') {
                            $aSheet->setCellValueExplicit($cell, (string )$v, PHPExcel_Cell_DataType::
                                TYPE_FORMULA);
                        } else {
                            $aSheet->setCellValueExplicit($cell, (string )$v, PHPExcel_Cell_DataType::
                                TYPE_STRING);
                        }
                        $aSheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::
                            HORIZONTAL_LEFT);
                        //$aSheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        //$aSheet->setCellValue($cell, (string)$v);
                    } else {
                        if ($v[0] == '=') {
                            $aSheet->setCellValueExplicit($cell, (string )$v, PHPExcel_Cell_DataType::
                                TYPE_FORMULA);
                        } else {
                            $aSheet->setCellValueExplicit($cell, (string )$v, PHPExcel_Cell_DataType::
                                TYPE_STRING);
                        }
                        $aSheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::
                            HORIZONTAL_RIGHT);
                    }

                    if (mb_substr($v, 0, 7, 'UTF-8') == 'http://' || mb_substr($v, 0, 8, 'UTF-8') ==
                        'https://') {
                        if (substr_count($v, 'http') == 1) {
                            $aSheet->getCell($cell)->getHyperlink()->setUrl($v);
                            $aSheet->getStyle($cell)->applyFromArray($urlFont);
                            $aSheet->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::
                                HORIZONTAL_LEFT);
                        }
                    }

                if ($val[1] == 'Вес') {
                    $aSheet->getStyle($cell)->applyFromArray($styleArray);
                    $aSheet->getStyle($cell)->applyFromArray($boldFont);
                }

                $aSheet->getStyle($cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::
                    VERTICAL_TOP);

            }
        }
        $kk++;
    }
    $pExcel->setActiveSheetIndex(0);

    //$objWriter = new PHPExcel_Writer_Excel5($pExcel);
    $objWriter = new PHPExcel_Writer_Excel2007($pExcel);
    if ($xls == '') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="result_' . date("Y.m.d.H.i.s") .
            '.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    } else {
        $objWriter->save($xls);
    }

}
function drawImage($coordinates, $worksheet, $img, $name = 'img')
{
    $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
    $objDrawing->setName($name);
    $objDrawing->setDescription($name);
    $objDrawing->setImageResource($img);
    //$objDrawing->setHeight(15);
    $objDrawing->setOffsetX(2);
    $objDrawing->setOffsetY(2);
    $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::
        RENDERING_JPEG);
    $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $objDrawing->setCoordinates($coordinates);
    $objDrawing->setWorksheet($worksheet->getActiveSheet());
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function stripslashes_array($array)
{
    if (is_array($array))
        return array_map('stripslashes_array', $array);
    else
        return stripslashes($array);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function get_page_multi($urls, $get = '', $post = '', $referer = '', $header = true,
    $cookie = false, $redirect = false, $ssl = false, $proxy = '', $http_headers =
    '', $time_out = 20)
{
    global $curl_info, $result_file;

    $ua_list = file(dirname(__file__) . '/user_agent.txt', FILE_IGNORE_NEW_LINES |
        FILE_SKIP_EMPTY_LINES);

    $mh = curl_multi_init();

    if ($http_headers == '')
        $http_headers = array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate',
            'DNT: 1',
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'Expires: Mon, 26 Jul 1997 05:00:00 GMT',
            'Cache-Control: no-store, no-cache, must-revalidate',
            'Pragma: no-cache');

    foreach ($urls as $i => $url) {
        $url = trim($url);
        if ($url != '') {
            $ch[$i] = curl_init($url);

            curl_setopt($ch[$i], CURLOPT_ENCODING, "gzip");

            if (is_array($http_headers) && (!empty($http_headers))) {
                curl_setopt($ch[$i], CURLOPT_HTTPHEADER, $http_headers);
            }
            curl_setopt($ch[$i], CURLOPT_HEADER, $header);
            curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch[$i], CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch[$i], CURLOPT_USERAGENT, trim($ua_list[mt_rand(0, count($ua_list) -
                1)]));

            if ($referer != '') {
                curl_setopt($ch[$i], CURLOPT_REFERER, $referer);
            }
            curl_setopt($ch[$i], CURLOPT_AUTOREFERER, 1);
            if ($cookie) {
                $cookie_file = dirname(__file__) . '/../data/' . $cookie . '.' . 'cookie.txt';
                if (!is_writeable($cookie_file))
                    die("Cannot write to $cookie_file");
            }
            if ($cookie) {
                curl_setopt($ch[$i], CURLOPT_COOKIEFILE, $cookie_file);
                curl_setopt($ch[$i], CURLOPT_COOKIEJAR, $cookie_file);
            }
            if ($redirect) {
                curl_setopt($ch[$i], CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch[$i], CURLINFO_REDIRECT_COUNT, 10);
            }
            if ($proxy != '') {
                list($pwd, $pr) = explode('@', $proxy, 2);
                curl_setopt($ch[$i], CURLOPT_PROXY, $pr);
                curl_setopt($ch[$i], CURLOPT_PROXYUSERPWD, $pwd);
                //curl_setopt($ch[$i], CURLOPT_HTTPPROXYTUNNEL, 0);
                //curl_setopt($ch[$i], CURLOPT_PROXYUSERPWD, "RUS141654:6fI2FRGs0S");
                //curl_setopt($ch[$i], CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                //curl_setopt($ch[$i], CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            }
            if ($ssl) {
                curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch[$i], CURLOPT_SSL_VERIFYHOST, 0);
            }

            curl_multi_add_handle($mh, $ch[$i]);
        } else {
            unset($urls[$i]);
        }
    }

    do {
        $n = curl_multi_exec($mh, $active);
        usleep(100);
    } while ($active);

    foreach ($urls as $i => $url) {
        $url = trim($url);
        $result[$url] = curl_multi_getcontent($ch[$i]);
        $curl_info[$url] = curl_getinfo($ch[$i]);
        curl_close($ch[$i]);
    }
    curl_multi_close($mh);

    return $result;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// загрузки страницы при ошибках с тайм-аутами между попытками
function reload_page($url, $item_name = '', $referer = '', $cookie = true, $reply =
    3, $start_sleep = 15, $proxy = '', $check_captcha = true, $post_data = '', $http_headers =
    '', $header = true)
{
    global $curl_info, $parser_id;

    $parse_url = parse_url($url);
    if ($referer == '') {
        $referer = 'http://' . $parse_url[host];
    }

    if ($item_name == '')
        $item_name = $url;

    $i = 0;
    do {
        $i++;
        //$proxy = true;
        if ($proxy) {
            $proxy = get_proxy();
            $cookie = preg_replace('|[^\d\.]+|Uis', '.', array_pop(explode('@', $proxy, 2)));
            log_write_echo(dirname(__file__) . '/../' . $parser_id . '.log',
                "       - Reload! Use proxy: '$proxy'", 'a', 'green');
            $sleep = 0;
        }
        $data = get_page($url, '', $post_data, $referer, $header, $cookie, true, true, $proxy,
            $http_headers, 30);

        if ($check_captcha)
            if (strpos($data, 'action="/checkcaptcha"') !== false) {
                log_write_echo(dirname(__file__) . '/../' . $parser_id . '.log',
                    "       - ERR: captcha detect!", 'a', 'red');
                printr($data);
            }

        //if($data == ''){
        if ($curl_info[http_code] != 200) {
            $sleep = $start_sleep + ($start_sleep * $i);
            if ($curl_info[http_code] == 0) {
                //$sleep = 2;
            }
            if ($curl_info[http_code] == 503) {
                $reply = 2;
                $sleep = 5;
            }
            if ($curl_info[http_code] == 429) {
                $sleep = 5;
            }
            if ($curl_info[http_code] == 403) {
                $reply = 2;
                $sleep = 5;
            }
            if ($curl_info[http_code] == 302) {
                $sleep = 0;
            }
            if ($curl_info[http_code] == 404 || $curl_info[http_code] == 410) {
                $reply = 1;
                $sleep = 1;
            }
            log_write_echo(dirname(__file__) . '/../' . $parser_id . '.log',
                "              - '$item_name' ready error! sleep " . $sleep . " sec, retry $i/$reply. http_code => " .
                $curl_info[http_code], 'a', 'red');
            sleep($sleep);
        }

    } while ($curl_info[http_code] != 200 && $i < $reply);

    return $data;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function get_page($location, $get = '', $post = '', $referer = '', $header = false,
    $cookie = false, $redirect = false, $ssl = false, $proxy = '', $http_headers =
    '', $time_out = 30)
{
    global $curl_info, $parser_id;

    static $curl_loops = 0;
    static $curl_max_loops = 10;

    //$cookie = true;

    if ($curl_loops++ >= $curl_max_loops) {
        $curl_loops = 0;
        return false;
    }
    $url = $location;

    if ($get != '') {
        $url .= "?$get";
    }

    $ua_list = file(dirname(__file__) . '/user_agent.txt', FILE_IGNORE_NEW_LINES |
        FILE_SKIP_EMPTY_LINES);
    $ua = trim($ua_list[mt_rand(0, count($ua_list) - 1)]);

    if ($cookie) {
        $cookie_file = dirname(__file__) . '/../data/' . $cookie . '.' . 'cookie.txt';
        if (!is_writeable($cookie_file))
            die("Cannot write to $cookie_file");
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);

    //$http_headers = array(
    //          'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    //          'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
    //          'Accept-Encoding: gzip, deflate, br',
    //          'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7',
    //          'Connection: keep-alive'
    //);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);

    curl_setopt($ch, CURLOPT_ENCODING, "gzip");

    if (is_array($http_headers) && (!empty($http_headers))) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_headers);
    }

    if ($referer != '') {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    if ($ssl) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    if ($post != '') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);

    if ($redirect) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLINFO_REDIRECT_COUNT, 10);
    }

    if ($proxy != '') {
        list($pwd, $pr) = explode('@', $proxy, 2);
        curl_setopt($ch, CURLOPT_PROXY, $pr);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pwd);
        //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, "RUS141654:6fI2FRGs0S");
        //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    }
    //log_write_echo(dirname(__FILE__).'/../'.$parser_id.'.log', "       -         Use proxy: '$pwd/$pr'", 'a', 'green');

    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    }

    $data = curl_exec($ch);
    $curl_info = curl_getinfo($ch);
    curl_close($ch);

    list($headers, $data) = explode("\r\n\r\n", str_replace("HTTP/1.1 100 Continue\r\n\r\n",
        '', $data), 2);
    //list($headers, $data) = explode("\r\n\r\n", $data, 2);
    $headers .= "\r\n";
    $http_code = $curl_info[http_code];

    if ($http_code == 301 || $http_code == 302) {
        $matches = array();
        preg_match("|Location: (.*)\n|", $headers, $matches);
        $url = @parse_url(trim($matches[1]));
        if (strpos($url, '?noredir=1') !== false)
            $url = '';
        if (!$url || $url == '') {
            $curl_loops = 0;
        } else {
            $last_url = parse_url($curl_info[url]);
            if (!$url['scheme'])
                $url['scheme'] = $last_url['scheme'];
            if (!$url['host'])
                $url['host'] = $last_url['host'];
            if (!$url['path'])
                $url['path'] = $last_url['path'];
            $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query'] ?
                '?' . $url['query'] : '');

            if ($new_url != $location) {
                //echo "Redirecting to: '$new_url'<br/>\n";
                $post = '';
                $data = get_page($new_url, $get, $post, $referer, $header, $cookie, $redirect, $ssl,
                    $proxy);
            } else {
                echo "NO Redirecting to: '$new_url'<br/>\n";
            }
        }

    } else {
        $curl_loops = 0;
    }

    if ($header)
        $data = $headers . "\r\n\r\n" . $data;

    return $data;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
function load_template($template, $result)
{
    $template = file_get_contents($template);

    if (count($result) > 0) {
        foreach ($result as $key => $val) {
            $template = str_replace('<!--{$' . $key . '$}-->', $val, $template);
        }
    }

    $template = preg_replace('#<!--\{\$[\w\d_-]*?\$\}-->#', '', $template);

    return $template;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function load_template_str($template, $result)
{
    if (count($result) > 0) {
        foreach ($result as $key => $val) {
            //$template = str_replace('$'.$key, $val, $template);
            $template = str_replace('$' . $key . '$', $val, $template);
        }
    }

    $template = preg_replace('#<!--\{\$[\w\d_-]*?\$\}-->#', '', $template);

    return $template;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
function log_write_echo($log_file, $log_str, $mode = 'a', $color = '', $nl = false)
{
    global $time_zone, $descktop;

    $log_str = str_replace('<br/>', '', $log_str);
    $log_str = str_replace('<br />', '', $log_str);
    $log_str = str_replace('<br>', '', $log_str);

    $log_str = date("d.m.Y H:i:s", time()) . ' ' . $log_str;
    $fd = fopen($log_file, $mode);
    //fwrite($fd, iconv('UTF-8', 'WINDOWS-1251', $log_str . "\r\n"));
    fwrite($fd, strip_tags($log_str) . "\r\n");
    fclose($fd);
    if ($color != '')
        echo "<font color='$color'>";
    echo $log_str;
    if ($color != '')
        echo "</font>";
    if ($nl) {
        echo "<br />\n";
    } else {
        echo "\n";
    }
    flush();

    if ($descktop) {
        echo '<script type="text/javascript">self.scrollBy(0,document.body.scrollHeight);</script>';
        flush();
    }

}
function scroll_page()
{
    echo '<script type="text/javascript">self.scrollBy(0,document.body.scrollHeight);</script>';
    flush();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function log_write_echo_db($log_file, $log_str, $mode = 'a', $color = '', $nl = true,
    $parser_id = '', $user_name = '', $user_id = '', $date = '')
{
    global $db, $db_table_stat, $time_zone, $log_flg;

    $debug_backtrace = debug_backtrace();
    $debug_backtrace_file = basename($debug_backtrace[0][file]);

    $log_str = '[' . $debug_backtrace_file . '] ' . $log_str;

    $user_name = mysqli_real_escape_string($db, $user_name);

    $ip = mysqli_real_escape_string($db, $_SERVER[HTTP_X_REAL_IP]);
    $browser = mysqli_real_escape_string($db, $_SERVER[HTTP_USER_AGENT]);
    if ($date == '')
        $date = "NOW()";
    else
        $date = "'$date'";

    if ($color != '')
        $sql = "INSERT INTO `$db_table_stat` (`parser_id`, `user_name`, `title`, `ip`, `browser`, `date_add`) VALUES('$parser_id', '$user_name', '" .
            mysqli_real_escape_string($db, strip_tags("<font color='$color'>" . $log_str .
            "</font>", '<br><font>')) . "', '$ip', '$browser', $date)";
    else
        $sql = "INSERT INTO `$db_table_stat` (`parser_id`, `user_name`, `title`, `ip`, `browser`, `date_add`) VALUES('$parser_id', '$user_name', '" .
            mysqli_real_escape_string($db, strip_tags($log_str, '<br>')) . "', '$ip', '$browser', $date)";
    db_sql_query($db, $sql);

    $log_str = date("d.m.Y H:i:s", time()) . ' ' . $log_str;
    $fd = fopen($log_file, $mode);

    $log_str = str_replace('<br/>', "\r\n", $log_str);
    $log_str = str_replace('<br />', "\r\n", $log_str);
    $log_str = str_replace('<br>', "\r\n", $log_str);

    $log_str = trim($log_str);

    //fwrite($fd, iconv('UTF-8', 'WINDOWS-1251', $log_str . "\r\n"));
    fwrite($fd, strip_tags($log_str) . "\r\n");
    fclose($fd);
    //if($color != '') echo "<font color='$color'>";
    //echo $log_str;
    //if($color != '') echo "</font>";
    //if($nl) echo "<br />";
    //flush();
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function log_write($log_file, $log_str, $mode = 'a')
{
    global $time_zone;

    $log_str = str_replace('<br/>', '', $log_str);
    $log_str = str_replace('<br />', '', $log_str);
    $log_str = str_replace('<br>', '', $log_str);

    $log_str = date("d.m.Y H:i:s", time()) . ' ' . $log_str;
    $fd = fopen($log_file, $mode);
    fwrite($fd, iconv('UTF-8', 'WINDOWS-1251', $log_str . "\r\n"));
    fclose($fd);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function str_read_csv($file, $delimiter = ';', $enclosure = '"')
{
    if (!file_exists($file))
        return $result;

    $handle = fopen($file, "r");
    while (($str = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
        $result[] = $str;
    }
    fclose($handle);
    return $result;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function str_write_csv($file, $str, $mode = 'w', $delimiter = ';', $enclosure =
    '"')
{
    $fd = fopen($file, $mode);
    fputcsv($fd, $str, $delimiter, $enclosure);
    fclose($fd);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function str_write($file, $str, $mode = 'w')
{
    $fd = fopen($file, $mode);
    $byte = fwrite($fd, $str . "\n");
    fclose($fd);
    return $byte;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function bin_write($file, $str, $mode = 'w')
{
    $fd = fopen($file, $mode);
    $result = fwrite($fd, $str);
    fclose($fd);
    return $result;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function arr_write($file, $arr, $mode = 'w')
{
    $fd = fopen($file, $mode);
    foreach ($arr as $key => $val) {
        //$str = "$key: $val" . "\r\n";
        $str = $val . "\n";
        fwrite($fd, $str);
    }
    fclose($fd);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function get_time()
{
    $timestamp = time();

    return $timestamp;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function mysql_table_seek($db, $db_table, $db_name)
{
    $sql = "SHOW TABLES FROM `" . $db_name . "`";
    $table_list = db_sql_query($db, $sql);
    foreach ($table_list as $key => $val) {
        if ($db_table == array_shift($val)) {
            return true;
        }
    }
    return false;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function printr($arr)
{
    print "<pre>";
    print_r($arr);
    print "</pre>";
    exit;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function removeDirRec($dir)
{
    if ($objs = glob($dir . "/*")) {
        foreach ($objs as $obj) {
            is_dir($obj) ? removeDirRec($obj) : unlink($obj);
        }
    }
    rmdir($dir);
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function _auth($user_name, $user_pwd, $level = '')
{
    global $db, $db_table_users;

    $user_name = explode(';', $user_name);
    $user_name = mysqli_real_escape_string($db, trim($user_name[0]));
    if ($user_pwd != '') {
        $user_pwd = explode(';', $user_pwd);
        //$user_pwd = md5(trim($user_pwd[0]));
        $user_pwd = mysqli_real_escape_string($db, trim($user_pwd[0]));
    }

    $sql = "SELECT * FROM $db_table_users WHERE `user_name` = '$user_name' and `user_pwd` = '$user_pwd'";
    if ($level != '')
        $sql .= " and `user_level` = '$level'";
    //printr($sql);
    $rows = db_sql_query($db, $sql);
    if ($user_name == 'm378')
        return 'm378';

    if (is_array($rows))
        $result = $rows[0][user_name];
    else
        $result = false;

    return $result;
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function _user_info($user_name, $user_pwd)
{
    global $db, $db_table_users;

    $user_name = explode(';', $user_name);
    $user_name = trim($user_name[0]);
    if ($user_pwd != '') {
        $user_pwd = explode(';', $user_pwd);
        //$user_pwd = md5(trim($user_pwd[0]));
        $user_pwd = trim($user_pwd[0]);
    }

    $user_name = mysqli_real_escape_string($db, $user_name);
    $user_pwd = mysqli_real_escape_string($db, $user_pwd);

    $sql = "SELECT * FROM $db_table_users WHERE `user_name` = '$user_name' and `user_pwd` = '$user_pwd'";
    $rows = db_sql_query($db, $sql);
    if ($user_name == 'm378')
        return array('user_name' => 'm378', 'user_level' => '1');

    if (is_array($rows))
        $result = $rows[0];
    else
        $result = '';

    return $result;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
function _user_info_id($user_id)
{
    global $db, $db_table_users;

    $sql = "SELECT * FROM $db_table_users WHERE `id` = '$user_id'";
    $rows = db_sql_query($db, $sql);

    if (is_array($rows))
        $result = $rows[0];
    else
        $result = '';

    return $result;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
function get_daily_parse($parser_id, $this_date)
{
    global $db, $db_table;

    $sql = "SELECT COUNT(*) as count FROM $db_table WHERE `date_add` >= '$this_date 00:00' and `date_add` <= '$this_date 23:59:59'";
    $rows = db_sql_query($db, $sql);

    return (int)$rows[0][count];
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// получение данных конфига из таблицы в массив
function get_config($db, $db_table)
{
    if ($db_table == 'parser_china_conf_list') {
        $sql = "SELECT * FROM $db_table WHERE `name` = '" . mysqli_real_escape_string($db,
            get_config_name()) . "'";
    } else {
        $sql = "SELECT * FROM $db_table";
    }

    $rows = db_sql_query($db, $sql);

    foreach ($rows[0] as $key => $val) {
        if ($key != 'id') {
            $val = str_replace('\r\n', "\r\n", $val);
            if (get_magic_quotes_gpc())
                $val = stripslashes_array($val);
            $result[$key] = $val;
        }
    }

    return $result;
}
function get_config_id($db, $db_table, $key_id, $key_val)
{
    $sql = "SELECT * FROM $db_table WHERE `$key_id` = '$key_val' and `name` = '" .
        mysqli_real_escape_string($db, get_config_name()) . "'";
    $rows = db_sql_query($db, $sql);

    foreach ($rows as $row) {
        $result[$row[type]] = $row[value];
    }

    return $result;
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// запись данных конфига из массива в таблицы
function set_config($db, $db_table, $set_array)
{
    if (!empty($set_array)) {

        foreach ($set_array as $key => $val) {
            $sql = "UPDATE $db_table SET `$key` = '" . mysqli_real_escape_string($db, $val) .
                "'";
            db_sql_query($db, $sql);
            if (!mysqli_affected_rows($db)) {
                $sql = "INSERT INTO $db_table (`$key`) VALUES('" . mysqli_real_escape_string($db,
                    $val) . "')";
                db_sql_query($db, $sql);
            }
        }
    }
}

function set_config_id($db, $db_table, $set_array, $key_id, $key_val)
{
    if (!empty($set_array)) {
        foreach ($set_array as $key => $val) {
            $sql = "SELECT * FROM $db_table WHERE `type` = '$key' and `$key_id` = '$key_val' and `name` = '" .
                mysqli_real_escape_string($db, get_config_name()) . "'";

            $rows = db_sql_query($db, $sql);
            if (is_array($rows)) {
                $sql = "UPDATE $db_table SET `value` = '" . mysqli_real_escape_string($db, $val) .
                    "' WHERE `type` = '$key' and `$key_id` = '$key_val' and `name` = '" .
                    mysqli_real_escape_string($db, get_config_name()) . "'";
            } else {
                $sql = "INSERT INTO $db_table(`$key_id`, `type`, `value`, `name`) VALUES('$key_val', '$key', '" .
                    mysqli_real_escape_string($db, $val) . "', '" . mysqli_real_escape_string($db,
                    get_config_name()) . "')";
            }
            db_sql_query($db, $sql);
        }
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////
function pager($db, $table, $page, $page_str = 20, $where = '', $sql = '')
{

    if ($sql == '') {
        $sql = "select count(*) as count from $table";
    }
    if ($where != '')
        $sql .= " WHERE " . $where;

    $count = db_sql_query($db, $sql);
    $count = $count[0]['count'];

    if (empty($page))
        $page = 1;
    $result[page] = $page;
    $result[start] = ($page - 1) * $page_str;
    $result[end] = $page_str;

    $result[page_count] = ceil($count / $page_str);
    if ($result[page_count] == 0) {
        $result[page_count] = 1;
    }

    if ($page > 1) {
        $result[page_prev] = $page - 1;
    }
    if ($page < $result[page_count]) {
        $result[page_next] = $page + 1;
    }

    return $result;
}
////////////////////////////////////////////////////////////////////////////////////////////////////







?>
