<?php

class survey extends CWidget
{

    var $plugin;    
    var $limit = 0;
    public function run()
    {
        
        $assets = dirname(__FILE__).'/assets';        
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);        
        Yii::app()->clientScript->registerCssFile($baseUrl . '/survey.css');

        $criteria = array();
        if ($this->limit > 0) {
            $criteria["limit"] = $this->limit;
        }
        $surveys = PSurvey::model()->active()->findAll($criteria);

        if (isset($_POST['PSurveyVote']))
        {
            foreach ($_POST['PSurveyVote'] as $s=>$v)
            {

                $model = PSurvey::model()->findByPk($s);
                if ($model->isVoted() == "2")
                {
                    $u = new PSurveyUsers();
                    $u->survey_id = $s;
                    $u->variant_id = $v;
                    $u->user_id = Yii::app()->user->id;

                    if ($u->save())
                    {
                        Yii::app()->request->redirect(Yii::app()->request->url);
                    }

                }

            }
        }

        $this->plugin = Yii::app()->plugins->survey;
        $this->render("index", array(
            "surveys"=>$surveys
        ));
    }
}

?>
