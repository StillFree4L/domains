$(function() {
    ECropper = BaseWidget.extend({

        defaultOptions : {
            id : "cropper",
            crop : true
        },

        template : "#cropper_modal",

        img : null,
        pr : null,
        mFile : null,
        queue : [],
        currentIndex : 0,
        processing : false,
        cropCoordinates : {},
        previews : [
            "preview"
        ],
        _initialize : function(args) {
            this.el = $(this.template);
            this.cropModalInitiate();
        },
        addQueue : function(item, callback)
        {
            this.queue.push({
                item : item,
                callback : callback
            });
            this.processNext.apply(this);
        },
        processNext : function() {
            if (!this.processing) {
                if (typeof this.queue[this.currentIndex] != "undefined") {
                    this.cropFile.apply(this);
                }
            }
        },
        cropFile : function() {
            var that = this;
            that.processing = true;
            var file = that.queue[that.currentIndex].item.model.get("file");
            var reader = new FileReader();
            reader.onloadend = function() {

                that.mFile = file;

                that.img = $("<img id='croptarget_"+that.options.id+"'/>");
                that.pr = $("<img class='croppr' id='croppr_"+that.options.id+"'/>");

                $(that.img).attr("src",reader.result);
                $(that.pr).attr("src",reader.result);

                $(that.el).find(".modal-body").html($(that.img));
                $(that.el).modal("show");

            };
            reader.readAsDataURL(file);

        },

        updateCoordinates : function(c, type) {

            var that = this;

            if (typeof that.cropCoordinates[type] == "undefined")
            {
                that.cropCoordinates[type] = {};
            }

            that.cropCoordinates[type].x = c.x;
            that.cropCoordinates[type].y = c.y;
            that.cropCoordinates[type].w = c.w;
            that.cropCoordinates[type].h = c.h;

            if (that.options.crop!==true) {
                var bounds = that.options.crop[type];

                var w_p = 1;
                var h_p = 1;

                if (c.w > bounds[0]) {
                    w_p = bounds[0]/c.w;
                }

                if (c.h > bounds[1])
                {
                    h_p = bounds[1]/c.h;
                }
                $(that.pr).css({
                    position:"absolute",
                    width:$(that.el).find(".jcrop-holder").width()*w_p+"px",
                    height:$(that.el).find(".jcrop-holder").height()*h_p+"px",
                    left:"-"+c.x*w_p+"px",
                    top:"-"+c.y*h_p+"px"
                });
            }

        },

        cropModalInitiate : function() {

            var that = this;

            $(Yii.app.el).append($(that.el));

            if (this.options.crop === true) {
                $(that.el).find(".crop_preview").hide();
            } else {
                $(that.el).find(".crop_crop").hide();
            }

            $(that.el).on('shown.bs.modal', function() {

                that.cropCoordinates = {};

                that.cropCoordinates.original = {};
                that.cropCoordinates.original.w = $('#croptarget_'+that.options.id).width();
                that.cropCoordinates.original.h = $('#croptarget_'+that.options.id).height();

                var preview = 0;
                var last_top = 0;
                var preview_popovers = [];

                if (that.options.crop !== true) {
                    $(that.el).find(".modal-body").popover("show");
                    $(that.el).find(".popover .popover-title").remove();
                    var opopover = $(that.el).find(".popover").hide();


                    var showCropProcess = function(key, value) {

                        $(that.el).find(".miniature_placeholder").html(value[0]+"x"+value[1]);
                        var displayPopover = function(t) {
                            preview_popovers[preview] = $(opopover).clone();
                            //$(preview_popovers[preview]).find(".popover-title .popover_miniature_placeholder").html(value[0]+"x"+value[1]);
                            $(that.el).find(".modal-content").append($(preview_popovers[preview]));
                            $(preview_popovers[preview]).css("width","auto").find(".popover-content").css({
                                position:"relative",
                                width:value[0]+"px",
                                height:value[1]+"px",
                                overflow:"hidden",
                                padding:0
                            }).html($(that.pr));

                            var top = ($(that.el).height()/2) - ($(preview_popovers[preview]).height()/2);
                            if (t)
                            {
                                if (t > top) {
                                    top = t;
                                }
                            }
                            $(preview_popovers[preview]).css({
                                top:top
                            }).show();
                        }

                        if (preview>0) {

                            var top = 0;
                            if ($(preview_popovers[preview-2]).length)
                            {
                                top = last_top;
                            }

                            $(preview_popovers[preview-1]).find(".popover-content").html($(pr).clone());
                            $(preview_popovers[preview-1]).animate({
                                top:top
                            }, 300, false, function() {
                                last_top = top + $(preview_popovers[preview-1]).height() + 10;
                                displayPopover(last_top);
                            });

                        } else {
                            displayPopover(false);
                        }

                        var startX = ($(that.img).width()/2) - (value[0]/2);
                        var endX = startX*1 + value[0]*1;
                        var startY = ($(that.img).height()/2) - (value[1]/2);
                        var endY = startY*1 + value[1]*1;
                        $('#croptarget_'+that.options.id).Jcrop({
                            aspectRatio:value[0]/value[1],
                            minSize:value,
                            setSelect:[startX,startY,endX,endY],
                            onSelect: function(c) {
                                that.updateCoordinates(c, key);
                            },
                            onChange: function(c) {
                                that.updateCoordinates(c, key);
                            }
                        });

                    }
                    showCropProcess(that.previews[0], that.options.crop[that.previews[0]]);
                    $(that.el).find(".sendAfterCrop").click(function() {
                        preview++;
                        if (typeof that.previews[preview] != "undefined") {
                            showCropProcess(that.previews[preview], that.options.crop[that.previews[preview]]);
                        } else {


                            $(that.el).modal("hide");
                        }
                    });

                } else {

                    $('#croptarget_'+that.options.id).Jcrop({

                        onSelect: function(c) {
                            that.updateCoordinates.apply(that,[c, "crop"]);
                        },
                        onChange: function(c) {
                            that.updateCoordinates.apply(that,[c, "crop"]);
                        }
                    });

                    $(that.el).find(".sendAfterCrop").click(function() {

                        $(that.el).modal("hide");

                    });

                }

            });

            $(that.el).on('hide.bs.modal', function () {

                that.cropComplete.apply(that);
                $(".modal-backdrop").remove();

            });

            $(that.el).on('hidden.bs.modal', function () {

                $(that.el).find(".modal-inner").html();
                $(that.el).find(".popover").remove();
                $(that.el).find(".sendAfterCrop").unbind("click");

            });
        },
        cropComplete : function() {
            var that = this;
            item = that.queue[that.currentIndex].item;
            item.model.set("cropCoordinates", that.cropCoordinates);
            callback = that.queue[that.currentIndex].callback;
            if (typeof callback == "function") {
                callback.apply(item);
            }
            that.processing = false;
            that.currentIndex++;
            that.processNext.apply(that);
        }

    })
})