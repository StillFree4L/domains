<?php
/**
 * @var \yii\web\View $this
 * @var \app\models\Message $model
 */
?>
<div class="incoming_msg">
    <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
    <div class="received_msg">
                <div class="received_withd_msg">
                  <p><?= $model->text ?></p>
                  <span class="time_date"><?= $model->from ?></span></div>
              </div>
</div>