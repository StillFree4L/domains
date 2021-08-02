<div id="notifications_main">

    <div class="notifications-icon"></div>

    <script type="text/template" id="notifications_icon_template">
        <div class='icon-inner'><a style='font-size:25px; <%=data.parent.collection.length > 0 ? "cursor:pointer" : "" %>' class='<%=data.parent.collection.length > 0 ? "link-purple" : "link-gray" %>' ><i class='fa fa-bell'></i><%
            if (data.parent.collection.length > 0) { %>
                <em class='sup'><%=data.parent.collection.length%></em>
            <% }
        %></a>
        </div>
    </script>

    <script type="text/template" id="notification_item_template">
        <% data = data.model.toJSON(); %>
        <div class="notification_body" sort-index='<%=data.ts%>' style='position:relative; margin:10px 0; padding:0px 5px 10px 5px; border-bottom:1px solid #ddd;'>

            <div style='margin-right:30px;'>
                <span class="notification_title"></span>
                <a href='<%=data.actionSite.url%>' class="notification_message"><%=data.actionSite.message%></a>
                <div style='margin-top:5px;' class="display-date"><?php echo \app\widgets\EDisplayDate\EDisplayDate::widget([
                        "time"=>"data.ts",
                        "type"=>Widget::TYPE_TEMPLATE
                    ]); ?></div>
                <div class='clear'></div>
            </div>

            <span style='position:absolute; right:0px; top:0px;' class="close delete-notification"><i class='fa fa-times'></i></span>

            <div style="clear:both;"></div>
        </div>
    </script>

    <div style="display:none;" class='notifications-content'>

    </div>

</div>