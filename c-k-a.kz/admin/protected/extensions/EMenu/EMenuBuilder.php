<?php

class EMenuBuilder extends CWidget
{
    public $templates = array("groups","menu","categories","pages");
    public $group_id = "";
    public function run()
    {

        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery_ui.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/menuBuilder.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery_ui.css');
        Yii::app()->clientScript->registerCssFile($baseUrl . '/menu.css');

            if (isset($_POST['updateMenuCaption']))
            {
                if (empty($_POST['menuCaption']) OR empty($_POST['updateMenuCaption'])) die("0");
                $caption = $_POST['menuCaption'];
                $m_id = $_POST['updateMenuCaption'];

                
                if (Menu::model()->updateByPk($m_id, array(
                    "label"=>$caption
                ))) {
                    die("1");
                } else {
                    die("0");
                }

            }

        // POST AND GET
            if (isset($_GET['menu_group_id']) AND !empty($_GET['menu_group_id']))
            {
                $this->group_id = $_GET['menu_group_id'];
                $group = MenuGroups::model()->findByPk($this->group_id);
            } else {
                $group = new MenuGroups;
            }

            if (isset($_GET['delete_group']) AND !empty($_GET['delete_group']))
            {
                $group = MenuGroups::model()->findByPk($_GET['delete_group']);
                $group->delete();
                Yii::app()->request->redirect( '/admin/'.Yii::app()->language.'/'.Yii::app ()->controller->id.'/' );
            }

            if (isset($_POST['MenuGroups']))
            {

                $group->attributes = $_POST['MenuGroups'];

                if ($group->validate() AND $group->save())
                {
                    Yii::app()->request->redirect( '/admin/'.Yii::app()->language.'/'.Yii::app ()->controller->id.'/' );
                }
            }
            if (isset($_POST['addInstance']) AND isset($_GET['menu_group_id']))
            {
                foreach ($_POST['addInstance'] as $instance)
                {
                    $i = new Menu();
                    $i ->refreshMetaData();
                    $i -> position = 9999;
                    $i -> instance_id = $instance;
                    $i -> group_id = $_GET['menu_group_id'];

                    $i -> save();
                    
                }
                Yii::app()->request->redirect( Yii::app()->request->url );
            }
            if (isset($_POST['saveMenu']) AND isset($_GET['menu_group_id']))
            {

                Menu::model()->deleteAll("group_id = :gid", array(":gid"=>$_GET['menu_group_id']));
                if (isset($_POST['menuItems'])) {
                    Menu::model()->saveMenuRecursive($_POST['menuItems'],$_GET['menu_group_id']);
                } else {
                    Menu::model()->deleteAll("group_id = :gid", array(":gid"=>$_GET['menu_group_id']));
                }
                die();
            }
        // -----------------

        $menu = array();
        if (!empty($this->group_id))
        {
            $menu = Menu::model()->byGroup($this->group_id)->top()->findAll();
        }

        $categories = Instances::model()->categories()->findAll();
        $pages = Instances::model()->pages()->findAll();

        $this->render("main", array(
            "group"=>$group,
            "menu"=>$menu,
            "categories"=>$categories,
            "pages"=>$pages
        ));

    }
}

?>
