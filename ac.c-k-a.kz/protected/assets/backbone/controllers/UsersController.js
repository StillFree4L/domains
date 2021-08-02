$(function() {
    UsersController = BaseController.extend({
        actionIndex : BaseAction.extend({

            _initialize : function()
            {

                $(this).find("table.users").find(".users-body").empty();

                var users = new BaseCollection();
                users.bind("add", this.addUser, this);

                users.set(this.controller.model.get("users"));

            },
            addUser : function(model) {
                var view = new this.userView({
                    model : model,
                    parent : this
                })

                model.view = view;
                $("table.users").find(".users-body").append($(view.render().el));

            },
            userView : BaseItem.extend({
                template : "#users_row_template"
            })

        }),
        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;


                var newUserModel = new BaseModel(this.controller.model.get("user"),{
                    yModel : "Users"
                });
                newUserModel.setRules(this.controller.model.get("rules"));
                newUserModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                Yii.app.widget("EForm", {
                    el : $("#newUserForm"),
                    model : newUserModel,
                    onSuccess : function(model,response) {
                        window.location.href = response.redirect;
                    }
                }, function(form) {
                    form.render();
                });

            }
        }),
        actionProfile : BaseAction.extend({
            afterRender : function() {

                var that = this;

                var newProfileModel = new BaseModel(this.controller.model.get("profile"),{
                    yModel : "Users"
                });
                newProfileModel.setRules(this.controller.model.get("rules"));
                newProfileModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                var EFileUploader = new EUploader({
                    el : $(this.el).find(".user-avatar"),
                    template : false,
                    options : {
                        multiple : false,
                        cropper : new ECropper({
                            options : {
                                crop : {
                                    "preview" : [
                                        "150",
                                        "150"
                                    ]
                                }
                            }
                        }),
                        video : false,
                        uploadFileContainer : $(this.el).find(".user-avatar").find(".image-placeholder"),
                        uploadFileTemplate : "#profile_uploaded_image_template",
                        onSuccess : function(file) {
                            //$(that.el).find(".user-avatar").find(".image-placeholder img").attr("src", file.model.get("response").preview);
                            $(that.el).find("#logo").val(JSON.stringify(file.model.get("response")));
                        },
                        allowedExtensions : ["jpg","jpeg","png"]
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
                    el : $("#newProfileForm"),
                    model : newProfileModel,
                    options : {
                        uploader : EFileUploader
                    },
                    onSuccess : function(model,response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }
                }, function(form) {
                    form.render();

                });

            }
        })
    })
})