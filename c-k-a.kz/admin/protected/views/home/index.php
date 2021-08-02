<?php if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

if (Yii::app()->user->hasFlash('fieldError')) {
    Yii::app()->user->setFlash('error', Yii::app()->user->getFlash('fieldError'));
    $this->widget('bootstrap.widgets.TbAlert');
} ?>
<div class="update_bock well well-small">
    
    <span class="version"><?=t("Версия ЦМС")?>: <span class="version_value"><?=$version['version']?></span></span>
    
    <?php
    
    if (count($version['updates'])>0)
    {
        ?>
            <span class="version has_update"><?=t("Доступно обновлений").": "?><span class="ucount"><?=count($version['updates'])?></span></span>
        <?php
    }
    
    ?>
            
    <a class="btn btn-primary btn-small pull-right" href="/admin/<?=Yii::app()->language?>/<?=Yii::app()->controller->id."/update"?>"><?=t("Обновить ЦМС")?></a>
    
</div>


<div class="online_block well well-small">
    <span class="block_header"><?=t("Посещения за месяц")?></span>
    <?php 
    
        $path = dirname(__FILE__);
    
        $this->widget("ext.EGraphs.EGraphs", array(
            "type"=>"line",
            "graph_uniq_name"=>"monthly",
            "data"=>$month_graph,
            "settings"=>$this->getSettings(),
            "file"=>false,
            "width"=>"100%",
            "height"=>"400",
        ));
        
        ?>
    
</div>

<div class="online_block well well-small">
    <span class="block_header"><?=t("Посещения за год")?></span>
    <?php
        
        $this->widget("ext.EGraphs.EGraphs", array(
            "type"=>"line",
            "graph_uniq_name"=>"yearly",
            "data"=>$year_graph,
            "settings"=>$this->getSettings(),
            "file"=>false,
            "width"=>"100%",
            "height"=>"400",
        ))
    
    ?>
    
</div>