<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->getBaseUrl()?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->getBaseUrl()?>/css/menu.css" />
        <link rel="stylesheet" type="text/css" href="<?=Yii::app()->theme->getBaseUrl()?>/css/instance.css" />

        <?php
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->theme->getBaseUrl().'/js/jquery.timers.js');
        $cs->registerScriptFile(Yii::app()->theme->getBaseUrl().'/js/main.js');
        ?>

	<title><?php echo CHtml::encode(Yii::app()->baseOptions->system_name); ?></title>
</head>

<body>
<?php
echo $content;
?>
</body>
</html>
