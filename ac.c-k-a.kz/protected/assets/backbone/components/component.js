$(function() {

    /**
     * Класс базового компонента, который наследуют контроллер, действие, представление
     * @type {*|void|extend|extend|extend|extend}
     */
    BaseComponent = Backbone.View.extend({

        events: {},
        defineArguments: function (args) {

        },
        initialize: function (args) {
            this.defineArguments(args);
            this.beforeInitialize(args);
            if (typeof args != "undefined" && args.events) {
                this.events = args.events;
                delete(args.events);
            }

            this._initialize(args);
            this.afterInitialize(args);
            return this;
        },

        _initialize: function (args) {

        },

        /**
         *  Вызыывается перед инициализацией контроллера
         */
        beforeInitialize: function (args) {

        },

        /**
         * Вызывается после инициализации контроллера
         * @param args
         */
        afterInitialize: function (args) {

        },

        render: function () {
            this.beforeRender();
            this._render();
            this.afterRender();
            return this;
        },

        _render: function () {

        },

        /**
         * Вызывается перед рендерингом контроллера
         */
        beforeRender: function () {

        },

        /**
         * Вызывается после рендеринга контроллера
         */
        afterRender: function () {

        }

    })
});