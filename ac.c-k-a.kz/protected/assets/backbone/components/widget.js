$(function() {

    BaseWidget = BaseComponent.extend({

        defaultOptions: {},
        options: {},
        defineArguments: function (args) {
            this.options = {};
            if (typeof args == "undefined") {
                args = {};
            }
            if (typeof args.options == "undefined") {
                args.options = {};
            }
            this.options = $.extend(_.clone(this.defaultOptions), args.options);
        },
        loadTemplate: function (name, callback) {
            Yii.app.loadTemplate(BACKBONE_ASSETS + "/templates/" + name + ".html", callback);
        }

    })

})