
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
               $quantity += $row->quantity;
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
    if (
        (
            (
                $_GET['dt'] == date('Y-m-d') or $_GET['dt'] == date("Y-m-d", strtotime("-1 DAY"))
            )
            and !$_GET['dt1'] and !$_GET['dt2']
        )
        or
        (
            (
                (
                    $_GET['dt2']  == date('Y-m-d') and $_GET['dt1']  == date('Y-m-d'))
                        or ($_GET['dt1']  == date("Y-m-d", strtotime("-1 DAY",strtotime($_GET['dt2']))))
                        or ($_GET['dt1'] == $_GET['dt2']
                )
            )
            and $_GET['dt1'] and $_GET['dt2']
        )
    ){
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
        if ($_GET['type'] == 1 and $value->forPay >= 0) {
            $finished += $value->forPay * $value->quantity;
        }
        else {
            $finished += $value->totalPrice * ((100 - $value->discountPercent) / 100);
        }
    }
    return $finished;
}

function bars($rows,$dt1,$dt2,$bool){
    if ($bool) {
        $dates = range_date(1,25);
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

function templates($bars,$bars_old,$bool,$temp,$num,$cur){
    $days = array( 0=>"Вс","Пн" , "Вт" , "Ср" , "Чт" , "Пт" , "Сб" ,"Вс");
    foreach($bars['date'] as $key=>$date){
        $difference = color(num_format(($bars[$temp][$key] - $bars_old[$temp][$key]),$num));
        $day = $bool ? '' : $days[date('w',strtotime($date))];
        $old = $bool ? '' : ' - <b>'.$bars_old['date'][$key].' '.$days[date('w',strtotime($bars_old['date'][$key]))].'</b>';
        if(!$bool){$date_val = date('d.m',strtotime($date));}else{$date_val = $date;}

        $tooltip = '<div class="div3"><b>'.$date.' '.$day.'</b>'.$old.'
            <br/><div id="kvadrat1"></div> Текущий период: '.num_format($bars[$temp][$key],$num).$cur.'
            <br/><div id="kvadrat2"></div> Предыдущий период: '.num_format($bars_old[$temp][$key],$num).$cur.'
            <br/>Разница: <b>'.$difference.'</b>'.$cur.'</div>';
        $arr[] = [$date_val,$tooltip,$bars[$temp][$key],$bars_old[$temp][$key]];
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

$bool = if_date();

    //var_dump($bool);

$bars = bars($tbl_rows,$dt1_bar_org,$dt2_bar_org,$bool);

    //var_dump($bars);

$bars_old = bars($tbl_rows_bar,$dt1_bar,$dt2_bar,$bool);

$arr = templates($bars,$bars_old,$bool,'finishedPrice',2,' руб');

    //var_dump($arr);

$arr_quantity = templates($bars,$bars_old,$bool,'quantity',0,' шт');

$difference = differences($bars,$bars_old);

$bars = bars_format($bars);
$bars_old = bars_format($bars_old);
?>

<style type="text/css">
#kvadrat1 {
    width: 10px;
    height: 10px;
    background: rgb(51, 102, 204);
    color: #ffffff;
    white-space: nowrap;
    display: inline-block;
    border: 1px solid black;
}

#kvadrat2 {
    width: 10px;
    height: 10px;
    background: rgb(220, 57, 18);
    color: #ffffff;
    white-space: nowrap;
    display: inline-block;
    border: 1px solid black;
}

#legend{
    position: absolute;
    display: flex;
    width: 70%;
    height: 50px;
    margin-left: 20%;
    margin-top: 10px;
    z-index: 99;

}

.line{
    width:  40px;
    height:  3px;
    margin-left: 0px;
    margin-right: 10px;
    margin-top: 6px;
}

.div{
    margin-right: 30px;
    font: 300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif;

}
.div1{
    font: 300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif;
}
.div3{
    white-space: nowrap;
    padding:5px;
    font: 300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif;
}
</style>

<div id="legend">

    <hr class="line" style="background-color:rgb(51, 102, 204);"/><span class="div">Текущий период: &ensp; <?=$bars['finished']?> руб &ensp; <?=$bars["count"]?> шт</span>
    <hr class="line" style="background-color:red;"/><span class="div"> Предыдущий период: &ensp; <?=$bars_old['finished']?> руб &ensp; <?=$bars_old["count"]?> шт</span>
    <span class="div">Разница: &ensp; <b><?=$difference['sum']?></b> руб &ensp; <b><?=$difference['count']?></b> шт</span>

    <div style="margin-top:-7px;" class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-secondary active">
            <input type="radio" name="options" id="option1" value="option1" autocomplete="off" checked> <span class="div1">Рубли</span>
        </label>
        <label class="btn btn-secondary">
            <input type="radio" name="options" id="option2" value="option2" autocomplete="off"> <span class="div1">Количество</span>
        </label>

    </div>
    <div style="margin-top:-7px;" class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-secondary" for="btn-check">
            <input type="checkbox" class="btn-check" id="option3" autocomplete="off"> <span class="div1">Сглаживание</span>
        </label>
    </div>
</div>

<script type="text/javascript" src="js/gstatic-charts-loader.js"></script>

<script type="text/javascript">

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    var data_price = new google.visualization.DataTable();

    data_price.addColumn('string', 'year');
    data_price.addColumn({
          type: 'string',
          label: 'Tooltip Chart',
          role: 'tooltip',
          'p': {html:true}
        });
    data_price.addColumn({
        type:'number',
        id:'period-1',
        label: 'Текущий период - <?=$bars["finished"]?>р - <?=$bars["count"]?>шт',
    });
    data_price.addColumn({
        type:'number',
        id:'period-2',
        label: 'Предыдущий период - <?=$bars_old["finished"]?>р - <?=$bars_old["count"]?>шт',
    });

    data_price.addRows(<?=json_encode($arr)?>);



  var range = data_price.getColumnRange(2);
  var re = /\B(?=(\d{3})+(?!\d))/g;
  var max = Math.ceil(range.max / 100) * 100;
    //alert(min);
  if (range.min>999) {var min = Math.ceil(range.min / 1000) * 1000;}
  else{var min = 10000;}

  if (min % 2 == 0) {min=5000;}

  var ticks_price = [];

i=0;
while(i <= max + min){
 ticks_price.push({
      v: i,
      f: i.toFixed(0).replace(re, " "),
    });
    i = i + min;
}

var data_quantity = new google.visualization.DataTable();

    data_quantity.addColumn('string', 'year');
    data_quantity.addColumn({
          type: 'string',
          label: 'Tooltip Chart',
          role: 'tooltip',
          'p': {html:true}
        });
    data_quantity.addColumn({
        type:'number',
        id:'period-1',
        label: 'Текущий период - <?=$bars["finished"]?>р - <?=$bars["count"]?>шт',
    });
    data_quantity.addColumn({
        type:'number',
        id:'period-2',
        label: 'Предыдущий период - <?=$bars_old["finished"]?>р - <?=$bars_old["count"]?>шт',
    });

    data_quantity.addRows(<?=json_encode($arr_quantity)?>);

  var range = data_quantity.getColumnRange(2);
  var max = Math.ceil(range.max / 300) * 300;

  if (range.min>99) {var min = Math.ceil(range.min / 300) * 300;}
  else{var min = 300;}

  var ticks_quantity = [];

i=0;
while(i <= max + min){
 ticks_quantity.push({
      v: i,
      f: i,
    });
    i = i + min;
}

    var options = {
        focusTarget: 'category',
        tooltip: {
            isHtml: true,
        },
      //  curveType: 'function',
        vAxis: {
            maxValue: 100,
            ticks: ticks_price,
            textStyle: {
                   fontName: 'Open Sans, Helvetica Neue, helvetica, arial, verdana, sans-serif',
                   fontSize: '13' }
        },
        hAxis: {
            textStyle: {
                   fontName: 'Open Sans, Helvetica Neue, helvetica, arial, verdana, sans-serif',
                   fontSize: '13' }
        },
        legend: {
            position: 'none',
        }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data_price, options);

    $('input[type=radio][name=options]').change(function() {
    if (this.value == 'option1'){
        options.vAxis.ticks = ticks_price;
        chart.draw(data_price, options);
    }else if (this.value == 'option2'){
        options.vAxis.ticks = ticks_quantity;
        chart.draw(data_quantity, options);
    }
});

    $('input[type=checkbox][id=option3]').change(function() {
        console.log(this.value);
    if (this.value == 'on'){
        if (options.curveType != null && options.curveType != '') {
            options.curveType = '';
        }else{
            options.curveType = 'function';
        }
        let checkeds = document.querySelectorAll('input[type=radio][name=options]');
        let i = 0;
        while(i<checkeds.length){
            if (checkeds[i].checked) {
                if (checkeds[i].value == 'option1') {
                    options.vAxis.ticks = ticks_price;
                    chart.draw(data_price, options);
                }else if (checkeds[i].value == 'option2'){
                    options.vAxis.ticks = ticks_quantity;
                    chart.draw(data_quantity, options);
                }
            }
            i++;
        }
    }
});
/*
function checkAddress(checkbox)
{
  if (checkbox.checked == true) { //если включаем чекбокс
        options.curveType = 'function';
        chart.draw(data_quantity, options);
    }else{
        console.log(321);
    }
}
document.querySelector('#option3').addEventListener('change', function(evt){
 // if (evt.target.classList.contains('checkbox') && evt.target.checked) {
    console.log(321);
  //}
});
*/







}

    </script>

    <div id="curve_chart" style="width: 100%; height: 300px"></div>
