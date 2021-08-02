$(function() {

    /**
     * @type {*|void|extend|extend|extend|extend}
     */
    EComments = BaseWidget.extend({
        collection : new BaseCollection([],{
            yModel : "Comments",
            model : BaseModel.extend({
                yModel : "Comments"
            }),
            comparator : "ts"
        }),
        defaultOptions : {
            target_id : null,
            target_type : null,
            page : 1,
            pageCount : 10,
            shownAll : false,
            totalCount : 0,
            canComment : true,
            canDelete : false,
            commentsCounter : ".comments-counter",
            defaultNavigate : null
        },
        commentFormModel : null,
        _initialize : function(args)
        {
            if (this.options.defaultNavigate === null) {
                if (window.location.hash) {
                    this.options.defaultNavigate = window.location.hash.replace("#","");
                }
            }

            this.collection.reset();
            this.collection.bind("add", this.addComment, this);
            this.collection.bind("remove", this.removeComment, this);
            $(this.el).find(".comments-content").empty();
            this.updateCounter(this.options.totalCount);
            this.populateComments();

        },
        _render : function()
        {
            var that = this;
            this.commentFormModel = new this.collection.model(
                {
                    target_id : that.options.target_id,
                    target_type : that.options.target_type,
                    comment : null
                }
            );

            var cForm = new ECommentsForm({
                model : this.commentFormModel,
                template : $("#comment_form"),
                data : "item",
                parent : this
            });
            $(this.el).find(".comment-form").html($(cForm.render().el));

        },
        populateComments : function()
        {
            var that = this;
            log("fetching comments");
            this.collection.fetch({
                data : {
                    target_id : that.options.target_id,
                    target_type : that.options.target_type
                },
                success : function(a, b, c)
                {
                    if (b.length > 0) {
                        that.startPoll(b ? b[0].ts : 0);
                    } else {
                        that.startPoll(0);
                    }
                    that.collection.trigger("fetched");
                },
                error : function(a, b)
                {
                    that.startPoll(0);
                }
            });


        },
        startPoll : function(last_ts)
        {
            Yii.app.poll.addEvent("comments", {
                yModel : "Comments",
                target_id : this.options.target_id,
                target_type : this.options.target_type,
                last_ts : last_ts
            }, this.newComments, this, {
                global : false,
                updateAttribute : "last_ts"
            })
        },
        newComments : function(response, event)
        {
            if (response.data) {
                this.collection.add(response.data);
            }

        },
        addComment : function(comment)
        {
            var view = new ECommentItem({
                template : "#comment_template",
                model : comment,
                parent : this,
                data : "item"
            });
            comment.view = view;

            var el = $(view.render().el);
            var p = $(this.el).find(".comments-content");
            if (comment.get("parent_id")) {
                var pp = $(this.el).find(".comments-content").find(".comment-body[cid='"+comment.get("parent_id")+"']");
                var p = $("<div class='answers-content'></div>");
                if (!$(pp).next().hasClass("answers-content")) {
                    $(pp).after(p);
                } else {
                    p = $(pp).next();
                }
            }

            var before = $(p).find(".comment-body").filter(function() {
                return $(this).attr("sort-index")*1 > $(el).attr("sort-index")*1;
            }).first();

            if ($(before).length) {
                $(before).before($(el));
            } else {
                $(p).append($(el));
            }

            if (this.options.defaultNavigate && this.options.defaultNavigate == comment.get("id")) {
                log("navigating to default comment");
                view.navigate();
            }

        },
        removeComment : function(model) {
            $(model.view.el).remove();
        },
        updateCounter : function()
        {

            $(this.options.commentsCounter).html(this.options.totalCount + " " + multiplier(this.options.totalCount, [
                Yii.t("main","комментарий"),
                Yii.t("main","комментария"),
                Yii.t("main","комментариев")
            ]));
        }

    })

    ECommentItem = BaseItem.extend({

        events : {
            "click .answer-comment" : "setAnswerForm",
            "click .delete-comment" : "deleteComment"
        },
        afterInitialize : function(args) {
            this.model.on("navigate", this.navigate, this);
        },
        deleteComment : function(event) {
            if (confirm(Yii.t("main","Вы уверены?"))) {
                this.model.destroy({
                    wait: true,
                    success : function() {
                        new BaseModel().callSuccess(Yii.t("main","Заметка успешно удалена"));
                    }
                });
            }
        },
        navigate : function() {
            $(Yii.app.currentController.modal ? ".wrapper #controller_modal" : document.body).animate({
                'scrollTop':   $(this.el).offset().top - 100
            }, 1000);
        },
        setAnswerForm : function(event) {

            var that = this;

            var m = new EComment(
                {
                    parent_id : that.model.get("id"),
                    target_id : that.parent.options.target_id,
                    target_type : that.parent.options.target_type,
                    comment : null
                }
            );

            var cForm = new ECommentsForm({
                model : m,
                template : $("#comment_form"),
                data : "item",
                parent : this.parent
            });
            $(this.el).find(".answer-form-container").html($(cForm.render().el));
        }

    })

    ECommentsForm = BaseItem.extend({
        events : {
            "click .send-comment" : "submitComment",
            "click .label-answer .close" : "closeForm"
        },
        closeForm : function(event) {
            $(this.el).remove();
        },
        submitComment : function(event)
        {
            var that = this;

            if (!that.parent.options.canComment)
            {
                return false;
            }

            var data = Backbone.Syphon.serialize(this);

            var submitComment = new BaseModel(this.model.toJSON(), {
                yModel : "Comments"
            });

            submitComment.save(data, {
                success:function(model, response, options) {

                    if (that.model.get("parent_id")) {
                        that.closeForm(null);
                    }

                    that.parent.collection.add(response);
                    that.parent.collection.findWhere({
                        id : response.id
                    }).trigger("navigate");

                    that.model.trigger("change");
                }
            });
        }
    })

})