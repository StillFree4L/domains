<?php
$_SESSION['theme_'.$_GET['eid']]['last_post'] = $post->id;
?>
<div class="theme_post">
    <a name="post<?=$post->id?>"></a>
    <div class="post_stat">

        <span class="post_author text_color">
            <?=$post->author->fio?>
        </span>

        <span class="post_messages">
            <?=t("Сообщений")?>: <span class="post_ccount"><?=$post->author->posts?></span>
        </span>
        
        <?php
            if ($post->author->author_type == "administrator")
            {
                ?><span class="post_admin"><?=t("Администратор")?></span><?php
            }
        ?>
                
        <?php
        $this->renderPartial("panel_items/edit_delete", array(
            "item"=>$post,
            "edit"=>"editPost",
            "delete"=>"deletePost",
        ));
        ?>

    </div>

    <div class="post_message">
        <div class="post_stat_inner">
            <div class="post_body post_<?=$post->state!=3 ? $post->author->author_type : "deleted"?>">
                <span class="post_date"><?=date('d.m.Y G:i',$post->ts)?></span>
                <a class="post_quote"><?=t("Цитировать")?></a>
                <div class="post_text">
                    <?=$post->state!=3 ? $post->post : t("Удалено")?>
                </div>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>