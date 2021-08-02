
<?php

    Yii::app()->user->setFlash('error', $error);
    $this->widget('bootstrap.widgets.TbAlert');

?>