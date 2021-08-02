<?php
$this->pageTitle=Yii::app()->name . t(' - Авторизация');
$this->breadcrumbs=array(
	'Login',
);
?>

<div id="outerDivShade">
	<div id="outerDiv">
		<div id="auth" class="control-group">
			<h1><?= t('Авторизация')?></h1>
			<?php
				$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					'id'=>'login-form',
					'htmlOptions'=>array('class'=>'well'),
					'inlineErrors' => 'inline', // how to display errors, inline or block?
					//'enableAjaxValidation'=>true,
				)); ?>

				<?php echo $form->errorSummary($model, null, null, array('class' => 'auth_form_error')); ?>

				<div class="row control-group ">
					<div class="span3"><?php echo $form->labelEx($model,'login'); ?></div>
					<div class="span2"><?php echo $form->textField($model,'login', array('class'=>'span3')); ?></div>
				</div>

				<div class="row control-group ">
					<div class="span3"><?php echo $form->labelEx($model,'password'); ?></div>
					<div class="span2"><?php echo $form->passwordField($model,'password', array('class'=>'span3')); ?></div>
				</div>

				<?php echo $form->checkboxRow($model, 'rememberMe'); ?>
				<?php echo CHtml::htmlButton('<i class="icon-ok"></i>&nbsp;'.t('Войти'), array(
					'class'=>'btn',
					'type'=>'submit'));
				?>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>