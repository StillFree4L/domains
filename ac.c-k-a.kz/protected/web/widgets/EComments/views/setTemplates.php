<?php
?>
<script type="text/template" id="comment_template">

    <div class="comment-body" sort-index='<%=data.model.get('ts')%>' cid='<%=data.model.get('id')%>'>

        <div class="item media">

            <div id='comment_<%=data.model.get('id')%>' name='comment_<%=data.id%>'></div>

            <div class="media-left">

                <div class="image-placeholder"><?=\app\helpers\Html::userImg("<%=data.model.get('user').logoPreview%>", [
                        "style"=>"max-width:none; width: 64px; height: 64px;"
                    ])?></div>

            </div>

            <div class="media-body">
                <h4 class="media-heading"><%=data.model.get("user").fio%></h4>
                <p class="text-muted"><%=data.model.get("user").roleCaption%> <span class='text-info'><%=data.model.get("user").myAd.caption%></span></p>
                <p><%=data.model.get('comment')%></p>
                <p class="small text-muted display-date"><?php echo \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time"=>"data.model.get('ts')",
                        "type"=>\app\components\Widget::TYPE_TEMPLATE
                    ]); ?></p>

                <?php if ($this->context->template == \app\widgets\EComments\EComments::comments) { ?>
                    <% if (data.model.get("user").id == Yii.app.user.id || data.parent.options.canDelete) { %>
                        <a style='margin-top:-2px;' class='delete-comment close'><i class='fa fa-times'></i></a>
                    <% } %>
                <? } ?>
            </div>
            </div>
        </div>

    </div>

</script>

<script type="text/template" id="comments_pager">

    <div class="alert alert-info"><%=Yii.t('main','Показать сообщения за предыдущие 3 дня')%></div>

</script>

<script type="text/template" id="comment_form">

    <form style='position:relative' class="form-vertical">
        <? if (\Yii::$app->user->isGuest) { ?>
            <div style='margin-top:24px;' class='cover-shadow'></div>
            <div style='margin-top:50px; left:50%; margin-left:-120px;' class='cover-content'>
                <table width='100%'>
                    <tr>
                        <td valign='middle' align='center'>
                            <div class="cover-inner modal-content"><?php echo app\widgets\EAuth\EAuth::widget(); ?></div>
                        </td>
                    <tr>
                </table>
            </div>
        <? } ?>
        <div class='form-group'>
            <label class='<%=data.model.get("parent_id") ? "label-answer" : "" %> label-cornered label-blue control-label'><%=data.model.get("parent_id") ? "<span style='color:#fff' class='close'><i class='fa fa-times'></i></span>" : "<?=Yii::t("main","Оставить сообщение")?>"%></label>
            <textarea name='comment' class='form-control' rows='5'><%=data.model.get("comment")%></textarea>
        </div>
        <? if (!\Yii::$app->user->isGuest) { ?>
            <a class='send-comment btn btn-primary btn-sm'><?=Yii::t("main","Отправить")?></a>
        <? } ?>


    </form>

</script>

<div class="comments" id="<?=$this->context->id?>">

    <div class="comments-pager"></div>
    <div class="comments-content"></div>
    <div style="margin-top:15px;" class="comment-form"></div>


</div>