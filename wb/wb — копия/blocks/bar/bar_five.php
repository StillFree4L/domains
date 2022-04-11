<?php

$exceptions = explode(" ",trim('retail_amount storage_cost acceptance_fee other_deductions ppvz_for_pay ppvz_vw ppvz_vw_nds delivery_amount return_amount delivery_rub total_payable quantity all_cost nalog7 pribil marga speed_back ss_one'));

if($ss_dom_lat){
    foreach ($ss_dom_lat as $key => $item) {
        $exceptions[] = $key;
    }
}

$bar = [];
$dates = [];
$columnNames = [];

$max = 0;
$finished = 0;
$days = array( 0=>"Вс","Пн" , "Вт" , "Ср" , "Чт" , "Пт" , "Сб" ,"Вс");

foreach ($data as $datum) {
    $str = strtotime($datum->rr_dt);
    if ($str > $max){
        $dates[] = $str;
    }
    foreach ($tbl_keys as $key=>$item){
        if (!in_array($key,$columnNames) and in_array($key,$exceptions)){
            $columnNames[] = $key;
        }
    }
}

foreach ($dates as $date){
    foreach ($columnNames as $key=>$columnName) {
        foreach ($data as $datum) {
            if (strtotime($datum->rr_dt) == $date){
                $bar['money'][$date][$key] += intval(str_replace(" ", "", (string)$datum->$columnName));
            }
        }
        $bar['finished'][$key] += $bar['money'][$date][$key];
    }
}

$columnArr = [];

foreach ($bar['money'] as $key=>$item){
    $columnArr['date'][] = $key*1000;
}

$hide = (array)json_decode($hideCheckbox);
$finished = [];
$hideShow = false;

$i = 0;
foreach ($columnNames as $key=>$item){

    $hideVisible = $hide[$tbl_keys[$item]] != null ? ($hide[$tbl_keys[$item]] == 'false' ? false : true) : true;

    if($hideVisible == true){$hideShow = $hideVisible;}

    if(!in_array($item,['quantity', 'delivery_amount', 'return_amount'])){

        $columnArr['price'][$i] = ['name' => $tbl_keys[$item], 'type' => $statusCheckbox, 'visible'=> $hideVisible, 'tooltip' => ['valueSuffix' => ' руб']];

        foreach ($bar['money'] as $it) {
            $columnArr['price'][$i]['data'][] = $it[$key];
        }

            $finished['price'] +=$bar['finished'][$key];

    }else{
        $columnArr['quantity'][$i] = ['name' => $tbl_keys[$item], 'yAxis'=>1, 'type' => $statusCheckbox,'visible'=> $hideVisible, 'tooltip' => ['valueSuffix' => ' шт']];

        foreach ($bar['money'] as $it) {
            $columnArr['quantity'][$i]['data'][] = $it[$key];
        }

        $finished['quantity'] += $bar['finished'][$key];

    }
    $i++;
}

$columnArr['full'] = array_merge($columnArr['price'],$columnArr['quantity']);

$lenghtDate = [];
$lenghtDate['Текущий период'] = date('d.m.Y',$columnArr['date'][count($columnArr['date'])-1]/1000).' - '.date('d.m.Y',$columnArr['date'][0]/1000);


$lenghtDate['price'] = 0;
foreach ($columnArr['price'][1]['data'] as $datum) {
    $lenghtDate['price'] += $datum;
}
$lenghtDate['quantity'] = 0;
foreach ($columnArr['quantity'][0]['data'] as $datum) {
    $lenghtDate['quantity'] += $datum;
}

$lenghtDate['price'] = number_format($lenghtDate['price'], 2, ',', ' ');
$lenghtDate['quantity'] = number_format($lenghtDate['quantity'], 2, ',', ' ');

$lenghtDate['all'] = 'Период: '.$lenghtDate['Текущий период'].'&nbsp;&nbsp;&nbsp;'.$columnArr['price'][1]['name'].': '.$lenghtDate['price'].' руб&nbsp;&nbsp;&nbsp;'.$columnArr['quantity'][0]['name'].': '.$lenghtDate['quantity'].' шт';




?>

<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script src="js/export-data.js"></script>
<script src="js/accessibility.js"></script>


<style type="text/css">

    .legendStyle{
        font: 300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif;
    }

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

    .tooltip-body{
        font: 300 11px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif!important;
        z-index: 999999!important;
    }
    .highcharts-colors {
        font: 300 11px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif!important;
    }

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

<script async type="text/javascript">
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

<figure style="margin-left: 10px; margin-right: 10px; z-index: 99999;" class="highcharts-figure">
    <div style="<?= $_GET['type'] == 9 ? 'height: 600px;' : ''?>" id="container"></div>
</figure>

