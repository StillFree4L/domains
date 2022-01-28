<?php

require_once(__dir__ . "/functions.php");

if (file_exists(__dir__."/update_log")) {
    unlink(__dir__."/update_log");
}
/*
if (isset($_GET['start_brand_r'])) {
    shell_exec('start cmd.exe /k ' . __dir__ . '/../../php.x64/php.exe -q ' . __dir__ . '/sync_brand_r.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');
} elseif (isset($_GET['start_sync'])) {
    exec(__dir__ . '/../../php.x64/php.exe -q ' . __dir__ . '/sync.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');
} else {
    //cmdexec(__dir__ . '/../../php.x64/php.exe -q ' . __dir__ . '/exec.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');
    
    shell_exec('start cmd.exe /k ' . __dir__ . '/../../php.x64/php.exe -q ' . __dir__ . '/exec.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');
}
*/


if (isset($_GET['start_brand_r'])) {
    shell_exec('start cmd.exe /k ' . __dir__ . '/../modules/php/PHP_7.4/php.exe -q ' . __dir__ . '/sync_brand_r.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');
} elseif (isset($_GET['start_sync'])) {
    exec(__dir__ . '/../modules/php/PHP_7.4/php.exe -q ' . __dir__ . '/sync.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');
} else {
    //cmdexec(__dir__ . '/../modules/php/PHP_7.4/php.exe -q ' . __dir__ . '/exec.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');

    shell_exec('start cmd.exe /k ' . __dir__ . '/../modules/php/PHP_7.4/php.exe -q ' . __dir__ . '/exec.php 0 1000000 '.($_REQUEST['restore'] == '1' ? '1' : '0').' '.($_REQUEST['start_comporator'] == '1' ? '1' : '0').'');
}

