$(function() {
    JournalController = BaseController.extend({

        actionIndex : BaseAction.extend({

            _initialize : function() {

                var that = this;

                var filter = this.controller.model.get("filter");

                $(that.el).find(".chosen-list").chosen();
                $(that.el).find(".chosen-list").change(function() {
                    filter[$(this).attr("name")] = $(this).val();
                    Yii.app.navigate(Yii.app.createUrl("/journal/index", {
                        filter : {
                            "subject_id" : filter['subject_id'],
                            "group_id" : filter['group_id']
                        }
                    }));
                });

                $(that.el).find("input.mark-input").keyup(function(e) {
                    var input = $();
                    if (e.keyCode == 37) {
                        var prev = $(this).parents("td").prev("td");
                        i = 0;
                        while ($(prev).length && !$(prev).find("input").length) {
                            prev = $(prev).prev("td");
                        }
                        input = $(prev).find("input");
                    }
                    if (e.keyCode == 38) {
                        if (!$(this).prev("input.mark-input").length) {
                            input = $(this).parents("tr").prev().find('td').eq($(this).parents("td").index()).find("input.mark-input");
                        } else {
                            input = $(this).prev("input.mark-input");
                        }
                    }
                    if (e.keyCode == 39) {
                        var next = $(this).parents("td").next("td");
                        while ($(next).length && !$(next).find("input").length) {
                            next = $(next).next("td");
                        }
                        input = $(next).find("input");
                    }
                    if (e.keyCode == 40) {
                        if (!$(this).next("input.mark-input").length) {
                            input = $(this).parents("tr").next().find('td').eq($(this).parents("td").index()).find("input.mark-input");
                        } else {
                            input = $(this).next("input.mark-input");
                        }
                    }

                    $(input).focus();

                });

                $(that.el).find("input.mark-input").focus(function() {
                    $(this).select();
                });


                var ballTextColor = function(el, input) {
                    $(el).removeClass("text-danger").removeClass("text-primary").removeClass("text-warning").removeClass("text-success");
                    if ((input && $(el).val() >= 90) || (!input && $(el).html()*1 >= 90)) {
                        $(el).addClass("text-success");
                        return;
                    }
                    if ((input && $(el).val() >= 75) || (!input && $(el).html()*1 >= 75)) {
                        $(el).addClass("text-primary");
                        return;
                    }
                    if ((input && $(el).val() >= 60) || (!input && $(el).html()*1 >= 60)) {
                        $(el).addClass("text-warning");
                        return;
                    }
                    if ((input && $(el).val() >= 50) || (!input && $(el).html()*1 >= 50)) {
                        $(el).addClass("text-warning");
                        return;
                    }

                    $(el).addClass("text-danger");

                }

                that.changed = [];
                $(that.el).find("input.mark-input").change(function() {

                    $(this).val(parseInt($(this).val()));

                    if ($(this).val() < 0) {
                        $(this).val(0);
                    }
                    if ($(this).val() > 100) {
                        $(this).val(100);
                    }

                    if ($(this).attr("t") == 8 || $(this).attr("t") == 2) {
                        var mark_8 = $(this).parents("tr").find("input[t='8']").val();
                        var mark_2 = $(this).parents("tr").find("input[t='2']").length ? $(this).parents("tr").find("input[t='2']").val() : $(this).parents("tr").find("p[t='2']").find("b").html();
                        console.log(Math.ceil((mark_2*1 + mark_8*1)/2));
                        $(this).parents("tr").find("p[t='3']").find("b").html(Math.ceil((mark_2*1 + mark_8*1)/2));
                        ballTextColor($(this).parents("tr").find("p[t='3']").find("b"))
                    }

                    ballTextColor(this, true);

                    if ($(this).attr("id")) {
                        var c = _(that.changed).findWhere({
                            id : parseInt($(this).attr("id"))
                        });
                        if (c) {
                            c.ball = $(this).val();
                        } else {
                            that.changed.push({
                                id : parseInt($(this).attr("id")),
                                ball : $(this).val()
                            })
                        }
                    } else {
                        var c = _(that.changed).findWhere({
                            ui_id : parseInt($(this).attr("ui_id")),
                            dis_id : parseInt(filter['subject_id']),
                            smstr : parseInt(filter['s']),
                            t : parseInt($(this).attr("t"))
                        });
                        if (c) {
                            c.ball = $(this).val();
                        } else {
                            that.changed.push({
                                ui_id : parseInt($(this).attr("ui_id")),
                                dis_id : parseInt(filter['subject_id']),
                                smstr : parseInt(filter['s']),
                                t : parseInt($(this).attr("t")),
                                ball : $(this).val(),
                                date : $(that.el).find("#date").val()
                            })
                        }
                    }

                    that.save();

                });

                $( "#date" ).datepicker({
                    format: "dd.mm.yyyy",
                    autoclose: true,
                    language : "ru"
                });
                $( "#date" ).mask("99.99.9999");
            },
            save : function() {
                var that = this;
                $(that.el).stopTime("save-marks");
                $(that.el).oneTime(1000, "save-marks", function() {
                    var model = new BaseModel({}, {
                        yModel : "TestStarted"
                    })

                    model.save({
                        "act" : "journal",
                        "marks" : that.changed
                    }, {
                        success : function() {
                        }
                    });
                    that.changed = [];

                })
            }

        })

    })

})