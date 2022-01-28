<?php

set_time_limit(0);

require_once (dirname(__file__) . "/config.inc.php");

echo '*** ' . date("Y-m-d H:i:s", time()) . '<hr>';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);

include_once 'simple_html_dom.php';

if (!function_exists('cmdexec')) {
    if (file_exists(__dir__ . "/update_log")) {
        unlink(__dir__ . "/update_log");
    }

    function cmdexec($command)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            //windows
            pclose(popen($command . " 1> " . __dir__ . "/update_log 2>&1 &",
                "r"));
        } else {
            //linux
            shell_exec($command . " > /dev/null 2>&1 &");
        }
    }
}

$conf = array_merge((array )get_config($db, $db_table_conf), (array )
    get_config_id($db, $db_table_conf_list, 'parser_id', $db_parser_id));

$link = mysqli_connect($db_host, $db_user, $db_pasw, $db_name) or die('Не удалось соединиться: ' .
    mysqli_error());
mysqli_set_charset($link, "utf8");

$parser_id = 'trademarkia.com';
$parse_count_all = 0;
$description = '';
$sql = "INSERT INTO `$db_table_parser_stat` (`parser_id`, `action`, `description`, `date_start`) VALUES('$parser_id', 'start/end', '$description', NOW())";
mysqli_query($link, $sql);
$db_table_parser_stat_id = mysqli_insert_id($link);

$sql = "SELECT * FROM `$db_table`";
$res = mysqli_query($link, $sql);
while ($row = mysqli_fetch_array($res, 3)) {
    $arr = array();
    $json = json_decode($row["info"], true);
    $brand = $json["Brand"];

    $arr["Brand_R"] = 'available';
    $arr["URL"] = 'https://trademarks.justia.com/search?q=' . str_replace(" ", "+",
        $brand);
    $ex = explode(" ", $brand);
    $ok = false;
    echo '<b>[' . $row["id"] . ']</b>';
    while ($ok == false) {
        for ($i = count($ex); $i >= 1; $i = ($i - 1)) {
            $find = '';
            for ($j = 0; $j < $i; $j++) {
                if ($find == '') {
                    $find = $ex[$j];
                } else {
                    $find .= '+' . $ex[$j];
                }
            }
            if ($find == str_replace(" ", "+", $brand)) {

                echo $find . '<br>';
                $html = getHTMLbrand($find);
                if (strpos($html, "Your search for trademarks did not match any records") == false) {
                    $arr = Pars($html, $find, 'https://trademarks.justia.com/search?q=' . $find);
                    $ok = 'parse';
                    break;
                }
            }
            if (strlen($find) < 15) {
                $ok = 'mini';
            } else {
                echo $find . '<br>';
                $html = getHTMLbrand($find);
                if (strpos($html, "Your search for trademarks did not match any records") == false) {
                    $arr = Pars($html, $find, 'https://trademarks.justia.com/search?q=' . $find);
                    $ok = 'parse';
                    break;
                }
            }
        }
        $ok = true;
        $parse_count_all++;
        print_r($arr);
        echo '<hr>';
        $json["Brand_R"] = $arr["Brand_R"];
        $json["trademarks.justia_url"] = $arr["URL"];
        $json["trademarks.justia_query"] = $arr["Query"];
        $json["trademarks.justia_count"] = $arr["Brand_count"];
        $json["trademarks.justia_category"] = $arr["Brand_category"];
        $json["trademarks.justia_owner"] = $arr["Brand_owner"];
        $sql = "UPDATE `$db_table` SET `info`='" . addslashes(json_encode($json)) .
            "' WHERE `id`=" . $row["id"];
        echo '[SQL]';
        mysqli_query($link, $sql);
        if ($conf['save_parser_info'] == 1 and $arr["Brand_R"] == 'brand') {
            DopUpd($brand, $arr);
        }
        if ($conf['del_brand_famous'] == 1 and $arr["Brand_R"] == 'brand') {
            $sql = "DELETE FROM `$db_table` WHERE `id`=" . $row["id"];
            echo $sql . '<br>';
            mysqli_query($link, $sql);
        }
        break;
    }


}

$description = array();
$description['parse_count_all'] = (int)$parse_count_all;
$description = json_encode($description);
$description = mysqli_real_escape_string($db, $description);
$sql = "UPDATE `$db_table_parser_stat` SET `description` = '$description', `date_end` = NOW() WHERE `id` = '$db_table_parser_stat_id'";

mysqli_query($link, $sql);

echo '*** ' . date("Y-m-d H:i:s", time()) . '<hr>';


