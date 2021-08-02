<?php
$this->pageTitle=Yii::app()->name . t(' - Регистрация');
?>

	
		<div class="control-group well well-small span6" style="margin:30px auto; float:none">
			<h1 style="margin-bottom:30px; margin-left:30px;"><?= t('Регистрация')?></h1>
			<?php
				$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					'id'=>'registration-form',					
					'inlineErrors' => 'inline', // how to display errors, inline or block?
                                        'type'=>'horizontal'
					//'enableAjaxValidation'=>true,
				)); 

                                echo $form->textFieldRow($model, "login", array(
                                    "class"=>"span4",
                                    'hint'=>t("От 6 до 32 символов")
                                ));
                                echo $form->textFieldRow($model, "email", array(
                                    "class"=>"span4"
                                ));
                                echo $form->passwordFieldRow($model, "password", array(
                                    "class"=>"span4",
                                    'hint'=>t("От 6 до 32 символов")
                                ));
                                echo $form->passwordFieldRow($model, "repeatPassword", array(
                                    "class"=>"span4"
                                ));
                                
                                echo $form->textFieldRow($model, "last_name", array(
                                    "class"=>"span4"
                                ));

                                echo $form->textFieldRow($model, "first_name", array(
                                    "class"=>"span4"
                                ));

                                echo $form->textFieldRow($model, "middle_name", array(
                                    "class"=>"span4"
                                ));

                                /*
                                
                                $regions = array(""=>"");
                                $regions = array_merge($regions, Users::model()->getRegions());


                                echo $form->dropDownListRow($model,"region_id", $regions, array(
                                    "class"=>"span4"
                                ));


                                $categories = array(""=>"");
                                $categories = array_merge($categories, Users::model()->getRanks());
                                
                                echo $form->dropDownListRow($model,"rank_id", $categories, array(
                                    "class"=>"span4"
                                ));
                                
                                echo $form->textFieldRow($model, "organization", array(
                                    "class"=>"span4"
                                ));
				*/

				echo CHtml::htmlButton('<i class="icon-ok"></i>&nbsp;'.t('Зарегистрироваться'), array(
					'class'=>'btn',
                                        'style'=>"margin-left:30px; margin-top:30px;",
					'type'=>'submit'));
				?>

			<?php $this->endWidget(); ?>
		</div>
