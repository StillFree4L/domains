<?php

require(__DIR__ . '/constants.php');

$config = [
    'id' => 'Glazolik',
    'basePath' => dirname(__DIR__)."/web",
    'aliases' => [
        '@glob' =>  dirname(__DIR__)."/global",
        '@PhpOffice' => dirname(__DIR__).'/vendors/PhpOffice',
        '@Zend' => dirname(__DIR__).'/vendors/Zend'
    ],
    'bootstrap' => ['log', 'urlManager'],
    'defaultRoute' => 'main/index',
    'language' => "ru-RU",
    'modules' => [
        "gii" => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*']
        ]
    ],
    'components' => [
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => SECRET_WORD,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // required for POST input via `php://input`
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'glob\models\Users',
            'loginUrl'=>'/auth/login',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'glob\components\PhpManager',
        ],
        'urlManager' => [
            "class"=>'glob\components\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName'=>false,
            'rules' => [
                'journal/<code:\d+>' => 'journal/code',
                '<controller:\w+>'=>'<controller>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['profile'],
                    'logFile'=>'@runtime/logs/profile.log'
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'data' => [
            'class' => 'app\components\JModel'
        ],
        'response' => [
            'class' => 'app\components\Response'
        ],
        'i18n' => [
            'translations' => [
                'main*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => "@glob/messages/"
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'jQuery' => [
                    'class' => 'app\bundles\JQueryBundle'
                ],
                'bootstrap' => [
                    'class' => 'app\bundles\BootstrapBundle'
                ],
                'backbone' => [
                    'class' => 'app\bundles\BackboneBundle'
                ],
                'base' => [
                    'class' => 'app\bundles\BaseBundle'
                ],
            ]
        ],
        'view' => [
            'class' => 'app\components\View',
        ],
        'breadCrumbs'=>[
            'class'=>'app\components\BreadCrumbs'
        ]
    ],
    'params' => [],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    /*$config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
    $config['modules']['gii']['allowedIps'] = ['*'];*/
    $config['components']['assetManager']['forceCopy'] = RELOAD_ASSETS;
}


return $config;

