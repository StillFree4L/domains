<?php

class PagesController extends BaseController
{
	public function actionIndex()
	{

            
            $model=new Instances('search');
            $model->resetScope();
            $model->pages();
            $model->unsetAttributes();  //

            $this->render('index', array(
                "model"=>$model
            ));
	}
        public function actionAdd()
        {

            Yii::import("ext.EInstance.EInstanceValidator");

            if (isset($_GET['iid']))
            {
                $model = Instances::model()->resetScope()->findByPk($_GET['iid']);
            } else
            {
                $model = new Instances();
            }

            if (isset($_POST['Instances'])) {

                $_POST['Instances']['type'] = 3;

                $model->attributes = $_POST['Instances'];
                $model = EInstanceValidator::validateInstance($model);
            }

            $this->render('add', array(
                "model"=>$model,
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