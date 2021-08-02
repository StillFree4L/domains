$(function() {

    /**
     * Базовое представление для отображения шаблонов
     * @type Backbone.View
     */
    BaseItem = BaseComponent.extend({
        // Указатель JQuery на script с шаблоном
        template: null,
        sortSelector : null,
        sortType : "ASC",
        // Вьюха родителя
        parent: null,
        // Модель представления
        model: null,
        // Новая ли запись и стоит ее добавить в ДОМ или изменить уже готовый элемент
        isNewView: true,
        // События
        events: {},
        data: "json",
        /**
         * Инициализация переданных аргументов в представление
         * @param args
         */
        _initialize: function (args) {
            this.parent = args.parent;
            if (args.data) {
                this.data = args.data;
            }

            if (args.template) {
                this.template = args.template;
            }

            if (args.sortSelector) {
                this.sortSelector = args.sortSelector;
            }

            if (args.sortType) {
                this.sortType = args.sortType;
            }

            if (this.template) {
                if (this.template instanceof jQuery || $(this.template).length) {
                    this.template = _.template($(this.template).html());
                } else {
                    throw "No " + this.template + " template present in DOM";
                }
            }

            this.setModelEvent();
            return this;
        },
        /**
         * Устанавливает событие на изменение модели, если вам нужны свои события, переопределите метод
         */
        setModelEvent: function () {
            // При изменении модели представления, полностью перерисовываем его
            this.model.on("change", this.render, this)
        },
        /**
         * Прорисовка представления на основании шаблона
         * @returns {BaseItem}
         */
        _render: function () {

            var that = this;
            if (this.template) {
                if (this.isNewView) {
                    var html = this.template({
                        data: this.getTemplateData()
                    });
                    this.setElement($(html));
                } else if (!this.isNewView) {
                    var html = this.template({
                        data: this.getTemplateData()
                    });

                    $(this.el).html($(html).html());
                    if ($(this.el).attr("sort-index") != undefined) {

                        this.appendTo($(this.el).parent());
                    }
                    for (i = 0; i < $(html)[0].attributes.length; i++) {
                        var a = $(html)[0].attributes[i];
                        $(that.el).attr(a.name, a.value);
                    }
                }
            }

            if (this.isNewView) {
                this.isNewView = false;
            }

            return this;
        },
        getTemplateData: function () {

            switch (this.data) {
                case "json":
                    return this.model.toJSON();
                    break;
                case "model" :
                    return this.model;
                    break;
                case "item" :
                    return this;
                    break;
                default:
                    return this.model.toJSON();
                    break;
            }

        },
        /**
         * Метод не вызывается автоматически. вызывите его после render()
         * для того чтобы произвести действия с уже вставленным элементом в DOM
         * Это нужно для того чтобы работали такие методы как
         * height()
         * width()
         * offset().left
         * offset().top
         * и тд.
         */
        afterAppend: function () {

        },
        /**
         * Вызывается при клике на представления, если в событиях указан events : { "click" : "toggleSelection" }
         */
        toggleSelection: function () {
            if (this.model.get('selected') == 0) {
                this._select();
            } else {
                this._unselect();
            }
        },
        /**
         * Помечает аттрибут selected в модели  = 0
         * @private
         */
        _unselect: function () {
            this.model.set("selected", 0);
        },
        /**
         * Помечает аттрибут selected в модели = 1
         * @private
         */
        _select: function () {
            this.model.set("selected", 1);
        },
        /**
         * Вызывается при изменении выбора. УСТАРЕЛ
         * @deprecated
         * @param model
         * @private
         */
        _selectionChanged: function (model) {

        },
        /**
         * Вызывается при удалении модели из родительской коллекции ( пр: список элементов )
         * @param event
         */
        removeItem: function (event) {
            if (this.parent && this.parent.collection) {
                this.parent.collection.remove(this.model);
                $(this.el).remove();
            }
        },
        appendTo : function(p) {
            var that = this;
            var el = this.el;
            var before = $(p).find(this.sortSelector).filter(function() {
                if (that.sortType == "ASC") {
                    return $(this).attr("sort-index") * 1 > $(el).attr("sort-index") * 1;
                } else {
                    return $(this).attr("sort-index") * 1 > $(el).attr("sort-index") * 1;
                }
            });
            if (that.sortType == "ASC") {
                before = $(before).first();
            } else {
                before = $(before).last();
            }
            if ($(before).length) {
                if (that.sortType == "ASC") {
                    $(before).before($(el));
                } else {
                    $(before).after($(el));
                }
            } else {
                if (that.sortType == "ASC") {
                    $(p).append($(el));
                } else {
                    $(p).prepend($(el));
                }
            }
        }
    });
});