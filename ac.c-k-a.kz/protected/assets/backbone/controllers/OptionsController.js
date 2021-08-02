$(function() {
    OptionsController = BaseController.extend({
        actionIndex : BaseAction.extend({

            _initialize : function()
            {
                var that = this;
                $(that.el).find(".options-list").empty();

                var options = new BaseCollection({}, {
                    model : BaseModel.extend({
                        yModel : "Options"
                    })
                });

                options.on("add", function(m) {
                    var view = new that.optionView({
                        parent : that,
                        model : m
                    });
                    m.view = view;
                    $(that.el).find(".options-list").append($(view.render().el));
                })

                options.set(this.controller.model.get("options"));

                $(".add-option").click(function() {
                    var option = new BaseModel({

                    }, {
                        yModel : "Options"
                    })

                    options.add(option);

                })

            },
            optionView : BaseItem.extend({
                template : "#option_template",
                data : "item",
                events : {
                    "change .value-input" : function(e) {
                        $(this.el).find(".commit").show();
                    },
                    "click .commit" : function() {
                        if (!this.model.get("id")) {
                            this.model.set({
                                name : $(this.el).find(".name-input").val()
                            }, {
                                silent : true
                            })
                        }

                        this.model.save({
                            value : $(this.el).find(".value-input").val()
                        });
                    }
                }
            })

        })
    })
})