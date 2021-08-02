$(function() {
    DicsController = BaseController.extend({
        actionIndex : BaseAction.extend({

            _initialize : function()
            {

                var that = this;
                $(that.el).find(".find-input").keypress(function(e) {
                    if (e.keyCode == 13) {
                        $(that.el).find(".find-button").click();
                    }
                })
                $(that.el).find(".find-button").click(function() {
                    filter = {};
                    filter.s = $(that.el).find(".find-input").val();
                    Yii.app.navigate(Yii.app.createUrl("/dics/index", {"filter" : filter}));
                });

                $("input.autocomplete").each(function() {
                    $(this).autocomplete({
                        serviceUrl: Yii.app.createUrl('/dics/dautocomplete'),
                        minChars: 1,
                        maxHeight: 400,
                        width: 300,
                        zIndex: 9999,
                        deferRequestBy: 200,
                        params: { attribute : $(this).attr("autocomplete-attribute")},
                        onSelect: function(data, value){

                        }
                    })
                });

            }

        }),
        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;
                var newDicModel = new BaseModel(this.controller.model.get("dic"),{
                    yModel : "Dics"
                });
                newDicModel.setRules(this.controller.model.get("rules"));
                newDicModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                var EFileUploader = new EUploader({
                    el : $(this.el).find(".uploader"),
                    template : false,
                    options : {
                        multiple : false,
                        cropper : false,
                        video : false,
                        uploadFileContainer : $(this.el).find(".uploader").find(".uploaded-loader"),
                        uploadFileTemplate : "#attached_file_template",
                        onSuccess : function(file) {
                            $(that.el).find("#excel_file").val(file.model.get("response").url);
                            file.remove();
                        },
                        allowedExtensions : [
                            'xls','xlsx'
                        ]
                    }
                });
                EFileUploader.newFile = function(file) {
                    var m = new EFileModel({
                        "name" : file.name,
                        "file" : file
                    })

                    EFileUploader.collection.set([m]);

                };
                EFileUploader.render();

                Yii.app.widget("EForm", {
                    el : $("#newDicsForm"),
                    model : newDicModel,
                    options : {
                        uploader : EFileUploader
                    },
                    onSuccess : function(model,response) {
                        if (that.controller.target == "modal") {
                            $(that.controller.el).modal("hide");
                        } else {
                            window.location.href = response.redirect;
                        }
                    }
                }, function(form) {
                    form.render();
                });

            }
        }),
        actionAddv : BaseAction.extend({
            _initialize : function() {

                var that = this;
                var newDicvModel = new BaseModel(this.controller.model.get("dicv"),{
                    yModel : "DicValues"
                });
                newDicvModel.setRules(this.controller.model.get("rules"));
                newDicvModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                Yii.app.widget("EForm", {
                    el : $("#newDicsvForm"),
                    model : newDicvModel,
                    onSuccess : function(model,response) {
                        if (that.controller.target == "modal") {
                            $(that.controller.el).modal("hide");
                        } else {
                            window.location.href = response.redirect;
                        }
                    }
                }, function(form) {
                    form.render();
                });

                $("input.autocomplete").each(function() {
                    $(this).autocomplete({
                        serviceUrl: Yii.app.createUrl('/dics/autocomplete'),
                        minChars: 1,
                        maxHeight: 400,
                        width: 300,
                        zIndex: 9999,
                        deferRequestBy: 200,
                        params: { attribute : $(this).attr("autocomplete-attribute")},
                        onSelect: function(data, value){

                        }
                    })
                });

            }
        })
    })
})