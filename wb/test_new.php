
<?php

function num_format($number,$count=0){
    return number_format($number, $count, ',', ' ');
}


function barss($tbl_rows,$dt1,$dt2){
    if (($_GET['dt'] == date('Y-m-d') or $_GET['dt'] == date("Y-m-d", strtotime("-1 DAY")) and !$_GET['dt1']) or ($_GET['dt2']  == date('Y-m-d') and $_GET['dt1']  == date('Y-m-d'))){

        $i=0;
        while ($i<25){
            $val = (int)0;
            foreach ($tbl_rows as $key => $value) {
                $day = intval(date('H', strtotime($value->date)));
                if ($i == $day) {
                    $val += (int)str_replace(" ", "", $value->finishedPrice);
                } else {
                    $val += (int)0;
                }
            }
            if ($val<0){$val*=-1;}
            $bars['date_value'][$i] = $i.':00';
            $bars['title'][$i] = $i.':00';
            $bars['finishedPrice'][$i] += (int)$val;
            $i++;
        }

    }else{
        $from = new DateTime($dt1);
        $to = new DateTime($dt2);

        $period = new DatePeriod($from, new DateInterval('P1D'), $to);

        $arrayOfDates = array_map(
            function ($item) {
                return $item->format('d.m.Y');
            },
            iterator_to_array($period)
        );
/////////////
      -  if ($_GET['dt'] == date("Y-m-d", strtotime("-1 DAY")) and !$_GET['dt1']){
            $arrayOfDates[] = date('d.m.Y');
            $arrayOfDates = array_reverse($arrayOfDates);
            $arrayOfDates[] = date('d.m.Y', strtotime("-2 DAY"));
      -  }

        $i = 1;
        foreach ($arrayOfDates as $arrayOfDate) {
            $val = 0;
            foreach ($tbl_rows as $value) {
                $day = date('d.m.Y', strtotime($value->date));



                if ($arrayOfDate == $day) {
                    $val += $value->finishedPrice;
                } else {
                    $val += 0;
                }
            }
            if ($val<0){$val*=-1;}

            $bars['date_value'][$i] = date('d.m',strtotime($arrayOfDate));
            $bars['title'][$i] = $arrayOfDate;
            $bars['finishedPrice'][$i] += $val;

            $i++;
        }
    }
    foreach ($tbl_rows as $key => $value) {$finished += $value->finishedPrice;}

    $bars['count'] = count($tbl_rows);
    $bars['date'] = array_keys($bars['finishedPrice']);
    $bars['finished'] = $finished;

    return $bars;
}

$bars = barss($tbl_rows,$dt1_bar_org,$dt2_bar_org);
$bars_old = barss($tbl_rows_bar,$dt1_bar,$dt2_bar);

function color($result){
    if ($result < 0) {
        return '<span style="color:red;">'.$result.'</span>';
    }else{
        return '<span style="color:green;">'.$result.'</span>';
    }
}

$days = array( 0=>"Вс",1 => "Пн" , "Вт" , "Ср" , "Чт" , "Пт" , "Сб" ,"Вс");

$arr = array();

foreach($bars['date'] as $key=> $value){

    $b = $bars['finishedPrice'][$value] - $bars_old['finishedPrice'][$value];

    $tooltip = '<div style="white-space: nowrap; padding:5px;"><b>'.$bars['title'][$value].' '.$days[date('w',strtotime($bars['title'][$value]))].'</b>
            <br/><div id="kvadrat1"></div> Текущий период: '.num_format($bars['finishedPrice'][$value]).'
            <br/><div id="kvadrat2"></div> Предыдущий период: '.num_format($bars_old['finishedPrice'][$value]).'
            <br/>Разница: <b>'.color(num_format($b)).'</b></div>';

    $arr[] = [$bars['date_value'][$value],$tooltip,$bars['finishedPrice'][$value],$bars_old['finishedPrice'][$value]];

}

$sum_difference = num_format($bars['finished'] - $bars_old['finished']);
$count_difference = num_format($bars["count"] - $bars_old["count"]);

$bars["count"] = num_format($bars["count"]);
$bars_old["count"] = num_format($bars_old["count"]);

$bars['finished'] = num_format($bars['finished'],2);
$bars_old['finished'] = num_format($bars_old['finished'],2);

$count_difference = color($count_difference);
$sum_difference = color($sum_difference);

?>

<script type="text/javascript" src="js/gstatic-charts-loader.js"></script>


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
</style>

<div id="legend">

    <hr class="line" style="background-color:rgb(51, 102, 204);"/><span class="div">Текущий период &ensp; <?=$bars['finished']?>р &ensp; <?=$bars["count"]?>шт</span>
    <hr class="line" style="background-color:red;"/><span class="div"> Предыдущий период &ensp; <?=$bars_old['finished']?>р &ensp; <?=$bars_old["count"]?>шт</span>
    <span class="div">Разница &ensp; <?=$sum_difference?>р &ensp; <?=$count_difference?>шт</span>

    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-secondary active">
            <input type="radio" name="options" id="option1" autocomplete="off" checked> Рубли
        </label>
        <label class="btn btn-secondary">
            <input type="radio" name="options" id="option2" autocomplete="off"> Количество
        </label>
    </div>

</div>

<script type="text/javascript">

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
         var data = new google.visualization.DataTable();

    data.addColumn('string', 'year');
    data.addColumn({
          type: 'string',
          label: 'Tooltip Chart',
          role: 'tooltip',
          'p': {html:true}
        });

    data.addColumn({
        type:'number',
        id:'period-1',
        label: 'Текущий период - <?=$bars["finished"]?>р - <?=$bars["count"]?>шт',
    });

    data.addColumn({
        type:'number',
        id:'period-2',
        label: 'Предыдущий период - <?=$bars_old["finished"]?>р - <?=$bars_old["count"]?>шт',
    });

    let datas = [];// = <?=json_encode($arr)?>;

   // data.addRows(<?=json_encode($arr)?>);
/*
if (document.getElementById("option1").checked) {
data.addRows(<?=json_encode($arr)?>);
}
*/
/*
$('input[name="options"]').change(function(e) { // Select the radio input group

if (document.getElementById("option1").checked) {
    datas = <?=json_encode($arr)?>;
}

  // console.log( $(this).val() );
   // console.log( $('input[name="options"]').val() );

});*/

data.addRows(datas);
//console.log(document.getElementById("option1").checked);

  var range = data.getColumnRange(2);
  var re = /\B(?=(\d{3})+(?!\d))/g;
  var max = Math.ceil(range.max / 100) * 100;

  if (range.min>999) {var min = Math.ceil(range.min / 1000) * 1000;}
  else{var min = 10000;}

  var ticks = [];

i=0;
while(i <= max + min){
 ticks.push({
      v: i,
      f: i.toFixed(0).replace(re, " "),
    });
    i = i + min;
}

    var options = {
        focusTarget: 'category',
        tooltip: {
            isHtml: true,
        },
        curveType: 'function',
        vAxis: {
            maxValue: 100,
            ticks: ticks,
        },
        legend: {
            position: 'none',
        }
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
}

    </script>

<style type="text/css">
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
        font-size: 14px;
    }
</style>



    <div id="curve_chart" style="width: 100%; height: 300px"></div>
