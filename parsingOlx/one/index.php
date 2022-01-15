<?php
    try {
        //echo(shell_exec('C:\OpenServer\domains\parsingOlx\phantomjs\bin\phantomjs.exe C:\OpenServer\domains\parsingOlx\phantomjs\bin\script2.js'));
        $cmd = 'cd C:\OpenServer\domains\parsingOlx\phantomjs\bin && phantomjs --ignore-ssl-errors=true --ssl-protocol=any getHtmlSource.js "https://www.olx.kz/d/obyavlenie/prodam-stiralnuyu-mashinu-lg-avtomat-6-kg-IDlGGMi.html#c9051cd4d2;promoted"';
        $html = shell_exec($cmd);
        var_dump($html);
        flush();
    } catch (Exception $exc) {
        echo('Ошибка!');
        echo $exc->getTraceAsString();
    }
?>