function DopUpd($brand, $arr)
{
    global $link;

    $sql = "SELECT * FROM `brand_reestr` WHERE `Brand_name`='" . $brand . "'";
    $res = mysqli_query($link, $sql);
    if (mysqli_num_rows($res) == 0) {
        $sql = "INSERT INTO `brand_reestr` (`Brand_name`, `Brand_owner`, `Serial_number`, `Brand_date`, `Brand_update`, `Count`, `Last_count`) VALUES ('" .
            $brand . "', '" . $arr["Brand_owner"] . "', '" . $arr["Serial"] . "', '" . date("Y-m-d",
            time()) . "', '" . date("Y-m-d", time()) . "', 0, 0);";
        mysqli_query($link, $sql);
    } else {
        $sql = "SELECT * FROM `brand_reestr` WHERE `Brand_name`='" . $brand . "'";
        $res = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($res, 3);

        $sql = "UPDATE `brand_reestr` SET `Brand_update`='" . date("Y-m-d", time()) .
            "', `Last_count`=" . $arr["Brand_count"] . ", `Count`=" . ($row["Count"] + 1) .
            " WHERE `id`=" . $row["id"] . ";";
        mysqli_query($link, $sql);
        echo $sql . '<br>';
    }


}


function Pars($html, $w, $url)
{
    //echo $html.'<hr>';
    $arr["Brand_R"] = 'available';

    $html = str_get_html($html);

    $pp = $html->find('p');
    for ($i = 0; $i < count($pp); $i++) {
        if (strpos($pp[$i]->plaintext, "seconds") !== false) {
            $ex = explode(" ", $pp[$i]->plaintext);
            $arr["Brand_count"] = str_replace(",", "", $ex[5]);
        }
        if ($pp[$i]->class == 'has-no-margin') {
            $ex = explode("<br>", $pp[$i]->innertext);
            if (strpos($ex[0], 'Filed') == false) {
                $arr["Brand_category"] = $ex[0];
            } else {
                $arr["Brand_category"] = $ex[1];
            }
            $a = $pp[$i]->find('a');
            $arr["Brand_owner"] = $a[0]->plaintext;
            $arr["Serial"] = $a[1]->plaintext;
            $arr["Brand_R"] = 'brand';
            $arr["URL"] = $url;
            $arr["Query"] = $w;
            break;
        }
    }


    return ($arr);

}

function getHTMLbrand($q)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://trademarks.justia.com/search?q=' . $q);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: trademarks.justia.com';
    $headers[] = 'Cache-Control: max-age=0';
    $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"95\", \"Chromium\";v=\"95\", \";Not A Brand\";v=\"99\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    $headers[] = 'Sec-Fetch-Site: none';
    $headers[] = 'Sec-Fetch-Mode: navigate';
    $headers[] = 'Sec-Fetch-User: ?1';
    $headers[] = 'Sec-Fetch-Dest: document';
    $headers[] = 'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7';
    //$headers[] = 'Cookie: referrer=https://trademarks.justia.com/; SESSJUSTIA=k5tqddipg0jtm8pfuudm7k8as8; jlisting_location=a%3A2%3A%7Bs%3A13%3A%22practice_area%22%3Bs%3A10%3A%22trademarks%22%3Bs%3A16%3A%22practice_area_id%22%3Bs%3A3%3A%22271%22%3B%7D; accounts_session=epKQav6N9kuPSe8zX0bmAdnilOmYRdHydxkwVMzD; cornell_lead=false; AMP_TOKEN=%24NOT_FOUND; _gid=GA1.2.1154902911.1636446396; _gat_UA-16120291-1=1; _ga=GA1.1.521427706.1636446395; XSRF-TOKEN=eyJpdiI6IlFkOWs3M3pCXC9oYVFZeWhTR0c0a0Z3PT0iLCJ2YWx1ZSI6InVOZUVGTSt5akMwcE82ZldYbDllY0JzWG5yT3FEcFlqdGpTVG4zejRQTFlxbGxWZEFBXC9mVFN6WjFsaFpJOUNIIiwibWFjIjoiM2Q3ZTA2M2FiM2UzZGQ3OGI1MTFhNjlmMjM0OWVmNjU1NzFiYmVjNjIwOGZkMzg5NjdkYmU2MTgzOWNhMzJhZSJ9; _ga_XMKHC12ZFL=GS1.1.1636446394.1.1.1636446405.0; gdpr=1';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);

    return $result;

}

shell_exec(__dir__ . '/a.vbs');

if ($conf['brandr_trademarkia_cmd'] == '1') {
    shell_exec('start cmd.exe /k ' . dirname(__dir__ ) . '/php.x64/php.exe -q ' . __dir__ . '/parseralibaba/sync_reestr_brand_r.php');
}
