<?php
if ($this->context->type == app\components\Widget::TYPE_TEMPLATE) {
    ?>
    <% if (<?=$this->context->model->instance?>) { %>
    <?php
}
?>
    <div <?=\yii\helpers\Html::renderTagAttributes($this->context->htmlOptions)?>>
        <div class="name">
        <span class="pull-left"><?=$this->context->model->fio ? $this->context->model->fio.", " : ""?> </span>
            <div class="pull-left"><em><?=($this->context->userType === null ? $this->context->model->roleCaption : $this->context->userType)?></em></div>
        </div>
    </div>
<?php
if ($this->context->type == app\components\Widget::TYPE_TEMPLATE) {
    ?>
<% } %>
<?php
}
?>