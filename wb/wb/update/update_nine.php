<?php

if ($_GET['percent']) {
	file_put_contents('json/9.json',json_encode($_GET['percent']));
}elseif ($_GET['status']) {
	if ($_GET['status'] == 'false') {
		$write = 'off';
	}else{
		$write = 'on';
	}
	file_put_contents('json/status.json',json_encode($write));
}
