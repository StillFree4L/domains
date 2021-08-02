<?php
$this->renderPartial("header", array(
    "libraries"=>$libraries
));

?>

<div class="bookset">
    
    <div class="bookset_bg bg"></div>
    <div class="bookset_content cc">
        
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
                    'data'=>$book,
                    'attributes'=>array(
                        array('name'=>'book_name', 'label'=>t('Название книги')),
                        array('name'=>'book_year', 'label'=>t('Год выпуска')),
                        array('name'=>'book_lang', 'label'=>t('Язык')),
                        array('name'=>'author_name', 'label'=>t('Авторы')),
                        array('name'=>'pub_view', 'label'=>t('Вид')),
                        array('name'=>'pub_code', 'label'=>t('Код издателя')),
                        array('name'=>'pub_name', 'label'=>t('Издатель')),
                        array('name'=>'pub_dep', 'label'=>t('Раздел')),
                        array('name'=>'pub_city', 'label'=>t('Город')),                        
                        array('name'=>'library_name', 'label'=>t('Библиотека')),
                        array('name'=>'book_preview', 'label'=>t('Краткое содержание')),
                        
                    ),
                )); ?>
        
        <div style="clear:both"></div>
        
    </div>
    
</div>



<?php

$this->renderPartial("footer");
?>