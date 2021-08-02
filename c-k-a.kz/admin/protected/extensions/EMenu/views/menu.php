

<div class="span5 well well-small" style="min-height:50px; margin-left:0;">

    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>t("Сохранить меню"),
                'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'icon'=>"ok white",
                'size'=>'small',
                "htmlOptions"=>array(
                    'onclick'=>'js:saveMenu()'
                )
            ));
    ?>

    <div class="menu_body instance_child_container " style="margin-top:10px; border-top:1px solid #bbb; padding-top:10px;">

    <?php

    function displayMenu($menu)
    {
        if (!empty($menu)) {
            foreach ($menu as $m)
            {

                ?>
                <div class="instance_container" id="mid_<?=$m->instance_id?>">
                    <div class="instance_item" >
                        <span m_id="<?=$m->id?>" class="instance_caption"><?=$m->instance->caption?></span>
                        <span class="instance_type"><?=$m->instance->typeCaption?><span class="caret"></span></span>
                        <a class="close">&times;</a>
                    </div>
                    <div class="instance_child_container" id="cc_<?=$m->instance_id?>">
                        <?php displayMenu($m->childs); ?>
                    </div>
                </div>
                <?

            }
        }
    }

    displayMenu($menu);
    ?>
    </div>

</div>

<div class="menu_candidates span" style="width:268px; margin-left:20px;">

    <?php

        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id'=>'addInstancesInMenu',
            'type'=>'horizontal',
            'htmlOptions'=>array(
                "style"=>"margin-bottom:0",
                "name"=>"addInstancesInMenu"
            ),
        ));

        if (in_array("categories", $this->templates))
        {
            $this->render("categories", array(
                "menu"=>$menu,
                "group"=>$group,
                "categories"=>$categories,
                "form"=>$form
            ));
        }

        if (in_array("pages", $this->templates))
        {
            $this->render("pages", array(
                "menu"=>$menu,
                "group"=>$group,
                "pages"=>$pages,
                "form"=>$form
            ));
        }

        if (in_array("pages", $this->templates) OR in_array("categories", $this->templates))
        {
         
           echo CHtml::htmlButton('<i class="icon-ok icon-white"></i> '.t('Добавить в меню'), array('class'=>'btn btn-primary pull-right btn-small', 'type'=>'submit'));

        }

        $this->endWidget();

    ?>
    
</div>