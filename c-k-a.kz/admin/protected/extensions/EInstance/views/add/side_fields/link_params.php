<?php

echo $form->radioButtonListRow($this->model, 'target', array(
    "_none" => t('В том же окне'),
    "_top" => t('Текущее окно или вкладка, без фреймов.'),
    "_blank" => t('Новое окно или вкладка')
));

?>
