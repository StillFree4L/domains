$(function() {

    /**
     * Базовая модель
     * @type Backbone.Model
     */
    BaseModel = Backbone.Model.extend({
        yModel : "BaseModel",
        sync : BaseSync,
        defaults : {
            "selected" : 0
        },
        url:URL_ROOT + "/backbone/request",
        rules : [],
        attributeLabels : null,
        fileAttribute: 'file',
        initialize : function(attributes, options)
        {
            if (options && options.yModel) {
                this.yModel = options.yModel
            }
            this.on("error", this.error, this);
            this.on("success", this.success, this);
        },
        setRules : function(rules) {

            var that = this;

            that.rules = [];
            _(rules).each(function(r) {

                // Получаем класс валидации
                var validation = r[1].charAt(0).toUpperCase() + r[1].slice(1) + "Validation";

                if (typeof window[validation] != 'undefined')
                {

                    var fields = r[0];
                    _(fields).each(function(f, k) {
                         fields[k] = f.trim();
                    })
                    var _r = _.clone(r); delete(_r[0]); delete(_r[1]);
                    _r.fields = fields;
                    _r.model = that;
                    var v = new window[validation](_r);


                    that.rules.push(v);

                }


            });
        },
        errors : {

        },
        successes : [],
        getError : function(attribute) {
            if (typeof this.errors[attribute] != 'undefined') {
                return this.errors[attribute];
            }
            return false;
        },
        getSuccess : function(attribute) {
            if (this.successes.indexOf(attribute) !== -1) return true;
            return false;
        },
        setAttributeLabels : function(attributeLabels) {
            this.attributeLabels = attributeLabels;
        },
        setUrl: function(url, absolute) {
            if (!absolute) {
                this.url = this.url + url;
            } else {
                this.url = url;
            }
        },
        callError: function(response) {
            if (response.responseText) {
                var errors = JSON.parse(response.responseText);
                for (var n in errors) {
                    $.jGrowl(errors[n][0], {
                        sticky:false,
                        theme:'error',
                        life:6000
                    });
                }
            }
        },
        callFormError: function(xhr) {
            var that = this;
            if (xhr.responseText) {
                var errors = JSON.parse(xhr.responseText);
                for (var n in errors) {
                    that.errors[n] = errors[n];
                }
            }
        },
        callSuccess: function(message)
        {
            $.jGrowl(message, {
                sticky:false,
                theme:'ok',
                life:6000
            });
        },
        validate : function() {
            var that = this;

            that.errors = {};
            that.successes = [];

            _(that.rules).each(function(r) {
                // Получаем класс валидации
                _(r.options.fields).each(function(f) {

                    var gf = that.get(f);

                    if (gf !== null) {

                        result = r.validate(f, gf);

                        if (result !== true) {

                            if (typeof that.errors[f] == 'undefined') that.errors[f] = [];
                            that.errors[f].push(result);
                            if (that.getSuccess(f) !== false) delete(that.successes[that.successes.indexOf(f)]);
                            return "some errors";

                        } else {

                            if (that.getError(f) === false) {
                                if (that.successes.indexOf(f) === -1) that.successes.push(f);
                            }

                        }

                    }
                });

            })

        }
});

})
