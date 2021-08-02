<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/protected/vendors/autoload.php');
require(__DIR__ . '/protected/vendors/yiisoft/yii2/Yii.php');

define("IS_GLOBAL", $_SERVER['HTTP_HOST'] == "poll.marikz.com" ? false : true);

$config = require(__DIR__ . '/protected/config/poll.php');

(new yii\web\Application($config))->run();
