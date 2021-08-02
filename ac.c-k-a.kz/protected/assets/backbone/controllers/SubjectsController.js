$(function() {
    SubjectsController = BaseController.extend({

        actionAdd : BaseAction.extend({
            _initialize : function() {

                var that = this;
                var newSubjectModel = new BaseModel(this.controller.model.get("model"),{
                    yModel : "Dis"
                });
                newSubjectModel.setRules(this.controller.model.get("rules"));
                newSubjectModel.setAttributeLabels(this.controller.model.get("attributeLabels"));

                Yii.app.widget("EForm", {
                    el : $("#newSubjectForm"),
                    model : newSubjectModel,
                    onSuccess : function(model,response) {
                        window.location.href = response.redirect;
                    }
                }, function(form) {
                    form.render();
                });

            }
        }),

        actionAssign : BaseAction.extend({
            _initialize : function() {

                var subject = this.controller.model.get("subject");

                $(".btn-g").click(function(e) {
                    if ($(e.target)[0].tagName == "INPUT") {
                        e.stopPropagation();
                        e.preventDefault();
                        return false;
                    }
                    var aid = $(this).attr("aid");
                    var type = $(this).attr("t");
                    $.post(Yii.app.createUrl("/subjects/toggle", {id : subject.id}), {
                        id : aid,
                        type : type
                    }, function(response) {
                    }, "json")

                });

                $(".semestr").keyup(function() {
                    var aid = $(this).parents(".btn").attr("aid");
                    var semestr = $(this).val();
                    $.post(Yii.app.createUrl("/subjects/toggle", {id : subject.id}), {
                        id : aid,
                        type : "semestr",
                        semestr : semestr
                    }, function(response) {
                    }, "json")
                })

            }
        })

    })

})