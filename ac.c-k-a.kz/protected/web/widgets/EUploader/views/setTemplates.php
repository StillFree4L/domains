<?php if ($this->context->standalone) { ?>
<script type="text/template" id="EUploader_template">

    <% console.log(data) %>
    <div class='uploader-widget'>
        <% if (data.options.files) { %>
        <div class='file-upload'>
            <div class='uploader-inner text-center'>
                <div class='uploader-area'>
                    <span style='margin-right:15px;' class='placeholder text-muted'><?=Yii::t("main","Перетащите файл, или нажмите на кнопку ")?></span>
                    <a class='btn btn-success upload-button'><li class='fa fa-file'></li> <?=Yii::t("main","Загрузить")?></a>
                </div>
            </div>
            <input name="fileUpload" type="file" <%=data.options.multiple ? "multiple='multiple'" : ""%> class="upload_file_file_input"  />
        </div>

        <div class='uploaded_list' style='margin-top:15px;'></div>

        <% }
        if (data.options.video) { %>

        <div class='video-upload'>
            <div class='form-group'>
                <label class='control-label'><?=Yii::t("main","Вставьте ссылку на видео с Youtube.com или Vimeo.com")?></label>
                <input class='form-control video-input' type='text' placeholder='<?=Yii::t("main","Ссылка на видео")?>' />
            </div>
        </div>

        <div class='video-preview' style='margin-top:15px;'>
        </div>

        <% } %>



    </div>

</script>

<script type="text/template" id="embed_video_template">
    <div class='embedded-video'>
        <% if (data.type == "youtube") { %>
        <div class="youtube-video">
            <iframe width="<%=data.width%>" height="<%=data.height%>" src="http://www.youtube.com/embed/<%=data.video_id%>" frameborder="0" allowfullscreen>
            </iframe>
        </div>
        <% } else if (data.type == "vimeo") { %>
        <div class="vimeo-video">
            <iframe src="http://player.vimeo.com/video/<%=data.video_id%>" width="<%=data.width%>" height="<%=data.height%>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
        <% } %>
        <input type='hidden' name='embedded[]' value='<%=JSON.stringify(data)%>' />
    </div>
</script>
<script type="text/template" id="uploaded_file_template" >

    <div  class='file <%=data.error ? 'upload-error' : ''%> clearfix well well-sm'>
        <div style='position:relative' class='clearfix'>
            <div style='width:40; height:50px;' class='inline-block file-icon'>
                <img src='<?=BASE_ASSETS."/img/icons/"?><%=getFileIcon(data.file)%>.png' />
            </div>
            <div style='margin-left:10px;' class='inline-block file-name'><%=data.name%></div>

            <span style='font-size:26px; position:absolute; right: 5px; top:50%; margin-top:-13px;' class='close inline-block pull-right'><li class='fa fa-times'></li></span>
            <% if (data.error) { %>
                <div  style='margin-right:35px; padding:8px 20px;' class='inline-block pull-right alert alert-danger'><%=data.error%></div>
            <% } %>
        </div>
        <% if (!data.error && data.percent>0 && data.percent < 100) { %>
        <div class='clearfix'>
            <div style='margin-top:5px; margin-bottom:5px;' class="progress progress-striped">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <%=data.percent%>%">
                    <span><%=(Math.ceil(data.loaded/1024)) + "kb" %>/<%=(Math.ceil(data.total/1024)) + "kb"%></span>
                </div>
            </div>
        </div>
        <% } %>

        <% if (data.response && data.response) { %>
        <input type='hidden' name='uploaded[]' value='<%=JSON.stringify({'file':data.response, 'name' : data.name})%>' />
        <% } %>

        <% if (data.response && data.response.type.match("image/*")) { %>
        <div class='clearfix'>
            <div style='margin-top:5px; margin-bottom:5px;' class="img-thumbnail">
                <img src='<%=data.response.url%>' />
            </div>
        </div>
        <% } %>

    </div>

</script>
<?php } ?>