<script type="text/template" id="chat_search_user_template">

    <a class="chat-item chat-search-user list-group-item" style="cursor:pointer; border:0">
        <div class="media">
            <div class="media-left">
                <div class="image-placeholder"><?=\app\helpers\Html::userImg("<%=data.model.get('logoPreview')%>", [
                        "style"=>"max-width:none; width: 32px; height: 32px;"
                    ])?></div>
            </div>
            <div class="media-body">
                <p style="margin-top:-3px; margin-bottom:0px;"><%=data.model.get("fio")%></p>
                <p style="margin-top:-3px;" class="small text-muted"><%=data.model.get("roleCaption")%></p>
            </div>
        </div>
    </a>

</script>

<script type="text/template" id="chat_template">

    <a class="chat-item list-group-item" style="cursor:pointer; border:0;" sort-index="<%=data.model.get("last_ts")%>">
        <%
        if (!data.model.get("name")) {
        var member = _(data.model.get("members")).filter(function(m) {
                return m.user_id != <?=Yii::$app->user->id?>;
        })[0];
        }
        if (member && member.user) {
        %>
        <div class="media">
            <div class="media-left">
                <div class="image-placeholder"><?=\app\helpers\Html::userImg("<%=member.user.logoPreview%>", [
                        "style"=>"max-width:none; width: 32px; height: 32px;"
                    ])?></div>
            </div>
            <div class="media-body" style="position:relative;">
                <p style="margin-top:-3px; margin-bottom:0px;"><%=member.user.fio%></p>
                <p style="margin-top:-3px; margin-bottom:0px;" class="small text-muted"><%=member.user.roleCaption%></p>
                <% if (data.model.get("new_messages") > 0) { %>
                <span style="position:absolute; z-index:2; bottom:3px; right:0px; font-size:10px; padding:1px 2px;" class="count label label-danger"><%=data.model.get("new_messages")%></span>
                <% } %>
            </div>
        </div>
        <% } %>
    </a>

</script>

<script type="text/template" id="messages_window_template">
    <div class="messages-window panel panel-default" style="display:none; position:absolute; bottom:0px; right:260px; z-index:1; width:300px; margin-bottom:0;">

        <%

        var name = data.parent.model.get("name");
        if (!name) {
            name = _(data.parent.model.get("members")).filter(function(m) {
                    return m.user_id != <?=Yii::$app->user->id?>;
            })[0].user.fio;
        }

        %>
        <div class="panel-heading">
            <span class="chat-header text-muted pull-left"><%=name%></span>
            <a class="close-chats close">&times;</a>
            <div class="clearfix"></div>
        </div>

        <div class="panel-body" style="padding:0 0; height:361px; margin-top:-1px;  position:relative;">

            <div style="overflow-y:auto; height:330px;">
                <div class="messages-list list-group" style="">

                </div>
            </div>

            <div class="send-message-form form-group" style="position:absolute; margin-bottom:0; bottom:-1px; right:-1px; left:-1px;">
                <textarea style="height:35px; margin:0; float: left; width: 265px; display: block;" rows="1" placeholder="<?=Yii::t("main","Ваше сообщение")?>" class="send-message-input input-sm form-control"></textarea>
                <a class="send-message-button pull-right btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
            </div>

        </div>

    </div>
</script>

<script type="text/template" id="message_template">

    <div class="message-item" style="border:0; display:block; padding:10px; " sort-index="<%=data.model.get("ts")%>">
        <%
        var member = _(data.parent.parent.model.get("members")).findWhere({
                user_id : parseInt(data.model.get("user_id"))
        });
        if (member) {
        %>
        <div class="media">
            <div class="media-left">
                <div class="image-placeholder"><?=\app\helpers\Html::userImg("<%=member.user.logoPreview%>", [
                        "style"=>"max-width:none; width: 24px; height: 24px;"
                    ])?></div>
            </div>
            <div class="media-body" style="position:relative;">
                <p style="margin-top:2px; margin-bottom:0px;"><%=member.user.fio%></p>
            </div>
        </div>
        <% } %>
        <p style="margin-bottom:0;" class="pull-right small text-muted display-date"><?php echo \app\widgets\EDisplayDate\EDisplayDate::widget([
                "time"=>"data.model.get('ts')",
                "type"=>\app\components\Widget::TYPE_TEMPLATE
            ]); ?></p>
        <div class="clearfix"></div>
        <p style="position:relative; padding:4px 10px; background: #dfdfdf; margin-left:0px; margin-top:0; border-radius:5px;">
            <span style="display:block; position:absolute; top:-10px; left:7px; width:5px; height:5px; border:solid transparent; border-bottom-color:#dfdfdf; border-width:0px 5px 10px 5px;"></span>
            <%=data.model.get("comment")%>
        </p>
    </div>

</script>

<div class="chats hidden-print" style="position:fixed; bottom:80px; right:20px; z-index:999999;" title="<?=Yii::t("main","Чаты")?>">
    <div style="display:none" class="sound"></div>
    <a class="chats-icon btn btn-primary btn-sm" style="cursor:pointer; position:relative; z-index:2;" target="modal" ><i class="fa fa-comments"></i>
        <span style="position:absolute; z-index:2; top:-1px; right:-1px; font-size:10px; padding:1px 3px;" class="count label label-xs label-danger"></span>
    </a>

    <div class="chats-window panel panel-default" style="display:none; position:absolute; bottom:0px; right:0px; z-index:1; width:250px; margin-bottom:0;">

        <div class="panel-heading">
            <span class="text-muted pull-left"><?=Yii::t("main","Чаты")?></span>
            <a class="close-chats close">&times;</a>
            <div class="clearfix"></div>
        </div>

        <div class="chat-search-user form-group clearfix" style="margin:-1px -1px 1px -1px;">
            <input type="text" placeholder="<?=Yii::t("main","Поиск собеседников по ФИО")?>" class="form-control user-search input-sm" />
            <div class="clearfix"></div>
        </div>

        <div class="panel-body" style="padding:0 0; height:300px; margin-top:-1px; overflow-y:auto;">

            <div class="chat-search-results clearfix">

            </div>


            <div class="text-muted alert no-chats" style="display:none;">
                <?=Yii::t("main","Нет начатых разговоров")?>
            </div>

            <div class="chats-list list-group">

            </div>

        </div>
        <div class="panel-footer" style="padding:14px 15px 15px 15px;">

        </div>
    </div>

</div>