$(function() {
    CasesController = BaseController.extend({

        actionIndex : BaseAction.extend({

            _initialize : function() {

                var that = this;

                var filter = this.controller.model.get("filter");

                $(that.el).find(".chosen-list").chosen();
                $(that.el).find(".chosen-list").change(function() {
                    filter[$(this).attr("name")] = $(this).val();
                    Yii.app.navigate(Yii.app.createUrl("/cases/index", {
                        filter : {
                            //"subject_id" : filter['subject_id'],
                            //"teacher_id" : filter['teacher_id'],
                            "type" : filter['type']
                        }
                    }));
                });

                var caseItem = BaseItem.extend({
                    template : "#uploaded_case_template",
                    data : "item",
                    events : {
                        "click .close" : function() {
                            if (confirm(Yii.t("main","Удалить данный файл?"))) {
                                var that = this;
                                this.model.destroy();
                            }
                        }
                    }
                })

                this.cases = new BaseCollection({}, {
                    model : BaseModel.extend({
                        yModel : "Cases"
                    })
                });
                this.cases.on("add", function(c) {
                    var i = new caseItem({
                        model : c,
                        parent : this
                    });
                    c.view = i;
                    $(".cases-list").prepend($(i.render().el));

                }, this);
                this.cases.on("remove", function(c) {
                    c.view.remove();
                }, this);

                this.cases.add(this.controller.model.get("cases"));

                var EFileUploader = new EUploader({
                    el : $(this.el).find(".upload-case"),
                    template : false,
                    options : {
                        multiple : false,
                        cropper : false,
                        video : false,
                        uploadFileContainer : $(this.el).find(".upload-case").find(".uploaded-case-container"),
                        uploadFileTemplate : "#upload_case_template",
                        onSuccess : function(file) {
                            //$(that.el).find(".user-avatar").find(".image-placeholder img").attr("src", file.model.get("response").preview);
                            //$(that.el).find("#logo").val(JSON.stringify(file.model.get("response")));

                            var caseModel = new BaseModel({
                                //subject_id : filter.subject_id,
                                //teacher_id : filter.teacher_id ? filter.teacher_id : 0,
                                type : filter.type,
                                s : filter.s,
                                info : file.model.get("response")
                            }, {
                                yModel : "Cases"
                            });

                            caseModel.save({}, {
                                success : function(model, response, xhr) {
                                    that.cases.add(response);
                                }
                            })

                        },
                        allowedExtensions : ["doc","docx","xls", "xlsx", "rar", "zip"]
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

            }

        }),

        actionAssign : BaseAction.extend({
            _initialize : function() {

                var caseModel = this.controller.model.get("case");

                $(".btn-g").click(function(e) {
                    if ($(e.target)[0].tagName == "INPUT") {
                        e.stopPropagation();
                        e.preventDefault();
                        return false;
                    }
                    var aid = $(this).attr("aid");
                    var type = $(this).attr("t");
                    $.post(Yii.app.createUrl("/cases/toggle", {id : caseModel.id}), {
                        id : aid,
                        type : type
                    }, function(response) {
                    }, "json")

                });

            }
        })

    })

})