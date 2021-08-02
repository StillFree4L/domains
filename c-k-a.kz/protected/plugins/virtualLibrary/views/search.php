<?php
$this->renderPartial("header", array(
    "libraries"=>$libraries
));

?>

<div class="bookset">
    
    <div class="bookset_bg bg"></div>
    <div class="bookset_content cc">
        
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
            
        <div style="clear:both"></div>
        
    </div>
    
</div>



<?php

$this->renderPartial("footer");
?>