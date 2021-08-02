<?php

class Pagination extends CWidget
{
    var $model = null;
    var $perPage;
    var $page;
    var $criteria = array();
    var $url = "";
    var $show_pages = "10";
    public function run()
    {
        
        if ($this->model == null) return;

        $count = $this->model->count($this->criteria);
        $pages = ceil($count/$this->perPage);

        $this->render("index", array(
            "pages"=>$pages,
            "page"=>$this->page
        ));
    }
}

?>
