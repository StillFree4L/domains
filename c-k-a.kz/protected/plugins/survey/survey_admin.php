<?php

class survey_admin extends CWidget
{

    var $plugin;
    public function run()
    {
        
        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/survey.css');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/survey.js', CClientScript::POS_HEAD);

        $survey = new PSurvey();

        $error = false;
        $new = true;

        if (isset($_POST['getPSurvey']))
        {
            $pSurvey = PSurvey::model()->findByPk($_POST['getPSurvey']);
            die($this->render("survey_body"));
        }

        if (isset($_GET['survey']))
        {
            $new = false;
            $survey = PSurvey::model()->findByPk($_GET['survey']);
        }

        if (!empty($survey->id))
        {
            $variants = $survey->pSurveyVariants;
        } else {
            $variants = array();
        }

        if (isset($_POST['PSurvey']))
        {
            
            $survey->attributes = $_POST['PSurvey'];

            
            if (isset($_POST['PSurveyVariants']))
            {
                foreach ($_POST['PSurveyVariants'] as $k=>$v)
                {
                    if (isset($v['id']))
                    {
                        $variants[$k]->name = $v['name'];
                    } else {
                        $variants[$k] = new PSurveyVariants();
                        $variants[$k]->name = $v['name'];
                        
                    }
                }
            }

            $transaction = PSurvey::model()->dbConnection->beginTransaction();
            $error = false;

            if ($survey->validate() AND $survey->save()) {

                foreach ($variants as $k=>$v)
                {
                    $variants[$k]->survey_id = $survey->id;
                    if (!$v->save())
                    {
                        
                        $error = true;
                    }
                }

            } else $error = true;

            if (!$error) {
                $transaction->commit();
                if ($new) {
                    Yii::app()->user->setFlash('fieldSubmitted', t('Опрос успешно добавлен'));
                    Yii::app()->request->redirect("/admin/".Yii::app()->language."/".Yii::app()->controller->id."/survey");
                } else {
                    Yii::app()->user->setFlash('fieldSubmitted', t('Опрос успешно обновлен'));
                }
                

            } else {

                Yii::app()->user->setFlash('fieldError', t('Ошибка добавления опроса'));
                $transaction->rollback();
            }

        }

        if (isset($_POST['submitType']))
        {

            if (isset($_POST['Surveys']))
            {


                foreach ($_POST['Surveys'] as $id)
                {

                    switch ($_POST['submitType'])
                    {
                        case "activate":
                            $instance = PSurvey::model()->updateByPk($id, array(
                                "active"=>"1"
                            ));
                            break;
                        case "deactivate":
                            $instance = PSurvey::model()->updateByPk($id, array(
                                "active"=>"0"
                            ));
                            break;
                        case "reset":
                            $instance = PSurveyUsers::model()->deleteAllByAttributes(array(
                                "survey_id"=>$id
                            ));
                            break;
                        case "delete":
                            $instance = PSurvey::model()->deleteByPk($id);
                            break;
                    }

                }

                Yii::app()->request->redirect( Yii::app()->request->url );
            }

        }

        $template = "admin/index";
        if (isset($_GET['act'])) {
            $template = "admin/".$_GET['act'];
        }

        $this->plugin = Yii::app()->plugins->suvey;
        $this->render($template, array(
            "surveys"=>$surveys,
            "survey"=>$survey,
            "variants"=>$variants,
            "error"=>$error,
        ));
    }    
}

?>
