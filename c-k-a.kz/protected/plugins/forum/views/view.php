
<?php
$this->renderPartial("panel", array(
    "pitems" => $pitems,
));
?>

<?php

if (Yii::app()->user->hasFlash('fieldSubmitted')) {
    Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('fieldSubmitted'));
    $this->widget('bootstrap.widgets.TbAlert');
}

?>

<div class="theme_view">

    <span class="theme_name text_color">
        <?=$theme->name?>
    </span>

    <script language="javascript">
         (function poll(){
            $.ajax({ url: window.location.href,
                type:"POST",
                crossDomain:true,
                data:
                    {
                      request:"getForumPosts"  
                    },
                success: function(data){
                //Update your dashboard gauge
                $("div.forum_posts").append(data);

            }, dataType: "text", complete: $("body").everyTime(5000,poll), timeout: 30000 });
        })();
   </script>
    
   <div class="forum_posts">
    <?php
    if (!empty($posts)) {
        foreach ($posts as $p)
        {
            $this->renderPartial("post", array(
                "post"=>$p
            ));
            
        }
    }
    ?>
   </div>
   
   <div class="forum_pager">
       <?php
       $this->widget("application.extensions.Pagination.Pagination", array(
           "model"=>PForumPosts::model(),
           "criteria"=>array("condition"=>"theme_id = :tid","params"=>array(":tid"=>$_GET['eid'])),
           "perPage"=>$this->limit,
           "page"=>$page,
           "url"=>"/".Yii::app()->language."/".Yii::app()->controller->id."/view/eid/".$_GET['eid']
       ))
       ?>
   </div>
   
   <div class="forum_online">
       <span class="forum_online_header"><?=t("Просматривают тему:")?></span>
       <?php
        $online = PForumThemeViews::model()->with(array(
            "user"=>array(
                "alias"=>"u",
            )
        ))->findAll("theme_id = :tid AND t.ts>:ts", array(
            ":tid"=>$theme->id,
            ":ts"=>time()-300,
        ));
        
        if (!empty($online))
        {
            foreach ($online as $o)
            {
                ?>
                    <span class="forum_online_user"><?=$o->user->fio?></span>
                <?php
            }
        }            
       ?>
   </div>

    <?php
        $this->renderPartial("post_form", array(
            "post"=>$post,
            "theme"=>$theme,
        ));
    ?>

</div>