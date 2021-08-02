<div class="library_main">

    <div class="library_bg bg"></div>
    <div class="library_content cc">
        
    <?php 
    
    
    
    $l_items[] = array("label"=>t("Все"), "url"=>$this->cUrl("l",null));
    $active = t('Библиотека');

    foreach ($libraries as $k=>$v)
    {
        if (isset($_GET['l']) AND $k==$_GET['l']) $active = str_replace("\"","",$v->library_name);       
        $l_items[] = array("label"=>  str_replace("\"","",$v->library_name), "url"=>$this->cUrl("l",$k));
    }
    
    $this->widget('bootstrap.widgets.TbNavbar', array(
    'brand'=>false,
    "fixed" => false,
    'collapse'=>true, // requires bootstrap-responsive.css
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(     
                array("url"=>"/".Yii::app()->language."/virtualLibrary", "itemOptions"=>array("class"=>"library_home")),
                array('label'=>$active, 'items'=>$l_items),
            ),
        ),
        '<form method="post" class="navbar-search pull-right" action="/'.Yii::app()->language.'/'.$this->id.'/search"><input type="text" name="search_in_library" class="search-query span3" placeholder="'.t("Введите ключевые слова").'"></form>',
        
    ),
)); ?>