<?php

namespace app\widgets\ELister;
use yii\widgets\ListView;

// TODO REWORK

class ELister extends ListView
{
    public $pager = [
        "class"=>'\app\widgets\EPager\EPager',
        'firstPageLabel'=>'<<',
        'prevPageLabel'=>'<',
        'nextPageLabel'=>'>',
        'lastPageLabel'=>'>>',
        'maxButtonCount'=>'6',
        "header"=>"",
    ];
    public $pagerCssClass = "EPager";
}
?>