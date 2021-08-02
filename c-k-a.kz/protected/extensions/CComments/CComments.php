<?php

class CComments extends CWidget
{

    var $model = null;
    var $canComment = true;
    public function run()
    {

        if ($this->model == null) return;
        if (!$this->model->is_c) return;
        
        $comments = Comments::model()->ByInstance($this->model->id)->findAll();

        $comment = new Comments();
        if (isset($_POST['Comments']))
        {
            $comment->attributes = $_POST['Comments'];
            $comment->instance_id = $this->model->id;

            if ($comment->validate() AND $comment->save())
            {
                Yii::app()->user->setFlash('fieldSubmitted', t('Ваш комментарий отправлен на проверку'));
                Yii::app()->request->redirect(Yii::app()->createUrl(Yii::app()->request->url,array("#"=>"comments")));
            }

            

        }        

        $this->render("index", array(
            "comments"=>$comments,
            "comment"=>$comment,
        ));

    }
}

?>
