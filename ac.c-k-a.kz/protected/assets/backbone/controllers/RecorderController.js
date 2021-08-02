$(function() {
    RecorderController = BaseController.extend({

        actionTests : BaseAction.extend({

            _initialize : function() {

                var that = this;
                var filter = this.controller.model.get("filter");
                if (!filter) {
                    var filter = {};
                }
                $(that.el).find(".chosen-list").chosen();
                $(that.el).find(".chosen-list").change(function() {

                    filter[$(this).attr("name")] = $(this).val();

                    Yii.app.navigate(Yii.app.createUrl("/recorder/tests", {
                        filter : _(filter).clone()
                    }));
                });

                $( "#date" ).datepicker({
                    format: "dd.mm.yyyy",
                    autoclose: true,
                    language : "ru"
                });
                $( "#date" ).mask("99.99.9999");

                $(that.el).find(".check-all").change(function() {
                    $(that.el).find(".check-student").prop("checked", $(this).prop("checked"));
                })

                $(that.el).find(".btn-set-test").click(function() {

                    var students = [];
                    $(that.el).find(".check-student:checked").each(function() {
                        students.push($(this).attr("uid"));
                    });

                    var m = new BaseModel({
                        t : $(that.el).find("select[name='t']").val(),
                        testdate : $(that.el).find("input[name='date']").val(),
                        qcount : $(that.el).find("input[name='qcount']").val(),
                        dis_id : filter.subject_id,
                        smstr : filter.s
                    }, {
                        yModel : "TestStarted"
                    });

                    m.save({
                        "act" : "recorder",
                        students : students
                    }, {
                        success : function() {
                            Yii.app.navigate(Yii.app.createUrl("/recorder/tests", {filter : _(filter).clone()}), "normal", {
                                scroll : false
                            });
                        },
                        error : function(m,response) {
                            m.callError(response);
                        }
                    })

                })

                $(that.el).find(".btn-set-access").click(function() {

                    var students = [];
                    $(that.el).find(".check-student:checked").each(function() {
                        students.push($(this).attr("uid"));
                    });

                    var m = new BaseModel({
                        t : $(that.el).find("select[name='t']").val(),
                        testdate : $(that.el).find("input[name='date']").val(),
                        dis_id : filter.subject_id,
                        smstr : filter.s
                    }, {
                        yModel : "TestStarted"
                    });

                    m.save({
                        "act" : "access",
                        students : students,
                        access : $(this).attr("access")
                    }, {
                        success : function() {
                            Yii.app.navigate(Yii.app.createUrl("/recorder/tests", {filter : _(filter).clone()}), "normal", {
                                scroll : false
                            });
                        },
                        error : function(m,response) {
                            m.callError(response);
                        }
                    })

                })

            }

        }),

        actionAccess : BaseAction.extend({

            _initialize : function() {

                var that = this;

                var filter = this.controller.model.get("filter");

                $(that.el).find(".chosen-list").chosen();
                $(that.el).find(".chosen-list").change(function() {
                    filter[$(this).attr("name")] = $(this).val();
                    Yii.app.navigate(Yii.app.createUrl("/recorder/access", {
                        filter : {
                            "student_id" : filter['student_id']
                        }
                    }));
                });

                $(that.el).find(".check-all").change(function() {
                    $(that.el).find(".check-access[t='"+$(this).attr("t")+"']").prop("checked", $(this).prop("checked"));
                })

                $(".save-access").click(function() {

                    var access = new BaseModel({}, {
                       yModel : "TestStarted"
                    });

                    access.set("ui_id", filter['student_id']);
                    access.set("semestr", filter['s']);

                    subjects = {};

                    $(that.el).find(".check-access").each(function() {
                        if (typeof subjects[$(this).attr("t")] == "undefined") {
                            subjects[$(this).attr("t")] = {};
                        }
                        subjects[$(this).attr("t")][$(this).attr("value")] = $(this).prop("checked");

                    })

                    access.set("subjects",subjects);
                    access.save({
                        act : "accessByStudent"
                    }, {
                        success : function(model, response, xhr) {

                        }
                    })

                })

            }

        })

    })

})