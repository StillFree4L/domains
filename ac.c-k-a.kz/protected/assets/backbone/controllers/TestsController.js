
$(function() {
    TestsController = BaseController.extend({

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

                    Yii.app.navigate(Yii.app.createUrl("/tests/index", {
                        filter : _(filter).clone()
                    }));
                });

            }

        }),
        actionProcess : BaseAction.extend({

            afterRender : function() {
                var that = this;

                $(that.el).find(".navigation").sticky({
                    topSpacing : 20
                });

                that.test = new BaseModel(that.controller.model.get("test"), {
                    yModel : "TestStarted"
                });

                var timeLeftView = BaseItem.extend({
                    template : "#time_left_template",
                    data : "item",
                    afterRender : function() {
                        var that = this;
                    }
                })
                var timeLeft = new timeLeftView({
                    model : that.test
                });
                $(that.el).find(".time-left").html($(timeLeft.render().el));

                $(that.el).everyTime(1000, "timeleft", function() {
                    that.test.set("timeLeft", that.test.get("timeLeft") - 1);
                });
                that.test.on("change", function() {
                    if (that.test.get("isExpired") || that.test.get("timeLeft") <= 0) {
                        $(that.el).stopTime("timeleft");
                        Yii.app.navigate(Yii.app.createUrl("/tests/finish", {id : that.test.get("id")}));
                    }
                });

                $(that.el).find(".questions").empty();
                $(that.el).find(".navigation .row").empty();

                _(that.controller.model.get("questions")).each(function(q) {
                    _(q.testAnswers).each(function(ta) {
                        if (that.test.get("jInfo")['answers'] && typeof that.test.get("jInfo")['answers'][q.id] != "undefined") {
                            if (that.test.get("jInfo")['answers'][q.id].indexOf(ta.mark) !== -1) {
                                ta.selected = 1;
                            }
                        }
                    });
                })

                var questions = new BaseCollection({
                }, {
                    yModel : "TestQuestions"
                })
                var n = 1;
                questions.on("add", function(m) {
                    m.set("n", n);
                    var view = new that.questionItem({
                        model : m,
                        parent : that
                    });
                    var nav = new that.navigationItem({
                        model : m,
                        parent : that
                    });
                    m.view = view;
                    m.nav = nav;
                    $(that.el).find(".questions").append($(m.view.render().el));
                    $(that.el).find(".navigation .items").append($(m.nav.render().el));
                    n++;
                });
                questions.set(that.controller.model.get("questions"));

            },
            questionItem : BaseItem.extend({
                template : "#question_template",
                data : "item",
                afterRender : function() {
                    var that = this;

                    $(that.el).find("a.answer").click(function() {

                        if (that.model.get("correct_count") == 1) {
                            answers = _(that.model.get("testAnswers")).where({
                                selected : 1
                            });
                            _(answers).each(function(a) {
                                a.selected = 0;
                            });
                        }

                        var answer = _(that.model.get("testAnswers")).findWhere({
                            mark : $(this).attr("mark")
                        });
                        answer.selected = answer.selected == 1 ? 0 : 1;
                        that.model.trigger("change");
                        that.saveAnswer();
                    });

                },
                saveAnswer : function() {
                    var that = this;
                    $(that.el).stopTime("saveAnswer");
                    $(that.el).oneTime(500, "saveAnswer", function() {

                        var question = {
                            id : that.model.get("id"),
                            answers : _(_(that.model.get("testAnswers")).where({
                                selected : 1
                            })).pluck("mark")
                        }

                        that.parent.test.save({
                            question : question
                        });
                    })
                }
            }),
            navigationItem : BaseItem.extend({
                template : "#question_navigation_template",
                data : "item",
                afterRender : function() {
                    var that = this;
                    $(this.el).click(function() {
                        $(document.body).animate({
                            'scrollTop':   $(that.parent.el).find(".questions").find("#question_" + that.model.get("n")).offset().top - 100
                        }, 400);
                    })

                }
            })

        })

    })

})