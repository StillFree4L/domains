<?php
$this->addTitle(Yii::t("main","Чаты"));

$this->registerJsFile(Yii::$app->assetManager->getBundle("jQuery")->baseUrl."/js/jquery.timers.js");

?>
<div class="container controller table-controller">


    <div class="action-content">

        <div class="row">

            <div class="chat-search-user form-group clearfix col-xs-12">
                <input type="text" placeholder="<?=Yii::t("main","Поиск собеседников по ФИО")?>" class="form-control user-search" />
                <div class="clearfix"></div>
            </div>

            <div class="chat-search-results form-group clearfix col-xs-12 row">

            </div>

            <script type="text/template" id="chat_search_user_template">

                <div class="chat-search-user col-xs-6">
                    <h4><%=data.model.get("fio")%></h4>
                    <p class="text-muted"><%=data.model.get("roleCaption")%> <span class="text-info"><%=data.model.get("myAd").caption%></span></p>
                    <a class="btn btn-primary btn-xs" href="<%=Yii.app.createUrl("/chats/add", {uid : data.model.get("id")})%>"><?=Yii::t("main","Начать разговор")?></a>
                </div>

            </script>

        </div>

        <div class="alert alert-danger no-chats" style="<?=$chats ? "display:none;" : ""?>">
            <?=Yii::t("main","Нет начатых разговоров")?>
        </div>

        <script type="text/template" id="chat_template">

            <a class="relative chat-item" href="<%=Yii.app.createUrl("/chats/view", {id:data.model.get("id")})%>">

                <% if (data.model.get("name")) { %>
                    <h3 class="pull-left chat-name"><%=data.model.get("name")%></h3>
                <% } else {
                    members = _(data.model.get("members")).filter(function(m) {
                        return m.user_id != <?=Yii::$app->user->id?>
                    });
                    var l = members.length < 5 ? members.length : 5;
                    for (i=0; i<l; i++) { %>
                        <div class="chat-name pull-left">
                            <h4 class="media-heading"><%=data.model.get("members")[members[i].user_id].user.fio%></h4>
                            <p class="text-muted"><%=data.model.get("members")[members[i].user_id].user.roleCaption%> <span class='text-info'><%=data.model.get("members")[members[i].user_id].user.myAd.caption%></span></p>
                        </div>
                    <% }
                } %>
                <p style="margin-left:10px;" class="pull-right count text-danger"><%=data.model.get("new_messages") ? "+"+data.model.get("new_messages") + " " + multiplier(data.model.get("new_messages"), [
                    Yii.t("main",'новое сообщение'),
                    Yii.t("main",'новых сообщения'),
                    Yii.t("main",'новых сообщений')
                    ]) : ""%></p>

                <div class="clearfix"></div>

                <% if (data.model.get("lastMessage")) {
                    var message = data.model.get("lastMessage");
                    var members = data.model.get("members");
                %>
                <div class="comment item media">
                    <div class="media-body">
                        <p class="media-heading"><%=members[message.user_id].user.fio%></p>
                        <p class="text-muted"><%=message.comment%></p>
                        <div>
                        <p class="pull-left small text-muted display-date"><?php echo \app\widgets\EDisplayDate\EDisplayDate::widget([
                            "time"=>"message.ts",
                            "type"=>\app\components\Widget::TYPE_TEMPLATE
                            ]); ?></p>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <% } %>
            </a>

            </div>

        </script>

        <div class="chats-list">

        </div>

    </div>

</div>