<?php

class PluginController extends BaseController
{
	public function actionIndex()
	{
                $plugin = Plugins::model()->byName($_GET['uname'])->find();
                Yii::app()->breadCrumbs->addLink($plugin->name);
		$this->render('index', array(
                    "plugin"=>$plugin
                ));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}