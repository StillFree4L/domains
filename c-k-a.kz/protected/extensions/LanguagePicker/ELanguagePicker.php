<?php
/**
 * @see CPortlet
 */
Yii::import('zii.widgets.CPortlet');
 /**
  * This is a simple portlet to choose language from your messages directory
  * in controller::init() put 
  * @example
  * <pre>
  * Yii::import('ext.LanguagePicker.ELanguagePicker'); 
  * EThemePicker::setLanguage();
  * </pre>
  * @author David Constantine Kurushin http://www.zend.com/en/store/education/certification/authenticate.php/ClientCandidateID/ZEND015209/RegistrationID/238001337
  */
class ELanguagePicker extends CPortlet
{
	/**
	 * @var string a title for the widget
	 */
    public $title = '';
    /**
     * 
     * @var string the default tag name of the container
     */
    public $tagName = 'div';

    /**
     * @var string type of render
     */
    public $type = "list"; //inline

    /**
     * @var array some html options for the dropdownlist
     */
	public $dropDownOptions = array(
		'submit'=>'',
		'csrf'=>true, 
		'class'=>'languageSelector span2' , 
		'id'=>'languageSelector',                
	);
	/**
	 * (non-PHPdoc)
	 * @see CPortlet::renderContent()
	 */
    protected function renderContent()
    {
        $translations = self::getLanguagesList();
        echo CHtml::form('', 'post', array('name'=>'languageSelectorForm'));

        switch ($this->type) {
            case "list":
                echo CHtml::dropDownList('languageSelector' , Yii::app()->getLanguage(), $translations, $this->dropDownOptions);
                break;
            case "inline":
                echo CHtml::hiddenField('languageSelector', '');
                foreach ($translations as $lang=>$caption)
                {
                    echo CHtml::link($caption, "", array(
                        "onclick"=>"js:$('#languageSelector').attr('value','$lang'); document.languageSelectorForm.submit();",
                        "class"=>"language_link ".($lang == Yii::app()->language ? "active_lang" : ""),
                    ));
                }
                break;
        }

    echo CHtml::endForm();
    }
    /**
     * set the language and save on cookie, or select from cookie
     * this should be called from  CController::init or CController::beforeAction etc.
     * @see CController::init() 
     * @see CController::beforeAction()
     * @param $cookieDays integer the amount of days the language choice will be saved, default 180 days
     */
    public static function setLanguage($cookieDays = 180)
    {
      if(Yii::app()->request->getPost('languageSelector') !== null && in_array($_POST['languageSelector'], array_keys(self::getLanguagesList()), true)){
		      Yii::app()->setLanguage($_POST['languageSelector']);
          $url = Yii::app()->request->requestUri;
          $find = preg_match('/\/(ru|kz|en)\//Dui', $url);
          if(!$find){
            $url = Yii::app()->createUrl(Yii::app()->controller->route, array('lang'=>Yii::app()->language));
          }else{
            $url = preg_replace('/\/(ru|kz|en)\//Dui', '/'.Yii::app()->language.'/', $url);
          }
          Yii::app()->request->redirect($url, true);
        /*
		 $cookie = new CHttpCookie('language', $_POST['languageSelector']);
	   $cookie->expire = time() + 60*60*24*$cookieDays; 
		 Yii::app()->request->cookies['language'] = $cookie;
	}else if(isset(Yii::app()->request->cookies['language']) && in_array(Yii::app()->request->cookies['language']->value, self::getLanguagesList(), true) ){
		Yii::app()->setLanguage(Yii::app()->request->cookies['language']->value);
	}else if(isset(Yii::app()->request->cookies['language'])){
		//if we came to this point, the language don't exists, so we better unset the cookie
		unset(Yii::app()->request->cookies['language']);
		throw new CHttpException(400, Yii::t('app', 'Invalid request. Translation don\'t exists!'));
	}
        */
      }
    }
    /**
     * Iterates the messages directory and list the languages available
     * @return array list of languages
     */
    private static function getLanguagesList(){
	
        $r = array();
        foreach (Yii::app()->urlManager->languages as $l)
        {
            $r[$l] = t($l);
        }
        return $r;
        
        $translations = array();
	$directoryIterator = new DirectoryIterator(Yii::app()->messages->basePath);
	foreach($directoryIterator as $item)
	if($item->isDir() && !$item->isDot() && $item->getFilename() != 'en')
		$translations[$item->getFilename()] = t($item->getFilename());
	return $translations;
    }
}