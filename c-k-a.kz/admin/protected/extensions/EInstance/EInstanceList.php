<?php

class EInstanceList extends CWidget
{

    var $template = "list"; // add, view
    var $type = "1";
    var $model = null;
    var $columns = array();
    var $defaultColumns = true;
    public function run()
    {

        if ($this->model == null)
        {
            $this->render("error", array("error"=>t('Ошибка. Укажите модель')));
            return;
        }


        if(isset($_POST['Instances']))
			$this->model->attributes=$_POST['Instances'];
        
        if (isset($_POST['submitType']))
        {
            
            if (isset($_POST['InstancesL']))
            {

                
                foreach ($_POST['InstancesL'] as $id)
                {

                    switch ($_POST['submitType'])
                    {
                        case "restore":
                            $instance = self::getInstance($id);
                            $instance->state = 2;
                            $instance->save();
                            break;
                        case "delete":
                            $instance = self::getInstance($id);
                            $instance->state = 3;
                            $instance->save();
                            break;
                        case "forse_delete":
                            $instance = self::getInstance($id);
                            $instance->delete();
                            break;
                        case "approve_comments":
                            $instance = self::getComment($id);
                            $instance->state = 2;
                            $instance->save();
                            break;
                        case "delete_comments":
                            $instance = self::getComment($id);
                            $instance->delete();
                            break;
                        default:
                            break;
                    }
                    
                }
                
                Yii::app()->request->redirect( '/admin/'.Yii::app()->language.'/'.Yii::app ()->controller->id );
            }

        }

        // Instance.js
        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/instance.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/instance.css', CClientScript::POS_HEAD);

        $this->render("list/".$this->template, array(
        ));
    }

    private static function getInstance($id)
    {
        $instance = Instances::model()->resetScope()->findByPk($id);
        $instance->refreshMetaData();
        return $instance;
    }
    private static function getComment($id)
    {
        $instance = Comments::model()->resetScope()->findByPk($id);
        $instance->refreshMetaData();
        return $instance;
    }

}

?>