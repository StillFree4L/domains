$(function() {
    ReportsController = BaseController.extend({

        actionIndex : BaseAction.extend({

            _initialize : function() {

                var that = this;
                var filter = this.controller.model.get("filter");
                if (!filter) {
                    var filter = {};
                }
                $(that.el).find(".chosen-list").chosen();
                $(that.el).find(".chosen-list").change(function() {

                    filter[$(this).attr("name")] = $(this).val();

                    Yii.app.navigate(Yii.app.createUrl("/reports/index", {
                        filter : _(filter).clone()
                    }));
                });

            }

        })

    })

})