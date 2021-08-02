<?php

class TranslateController extends BaseController
{
	var $defaultAction = "site";
        
       public function actionSite()
        {
            $path = Yii::getPathOfAlias("frontend.messages");
            $this->doAction($path);            
        }
        public function actionAdmin()
        {
            $path = Yii::getPathOfAlias("application.messages");
            $this->doAction($path);
        }
        public function doAction($path)
        {
                        
            $lang = Yii::app()->language;
            if (isset($_GET['l']))
            {
                $lang = $_GET['l'];
            }
           
            $words = include($path."/$lang/app.php");
            
            if (isset($_POST['words']))
            {
                $words = array();
                $contents = "<?php return array(";
                foreach ($_POST['words'] as $word)
                {
                    
                    $key = addSlashes(str_replace("'","",$word['key']));
                    $value = addSlashes(str_replace("'","",$word['value']));
                    $words[$key] = $value;
                    $contents .= " \"$key\" => \"$value\", \n";
                                        
                }
                $contents .= "); ?>";
                
                if (file_put_contents($path."/$lang/app.php", $contents)) {
                        Yii::app()->user->setFlash('fieldSubmitted', t('Слова успешно обновлены'));
                    } else {
                        Yii::app()->user->setFlash('fieldError', t('Ошибка записи. Обратитесь к администратору'));
                    }
                
                
            }
            
            
            $this->render("index", array(
                "lang"=>$lang,
                "words"=>$words,
            ));
        }
}