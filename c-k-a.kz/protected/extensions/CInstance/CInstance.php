<?php

class CInstance extends CWidget
{
    var $model = null;    
    var $blocks = array(
        "caption",
        "ts",
        "preview",
        "body",
        "comments"
    );
    var $limit = null;
    var $page = 1;
    var $template = null;

    public function run()
    {

        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/cinstance.css', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/cinstance.js', CClientScript::POS_HEAD);
        
        if ($this->model == null) return;

        $childs = $this->model->menu->childs;

        $records = array();        

        if (isset($_GET['page']))
        {
            $this->page = $_GET['page'];
        }

        if ($this->model->type==1 OR $this->model->type==4) {

            $criteria = new CDbCriteria();
            $criteria->condition = "p_id=".$this->model->id;

            if (intval($this->limit)>0)
            {
                $criteria->limit = $this->limit;                
            } else {
                $criteria->limit = Yii::app()->baseOptions->pageSize;
            }

            $criteria->offset = $criteria->limit*(intval($this->page)-1);

            $records = InstanceRelations::model()->with(array('instance'=>array(
            'joinType'=>'INNER JOIN')))->findAll($criteria);

            $pagerModel = InstanceRelations::model()->with(array('instance'=>array(
            'joinType'=>'INNER JOIN')));
            
        }

        if ($this->template == null) {
        $view = $this->model->label;        
        if ($this->getViewFile($view)===false) {
            $view = "index";
        }
        } else $view = $this->template;

        $this->render($view, array(
            "model"=>$this->model,
            "records"=>$records,
            "pagerModel"=>$pagerModel,
            "childs"=>$childs,
        ));

    }

    
}

?>
