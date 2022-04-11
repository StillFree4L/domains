<!-- подключение модулей библиотеки -->
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script src="js/export-data.js"></script>
<script src="js/accessibility.js"></script>

<!-- перевод библиотеки -->
<script type="text/javascript">
    Highcharts.setOptions({
        lang: {
            loading: 'Загрузка...',
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
            shortWeekdays: undefined,
            shortMonths: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'],
            exportButtonTitle: "Экспорт",
            printButtonTitle: "Печать",
            rangeSelectorFrom: "С",
            rangeSelectorTo: "По",
            rangeSelectorZoom: "Период",
            downloadPNG: 'Скачать PNG',
            downloadJPEG: 'Скачать JPEG',
            downloadPDF: 'Скачать PDF',
            downloadSVG: 'Скачать SVG',
            downloadCSV: 'Скачать CSV',
            downloadXLS: 'Скачать XLS',
            viewFullscreen: 'Вывести в полноэкранный режим',
            viewData: 'Вывести таблицу данных',
            printChart: 'Напечатать график',
            contextButtonTitle: 'Контекстное меню графика',
            exitFullscreen:'Выход из полноэкранного режима',
            hideData: 'Скрыть таблицу данных',
        },
    });

</script>

<?php
//часы для фильтраций по дням
function range_date($min,$max){
    $arr = [];
    foreach (range($min, $max) as $number) {
        $arr[] = $number;
    }
    return $arr;
}

//формулы подсчета фактической цены
function form_price($value){
    $finished = 0;
    if ($_GET['type'] == 1 and $value->forPay >= 0) {
        $finished = intval(str_replace(" ", "", $value->forPay)) * intval(str_replace(" ", "", $value->quantity));
    }
    elseif (in_array($_GET['type'],[2,10]) and $value->totalPrice >= 0 and $value->isCancel != 1) {
        $finished = intval(str_replace(" ", "", $value->finishedPrice));
    }
    elseif(!in_array($_GET['type'],[1,2,10])) {
        $finished = intval(str_replace(" ", "", $value->totalPrice)) * ((100 - intval(str_replace(" ", "", $value->discountPercent))) / 100);
    }
    if ($finished < 0) {
        $finished *= -1;
    }
    return $finished;
}

//условие для фильтраций по дням или часам
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

//сортировка дней
function sort_date($a_new, $b_new) {

    $a_new = strtotime($a_new);
    $b_new = strtotime($b_new);

    return $a_new - $b_new;

}
//выборка периода
function dates($bool,$data1,$data2,$cdt){
    if ($bool) {
        $dates['column1'] = range_date(1,25);
        $dates['column2'] = range_date(1,25);

    }
    else{
        $dates = [];
        $dates['column1'] = [];
        $dates['column2'] = [];
        $count = 0;
        $date1 = '';
        $date2 = '';
        if($data1 and count($data1) > $count){
            $count = count($data1);
        }
        if($data2 and count($data2) > $count){
            $count = count($data2);
        }
        for ($i=0;$i<$count;$i++){
            if($data1[$i]->$cdt and $data1[$i]->$cdt != null){
                $date1 = date('d.m.Y', strtotime($data1[$i]->$cdt));
            }
            if($data2[$i]->$cdt and $data2[$i]->$cdt != null){
                $date2 = date('d.m.Y', strtotime($data2[$i]->$cdt));
            }
            if($date1 and !in_array($date1,$dates['column1'])){
                $dates['column1'][] = $date1;
            }
            if($date2 and !in_array($date2,$dates['column2'])){
                $dates['column2'][] = $date2;
            }
        }

        usort($dates['column1'], "sort_date");
        usort($dates['column2'], "sort_date");
    }

    for ($i=0;$i<count($dates['column1']);$i++){
        $dates['dataFinal'][] = $bool ? $dates['column1'][$i] : strtotime($dates['column1'][$i])*1000;
    }

    return $dates;
}

