<?php

require_once('blocks/func_key.php');

$result = mysqli_query($link, 'SELECT count(id)>0 FROM `wb_data` WHERE userId='.$userId.' and type='.$type);

?>
