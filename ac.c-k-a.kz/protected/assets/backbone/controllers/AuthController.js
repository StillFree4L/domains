$(function() {
    AuthController = BaseController.extend({

        actionLogin : BaseAction.extend({
            _initialize: function () {
                var loginModel = new BaseModel(this.controller.model.get("user"), {
                    yModel: "Users"
                });
                loginModel.setRules(this.controller.model.get("rules"));
                loginModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                var loginForm = new EForm({
                    el: $("#loginForm"),
                    model: loginModel,
                    onSuccess: function (model, response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }
                }).render();

            }
        })
    })

})