//выборка цен и кол-во
function series($bool,$data1,$data2,$cdtDate,$cdtTotal,$dates,$cdtQuan){
    $series = [];
    $formatDate = $bool ? 'H' : 'd.m.Y';

        foreach ($dates['column1'] as $keyDate => $itemDate) {
            if ($data1){

                foreach ($data1 as $item1) {
                    $dat1 = $bool ? intval(date($formatDate, strtotime($item1->$cdtDate))) : date($formatDate, strtotime($item1->$cdtDate));

                    if ($dat1 == $itemDate) {
                      $tot1 = intval(str_replace(" ", "", $item1->$cdtTotal));
                      if ($tot1 < 0) {
                          $tot1 *= -1;
                      }
                        $series['dataTotal1'][$keyDate] += $tot1;
                        $series['dataQuan1'][$keyDate] += intval(str_replace(" ", "", $item1->$cdtQuan));
                        $series['sum']['dataQuan1'] += intval($item1->$cdtQuan);
                    }

                }
            }
            if($data2){
                foreach ($data2 as $item2) {
                    $itemDate2 = $bool ? $itemDate : $dates['column2'][$keyDate];
                    $dat2 = $bool ? intval(date($formatDate, strtotime($item2->$cdtDate))) : date($formatDate, strtotime($item2->$cdtDate));
                    if ($dat2 == $itemDate2) {
                      $tot2 = intval(str_replace(" ", "", $item2->$cdtTotal));
                      if ($tot2 < 0) {
                          $tot2 *= -1;
                      }
                        $series['dataTotal2'][$keyDate] += $tot2;
                        $series['dataQuan2'][$keyDate] += intval($item2->$cdtQuan);
                        $series['sum']['dataQuan2'] += intval($item2->$cdtQuan);
                    }
                }
            }
            if($bool){
                if (!$series['dataTotal1'][$keyDate]){
                    $series['dataTotal1'][$keyDate] = 0;
                }
                if (!$series['dataTotal2'][$keyDate]){
                    $series['dataTotal2'][$keyDate] = 0;
                }
                if (!$series['dataQuan1'][$keyDate]){
                    $series['dataQuan1'][$keyDate] = 0;
                }
                if (!$series['dataQuan2'][$keyDate]){
                    $series['dataQuan2'][$keyDate] = 0;
                }
            }
        }

    return $series;
}

//по каким данным считать
$cdtTotal = 'finishedPrice';
$cdtQuan = 'quantity';
$cdtDate = 'date';

$bool = if_date();
$dates = dates($bool,$data,$data_bar,$cdtDate);
$series = series($bool,$data,$data_bar,$cdtDate,$cdtTotal,$dates,$cdtQuan);

//название линий
$names = ['dataTotal1'=>'Текущий период','dataTotal2'=>'Предыдущий период','dataQuan1'=>'Текущий период','dataQuan2'=>'Предыдущий период'];
//валюта
$valueSuffix = ['dataTotal1'=>' руб','dataTotal2'=>' руб','dataQuan1'=>' шт','dataQuan2'=>' шт'];
//цвета линий
$colors = ['dataTotal1'=>'#7cb5ec','dataTotal2'=>'#434348','dataQuan1'=>'#7cb5ec','dataQuan2'=>'#434348'];

//настройка данных для графика
if($series){
    $i = 0;
    foreach ($series as $keySeries => $itemSeries) {
        $columnArr[$keySeries][$i] = ['id' => (string)$i, 'name' => $names[$keySeries], 'color' => $colors[$keySeries], 'type' => $statusCheckbox, 'visible' => true, 'tooltip' => ['valueSuffix' => $valueSuffix[$keySeries]]];
        //echo '<pre>'; var_dump($itemSeries);
        foreach ($itemSeries as $k => $itemSery) {
            //echo '<pre>'; var_dump($k);
            $columnArr[$keySeries][$i]['data'][] = $itemSery;
            $dataArr[$keySeries][] = $itemSery;
           // $dataArr['sum'][$keySeries] += form_price($itemSery);//$itemSery;
        }
        $i++;
    }
}

//валидация по валютам
if($columnArr['dataTotal1'] and $columnArr['dataTotal2']){
    $columnArr['full'] = array_merge($columnArr['dataTotal1'],$columnArr['dataTotal2']);
}elseif($columnArr['dataTotal1']){
    $columnArr['full'] = $columnArr['dataTotal1'];
}elseif($columnArr['dataQuan1'] and $columnArr['dataQuan2']){
    $columnArr['full'] = array_merge($columnArr['dataQuan1'],$columnArr['dataQuan2']);
}elseif($columnArr['dataQuan1']){
    $columnArr['full'] = $columnArr['dataQuan1'];
}



//валидация по периодам
$dataArr['Текущий период'][] = $PRICE_SUM;//$series['sum']['dataTotal1'];
$dataArr['Текущий период'][] = $CNT_SUM;
$dataArr['Предыдущий период'][] = $PRICE_SUM_BAR;//$series['sum']['dataTotal2'];
$dataArr['Предыдущий период'][] = $CNT_SUM_BAR;



