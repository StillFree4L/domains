<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

require_once( dirname(__FILE__) . '/../../../protected/helpers/tools.php');

$frontend = dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'protected';
Yii::setPathOfAlias('frontend',$frontend);

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',	
        'language' => 'ru',        
	'defaultController' => 'home',
	'preload' => array(
		'log',
		'bootstrap',
		'less',
                'baseOptions',
                'plugins',
                'userCount'
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
                'frontend.models.*',
                'frontend.components.*',
		'application.components.*',
                'frontend.plugins.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'newpassword123',
			'ipFilters'=>array('*'),
		)                
		
	),

	// application components
	'components'=>array(
		'lessCompiler' => array(
			'class' => 'frontend.extensions.less.components.LessCompiler',
			'forceCompile' => false, // indicates whether to force compiling
			'compress' => false, // indicates whether to compress compiled CSS
			'debug' => false, // indicates whether to enable compiler debugging mode
			'paths' => array(
				'less/style.less' => 'css/style.css',
			),
		),
		'bootstrap' => array(
			'class' => 'frontend.extensions.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
			'coreCss' => true, // whether to register the Bootstrap core CSS (bootstrap.min.css), defaults to true
			'responsiveCss' => true, // whether to register the Bootstrap responsive CSS (bootstrap-responsive.min.css), default to false
			'plugins' => array(
				'transition' => false, // disable CSS transitions
				'tooltip' => array(
					'selector' => 'a.tooltip', // bind the plugin tooltip to anchor tags with the 'tooltip' class
					'options' => array(
						'placement' => 'bottom', // place the tooltips below instead
					),
				),
			),
		),
                'user' => array(
			'allowAutoLogin' => true,
			'class' => 'frontend.components.WebUser',
			'loginUrl' => array('/authentication/login'),
		),
		'authManager' => array(
			'class' => 'frontend.components.PhpAuthManager',
			'defaultRoles' => array('guest'),
		),
                
		// uncomment the following to enable URLs in path-format
		
		'urlManager' => array(
			//'class'=>'application.extensions.urlManager.LangUrlManager',
			'class' => 'frontend.components.UrlManager',
			'urlFormat' => 'path',
			'showScriptName' => false,
			'languages' => array('ru', 'kz', 'en'),
			'langParam' => 'lang',
                        'rules'=>array(

                            'gii' => 'gii',
                            'gii/<controller:[\w\-]+>' => 'gii/<controller>',
                            'gii/<controller:[\w\-]+>/<action:\w+>' => 'gii/<controller>/<action>',

                            '<lang:(ru|kz|en)>/<_c:(plugin)>/<uname:\w+>/*' => '<_c>/index',
                            '<_c:(plugin)>/<uname:\w+>/*' => '<_c>/index',

                            '<lang:(ru|kz|en)>/<_c:\w+>' => '<_c>',
                            '<_c:\w+>' => '<_c>',

                            '<lang:(ru|kz|en)>/<_c:\w+>/<_a:\w+>/*' => '<_c>/<_a>',
                            '<_c:\w+>/<_a:\w+>/*' => '<_c>/<_a>',

                            
                        )
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'/error/base',
		),
		'db' => array(
			'class' => 'system.db.CDbConnection',
			'connectionString' => 'mysql:host='.OCMS_HOST.';dbname='.OCMS_DB.';',
			'emulatePrepare' => true,
			'username' => OCMS_USER,
			'password' => OCMS_PASSWORD,
			'charset' => 'utf8'			
		),			
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				/*
				array(
					'class' => 'CWebLogRoute',
				), */
				
			),
		),
                'baseOptions'=>array(
                    'class'=>'frontend.components.BaseOptions'
                ),
                'plugins'=>array(
                    'class'=>'frontend.components.PluginLoader'
                ),
                'breadCrumbs'=>array(
                    'class'=>'frontend.components.BreadCrumbs'
                ),
                'userCount'=>array(
                    "class"=>'frontend.components.UserCount',
                    "count"=>false
                )
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']        
	'params'=>array(
                'adminUrl'=>'/admin/',
                'perPage'=>'20',
                'uploadDir'=>dirname(__FILE__) . '/../../uploaded/',    
                'uploadFilesDir'=>dirname(__FILE__) . '/../../uploaded/files/',
                'uploadFlashDir'=>dirname(__FILE__) . '/../../uploaded/flash/',
                'uploadImagesDir'=>dirname(__FILE__) . '/../../uploaded/images/',
	),
);


    