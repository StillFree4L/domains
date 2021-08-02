$(function() {

    EForm = BaseWidget.extend({

        defaultOptions : {
            validationIcons : false,
            uploader : null
        },
        formVariable : null,
        inputs : [],
        events : {
            "submit" : "submitForm"
        },
        onSuccess : null,
        onError : null,
        method : "post",
        _initialize : function(args) {

            this.onSuccess = args.onSuccess;
            this.onError = args.onError;

        },

        render : function() {

            var that = this;

            if ($(this.el).attr("method")) {
                this.method = $(this.el).attr("method");
            }

            _(this.model.attributes).each(function(attr, k) {

                // Создаем экземпляр инпута

                if ($(that.el).find(".form-group[attribute='"+k+"']").length) {
                    var i = new Input({
                        el : $(that.el).find(".form-group[attribute='"+k+"']"),
                        parent : that,
                        attribute : k,
                        model : that.model,
                        data : "model"
                    }).render();

                    that.inputs.push(i);
                }

            });

            this.model.on("change", this.destroyPopover, this);

            return this;

        },
        submitForm : function(event) {

            var that = this;

            event.preventDefault();

            $(event.currentTarget).find("input[type='submit']").popover("destroy");

            if (this.options.uploader) {
                if (!this.options.uploader.isFinished()) {
                    $(event.currentTarget).find("input[type='submit']").popover({
                        content : Yii.t("main","Файл еще не загрузился на сервер. Пожалуйста, подождите окончания загрузки"),
                        trigger : "manual"
                    }).popover("show");
                    return false;
                }
            }

            _(this.model.attributes).each(function(a, k) {
                if (a === null) {

                    that.model.set(k, "");
                }
            })

            var data = Backbone.Syphon.serialize(this);

            if (this.method == "post") {

                this.model.setUrl($(this.el).attr("action"), true);
                Yii.app.loading(true);
                this.model.save(data, {
                    success : function(model, response, options) {
                        Yii.app.loading(false);
                        if (typeof that.onSuccess == "function") {
                            that.onSuccess(model, response, options);
                        }
                    },
                    error : function(model, xhr, response) {
                        Yii.app.loading(false);
                        that.model.callFormError(xhr);
                        _(that.inputs).each(function(i) {
                            i.render();
                        });
                        if (typeof that.onError == "function") {
                            that.onError(model, xhr, response);
                        }
                    }
                })
            } else {
                Yii.app.navigate(Yii.app.createUrl($(this.el).attr("action"), data));
            }

            /*
            } else {

                $(event.currentTarget).find("input[type='submit']").popover({
                    content : Yii.t("main","Сначало исправьте ошибки в заполнении"),
                    trigger : "manual"
                }).popover("show");

            } */

            return false;

        },
        destroyPopover : function(m) {
            $(this.el).find("input[type='submit']").popover("destroy");
        }
    });

    Input = BaseItem.extend({
        attribute : null,
        events : {
            "change input, change select" : "changeAttribute"
        },
        _initialize : function(args) {
            this.parent = args.parent;
            this.setModelEvent();
        },
        render : function() {

            if ($(this.el).find("input[name='"+this.attribute+"'], select[name='"+this.attribute+"']").not(":checkbox").not(":radio").length && this.model.get(this.attribute)) {
                $(this.el).find("input, select").val(this.model.get(this.attribute));
            }

            $(this.el).removeClass("has-error").removeClass("has-feedback");
            $(this.el).find(".messages").remove();
            $(this.el).find(".form-control-feedback").remove();

            var add_class = false;
            var add_html = false;
            var errors = this.model.getError(this.attribute);
            var msgs = "";
            if (errors !== false)
            {
                add_class = "has-error has-feedback";
                add_html = "<span class=\"glyphicon glyphicon-remove form-control-feedback\"></span>";


                _(errors).each(function(e) {
                    msgs += "<p class='text-danger error'>" + e + "</p>";
                })

            } else if (this.model.getSuccess(this.attribute) !== false) {
                add_class = "has-success has-feedback";
                add_html = "<span class=\"glyphicon glyphicon-ok form-control-feedback\"></span>";
            }

            if (add_class) {
                $(this.el).addClass(add_class);
            }

            if (this.parent.options.validationIcons && add_html && $(this.el).find("input, select").length) {
                $(this.el).find("input, select").after(add_html);
            }
            if (msgs != "" ) {
                $(this.el).find("input, select").after("<div class='messages'><strong>"+msgs+"</strong></div>");
            }

            return this;

        },
        beforeInitialize : function(args) {
            this.attribute = args.attribute;
        },
        changeAttribute : function(event) {
            this.model.set(this.attribute, $(event.currentTarget).attr("value"));
        },
        setModelEvent : function() {
            // При изменении модели представления, полностью перерисовываем его
            this.model.on("change:"+this.attribute, this.validateAndRender, this);
        },
        validateAndRender : function(m) {
            this.model.validate();
            this.render();
        }
    })

})