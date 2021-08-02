<?php

class VirtualLibraryController extends BaseController
{
    
    var $layout = "//layouts/index_1column";
    var $limit = 30;
    
    public function beforeRender()
    {
        parent::beforeRender($view);
        
        $assets = dirname(__FILE__).'/assets';        
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);        
        Yii::app()->clientScript->registerCssFile($baseUrl . '/virtualLibrary.css');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/virtualLibrary.js', CClientScript::POS_HEAD);
        
        $assets = Yii::getPathOfAlias('frontend.extensions.chosen');
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/chosen.jquery.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/chosen.css');
        
        return true;
    }
    
    public function actionIndex()
    {
        
        $libraries = PVirtualLibrary::model()->findAllBySql("SELECT DISTINCT library_name FROM p_virtual_library ORDER BY library_name");
        
        $chars = PVirtualLibrary::model()->findAllBySql("SELECT DISTINCT LEFT(book_name,'1') as book_name FROM p_virtual_library ORDER BY book_name");
        
        $page = 1;
        
        $criteria = new CDbCriteria();
        
        $cond = "";
        
        if (isset($_GET['l']))
        {
            $cond .= "library_name LIKE '".$libraries[$_GET['l']]->library_name."' AND ";
            
        }
        if (isset($_GET['c']))
        {
            $cond .= "book_name LIKE '".mysql_escape_string($_GET['c'])."%' AND ";

        }
        $cond = rtrim($cond,"AND ");        
        $criteria->condition = $cond;
        
        
        
        $pager_criteria = array("condition"=>$cond);
        
        if (isset($_GET['page']))
        {
            $page = $_GET['page'];
        }            

        $criteria->limit = $this->limit;
        $criteria->offset = $this->limit*($page-1);  
        $criteria->order = "book_name";       


        $books = PVirtualLibrary::model()->with()->findAll($criteria);
        
        
        
        $this->render("index", array(
            "libraries"=>$libraries,
            "chars"=>$chars,
            "books"=>$books,
            "page"=>$page,
            "pager_criteria"=>$pager_criteria
        ));
        
    }
    
    public function actionView()
    {
        
        if (!isset($_GET['book']))
        {
            Yii::app()->request->redirect($this->cUrl());
        }
        
        $libraries = PVirtualLibrary::model()->findAllBySql("SELECT DISTINCT library_name FROM p_virtual_library ORDER BY library_name");
        
        $book = PVirtualLibrary::model()->with()->findByPk($_GET['book']);
        
        $this->render("view", array(
            "libraries"=>$libraries,
            "book"=>$book
        ));
    }
    
    public function actionSearch()
    {
        if (!isset($_POST['search_in_library']))
        {
            Yii::app()->request->redirect($this->cUrl());
        }
        
        $libraries = PVirtualLibrary::model()->findAllBySql("SELECT DISTINCT library_name FROM p_virtual_library ORDER BY library_name");
        
        $s = $_POST['search_in_library'];
        $search = new PVirtualLibrary();
        $search->book_name = $s;
        $search->book_code = $s;
        $search->pub_view = $s;
        $search->pub_name = $s;
        $search->pub_code = $s;
        $search->author_name = $s;
        
        $books = $search->search()->getData();
        
        
        $this->render("search",array(
            "libraries"=>$libraries,
            "books"=>$books,
        ));
        
    }
    
    public function getViewPath()
    {
        return Yii::getPathOfAlias("virtualLibrary.views");
    }
    
    public function cUrl($k = null,$v = null)
    {
        $params = array();
        if (isset($_GET['l']) AND $k!="l")
        {
            $params['l'] = $_GET['l'];
        }
        
        if (isset($_GET['c']) AND $k!="c")
        {
            $params['c'] = $_GET['c'];
        }
        
        if ($v !== null) {
            $params[$k] = $v;
        }
        
        return Yii::app()->createUrl("/".Yii::app()->language."/".Yii::app()->controller->id."/index", $params);
    }
    
}

?>
