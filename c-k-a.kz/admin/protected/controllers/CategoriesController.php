<?php

class CategoriesController extends BaseController
{
	public function actionIndex()
	{

            if (!isset($_GET['cat']))
            {
                $_GET['cat'] = "1";
            }

            if ($_GET['cat'] == "1")
            {
                $model=new Instances('search');
                $model->resetScope();
                $model->categories();
		$model->unsetAttributes();  // clear any default values		
                
            }
            if ($_GET['cat'] == "4") 
            {
                
                $model=new Instances('search');
                $model->resetScope();
                $model->linkCategories();
		$model->unsetAttributes();
            }
           
            
            
            $this->render('index', array(
                "model"=>$model,
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
                
                $_POST['Instances']['state'] = 2;                

                $model->attributes = $_POST['Instances'];
                
                $model = EInstanceValidator::validateInstance($model);
            }

            $this->render('add', array(
                "model"=>$model,
            ));
        }

        public function actionDelete()
        {
            
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