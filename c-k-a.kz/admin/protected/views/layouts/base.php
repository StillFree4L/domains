<?php $this->beginContent('//layouts/main'); ?>
<div id="page">

        <div id="left_content">

            <div class="bs-docs-sidebar">
                <?php
                    $controllers = Lists::getControllerList();
                ?>
                <ul class="nav nav-list bs-docs-sidenav affix-top">
                    
                    <?php
                    $version = include(Yii::getPathOfAlias("application.config")."/version.php"); 
                    ?>
                    
                    <li><a href="/admin/<?=Yii::app()->language?>/home"><i class="icon-chevron-right"></i><?=$controllers['home'].(count($version['updates'])>0 ? " <b>+".count($version['updates'])."</b>" : "")?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/categories"><i class="icon-chevron-right"></i><?=$controllers['categories']?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/records"><i class="icon-chevron-right"></i><?=$controllers['records']?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/pages"><i class="icon-chevron-right"></i><?=$controllers['pages']?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/links"><i class="icon-chevron-right"></i><?=$controllers['links']?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/users"><i class="icon-chevron-right"></i><?=$controllers['users']?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/translate"><i class="icon-chevron-right"></i><?=$controllers['translate']?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/menu"><i class="icon-chevron-right"></i><?=$controllers['menu']?></a></li>
                    <?php $ccount = Comments::model()->resetScope()->nonApproved()->count(); ?>
                    <li><a href="/admin/<?=Yii::app()->language?>/comments"><i class="icon-chevron-right"></i><?=$controllers['comments'].($ccount>0 ? " <b>+".$ccount."</b>" : "")?></a></li>
                    <li><a href="/admin/<?=Yii::app()->language?>/options"><i class="icon-chevron-right"></i><?=$controllers['options']?></a></li>


                    <?php // Plugins

                    $plugins = Plugins::model()->findAll();
                    if (!empty($plugins))
                    {
                        foreach ($plugins as $plugin)
                        {
                            $path = Yii::getPathOfAlias("frontend.plugins.".$plugin->uniq_name);

                            if (file_exists($path."/".$plugin->uniq_name."_menu.php"))
                            {
                                $this->widget("frontend.plugins.".$plugin->uniq_name.".".$plugin->uniq_name."_menu", array());
                            }
                        }
                    }
                    ?>

                </ul>
            </div>

        </div>

    <div id="wrapper">

	<div id="header">

            <h1 class="cp_header"><?=t("Панель управления сайтом")?></h1>

            <div class="span pull-right">
            <?php
                $this->widget('ext.LanguagePicker.ELanguagePicker', array());
            ?>
            </div>

	</div>
        <!-- header -->

        <div id="body">

            <?php $this->widget('frontend.extensions.bootstrap.widgets.TbBreadcrumbs', array(
                'homeLink'=>"<a href='/admin/home'>".t('Главная')."</a>",
                'links'=>Yii::app()->breadCrumbs->getLinks(),
            )); ?>

            <div id="main_content">

                <?php echo $content; ?>

            </div>

            <div class="clear"></div>

        </div>

	<div id="footer">



	</div>
        <!-- footer -->

        </div>

</div>
<!-- page -->
<?php $this->endContent(); ?>