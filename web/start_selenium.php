<?php

$port_selenium = '4444';
$url_selenium = 'http://localhost:' . $port_selenium;

set_time_limit(5);

function isStartSelenium($url)
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json;charset=UTF-8',
        'Accept: application/json',
        ));

    curl_setopt($curl, /* CURLOPT_TIMEOUT_MS */ 155, 130000);
    curl_setopt($curl, /* CURLOPT_CONNECTTIMEOUT_MS */ 156, 120000);

    $raw_results = trim(curl_exec($curl));

    $results = json_decode($raw_results, true);

    if (!($results === null && json_last_error() !== JSON_ERROR_NONE) && is_array($results)) {
        return true;
    }

    return false;
}

function cmdexec($command)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        //windows
        pclose(popen("start /B " . $command . " 1> " . __dir__ . "/update_log 2>&1 &",
            "r"));
    } else {
        //linux
        shell_exec($command . " > /dev/null 2>&1 &");
    }
}

if (isset($_SERVER) && isset($_SERVER['argv']) && isset($_SERVER['argv'][1]) &&
    $_SERVER['argv'][1] == 'start') {
    if (!isStartSelenium($url_selenium)) {
        shell_exec('start cmd.exe /k ' . dirname(__dir__ ) . '\start.selenium.server.cmd');
    }
} else {
    cmdexec(dirname(__dir__ ) . '/../modules/php/PHP_7.4/php.exe -q ' . __dir__ .
        '/start_selenium.php start');
}
