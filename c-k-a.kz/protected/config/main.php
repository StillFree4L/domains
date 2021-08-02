<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

$frontend = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'protected';
Yii::setPathOfAlias('frontend',$frontend);


require_once( dirname(__FILE__) . '/../helpers/tools.php');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',	
        'language' => 'ru',
	'theme'=> OCMS_THEME,
	'defaultController' => 'home',
	'preload' => array(
               	
                'log',
		'bootstrap',
		'less',		
                'plugins',
                
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.plugins.*',
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
			'class' => 'ext.less.components.LessCompiler',
			'forceCompile' => false, // indicates whether to force compiling
			'compress' => false, // indicates whether to compress compiled CSS
			'debug' => false, // indicates whether to enable compiler debugging mode
			'paths' => array(
				'less/style.less' => 'css/style.css',
			),
		),
		'bootstrap' => array(
			'class' => 'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
			'coreCss' => true, // whether to register the Bootstrap core CSS (bootstrap.min.css), defaults to true
			'responsiveCss' => false, // whether to register the Bootstrap responsive CSS (bootstrap-responsive.min.css), default to false
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
			'class' => 'application.components.WebUser',
			'loginUrl' => array('/admin/authentication/login'),
		),
		'authManager' => array(
			'class' => 'application.components.PhpAuthManager',
			'defaultRoles' => array('guest'),
		),
                'plugins'=>array(
                    'class'=>'frontend.components.PluginLoader'
                ),
		
		'urlManager' => array(
			//'class'=>'application.extensions.urlManager.LangUrlManager',
			'class' => 'application.components.UrlManager',
			'urlFormat' => 'path',
			'showScriptName' => false,
			'languages' => array('ru', 'kz', 'en'),
			'langParam' => 'lang',
                        'rules'=>array(

                            'gii' => 'gii',
                            'gii/<controller:[\w\-]+>' => 'gii/<controller>',
                            'gii/<controller:[\w\-]+>/<action:\w+>' => 'gii/<controller>/<action>',

                            '<lang:(ru|kz|en)>' => 'home/index',

                            '<lang:(ru|kz|en)>/page/<view:\w+>/*' => 'page/index',
                            'page/<view:\w+>/*' => 'page/index',
                            
                            '<lang:(ru|kz|en)>/<_c:\w+>/<iid:\d+>/page/<page:\d+>' => '<_c>/index',
                            '<_c:\w+>/<iid:\d+>/page/<page:\d+>' => '<_c>/index',

                            '<lang:(ru|kz|en)>/<_c:\w+>/<iid:\d+>' => '<_c>/index',
                            '<_c:\w+>/<iid:\d+>' => '<_c>/index',
                            
                            '<lang:(ru|kz|en)>/<_c:\w+>' => '<_c>',
                            '<_c:\w+>' => '<_c>',
                            
                            '<lang:(ru|kz|en)>/<_c:\w+>/<_a:\w+>/*' => '<_c>/<_a>',
                            '<_c:\w>/<_a:\w+>/*' => '<_c>/<_a>',
                                                        
                            'admin/<_c:[\w\-]+>/<_a:\w+>' => 'admin/<_c>/<_a>',                                                                  

                            
                            
                        )
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'error/base',
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
                                array(
                                    'class'=>'CProfileLogRoute',
                                    'levels'=>'profile',
                                    'enabled'=>true,
                                ),
				/*array(
					'class' => 'CWebLogRoute',
				)*/
			),
		),
                'baseOptions'=>array(
                    'class'=>'application.components.BaseOptions'
                ),
                'breadCrumbs'=>array(
                    'class'=>'frontend.components.BreadCrumbs'
                ),      
                'userCount'=>array(
                    "class"=>'frontend.components.UserCount',
                   
                )
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']        
	'params'=>array(
                
	),
);