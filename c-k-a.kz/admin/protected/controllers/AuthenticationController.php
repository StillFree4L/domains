<?php
class AuthenticationController extends BaseController
{
	/**
	 * Declares class-based actions.
	 */
        public $layout='//layouts/auth';
        public $defaultAction = "login";

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		

                $model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
                        {

                                $this->redirect(Yii::app()->user->returnUrl);
                        }
		}
		// display the login form
                //$baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
		Yii::app()->getClientScript()->registerCssFile('/admin/css/authentication/authentication.css');
		$this->render('login',array('model'=>$model));
	}
        
        function actionRegistrationComplete()
        {
            $this->render("registrationComplete");            
        }
        
        function actionActivate()
        {
            if (isset($_GET['account']))
            {
                if (Users::model()->activate($_GET['account']))
                {
                    $this->render("activated");
                } else {
                    Yii::app()->request->redirect("/");
                }
            }
        }

        function actionRegistration()
        {
            $model = new Users();
            
            if (isset($_POST['Users']))
            {
                
                $transaction = $model->dbConnection->beginTransaction();
                $model->attributes = $_POST['Users'];
                
                $model->active = 1;
                if ($model->validate() AND $model->save()) 
                {
                    $transaction->commit();
                    Yii::app()->request->redirect( '/admin/'.Yii::app()->language.'/'.Yii::app ()->controller->id."/registrationComplete" );
                    
                    /*
                    Yii::import('application.extensions.phpmailer.JPhpMailer');
                    $mail = new JPhpMailer;
                    $mail->IsSMTP();
                    $mail->CharSet = 'utf-8';  
                    $mail->Host = Yii::app()->baseOptions->site_host;
                    //$mail->SMTPAuth = true;
                    //$mail->Username = 'yourname@163.com';
                    //$mail->Password = 'yourpassword';
                    $mail->SetFrom('admin@'.Yii::app()->baseOptions->site_host, Yii::app()->baseOptions->system_name);
                    $mail->Subject = 'Активация вашего аккаунта';                   
                    $mail->MsgHTML('Для того чтобы завершить регистрацию вашего аккаунта на сайте '.Yii::app()->baseOptions->system_name.' пройдите по ссылке <a href="http://'.Yii::app()->baseOptions->site_host.'/admin/authentication/activate/account/'.$model->id.'">http://'.Yii::app()->baseOptions->site_host.'/admin/authentication/activate/account/'.$model->id.'</a>');
                    $mail->AddAddress($model->email);
                    if ($mail->Send()) {                    
						$transaction->commit();
						Yii::app()->request->redirect( '/admin/'.Yii::app()->language.'/'.Yii::app ()->controller->id."/registrationComplete" );
					} else {
						$model->addError("email",t("Ошибка. Возможно неправильный email."));
						$transaction->rollback();
					}
                     * 
                     */
                }
                
            }
            
            $this->render("registration", array(
                "model"=>$model,
            ));
        }

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect("/");
	}
}