<?php

date_default_timezone_set('Asia/Dhaka');

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';

$config=dirname(__FILE__).'/protected/config/main.php';
include(dirname(__FILE__).'/config/cms_config.php');

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

defined('IS_ADMIN') or define('IS_ADMIN',false);


require_once($yii);
Yii::createWebApplication($config)->run();
