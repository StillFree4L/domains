<?php
$this->renderPartial("header", array(
    "libraries"=>$libraries
));

?>

<div class="bookset">
    
    <div class="bookset_bg bg"></div>
    <div class="bookset_content cc">
        
        <div class="charset span">
            <div class="char">
                <a href="<?=$this->cUrl("c",null)?>"><?=t("Все")?></a>
            </div>    
            <?php
            if (!empty($chars)) {
                foreach ($chars as $k=>$v) {
                    ?>
                        <div class="char">
                            <a href="<?=$this->cUrl("c",$v->book_name)?>"><?=$v->book_name?></a>
                        </div>
                    <?php
                }
            }
            ?>

            

        </div>
        
        <div class="bookset_inner">
            
            <div class="books">
                
                <?php
                
                if (!empty($books)) {
                    
                    foreach ($books as $book)
                    {
                        ?>
                
                <div class="book">
                    
                    <a href="<?="/".Yii::app()->language."/".$this->id."/view/book/".$book->id?>" class="book_name stl"><?=$book->book_name?></a>
                    

                    
                    <div class="book_info">
                        
                        <span class="book_info_b">
                            <span class="book_info_h"><?=t("Язык").": "?></span>
                            <span class="book_info_v text_color"><?=$book->book_lang?></span>
                        </span>
                        
                        <span class="book_info_b">
                            <span class="book_info_h"><?=t("Вид").": "?></span>
                            <span class="book_info_v text_color"><?=$book->pub_view?></span>
                        </span>
                        
                        <span class="book_info_b">
                            <span class="book_info_h"><?=t("Издатель").": "?></span>
                            <span class="book_info_v text_color"><?=$book->pub_name?></span>
                        </span>
                        
                        <span class="book_info_b">
                            <span class="book_info_h"><?=t("Город").": "?></span>
                            <span class="book_info_v text_color"><?=$book->pub_city?></span>
                        </span>
                        
                        <span class="book_info_b">
                            <span class="book_info_h"><?=t("Авторы").": "?></span>
                            <span class="book_info_v text_color"><?=$book->author_name?></span>
                        </span>
                        <span class="book_info_b">
                            <span class="book_info_h"><?=t("Год").": "?></span>
                            <span class="book_info_v text_color"><?=$book->book_year." ".t("г.")?></span>
                        </span>
                        
                        
                    </div>
                    
                </div>
                
                        <?php
                    }
                    
                }
                
                ?>
                
            </div>
            
            <div class="bookset_pager">
                    <?php
                    $this->widget("application.extensions.Pagination.Pagination", array(
                         "model"=>  PVirtualLibrary::model(),
                         "criteria"=>$pager_criteria,
                         "perPage"=>$this->limit,
                         "page"=>$page,
                         "url"=>$this->cUrl()
                     ));
                    ?>
             </div>
            
        </div>
        
        <div style="clear:both"></div>
        
    </div>
    
</div>



<?php

$this->renderPartial("footer");
?>