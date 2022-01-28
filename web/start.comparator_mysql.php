<?php
session_start();

header("Content-Type: text/html; charset=UTF-8");
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

ini_set('log_errors', 'on');
ini_set('error_log', dirname(__file__) . '/error_log.txt');
ini_set('max_execution_time', '864000');
ini_set('memory_limit', '4048M');
ini_set("pcre.backtrack_limit", 1000000000);

ignore_user_abort(1);
error_reporting(1);

require_once (dirname(__file__) . "/config.inc.php");
require_once (dirname(__file__) . "/lib/rus-to-lat.inc.php");

$contents_dir = dirname(__file__) . '/contents/';

$parser_id_main = 'brand';
$parser_id = 'start.comparator';
$login = $parser_id;
// -----------------------------------------------------------------------
$start_time = time();

echo "<pre>";
//echo "<font size='1'>";

if (!file_exists(dirname(__file__) . '/data'))
    mkdir(dirname(__file__) . '/data');
if (!file_exists(dirname(__file__) . '/data/' . $login . '.' . 'cookie.txt'))
    bin_write(dirname(__file__) . '/data/' . $login . '.' . 'cookie.txt', '', 'w');
//bin_write(dirname(__FILE__).'/data/'.$login.'.'.'cookie.txt', '', 'w');

// ------------------------------------------------------------------------
$time_out = 0 * 60;
$run_flag = dirname(__file__) . "/data/" . 'events.flag';
if (file_exists($run_flag)) {
    $last_time = time() - (int)trim(file_get_contents($run_flag));
    if ($last_time < $time_out) {
        log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
            "Запуск запрещен! Последнее событие было $last_time/$time_out сек назад, возможно парсер еще работает, попробуйте чуть позже...",
            'a', 'red');
        echo "<br /><hr />";
        echo '<div><input type="button" name="index" id="index" value="index" align="center"  onclick="location.href=\'admin.php\'" /></div>';
        exit();
    } else {
        log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
            "Поехали! Последнее событие парсера было $last_time сек назад...");
    }
}
bin_write($run_flag, time());
// ------------------------------------------------------------------------
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', 'start parse...',
    'w');
if ($_SERVER[SERVER_PORT] != '8080') {
    log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', 'Запуск CMD!', 'a');
}

log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', "limits: $test_limit/$test_limit_url",
    'a', '#D56A00');

log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
    "---------------------------------------------------");
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
    "max_execution_time: " . ini_get("max_execution_time") . " sec");
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', "memory_limit: " .
    ini_get("memory_limit") . "");
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', "error_log: " .
    ini_get("error_log") . "");
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
    "---------------------------------------------------");
// -----------------------------------------------------------------------
flush();
flush();
//print_r($_SERVER);
// -----------------------------------------------------------------------
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
    ' - START comparator!');
// -----------------------------------------------------------------------
$description = '';
$sql = "INSERT INTO `$db_table_parser_stat` (`parser_id`, `action`, `description`, `date_start`) VALUES('comparator', 'start/end', '$description', NOW())";
db_sql_query($db, $sql);
$db_table_parser_stat_id = mysqli_insert_id($db);
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', "log ID: " . $db_table_parser_stat_id .
    "");
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
    "---------------------------------------------------");
// ------------------------------------------------------------------------
exec("START /WAIT " . dirname(__file__) . "/lib/comparator_mysql/run.bat");
// -----------------------------------------------------------------------
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log',
    ' - END comparator!');
// ---------------------------------------------------------------------------
$description = array();
$description['parse_count_all'] = '';
$description = json_encode($description);
$description = mysqli_real_escape_string($db, $description);
$sql = "UPDATE `$db_table_parser_stat` SET `description` = '$description', `date_end` = NOW() WHERE `id` = '$db_table_parser_stat_id'";
db_sql_query($db, $sql);
// ---------------------------------------------------------------------------
$end_time = time();
$time_diff_sec = ($end_time - $start_time);
$time_sleep_sec = (int)$long_rand_all;

$time_diff = ($time_diff_sec) / 60;
$time_sleep = ($time_sleep_sec) / 60;

$time_diff = number_format($time_diff, 0, '.', '');
$time_sleep = number_format($time_sleep, 0, '.', '');

echo "<br />";
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', "total time: $time_diff min ($time_diff_sec) / sleep: $time_sleep min ($time_sleep_sec)");
log_write_echo(dirname(__file__) . '/' . $parser_id . '.log', 'end...');
echo "<br /><hr />";
echo "<embed src='mp3/Hillside.mp3' hidden=true></embed>";
flush();
flush();
echo '<div><input type="button" name="index" id="index" value="index" align="center"  onclick="location.href=\'admin.php\'" /></div>';

echo '<script type="text/javascript">self.scrollBy(0,document.body.scrollHeight);</script><br/>';
flush();
exit();

?>
