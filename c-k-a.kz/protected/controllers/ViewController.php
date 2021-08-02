<?php

class ViewController extends BaseController
{
        
	public function actionIndex()
	{

            
                $instance = Instances::model()->findByPk($_GET['iid']);
                Yii::app()->breadCrumbs->addLink($instance->caption);

		$this->render('index', array(
                    "instance"=>$instance
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