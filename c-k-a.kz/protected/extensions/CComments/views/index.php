<a name="comments"></a>

<?php

$assets = dirname(__FILE__).'/../assets';
$baseUrl = Yii::app()->assetManager->publish($assets,false,-1,true);
Yii::app()->clientScript->registerCssFile($baseUrl . '/comments.css');

if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('warning', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

?>

<div class="commentsHeader"><?=t("Комментарии")?></div>
<div class="commentslist">

    <?php
        foreach ($comments as $ccomment)
        {
            ?>

                <div class="comment">
                    <a name="comment<?=$ccomment->id?>"></a>
                    <span class="date system_font_color"><?=date("d.m.Y",$ccomment->ts)?></span><span class="user"><?=$ccomment->name?></span>

                    <div class="comment_text">
                        <?=$ccomment->comment?>
                    </div>

                </div>

            <?php
        }
    ?>

</div>

<?php
if ($this->canComment) {
    $this->render("comment_form", array(
        "comment"=>$comment
    ));
}



?>
