$(function() {

    BaseValidation = BaseComponent.extend({
        error: null,
        options: null,
        model: null,
        _initialize: function (args) {
            this.options = args;
        },
        setError: function (options) {

            var that = this;

            var e = Yii.t("main", this.error);
            _(options).each(function (o, k) {

                if (typeof that.model.attributeLabels[o] != "undefined") {
                    o = that.model.attributeLabels[o];
                }

                e = e.replace(k, o);
            })
            this.error = e;
            return _.clone(this.error);
        }

    });

    RequiredValidation = BaseValidation.extend({
        error: {},
        validate: function (field, value) {

            if (!this.options.message) {
                this.error = "{attribute} cannot be blank.";
                if (this.options.requiredValue) this.error = "{attribute} must be {value}.";
            } else this.error = this.options.message;

            if (this.options.strict) {
                if (this.options.requiredValue) {
                    if (value !== this.options.requiredValue) {

                        return this.setError({
                            "{attribute}": field,
                            "{value}": this.options.requiredValue
                        });
                    }
                }
            } else {
                if (this.options.requiredValue) {
                    if (field != this.options.requiredValue) {
                        return this.setError({
                            "{attribute}": field,
                            "{value}": this.options.requiredValue
                        });
                    }
                }
            }

            if (value === "") {
                return this.setError({
                    "{attribute}": field
                });
            }

            return true;

        }

    });

    CompareValidation = BaseValidation.extend({
        error: null,
        afterInitialize: function (args) {

            var that = this;

            if (typeof this.options.compareAttribute == 'undefined') {
                throw "no compareAttribute in compare validation. Please fix model";
            }

            this.model.on("change:" + this.options.compareAttribute, function () {
                _(that.options.fields).each(function (f) {
                    that.model.trigger("change:" + f);
                })
            });

        },
        validate: function (field, value) {

            if (!this.options.message) {
                this.error = '{attribute} must be repeated exactly.';
            } else this.error = this.options.message;

            var that = this;
            // Вешаем изменение на сравниваемый аттрибут

            var compareAttribute = this.model.get(this.options.compareAttribute);

            if (value != compareAttribute) {
                return this.setError({
                    "{attribute}": field
                });
            }

            return true;

        }
    });

    EmailValidation = BaseValidation.extend({
        error: {},
        pattern: /^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/,
        validate: function (field, value) {

            if (!this.options.message) {
                this.error = "{attribute} incorrect email.";
            } else this.error = this.options.message;

            // make sure string length is limited to avoid DOS attacks
            var valid = false;
            if (typeof value == "string" && value.length <= 254) {
                var thisRegex = new RegExp(this.pattern);
                valid = thisRegex.test(value);
            }

            if (valid) {
                return true;
            } else {
                return this.setError({
                    "{attribute}": field
                });
            }

        }

    });

    LengthValidation = BaseValidation.extend({
        error: {},
        validate: function (field, value) {

            if (!this.options.message) {
                this.error = "{attribute} incorect length.";
            } else this.error = this.options.message;

            if (typeof value != 'undefined' && this.options.min && value.length < this.options.min) {
                return this.setError({
                    "{attribute}": field,
                    "{min}": this.options.min
                });
            }
            if (typeof value != 'undefined' && this.options.max && value.length > this.options.max) {
                return this.setError({
                    "{attribute}": field,
                    "{max}": this.options.max
                });
            }

            return true;

        }
    });

    NumericalValidation = BaseValidation.extend({
        error: {},
        IsNumeric: function (input) {
            return (input - 0) == input && ('' + input).replace(/^\s+|\s+$/g, "").length > 0;
        },
        validate: function (field, value) {

            if (!this.options.message) {
                this.error = "{attribute} должен быть числом.";
            } else this.error = this.options.message;

            if (typeof value != 'undefined' && !this.IsNumeric(value)) {
                return this.setError({
                    "{attribute}": field
                });
            }
            return true;

        }
    })

});
