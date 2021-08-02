$(function() {

    EUploader = BaseWidget.extend({
        template : "#EUploader_template",
        defaultOptions : {
            id:'uploader',
            multiple : true,
            files : true,
            video : true,
            maxFileSize:1024*1024*100,
            msgFileToBig:Yii.t("main",'Размер файла слишком большой'),
            msgWrongFile:Yii.t('main','Запрещенный тип файла'),
            msgErrorLoading:Yii.t("main","Ошибка загрузки"),
            onSuccess:false,
            dragBoxId:false,
            cropper : null,
            asyncLoad:false,
            uploadFileTemplate : "#uploaded_file_template",
            uploadFileContainer : null,
            allowedExtensions : [
                'zip','gz','rar','jpg','png','gif','jpeg','tif','tiff','txt','htm','html','rtf','doc','docx','xls','xlsx','ppt','pptx','pdf','mp3','mp4','waf','flp'
            ],
            fileObject : "EFile"
        },
        el : null,
        drag_box : null,
        xhr : null,

        responses : [],
        sended : 0,
        drop_target : null,
        files_count : 0,
        events : {
            "click .upload-button" : "buttonClicked",
            "change input[type='file']" : "selectFiles",
            "change input.video-input" : "parseVideo"
        },
        collection : null,
        cropper : null,
        video : null,
        buttonClicked : function(event) {
            $(this.el).find("input[type='file']").click();
        },
        _initialize : function(args)
        {
            if (args.template === false) {
                this.template = null;
            }
            this.collection = new BaseCollection({
                model : EFileModel
            })
            this.collection.bind("add", this.addFile, this);
            this.collection.bind("remove", this.removeFile, this);

        },
        _render : function(args)
        {
            var that = this;
            if (this.template) {
                this.template = _.template($(this.template).html());
                $(this.el).html(this.template({
                    data : that
                }));
            }

            if (this.options.files) {
                this.xhr = new XMLHttpRequest();
                if (this.xhr.upload) {
                    this.setDragDrop();
                }
            }

        },
        setDragDrop : function()
        {

            var that = this;

            var dragStart = function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(that.drag_box).removeClass("hovered");
                $(that.drag_box).addClass("dragging");
            }
            var dragOver = function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(that.drag_box).addClass("hovered");
            }
            var dragEnd = function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(that.drag_box).removeClass("hovered");
                $(that.drag_box).removeClass("dragging");
            }

            this.drag_box = $(this.el).find("div.file-upload");
            if (this.options.dragBoxId) {
                this.drag_box = $("#"+this.options.dragBoxId);
            }

            $("body").bind("dragover",dragStart);
            this.drag_box.bind("dragover",dragOver);
            this.drag_box.bind("dragend",dragEnd);
            this.drag_box.bind("drop",function(e) {
                that.selectFiles(e);
            });
            $("body").bind("drop",dragEnd);

        },

        selectFiles : function(e) {

            var that = this;

            this.drop_target = e.target;

            this.sended = 0;

            var oe = e.originalEvent;
            var files = oe.target.files || oe.dataTransfer.files;
            this.files_count = files.length;

            if (!that.options.multiple) {
                that.files_count = 1;
                that.newFile(files[0]);
            } else {

                for (var i = 0; i<that.files_count; i++) {
                    that.newFile.apply(this,files[i]);
                }

            }

            this.responses = [];

        },
        newFile : function(file)
        {
            var m = new EFileModel({
                "name" : file.name,
                "file" : file
            });
            this.collection.add(m);
        },
        addFile : function(model)
        {
            var file_view = new window[this.options.fileObject]({
                template : this.options.uploadFileTemplate,
                model : model,
                parent : this,
                options : this.options
            });
            if (this.options.uploadFileContainer) {
                if (this.options.multiple) {
                    $(this.options.uploadFileContainer).append($(file_view.render().el));
                } else {
                    $(this.options.uploadFileContainer).html($(file_view.render().el));
                }
            } else {
                if (this.options.multiple) {
                    $(this.el).find(".uploaded_list").append($(file_view.render().el));
                } else {
                    $(this.el).find(".uploaded_list").html($(file_view.render().el));
                }
            }

        },
        removeFile : function(model) {
            if (model.item) {
                $(model.item.el).remove();
            }
        },
        isFinished : function() {

            if (this.collection.where({
                finished : false
            }).length>0) {
                return false;
            }
            return true;
        },

        /* VIDEO */
        parseVideo : function(event) {

            var that = this;
            if ($(event.currentTarget).attr("value") == "") {
                if (that.video) {
                    that.video.item.remove();
                }
                return;
            }

            var url = $(event.currentTarget).attr("value");
            v = parseVideo(url);

            if (v) {
                that.video = new BaseModel({
                    "type" : v.type,
                    "video_id" : v.id,
                    "width" : 640,
                    "height" : 480
                });

                var video_view = new EVideo({
                    model : that.video,
                    parent : this,
                    options : this.options
                });
                $(this.el).find(".video-preview").html($(video_view.render().el));
            }

        }


    })

    EVideo = BaseItem.extend({
        options : {},
        template : "#embed_video_template",
        remove : function(e) {
            $(this.el).remove();
        },
        afterInitialize : function() {
            this.model.item = this;
        }
    });

    EFile = BaseItem.extend({

        options : {},

        MAX_WIDTH : 1024,
        MAX_HEIGHT : 768,

        events : {
            "click .close" : "remove"
        },
        remove : function(e) {
            this.parent.collection.remove(this.model);
        },
        defineArguments : function(args)
        {
            this.options = args.options;
        },
        afterInitialize : function() {
            this.model.item = this;
            this.model.on("progress", this.progress, this);
            if (!this.model.get("finished")) { this.parseFile() };
        },
        beforeRender : function() {
        },

        parseFile : function() {

            var that = this;
            var file = this.model.get("file");

            var chunks = String(file.name).split(".");
            var ext = chunks[chunks.length - 1];

            if (file.type.match('image/*') && file.type != "image/gif") {

                if (typeof FileReader != 'undefined') {
                    that.resizeFile.apply(that, [function(response) {

                        response.name = file.name;
                        response.filename = file.name;
                        that.model.set("file", response);


                        if (that.options.cropper) {
                            that.options.cropper.addQueue(that, that.sendFile);
                        } else {
                            that.sendFile.apply(that);
                        }

                    }]);
                } else {
                    that.sendFile.apply(that);
                }

            } else if (file.type == "image/gif" || that.options.allowedExtensions.indexOf(ext.toLowerCase()) !== -1) {
                that.sendFile.apply(that);
            } else {
                that.model.set("error", that.options.msgWrongFile);
                return false;
            }

        },
        resizeFile : function(callback) {
            var that = this;
            var file = that.model.get("file");
            var reader = new FileReader();
            var imageEncoding = file.type;
            reader.onloadend = function() {
                var tempImg = new Image();
                tempImg.src = reader.result;
                tempImg.onload = function() {

                    var tempW = tempImg.width;
                    var tempH = tempImg.height;
                    if (tempW > tempH) {
                        if (tempW > that.MAX_WIDTH) {
                            tempH *= that.MAX_WIDTH / tempW;
                            tempW = that.MAX_WIDTH;
                        }
                    } else {
                        if (tempH > that.MAX_HEIGHT) {
                            tempW *= that.MAX_HEIGHT / tempH;
                            tempH = that.MAX_HEIGHT;
                        }
                    }

                    var canvas = document.createElement('canvas');
                    canvas.width = tempW;
                    canvas.height = tempH;
                    var ctx = canvas.getContext("2d");
                    ctx.drawImage(this, 0, 0, tempW, tempH);

                    callback(blob = that.dataURLToBlob(canvas.toDataURL(imageEncoding)));

                }

            }
            reader.readAsDataURL(file);
        },
        dataURLToBlob : function(dataURL) {
            var BASE64_MARKER = ';base64,';
            if (dataURL.indexOf(BASE64_MARKER) == -1) {
                var parts = dataURL.split(',');
                var contentType = parts[0].split(':')[1];
                var raw = parts[1];

                return new Blob([raw], {type: contentType});
            }

            var parts = dataURL.split(BASE64_MARKER);
            var contentType = parts[0].split(':')[1];
            var raw = window.atob(parts[1]);
            var rawLength = raw.length;

            var uInt8Array = new Uint8Array(rawLength);

            for (var i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }

            return new Blob([uInt8Array], {type: contentType});
        },
        progress : function(event) {
            var percent = parseInt(event.loaded / event.total * 100);
            this.model.set({
                percent : percent,
                loaded : event.loaded,
                total : event.total
            });
        },
        sendFile : function()
        {

            var that = this;
            var file = that.model.get("file");

            if (file.size < that.options.maxFileSize)
            {
                that.model.save({}, {
                    success : function(model, response, options) {
                        if (typeof that.parent.options.onSuccess == "function") {
                            that.parent.options.onSuccess.call(that.parent, that);
                        }
                        that.model.set("finished",true);
                    },
                    error : function(response) {
                        that.model.set('error', that.options.msgErrorLoading);
                    }
                });

            } else {

                that.model.set('error', that.options.msgFileToBig)

            }

        }

    });

    EFileModel = BaseModel.extend({
        yModel : "Upload",
        defaults : {
            error : false,
            percent : 0,
            finished : false
        },

        parse : function(response) {
            this.set("response", response);
        }
    })

})