<?php

class ForumController extends BaseController
{

    var $limit = 30;
    var $user = false;
    
    public function init()
    {
        if (!empty(Yii::app()->user->id)) {
            $this->user = PForumUsers::model()->findByPk(Yii::app()->user->id);
        }
        parent::init();        
    }
    
    public function beforeAction($action)
    {
        
        Yii::app()->breadCrumbs->addLink(t("Форум"),"/".Yii::app()->language."/".Yii::app()->controller->id);
        if (isset($_GET['cat']))
        {
            $cat = PForumCategories::model()->findByPk($_GET['cat']);
            $this->addbreadCrumbs($cat);
        } else if (isset($_GET['eid']))
        {
            $cat = PForumThemes::model()->findByPk($_GET['eid'])->category;
            $this->addbreadCrumbs($cat);
        }
        return parent::beforeAction($action);
    }
    
    public function beforeRender($view)
    {
        parent::beforeRender($view);
        
        $assets = dirname(__FILE__).'/assets';        
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);        
        Yii::app()->clientScript->registerCssFile($baseUrl . '/forum.css');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/forum.js', CClientScript::POS_HEAD);
        
        $assets = Yii::getPathOfAlias('application.extensions.chosen');
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/chosen.jquery.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/chosen.css');
        
        return true;
    }
    
    public function actionIndex()
    {
        
        
        $themes = array();
        if (!isset($_GET['cat']))
        {
            $categories = PForumCategories::model()->top()->findAll();

        } else {

            
            $categories = PForumCategories::model()->findAll("parent_id = :pid", array(":pid"=>$_GET['cat']));
            
              $page = 1;
        
            $criteria = new CDbCriteria();
            $criteria->condition = "category_id = :tid";
            $criteria->params = array(":tid"=>$_GET['cat']);        

            $count = PForumThemes::model()->count($criteria);
            $pages = ceil($count/$this->limit);

            if (isset($_GET['page']))
            {
                $page = $_GET['page'];
            }            
            
            $criteria->limit = $this->limit;
            $criteria->offset = $this->limit*($page-1);  
            $criteria->with = array(
                "lastPost"=>array(
                    "select"=>array("p.author_id","p.ts","p.theme_id"),
                    "alias"=>"p",                    
                )
            );
            $criteria->order = "p.ts DESC";       
           
            
            $themes = PForumThemes::model()->with()->findAll($criteria);
            
            
            
        }

        
        $pitems = array(
            "add"
        );
        
        $this->render("index", array(
                "categories"=>$categories,
                "pitems" => $pitems,
                "themes" => $themes,
                "page"=>$page,
        ));
        
    }

    public function actionView()
    {
        $_SESSION['lastUrl'] = Yii::app()->request->url;
        if (!isset($_GET['eid']))
        {

            Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/index/".(isset($_GET['cat']) ? "cat/".$_GET['cat'] : ""));

        }

        if (!empty(Yii::app()->user->id)) {
            if (PForumThemeViews::model()->exists(
                    "theme_id = :tid AND user_id = :uid",
                    array(
                        ":tid"=>$_GET['eid'],
                        ":uid"=>Yii::app()->user->id
                    )
            )) {
                PForumThemeViews::model()->updateAll(array(
                    "ts"=>time()
                ), "theme_id = :tid AND user_id = :uid",
                    array(
                        ":tid"=>$_GET['eid'],
                        ":uid"=>Yii::app()->user->id
                    ));
            } else {
                $view = new PForumThemeViews();
                $view->user_id = Yii::app()->user->id;
                $view->theme_id = $_GET['eid'];
                $view->save();
            }
        }
        
        $page = 1;
        
        $criteria = new CDbCriteria();
        $criteria->condition = "theme_id = :tid";
        $criteria->params = array(":tid"=>$_GET['eid']);        
        
        $count = PForumPosts::model()->count($criteria);
        $pages = ceil($count/$this->limit);
        
        if (isset($_GET['page']))
        {
            $page = $_GET['page'];
        }
        
        if (isset($_POST['request']) AND $_POST['request'] == "getForumPosts")
        {
            if ($page == $pages) {
                $this->pollMessage();
            } else {
                die();
            }
        }
            
        $theme = PForumThemes::model()->findByPk($_GET['eid']);               
        
        $criteria->limit = $this->limit;
        $criteria->offset = $this->limit*($page-1);
                
        $posts = PForumPosts::model()->findAll($criteria);      
        
        
        Yii::app()->breadCrumbs->addLink($theme->name);

        $post = new PForumPosts();
        if (isset($_POST['PForumPosts']))
        {
            
            $post->post = $_POST['PForumPosts']['post'];
            $post->theme_id = $_GET['eid'];
            
            if (($theme->state == '1' OR $this->checkAccess("addPostAdmin")) AND $post->validate() AND $post->save())
            {
                
                $criteria = new CDbCriteria();
                $criteria->condition = "theme_id = :tid";
                $criteria->params = array(":tid"=>$_GET['eid']);        

                $count = PForumPosts::model()->count($criteria);
                $pages = ceil($count/$this->limit);
                
                Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/view/eid/".$theme->id.($pages>1 ? "/page/".$pages : "")."#post".$post->id);
            } 

        }

        $pitems = array(
            "close"
        );

        $this->render("view", array(
                "pitems" => $pitems,
                "theme" => $theme,
                "posts"=>$posts,
                "post"=>$post,
                "page"=>$page,
        ));
    }
    
    public function actionAddCategory()
    {        
        
        Yii::app()->breadCrumbs->addLink(t("Добавление категории"));
        
        if (isset($_GET['eid']))
        {
            $category = PForumCategories::model()->findByPk($_GET['eid']);
        } else {
            $category = new PForumCategories();
        }
        
        if (isset($_GET['cat']))
        {
            $category->parent_id = $_GET['cat'];
        }
        
        if (isset($_POST['PForumCategories']))
        {
            $category->attributes = $_POST['PForumCategories'];
            
            if (!isset($_POST['PForumCategories']['can_add_themes']))
            {
                $category->can_add_themes = 0;
            }
            
            if ($category->validate() AND $category->save())
            {
                if (isset($_GET['eid'])) {
                    Yii::app()->user->setFlash('fieldSubmitted', t('Запись успешно обновлена'));
                } else {
                    Yii::app()->user->setFlash('fieldSubmitted', t('Запись успешно добавлена'));
                    Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/index/".(isset($_GET['cat']) ? "cat/".$_GET['cat'] : ""));
                }
            }
            
        }
        
        $this->render("addCategory",array(
            "category"=>$category
        ));
    }

    public function actionDeleteCategory()
    {
        if (isset($_GET['eid']))
        {
            if (PForumCategories::model()->deleteByPk($_GET['eid']))
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Запись успешно удалена'));
                Yii::app()->request->redirect(Yii::app()->request->urlReferrer);
            }
        }
    }

    public function actionDeleteTheme()
    {
        if (isset($_GET['eid']))
        {
            if (PForumThemes::model()->updateByPk($_GET['eid'], array(
                "state"=>"3"
            )))
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Запись успешно удалена'));
                Yii::app()->request->redirect(Yii::app()->request->urlReferrer);
            }
        }
    }
    
    public function actionDeletePost()
    {
        if (isset($_GET['eid']))
        {
            if (PForumPosts::model()->updateByPk($_GET['eid'], array(
                "state"=>"3"
            )))
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Запись успешно удалена'));
                Yii::app()->request->redirect(Yii::app()->request->urlReferrer);
            }
        }
    }

    public function actionAddTheme()
    {

        if (isset($_GET['eid']))
        {
            Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/editTheme/eid/".$_GET['eid']);
        }

        Yii::app()->breadCrumbs->addLink(t("Добавление темы"));

        $theme = new PForumThemes();

        if (isset($_GET['cat']))
        {
            $theme->category_id = $_GET['cat'];
        } else {
            Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/index");
        }
        
        if (!PForumCategories::model()->findByPk($_GET['cat'])->can_add_themes == 1 AND !$this->checkAccess("addThemeAdmin"))
        {
            Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/index");
        }

        if (isset($_POST['PForumThemes']))
        {
            $theme->name = $_POST['PForumThemes']['name'];    
            $theme->post = $_POST['PForumThemes']['post'];

            if ($theme->validate() AND $theme->save())
            {
                //Yii::app()->user->setFlash('fieldSubmitted', t('Тема успешно добавлена'));
                Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/view/eid/".$theme->id);
            }

        }

        $this->render("addTheme",array(
            "theme"=>$theme
        ));
    }
    
    public function actionEditTheme()
    {
        if (isset($_GET['eid']))
        {
            $theme = PForumThemes::model()->findByPk($_GET['eid']);
        } else {
            Yii::app()->request->redirect(Yii::app()->request->urlReferrer);
        }

        Yii::app()->breadCrumbs->addLink(t("Добавление темы"));

        if (isset($_POST['PForumThemes']))
        {
            $theme->attributes = $_POST['PForumThemes'];            

            if ($theme->validate() AND $theme->save())
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Тема успешно обновлена'));                
            }

        }

        $this->render("editTheme",array(
            "theme"=>$theme
        ));
    }

    public function actionEditPost()
    {

        if (isset($_GET['eid']))
        {
            $post = PForumPosts::model()->findByPk($_GET['eid']);
        } else {
            Yii::app()->request->redirect(Yii::app()->request->urlReferrer);
        }

        if (!$this->checkAccess("editPost",$post))
        {
            Yii::app()->request->redirect(Yii::app()->request->urlReferrer);
        }

        if (isset($_POST['PForumPosts']))
        {
            $post->post = $_POST['PForumPosts']['post'];
            $post->last_time_edited = time();

            if ($post->validate() AND $post->save())
            {
                Yii::app()->request->redirect($_SESSION['lastUrl']."#post".$post->id);
            }            

        }

        $this->render("post_form",array(
            "post"=>$post
        ));
    }
    
    public function actionCloseTheme()
    {
        if (isset($_GET['eid']))
        {
            if (PForumThemes::model()->resetScope()->updateByPk($_GET['eid'], array(
                "state"=>"2"
            )))
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Тема успешно закрыта'));
                Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/view/eid/".$_GET['eid']);
            }
            Yii::app()->request->redirect("/".Yii::app()->language."/".Yii::app()->controller->id."/view/eid/".$_GET['eid']);
        }
    }

    public function getViewPath()
    {
        return Yii::getPathOfAlias("forum.views");
    }
    
    public function checkAccess($action, $item = null)
    {
        $actions = array(
            Users::ROLE_ADMIN => array("index","addCategory","deleteCategory","addTheme","addThemeAdmin","editTheme","deleteTheme","addPost","addPostAdmin","editPost","deletePost","closeTheme"),
            Users::ROLE_ROOT => array("index","addCategory","deleteCategory","addTheme","addThemeAdmin","editTheme","deleteTheme","addPost","addPostAdmin","editPost","deletePost","closeTheme"),
            Users::ROLE_USER => array("index","addTheme","addPost","editPost")
        );

        if (!empty(Yii::app()->user->role)) {
            if (in_array($action,$actions[Yii::app()->user->role]))
            {

                if ($action == "editPost")
                {
                    if ($item->author_id == Yii::app()->user->id OR Yii::app()->user->role == Users::ROLE_ADMIN OR Yii::app()->user->role == Users::ROLE_ROOT)
                    {
                        return true;
                    } else {
                        return false;
                    }
                }
                
                return true;
            }
        }
        return false;
        
    }
    
    public function filters()
	{
            return array(
                'accessControl',
            );
	}

	public function accessRules()
	{
                return array(
			// allow all for root and admin                        
                        array(
				'allow',
				'actions'=>array('index',"view"),
				'users'=>array('*'),
			),
                        array(
                                'allow',
                                'actions'=>array('addTheme','editPost'),
                                'users'=>array('@'),
                        ),
                        array(
                                'allow',
                                'actions'=>array('addCategory','deleteCategory','editTheme','deleteTheme','deletePost','closeTheme'),
                                'roles'=>array(Users::ROLE_ADMIN,Users::ROLE_ROOT)
                        ),
                        array(
                            'deny',
                            'users'=>array('*')
                        )
                    );
	}
        
        private function addbreadCrumbs($cat)
        {
            
            if (!empty($cat->parent))
            {
                $this->addbreadCrumbs($cat->parent);
            }
            
            if ($cat->type!=2) {
                Yii::app()->breadCrumbs->addLink($cat->name,"/".Yii::app()->language."/".Yii::app()->controller->id."/index/cat/".$cat->id);
            } else {
                Yii::app()->breadCrumbs->addLink($cat->name." / ");
            }
        }
        
        private function pollMessage()
        {
           
          $posts = PForumPosts::model()->findAll("theme_id = :tid AND id > :id", array(
                ":tid"=>$_GET['eid'],
                ":id"=>$_SESSION['theme_'.$_GET['eid']]['last_post']
            ));
          
            if (!empty($posts))
            {
                foreach ($posts as $post) {
                    $this->renderPartial("post", array(
                        "post"=>$post
                    ));
                }
            }
            die();
        }

    
}

?>
