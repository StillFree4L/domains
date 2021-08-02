<?php

class question_answer_admin extends CWidget
{

    var $plugin;
    public function run()
    {
        
        Yii::setPathOfAlias("question_answer", dirname(__FILE__));

        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/questions.css');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/questions.js', CClientScript::POS_HEAD);

        // Including models
        Yii::import("question_answer.models.*");

        $question = new PQuestionAnswer();        

        $error = false;

        if (isset($_POST['getPQAnswer']))
        {
            $question = PQuestionAnswer::model()->findByPk($_POST['getPQAnswer']);
            die($question->answer);
        }

        if (isset($_POST['PQuestionAnswer']))
        {

            $question = PQuestionAnswer::model()->findByPk($_POST['PQuestionAnswer']['id']);
            $question->answer = $_POST['PQuestionAnswer']['answer'];

            if ($question->save())
            {

                Yii::import('application.extensions.phpmailer.JPhpMailer');
                $mail = new JPhpMailer;
                $mail->IsSMTP();
                $mail->CharSet = 'utf-8';
                $mail->Host = 'c-k-a.kz';
                //$mail->SMTPAuth = true;
                //$mail->Username = 'yourname@163.com';
                //$mail->Password = 'yourpassword';
                $mail->SetFrom('admin@c-k-a.kz', 'Центрально-Казахстанская академия');
                $mail->Subject = 'На ваш вопрос ответили';
                $mail->MsgHTML("Для того, чтобы увидеть ответ на ваш вопрос на сайте Центрально-Казахстанской академии, пройдите по ссылке <a href='http://c-k-a.kz/".Yii::app()->language."/page/question_answer#question_".$question->id."'>Посмотреть ответ</a>");
                $mail->AddAddress($question->email);
                $mail->Send();

            } else {
                
                $error = true;
            }

        }

        if (isset($_POST['submitType']))
        {
            
            if (isset($_POST['Questions']))
            {


                foreach ($_POST['Questions'] as $id)
                {

                    switch ($_POST['submitType'])
                    {
                        case "approve":
                            $instance = PQuestionAnswer::model()->findByPk($id);
                            $instance->refreshMetaData();
                            $instance->state = 2;
                            $instance->save();
                            break;
                        case "delete":
                            $instance = PQuestionAnswer::model()->findByPk($id);
                            $instance->refreshMetaData();
                            $instance->state = 3;
                            $instance->save();
                            break;                        
                    }

                }

                Yii::app()->request->redirect( Yii::app()->request->url );
            }

        }

        $this->plugin = Plugins::model()->byName("question_answer")->find();
        $this->render("admin/index", array(
            "questions"=>$questions,
            "question"=>$question,
            "error"=>$error,
        ));
    }    
}

?>
