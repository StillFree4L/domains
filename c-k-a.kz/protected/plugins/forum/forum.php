<?php

class forum extends CWidget
{

    public function run()
    {

        if (isset($_GET['act']))
        {
            
        }

        if (!isset($_GET['cat']))
        {
            $categories = PForumCategories::model()->top()->findAll();
            $template = "top";
        } else {
            $categories = PForumCategories::model()->findAll("parent_id = :pid", array(":pid"=>$_GET['cat']));
            $template = "inner";
        }
        
        $this->render($template, array(
                "categories"=>$categories
        ));
        

        
    }
    
    public function hasAccess($action)
    {
        if (Yii::app()->user->role == "admin")
        {
            return true;
        }
        return false;
    }
    
}

?>
