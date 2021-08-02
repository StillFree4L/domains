<?php

class question_answer extends CWidget
{

    var $plugin;
    var $type = "page"; // page, last_questions
    var $limit = 0;
    var $label = true;
    public function run()
    {
        
        Yii::setPathOfAlias("question_answer", dirname(__FILE__));
        
        $assets = dirname(__FILE__).'/assets';        
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);        
        Yii::app()->clientScript->registerCssFile($baseUrl . '/questions.css');

        
        // Including models
        Yii::import("question_answer.models.*");

        $question = new PQuestionAnswer();

        $error = false;
        if (isset($_POST['PQuestionAnswer']))
        {
            $question -> name = $_POST['PQuestionAnswer']['name'];
            $question -> email = $_POST['PQuestionAnswer']['email'];
            $question -> question = $_POST['PQuestionAnswer']['question'];

            if ($question->save())
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Ваш вопрос отправлен на проверку'));
                Yii::app()->request->redirect(Yii::app()->createUrl(Yii::app()->request->url,array("#"=>"comments")));
            } else {
                $error = true;
            }

        }
        
        if ($this->type == "page") {
            $questions = PQuestionAnswer::model()->approved()->findAll();
        } else if ($this->type == "last_questions")
        {
            $limit = 3;
            if (intval($this->limit)>0) $limit = $this->limit;
            $questions = PQuestionAnswer::model()->approved()->findAll(array("limit"=>$limit));
        }

        
        $this->plugin = Plugins::model()->byName("question_answer")->find();
        $this->render("index", array(
            "questions"=>$questions,
            "question"=>$question,
            "error"=>$error,
        ));
    }
}

?>
