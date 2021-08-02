<?php

if (@$_GET['hash']!="") {

$hash = MD5('thisiscodewordmazafaka123987555');

if ($_GET['hash'] == $hash) {

file_put_contents('ip.txt',$_SERVER['REMOTE_ADDR']);
file_put_contents('runs.txt',date('d.m.Y H:i:s'));

}

}

?>