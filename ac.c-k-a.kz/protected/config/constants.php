<?php

define('SECRET_WORD','mysecretword');
define('POLL_SECRET_WORD', "fhtpawrRFn349");

#define('PG_DATABASE', 'v_4762_db_academy');
#define('PG_HOST','localhost');
#define('PG_PORT','5432');
#define('PG_USER', 'v_4762_db_admin');
#define('PG_PASSWORD', 'newpassword123');

define('PG_DATABASE', 'v_4762_2');
define('PG_HOST','localhost');
define('PG_PORT','3306');
define('PG_USER', 'v-476_hoster');
define('PG_PASSWORD', 'Yai7e5_5');
define('FILES_ROOT', '/var/www/vhosts/v-4762.webspace/www/files.c-k-a.kz/');
define('FILES_HOST', 'http://files.c-k-a.kz/');
define("POLL_URL", 'http://poll.c-k-a.kz/');

define("LOGGING" , (isset($_GET['log']) AND $_GET['log']=='t') ? true : false);

if (isset($_GET['ra'])) {
    define("RELOAD_ASSETS", true);
} else {
    define("RELOAD_ASSETS", true);
}
define("BLANK_VIDEO", '2Z4m4lnjxkY');

define("BPATH", dirname(__FILE__)."/protected/");
define("YPATH", BPATH."yii/");



?>
