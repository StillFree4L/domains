<?php

function num_format($number,$count=0){
    return number_format($number, $count, ',', ' ');
}

function color($result){
    if ($result < 0) {
        return '<span style="color:red;">'.$result.'</span>';
    }else{
        return '<span style="color:green;">'.$result.'</span>';
    }
}


function dates($dt1,$dt2){
    $from = new DateTime($dt1);
    $to = new DateTime($dt2);
    $period = new DatePeriod($from, new DateInterval('P1D'), $to);
    $arrayOfDates = array_map(
        function ($item) {
            return $item->format('d.m.Y');
        },
        iterator_to_array($period)
    );
    return $arrayOfDates;
}

function bar($rows,$dates,$format){
    $i = 0;
    foreach ($dates as $date) {
        $price = 0;
        $quantity = 0;
        foreach ($rows as $row) {
           $day = date($format, strtotime($row->date));

           if ($date == $day) {
               $price += $row->finishedPrice;
               // var_dump($price);
               $quantity += $row->quantity;
           } else {
                //$price += 0;
            }
        }
        if ($price < 0){$price*=-1;}
        if ($quantity < 0){$quantity*=-1;}
        if ($format == 'H') {$date = $i.':00';}
        $bars['date'][$i] = $date;
        $bars['finishedPrice'][$i] += $price;
        $bars['quantity'][$i] += $quantity;
        $i++;
    }
    return $bars;
}

function if_date(){
    if (($_GET['dt'] == date('Y-m-d')
        or $_GET['dt'] == date("Y-m-d", strtotime("-1 DAY")) and !$_GET['dt1'])
        or ($_GET['dt2']  == date('Y-m-d') and $_GET['dt1']  == date('Y-m-d'))){
        return true;
    }
    return false;
}

function range_date($min,$max){
    foreach (range($min, $max) as $number) {
        $arr[] = $number;
    }
    return $arr;
}

function counts($rows){
    foreach ($rows as $key => $value) {
        $finished += $value->finishedPrice;
    }
    return $finished;
}

function bars($rows,$dt1,$dt2,$bool){
    if ($bool) {
        $dates = range_date(0,25);
        $format = 'H';
    }
    else{
        $dates = dates($dt1,$dt2);
        $format = 'd.m.Y';
    }

    $bars = bar($rows,$dates,$format);

    $bars['count'] = count($rows);
    $bars['finished'] = counts($rows);

    return $bars;
}

function templates_price($bars,$bars_old,$bool){
    $days = array( 0=>"Вс","Пн" , "Вт" , "Ср" , "Чт" , "Пт" , "Сб" ,"Вс");
    foreach($bars['date'] as $key=>$date){
        $difference = color(num_format(($bars['finishedPrice'][$key] - $bars_old['date'][$key]),2));
        $day = $bool ? '' : $days[date('w',strtotime($date))];
        if(!$bool){$date_val = date('d.m',strtotime($date));}else{$date_val = $date;}

        $tooltip = '<div style="white-space: nowrap; padding:5px;"><b>'.$date.' '.$day.'</b>
            <br/><div id="kvadrat1"></div> Текущий период: '.num_format($bars['finishedPrice'][$key],2).'
            <br/><div id="kvadrat2"></div> Предыдущий период: '.num_format($bars_old['finishedPrice'][$key],2).'
            <br/>Разница: <b>'.$difference.'</b></div>';
        $arr[] = [$date_val,$tooltip,$bars['finishedPrice'][$key],$bars_old['finishedPrice'][$key]];
    }
    return $arr;
}

function differences($bars,$bars_old){
    $difference['sum'] = color(num_format(($bars['finished'] - $bars_old['finished']),2));
    $difference['count'] = color(num_format($bars["count"] - $bars_old["count"]));
    return $difference;
}

function bars_format($bars){
    $bars["count"] = num_format($bars["count"]);
    $bars['finished'] = num_format($bars['finished'],2);
    return $bars;
}

function templates_quantity($bars,$bars_old,$bool,$temp){
    $days = array( 0=>"Вс","Пн" , "Вт" , "Ср" , "Чт" , "Пт" , "Сб" ,"Вс");
    foreach($bars['date'] as $key=>$date){
        $difference = color(num_format(($bars[$temp][$key] - $bars_old['date'][$key]),2));
        $day = $bool ? '' : $days[date('w',strtotime($date))];
        if(!$bool){$date_val = date('d.m',strtotime($date));}else{$date_val = $date;}

        $tooltip = '<div style="white-space: nowrap; padding:5px;"><b>'.$date.' '.$day.'</b>
            <br/><div id="kvadrat1"></div> Текущий период: '.num_format($bars[$temp][$key],2).'
            <br/><div id="kvadrat2"></div> Предыдущий период: '.num_format($bars_old[$temp][$key],2).'
            <br/>Разница: <b>'.$difference.'</b></div>';
        $arr[] = [$date_val,$tooltip,$bars[$temp][$key],$bars_old[$temp][$key]];
    }
    return $arr;
}