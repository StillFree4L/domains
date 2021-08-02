$(function() {
    EmployeesController = BaseController.extend({
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
        })
    })
})