<?php
 
class BaseController extends Controller
{
	public $layout='//layouts/base';
	public function init()
	{
                Yii::import("frontend.helpers.Lists");
		if(isset($_GET['lang']) && in_array($_GET['lang'], Yii::app()->urlManager->languages)){
			Yii::app()->language = $_GET['lang'];
		}
		Yii::import('frontend.extensions.LanguagePicker.ELanguagePicker');
		ELanguagePicker::setLanguage();
		parent::init();
	}
 
	/**
	 * Include some js and css automatically
	 * 
	 * @param string $action
	 */
	public function beforeRender($view) 
	{
                
                $bread = Lists::getControllerCaption(Yii::app()->controller->id);
                if ($bread)
                {
                    Yii::app()->breadCrumbs->addLink($bread,"/admin/".Yii::app()->controller->id);
                }

		parent::beforeRender($view);
		
		
		// default files
		$cs = Yii::app()->getClientScript();
		
		$module = isset(Yii::app()->controller->module) 
			? Yii::app()->controller->module->id
			: '';

		if($module == ''){
			$staticRoot = "webroot.";
		}else{
			$staticRoot = "application.modules." . $module . ".assets.";
		}
		
		// JS
		
		$cs->registerScriptFile(CHtml::asset('js/common.js', false));

		$jsControllerFile = Yii::getPathOfAlias($staticRoot . "js." . Yii::app()->controller->id) 
			. '/' . Yii::app()->controller->id . ".js";
		if(file_exists($jsControllerFile)){
			$cs->registerScriptFile(CHtml::asset($jsControllerFile, false));
		}
		
		$jsActionFile = Yii::getPathOfAlias($staticRoot . ".js." . Yii::app()->controller->id) 
			. '/' . Yii::app()->controller->action->id . '.js';
		if(file_exists($jsActionFile)){
			$cs->registerScriptFile(CHtml::asset($jsActionFile, false));
		}
		
		// CSS
		
		$cssControllerFile = Yii::getPathOfAlias($staticRoot . ".css." . Yii::app()->controller->id)
			. '/' . Yii::app()->controller->id . ".css";
		if(file_exists($cssControllerFile)){
			$cs->registerCSSFile(CHtml::asset($cssControllerFile, false));
		}
		
		$cssActionFile = Yii::getPathOfAlias($staticRoot . ".css." . Yii::app()->controller->id)
			. '/' . Yii::app()->controller->action->id . '.css';
		if(file_exists($cssActionFile)){
			$cs->registerScriptFile(CHtml::asset($cssActionFile, false));
		}
		
		return true;
	}
	
	public function filters()
	{
            return array(
                'accessControl',
            );
	}
	
	public function accessRules()
	{
		
		return array(
			// allow all for root and admin			
                       array(
                            'allow',
                            'roles'=>array(Users::ROLE_ADMIN,Users::ROLE_ROOT),
                        ),
                        array(
                            'allow',
                            'actions'=>array('login', 'logout', 'registration', 'registrationComplete','activate'),
                            'controllers'=>array('authentication'),
                            'users'=>array('*'),
			),
                        array(
                            'deny',
                            'users'=>array('*'),
                            'deniedCallback' => array($this,"goHome"),
                        )
                );
	}
        public function goHome()
        {
            if (!isset(Yii::app()->user->id)) {
                    Yii::app()->controller->redirect("/admin/authentication/login");
                } else {
                    Yii::app()->controller->redirect("/");
                }
        }
        
}