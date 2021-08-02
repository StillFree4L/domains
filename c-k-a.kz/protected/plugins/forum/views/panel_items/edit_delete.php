<?php

if ($this->checkAccess("$edit", $item))
{
    
    ?>
    <a style="margin-left:15px;" class="update" title="Редактировать" rel="tooltip" href="<?="/".Yii::app()->language."/".Yii::app()->controller->id."/$edit/eid/".$item->id?>"><i class="icon-pencil"></i></a>
    <?php          
}
if ($this->checkAccess("$delete"))
{
    ?>
    <a onclick='js:if(confirm("<?=t("Вы уверены?")?>")) { return true; } return false;' class="delete" title="Удалить" rel="tooltip" href="<?="/".Yii::app()->language."/".Yii::app()->controller->id."/$delete/eid/".$item->id?>"><i class="icon-trash"></i></a></td>
    <?php
}

?>