//даты предыдущего периода
if(!$bool){
    //echo '<pre>';var_dump($dates['column2']);
    $lenghtDate = [
            'Текущий период'=>[$dates['column1'][0].' - '.$dates['column1'][count($dates['column1'])-1]],
            'Предыдущий период'=>[$dates['column2'][0].' - '.$dates['column2'][count($dates['column2'])-1]],
    ];
    foreach ($dates['column2'] as $key => $date) {
        $dates['column2'][$dates['column1'][$key]] = strtotime($date) * 1000;
    }
}else  if($_GET['dt1'] == $_GET['dt2']){

  $lenghtDate = [
          'Текущий период'=>[$_GET['dt1'] ? date('d.m.Y',strtotime($_GET['dt1'])) : date('d.m.Y',strtotime($_GET['dt']))],
          'Предыдущий период'=>[$_GET['dt1'] ?  date("d.m.Y", strtotime("-1 DAY",strtotime($_GET['dt1']))) :  date("d.m.Y", strtotime("-1 DAY",strtotime($_GET['dt'])))],
     ];
}else{

    $lenghtDate = [
            'Текущий период'=>[$_GET['dt1'] ? date('d.m.Y',strtotime($_GET['dt1'])).' - '.date('d.m.Y',strtotime($_GET['dt2'])) : date('d.m.Y',strtotime($_GET['dt']))],
            'Предыдущий период'=>[$_GET['dt1'] ?  date("d.m.Y", strtotime("-1 DAY",strtotime($_GET['dt2']))).' - '.$_GET['dt2'] :  date("d.m.Y", strtotime("-1 DAY",strtotime($_GET['dt'])))],
       ];
 }

?>


<?php if ($dataArr['dataTotal1'] and count($dates['dataFinal']) and $series and $data): ?>
<style type="text/css">
/*стили легенды*/
    .legendStyle{
        font: 300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif;
    }
/*стили подсказки*/
    .highcharts-tooltip>span {
        background-color: #fff;
        border: 1px solid #172F8F;
        border-radius: 5px;
        opacity: 1;
        z-index: 99999;
        padding: .8em;
        left: 0!important;
        top: 0!important;
    }
/*стили тела подсказки*/
    .tooltip-body{
        font: 300 11px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif!important;
        z-index: 999999!important;
    }
/*стили оси x*/
    .highcharts-colors {
        font: 300 11px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif!important;
    }
/*круги цвета в подсказке*/
    .line{
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-left: 0px;
        margin-right: 5px;
        margin-top: 4px;
        display: inline-block;
    }
</style>

<!-- контейнер графика -->
<figure style="margin-left: 10px; margin-right: 10px; z-index: 99999;" class="highcharts-figure">
    <div style="<?= $_GET['type'] == 9 ? 'height: 600px;' : ''?>" id="container"></div>
</figure>

<script type="text/javascript">
//валидация валют
    valut = '<?=$optionCheckbox?>';
//валидация даты для csv таблиц
   <?= $bool ? "" : "(function(H) {
        H.wrap(H.Chart.prototype, 'getDataRows', function(proceed, multiLevelHeaders) {

            var rows = proceed.call(this, multiLevelHeaders);
            //console.log(rows);

            rows = rows.map(row => {

                if (!isNaN(row.x)) {
                    row[0] = Highcharts.dateFormat('%d.%m.%Y %A', row[0]);
                }
                else{
                    row[0] = 'Дата';
                }
                return row;
            });

            return rows;
        });
    }(Highcharts));" ?>
//кнопка сглаживание
    function checkboxButton(){
        let cdt;
        var series = $('#container').highcharts().series;

        if (series[0].type === 'line') {
          $('#check')[0].className = "btn btn-sm btn1 btn-warning";
            cdt = 'spline';
        } else {
          $('#check')[0].className = "btn btn-sm btn1 btn-color";
            cdt = 'line';
        }
        $.get("/wb/update.php?status=" + cdt, function (dt) {
        });
        let i = 0;
        while (i < series.length) {
            series[i].update({
                type: cdt
            });
            i++;
        }
    }

//кнопка рубли
    function totalButton(){
      $('#quan')[0].className = "btn btn-sm btn1 btn-color";
      $('#total')[0].className = "btn btn-sm btn1 btn-warning";
        var series = $('#container').highcharts().series;

        series[0].setData(<?=json_encode($dataArr['dataTotal1'])?>);
        <?=is_array($dataArr['dataTotal2']) ? 'series[1].setData('.json_encode($dataArr['dataTotal2']).');' : ''?>
        valut = ' руб';
        $.get("/wb/update.php?option=total", function (dt){});
    }
//кнопка кол-во
    function quanButton(){
      $('#quan')[0].className = "btn btn-sm btn1 btn-warning";
      $('#total')[0].className = "btn btn-sm btn1 btn-color";
        var series = $('#container').highcharts().series;
        series[0].setData(<?=json_encode($dataArr['dataQuan1'])?>);
        <?=is_array($dataArr['dataQuan2']) ? 'series[1].setData('.json_encode($dataArr['dataQuan2']).');' : ''?>
        valut = ' шт';
        $.get("/wb/update.php?option=quan", function (dt){});
    }
