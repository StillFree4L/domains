<?php

$ip = file_get_contents('ip.txt');

if ($_SERVER['REMOTE_ADDR'] == $ip) {
$ip = "10.10.0.1/femida";
}

?>

<style>

iframe {

width:100%;
height:100%;
border:0px


}

</style>

<iframe src='http://<?=@$ip?>'>
</iframe>
