<?php $ccount = PQuestionAnswer::model()->resetScope()->nonApproved()->count(); ?>
<li><a href="/admin/<?=Yii::app()->language?>/plugin/<?=$this->model->uniq_name?>"><i class="icon-chevron-right"></i><?=$this->model->name.($ccount>0 ? " <b>+".$ccount."</b>" : "")?></a></li>