<script async type="text/javascript">

    (function(H) {
        H.wrap(H.Chart.prototype, 'getDataRows', function(proceed, multiLevelHeaders) {
            var rows = proceed.call(this, multiLevelHeaders);
            rows = rows.map(row => {
                if (!isNaN(row.x)) {
                    row[0] = Highcharts.dateFormat('%d.%m.%Y %A', row[0]);
                }
                else{
                    row[0] = 'Дата операций';
                }
                return row;
            });
            return rows;
        });
    }(Highcharts));

    function showHideButton() {
        var series = $('#container').highcharts().series;
        const serieLen = series.length;
        var bools = false;
        let hide = {};

        for (i = 0; i < serieLen; i++) {
            if (!series[i].visible) {
                series[i].setVisible(true, false);
                bools = true;
                hide[series[i].name] = true;
            }
        }
        if (bools === false) {
            for (i = 0; i < serieLen; i++) {
                if (series[i].visible) {
                    series[i].setVisible(false, false);
                }
                if (!series[i].visible) {
                    hide[series[i].name] = series[i].visible;
                }
            }
        }

        $.post("/wb/update.php", {hide: hide});
    }

    function checkboxButton(){
        let cdt;
        let cdtSt;
        var series = $('#container').highcharts().series;

        if (series[0].type === 'line') {
          $('#check')[0].className = "btn btn-sm btn1 btn-warning";
            cdt = 'spline';
        } else {
          $('#check')[0].className = "btn btn-sm btn1 btn-color";
            cdt = 'line';
        }

        $.get("/wb/update.php?status=" + cdt, function (dt) {});
        let i = 0;
        const cdLine = [];
        while (i < series.length) {
            cdLine[i] = {type:cdt};
            i++;
        }
        $('#container').highcharts().update({
            series: cdLine,
    });
    }


    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    Highcharts.chart('container', {
        title:{
           // useHTML:true,
            text:'<?=$lenghtDate["all"]?>',
            align: 'left',
            verticalAlign: 'top',
            y:-10,
            style: {
                font: "300 14px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif",
            }
        },
        subtitle: {
            text: ''
        },
        chart: {
            spacingBottom: 20,
            spacingTop: 30,
            spacingLeft: 20,
            spacingRight: 25,
            zoomType: 'xy',
        },
        credits: {
            enabled: false
        },
        xAxis: [{
            reversed: true,
            className: 'highcharts-colors',
            categories: <?=json_encode($columnArr['date'])?>,
            crosshair: true,
            labels: {
                formatter: function() {
                    return Highcharts.dateFormat('%d.%m',this.value);
                }
            }
        }],
        yAxis: [{ // Primary yAxis
            className: 'highcharts-colors',
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ')+' руб';
                },
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
        }, { // Secondary yAxis
            className: 'highcharts-colors',
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            labels: {
                formatter: function () {
                    return Highcharts.numberFormat(this.value, 0, '', ' ')+' шт';
                },
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            opposite: true
        }],
        tooltip: {
            borderWidth: 0,
            backgroundColor: "rgba(255,255,255,0)",
            shadow: false,
            useHTML: true,
            shared: true,
            valueDecimals: 2,
            headerFormat: '<div class="tooltip-body">{point.key:%d.%m.%Y %A}<br>',
            pointFormat: '<div class="line" style="background-color:{series.color}"></div>{series.name}: ' +
                '{point.y}<br>',
            footerFormat: '</div>',
            style: {
                font: "300 13px/19px 'Open Sans', 'Helvetica Neue', helvetica, arial, verdana, sans-serif",
            }
        },
        legend: {
             enabled: true,
            useHTML: true,
            layout: 'vertical',
            align: 'left',
            x:-10,
            y:30,
            verticalAlign: 'top',
            labelFormatter: function() {
               // var re = /\B(?=(\d{3})+(?!\d))/g;
              //  var total = 0;
             //   for(var i=this.yData.length; i--;) { total += this.yData[i]; };
              //  var te = ' руб';
              //  if(this.name === "Количество продаж" || this.name === "Кол-во возвратов" || this.name === "Кол-во возвратов"){te = ' шт'}
                return '<span class="legendStyle">'+this.name + /*' - ' + total.toFixed(2).replace(re, " ")+te+*/'</span>';
            },
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },
        plotOptions: {
            series: {
                events: {
                    legendItemClick: function(event) {
                        const series = this.chart.series;
                        const serieLen = series.length;
                        let cdt = true;
                        let hide = {};

                        for (let i = 0; i < serieLen; i += 1){
                            if(i===this.index){
                                if(series[i].visible){
                                    cdt = false;
                                    hide[series[i].name] = cdt;
                                }

                            }
                            if(!series[i].visible && i !== this.index){
                                hide[series[i].name] = series[i].visible;
                            }
                        }
                        $.post("/wb/update.php", {hide:hide});
                    },
                }
            }
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
                    y:  -20,
                    align:'right',
                    text: 'Дополнительно ',
                    enabled: true,
                },
            },
        }

    });

    let hideShow = <?=json_encode($hideShow)?>;

    if (hideShow === false){
        var series = $('#container').highcharts().series;
        const serieLen = series.length;
        for (i = 0; i < serieLen; i++) {
            if (!series[i].visible) {
                 series[i].show();
            }
        }
        showHideButton();
    }

</script>
