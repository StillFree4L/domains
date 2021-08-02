
<div class="well">
    <?php
        
        $groups = MenuGroups::model()->findAll();        

        $data = array();
        $data[Yii::app()->params['adminUrl'].Yii::app()->language.'/'.Yii::app ()->controller->id."/index/"] = t("Выберите меню");
        if ($groups)
        {
            foreach ($groups as $k=>$g)
            {
                $data[Yii::app()->params['adminUrl'].Yii::app()->language.'/'.Yii::app ()->controller->id."/index/menu_group_id/".$g->id] = $g->caption;
            }
        }

        
        echo CHtml::dropDownList("menu_group",Yii::app()->params['adminUrl'].Yii::app()->language.'/'.Yii::app ()->controller->id."/index/menu_group_id/".$this->group_id,$data, array("style"=>"margin-bottom:0"));

    ?>

    <div class="btn-toolbar inline-toolbar">

        <?php

        $disabled = "disabled";
        if (!empty($this->group_id) AND $group->is_deletable)
        {
            $disabled = "";
        }

        $this->widget('bootstrap.widgets.TbButton', array(
            'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'icon'=>"plus white",
            "htmlOptions"=>array(
                "title"=>t("Добавить меню"),
                'data-toggle'=>'modal',
                'data-target'=>'#menuGroupModal',
                'onclick'=>'js:$("#horizontalForm input").each(function() {$(this).attr("value","");});'
            )
        ));

        $this->widget('bootstrap.widgets.TbButton', array(
            'type'=>'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'icon'=>"pencil white",
            "htmlOptions"=>array(
                "class"=>$disabled,
                'data-toggle'=>'modal',
                'data-target'=>'#menuGroupModal',
                "title"=>t("Редактировать меню")
            )
        ));

        $this->widget('bootstrap.widgets.TbButton', array(
            'type'=>'danger', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'icon'=>"remove white",
            "htmlOptions"=>array(
                "class"=>$disabled,
                "title"=>t("Удалить меню"),
                "onclick"=>"js:
                    if (confirm('".t("Вы уверены?")."')) {
                            window.location.href='/admin/".Yii::app()->language.'/'.Yii::app ()->controller->id."/index/delete_group/".$group->id."';
                        }
                    "
            )
        ));

        ?>

    </div>

    <?php $this->beginWidget('bootstrap.widgets.TbModal', array(
            'id'=>'menuGroupModal',
            "htmlOptions"=>array("style"=>"display:none"),
        )); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?=t('Добавить/Редактировать меню')?></h4>
    </div>

    <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'horizontalForm',
            'type'=>'horizontal',
            'htmlOptions'=>array(
                "style"=>"margin-bottom:0",
                "name"=>"editGroupForm"
            ),
        ));
    ?>
    <div class="modal-body">


        <?php
            echo CHtml::hiddenField("menu_group_id", $group->id);
            echo $form->textFieldRow($group, 'uniq_name', array("class"=>"span3"));
            echo $form->textFieldRow($group, 'caption', array("class"=>"span3"));

        ?>
    
    </div>

    <div class="modal-footer">
        <?php echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Сохранить'), array('class'=>'btn btn-primary', 'type'=>'submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>t('Закрыть'),
            'url'=>'#',
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
    </div>
    <?php $this->endWidget(); ?>
    <?php $this->endWidget(); ?>


</div>