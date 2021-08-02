<?php

require(__DIR__ . '/constants.php');

$config = [
    'id' => 'AdManager',
    'basePath' => dirname(__DIR__)."/poll",
    'aliases' => [
        '@glob' =>  dirname(__DIR__)."/global",
    ],
    'bootstrap' => ['log', 'urlManager'],
    'defaultRoute' => 'main/index',
    'language' => "ru-RU",
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => POLL_SECRET_WORD,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // required for POST input via `php://input`
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'glob\models\Users',
            'enableSession' => false,
            'loginUrl' => null
        ],
        'authManager' => [
            'class' => 'glob\components\PhpManager',
        ],
        'urlManager' => [
            "class"=>'glob\components\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName'=>false,
            'rules' => [
                '<controller:\w+>'=>'<controller>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
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
        'db' => require(__DIR__ . '/db.php'),
        'i18n' => [
            'translations' => [
                'main*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@glob/messages/"
                ],
            ],
        ],
    ],
    'params' => [],
];

return $config;

