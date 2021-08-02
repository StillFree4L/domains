<script type="text/template" id="likes_template">
    <div class="likes-block clearfix">

        <% if (data.parent.options.canLike) { %><div class='pull-left like-up text-success'><i class='fa fa-chevron-circle-up'></i></div><% } %>
        <div class='pull-left like-count'><span><%=data.model.get("likes") ? data.model.get("likes") : 0%></span></div>
        <% if (data.parent.options.canLike) { %><div class='pull-left like-down text-danger'><i class='fa fa-chevron-circle-down'></i></div><% } %>

        <div class='clearfix'></div>

    </div>
</script>