//глобальные настройки
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
//настройка библиотеки
    Highcharts.chart('container', {
        title:{
            text: '',
        },
        subtitle: {
            text: ''
        },
        chart: {
            spacingBottom: 20,
            spacingTop: 10,
            spacingLeft: 20,
            spacingRight: 25,
            zoomType: 'xy',
            events: {
                load: function(event) {
                    var re = /\B(?=(\d{3})+(?!\d))/g;
                    let col = <?=json_encode($dataArr)?>;
                    let differentSum = col['Текущий период'][0] - col['Предыдущий период'][0];
                    let differentQuan = col['Текущий период'][1] - col['Предыдущий период'][1];
                    let color1 = 'red';
                    let color2 = 'red';
                    if(differentSum >= 0){color1 = 'green';}if(differentQuan >= 0){color2 = 'green';}
                    let all = $('.highcharts-legend-item').last()[0];
                    all.style.display = 'inline-block';
                    all.style.width = '1000px';
                    all.querySelector('span').style.position = 'relative';
                    all.querySelector('span').style.float = 'left';
                  //  console.log(all.querySelector('span'));
                  //  $('.highcharts-legend-item span').last()[0].style.position = 'relative'
                    $('.highcharts-legend-item').last().append('<div style="float: left; margin-top:3px; margin-left:40px; width:300px;">Разница: &nbsp;<span style="color: '+color1+'">' + differentSum.toFixed(2).replace(re, " ") + '</span> руб<span style="color: '+color2+'">' + ' &nbsp;' + differentQuan + '</span> шт</div>');

                },
            }
        },
        credits: {
            enabled: false
        },
        xAxis: [{
            className: 'highcharts-colors',
            categories: <?=json_encode($dates['dataFinal'])?>,
            crosshair: true,
            labels: {
                formatter: function() {
                    return <?=$bool ? "this.value" : "Highcharts.dateFormat('%d.%m',new Date(this.value))";?>;
                }
            }
        }],
        yAxis: [{ // Primary yAxis
            className: 'highcharts-colors',
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ');
                },
                 format: '{value} '+valut,
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }],
        tooltip: {
            borderWidth: 0,
            backgroundColor: "rgba(255,255,255,0)",
            shadow: false,
            useHTML: true,
            shared: true,
            formatter: function() {
                var re = /\B(?=(\d{3})+(?!\d))/g;
                let col2 = <?=json_encode($dates['column2'])?>;
                let bool = <?=json_encode($bool)?>;
                let s = '<div class="tooltip-body">';
                if(bool){
                    if(this.x < 10){
                        s += '0';
                    }
                    s += this.x;
                }
                else{
                    s += Highcharts.dateFormat('%d.%m.%Y %A',this.x)+' - '+Highcharts.dateFormat('%d.%m.%Y %A',col2[Highcharts.dateFormat('%d.%m.%Y',this.x)]);
                }
                s += '<br>';
                $.each(this.points, function(i, point) {
                    s += '<div class="line" style="background-color:'+point.series.color+'"></div>'+point.series.name+': '+point.y.toFixed(2).replace(re, " ")+valut+'<br>'
                });
                let total = 0;
                if(this.points[1]){
                    total = this.points[0].point.y - this.points[1].point.y;
                }else{
                    total = this.points[0].point.y;
                }
                let color = 'red';
                if(total >= 0) {color = 'green';}
                s += 'Разница: <span style="color: '+color+'">'+ total.toFixed(2).replace(re, " ")+'</span>' + valut +'</div>';
                return s;
            },
            style: {
                font: "300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif",
            }
        },
        legend: {
            enabled: true,
            useHTML: true,
            align: 'left',
            verticalAlign: 'top',
            labelFormatter: function() {
                var re = /\B(?=(\d{3})+(?!\d))/g;
                let lengthDate = <?=json_encode($lenghtDate)?>;
                let sum = <?=json_encode($dataArr)?>;
                return '<span class="legendStyle">'+this.name+': &nbsp; '+lengthDate[this.name][0]+'  &nbsp; '+sum[this.name][0].toFixed(2).replace(re, " ")+' руб &nbsp; '+sum[this.name][1]+' шт'+'</span>';
            },
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },
        series: <?=json_encode($columnArr['full'])?>,
        navigation: {
            buttonOptions: {
                theme: {
                  states: {
                    hover: {
                      fill: '#fff',
                    },
                    select: {
                      fill: "#efefef",
                    },
                  },
                    style: {
                        fontWeight: 'normal',
                        font: "300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif",
                    }
                }
            }
        },

        exporting: {
            buttons: {
                contextButton: {
                    y:    0,
                    align: 'right',
                    text: 'Дополнительно ',
                    enabled: true,
                },
            },
        }

    });

    if(valut==' шт'){
      quanButton();
    }else{
      totalButton();
    }

</script>

<?php endif;?>
