<?php

if ($_GET['percent']) {
	file_put_contents('json/9.json',json_encode($_GET['percent']));
}