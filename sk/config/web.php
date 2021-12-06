<?php
use lav45\activityLogger\LogMessageDTO;
use \yii\web\Request;
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'language'=>'ru-RU',
    'charset'=>'utf-8',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules'=>[
        'admin' => [
            'class'=>'app\modules\admin\Module'
        ],
        'yii2images' => [
            'class' => 'rico\yii2images\Module',
            'imagesStorePath' => 'upload/store', //path to origin images
            'imagesCachePath' => 'upload/cache', //path to resized copies
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
            'placeHolderPath' => '@webroot/upload/store/no.png'
        ],
        'logger' => [
            'class' => \lav45\activityLogger\modules\Module::class,
            'entityMap' => [
                'master' => 'app\models\Master',
                'repairs' => 'app\models\Repairs',
                'results' => 'app\models\Results',
                'user' => 'app\models\User',
                'services' => 'app\models\Services',
                'signup-form' => 'app\models\SignupForm',
                'login-form' => 'app\models\LoginForm',
            ],
        ],
    ],
    'components' => [
        'activityLogger' => [
            'class' => \lav45\activityLogger\Manager::class,
            //'enabled' => YII_ENV_PROD,
            'user' => 'user',
            'userNameAttribute' => 'username',
            'storage' => 'activityLoggerStorage',
        ],
        'activityLoggerStorage' => [
            'class' => \lav45\activityLogger\DbStorage::class,
            'tableName' => '{{%activity_log}}',
            'db' => 'db',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'HiLEapjiYBP5Y9FG7faR3cCiqy5xytwi',
            'baseUrl' => '',
        ],
        'authManager' => [
            'cache' => 'cache',
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'Statuskaraganda@Gmail.Com',
                'password' => '12345677654321',
                'port' => '587',
                'encryption' => 'tls',
                'streamOptions' => [ 'ssl' => [ 'allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false, ], ]
            ],
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'admin/<controller:\w+>/<action:\w+>/<id:\d+>' => 'admin/<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                'admin/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ],
        ],
    ],
    'on beforeAction' => function(){
        if(!Yii::$app->user->isGuest){
        $message = Yii::createObject([
            'class' => LogMessageDTO::class,
            'entityName' => Yii::$app->user->identity->role->name,
            'entityId' => Yii::$app->user->identity->role->type,
            'action' => Yii::$app->controller->route,
            'data' => ['Посещение'],
        ]);
        Yii::$app->activityLogger->log($message);
      }
    },
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
