$(function() {

    // Стартуем хистори
    Backbone.history.start({ pushState: true });

    // Кэширование запросов
    $.ajaxSetup({
        cache: true
    });

    /**
     * Логируем сообщения, если дебаг
     * @param msg
     */

    if (!DEBUG) {
        console = {};
        console.log = function(){};
    }

    log = function(msg) {
        if (DEBUG) {
            console.log(msg);
        }
    }

    /**
     * Главный класс приложения
     * @type {*}
     */
    BaseApplication = Backbone.View.extend({
        backbone_bundle : null,
        defaultController : "main",
        // Текущий загруженный контроллер
        currentController : null,
        // Элемент контроллера .controller-content по умолчанию
        controllerEl : null,
        innerEl : null,
        // Загруженные скрипты и стили
        assets : {
            css : [],
            js : [],
            jsScripts : {}
        },
        // Виджеты приложения
        widgets : {

        },
        templates : {

        },
        user : {

        },
        poll : null,
        stepsInModal : 0,
        targetTypes : {
            user : 1,
            platform : 2,
            order : 3,
            rivalPlatform : 4,
            rivalOrder : 5,
            chat : 6
        },
        top : 0,
        // Инициализация приложения
        initialize : function(args) {

            var that = this;
            this.controllerEl = args.controllerEl;
            this.innerEl = args.innerEl;
            this.backbone_bundle = args.backbone_bundle;

            $("body").on("click", "a[href^='/']", function(event) {
                return that.navigate(event);
            })


            if (TRACKING_CODE) {
                window.ga = window.ga || function () {
                    (ga.q = ga.q || []).push(arguments)
                };
                ga.l = +new Date;
                ga('create', TRACKING_CODE, 'auto');
            }


            // Инициируем лонг поллинг
            this.poll = new Poll();

            // Если меняется состояние текущего урла
            window.onpopstate = function(event) {

                if(event.state){

                    var state = event.state;
                    if (state.url) {
                        Yii.app.navigate(state.url, null, {
                            scroll : false
                        });
                    }
                    /*if (typeof state == "string" && typeof Storage != "undefined") {
                     state = JSON.parse(localStorage.getItem(event.state));
                     }

                     $("body").removeClass("modal-open");
                     // Востанавливаем ХТМЛ из истории
                     $(that.innerEl).html(state.html);

                     $(".modal-backdrop").remove();

                     var renderController = function()
                     {

                     Yii.app.currentController = null;
                     Yii.app.currentController = new window[state.name]({
                     el : state.target == "modal" ? $("#controller_modal") : $(that.el).find(that.controllerEl),
                     name : state.name,
                     url : state.url,
                     model : state.model,
                     baseUrl : state.baseUrl,
                     target : state.target
                     });
                     Yii.app.currentController.render();

                     }

                     // Если вдруг файл контроллера не подключен, то подключаем его
                     if (typeof window[state.name] == 'undefined') {
                     $.getScript( that.backbone_bundle + "/controllers/" + state.name + ".js", function( data, textStatus, jqxhr ) {
                     renderController();
                     });
                     } else {
                     renderController();
                     }*/

                }
            };

            // Логим подключеные скрипты и стили, для последующей проверки, чтобы не подключать 2 раза одно и тоже
            $("html script[src!='']").each(function() {
                if ($(this).attr("src") != undefined) that.assets.js.push($(this).attr("src"));
            })
            $("html script[type='text/javascript'][id!='']").each(function() {
                if ($(this).attr("id") != undefined) {
                    that.assets.js.push($(this).attr("id"));
                    that.assets.jsScripts[$(this).attr("id")] = $(this).html();
                }

            })
            $("head link").each(function() {
                if ($(this).attr("href") != undefined) that.assets.css.push($(this).attr("href"));
            })



        },
        render : function()
        {
            // Проверяем есть ли вызванный модал
            if (typeof $_GET['z'] != 'undefined') {
                this.navigate($_GET['z'], "modal");
            }

            if (Yii.app.user.isGuest === false && !Yii.app.user.in_test) {
                this.chat = new EChat({});
                this.chat.render();
            }

        },
        /**
         * Направляет по урлу через АЯКС
         * @param event - нажатая ссылка, либо урл
         * @returns {boolean}
         */
        navigate : function(event, target, options) {

            _o = {
                scroll : true
            }
            options = _.extend(_o, options);

            var that = this;
            // Splitting event href to get controller
            // Если передан контроллер, то подгружаем его
            // TODO - сделать более гибкую парсилку урла
            if (typeof event == "string") {
                var url = event;
                if (target == "_blank")
                {
                    window.open(event, '_blank');
                    return false;
                }
                var href = String(event).split("/");
            } else {
                var link = $(event.currentTarget);
                var url = $(link).attr("href");
                if ($(link).attr("confirm")) {
                    if (!confirm($(link).attr("confirm"))) {
                        return false;
                    }
                }

                href = $(link).attr('href').split("/")
                target = $(link).attr("target");

                if ($(link).attr("noscroll")) {
                    options.scroll = false;
                }

                if (target == "_parent" || $("head base").attr("target")=="_parent") {
                    return true;
                }

                if (target == "_blank")
                {
                    return true;
                }

                if (target == "_full")
                {
                    window.location.href = href.join("/");
                    return false;
                }

            }

            if (!target && (Yii.app.currentController != null && Yii.app.currentController.target == "modal")) {
                options['scroll'] = false;
                target = "modal";
            } else if (target == "normal" && Yii.app.currentController != null && Yii.app.currentController.target == "modal") {
                Yii.app.currentController.navigating = true;
                $(Yii.app.currentController.el).modal("hide");
            }

            if (!href[1]) {
                href[1] = this.defaultController;
            }

            var controller = href[1].charAt(0).toUpperCase() + href[1].slice(1) + "Controller";

            that.loadController(controller, href.join("/"), target, options);

            return false;
        },
        /**
         * Загружает скрипт контроллера и вызывает его
         * @param controller
         * @param href
         */
        loadController : function(controller, href, target, options) {
            var that = this;
            /**
             * Рендерит контроллер
             * @param controller
             * @param href
             */
            var renderCurrentController = function() {
                var baseUrl = "";
                if (target == "modal" && that.currentController.target != "modal")
                {
                    baseUrl = that.currentController.url;
                } else if (that.currentController.target == "modal")
                {
                    baseUrl = that.currentController.baseUrl;
                }

                // Удаляем модальное окно, если текущий контроллер открыт в модальном окне
                var c = new window[controller]({
                    template : target == "modal" ? "#controller_modal_template" : "#controller_template",
                    name : controller,
                    url : href,
                    baseUrl : baseUrl,
                    target : target
                });

                c.scrollTop = options.scroll;
                c.load(function(state) {
                    if (state == 1) {
                        that.pushState(c);
                        that.currentController = c;
                    }
                    if (state == 2)
                    {
                        if (!c.noState) {
                            that.replaceState(c);
                        }
                        that.currentController = c;
                    }
                });


            }

            // Проверяем не загружен ли скрипт контроллера, если нет то подгружаем
            if (typeof window[controller] == 'undefined') {
                $.getScript( BACKBONE_ASSETS + "/controllers/" + controller + ".js", function( data, textStatus, jqxhr ) {
                    renderCurrentController();
                }).fail(function(jqxhr, settings, exception ) {
                    $.jGrowl(exception.message, {
                        sticky:false,
                        theme:'error',
                        life:6000
                    });
                });
            } else {
                renderCurrentController();
            }
        },
        /**
         *
         * TODO Доработать до нормального состояния
         *
         * Загружает яваскриптовый виджет
         * @param name
         */
        widget : function(name, args, callback) {
            var widget_path = BACKBONE_ASSETS + "/widgets/" + name + ".js";

            var returnWidget = function() {
                if (typeof(callback) == "function") {
                    if (window[name]) {
                        callback(new window[name](args));
                    }
                }
            }

            if (typeof window[name] != 'undefined') {
                return returnWidget();
            } else {
                this.loadScript(widget_path, function( data, textStatus, jqxhr )
                {
                    return returnWidget();
                });
            }
        },
        /**
         *
         * TODO Доработать до нормального состояния
         *
         * @param name
         * @param callback
         */
        loadTemplate : function(name, callback) {
            var that = this;
            var returnTemplate = function() {
                callback(that.templates[name]);
            }
            if (typeof this.templates[name] != "undefined")
            {
                returnTemplate();
            } else {
                $.get(name, function (data) {
                    that.templates[name] = data;
                    returnTemplate();
                });
            }
        },
        /**
         * Загружает скрипт, проверяет если не загружен
         * @param script
         * @param callback
         */
        loadScript : function(script, callback) {
            var that = this;

            var script_name = script;
            if (typeof script == "object") {
                script_name = script.id;
            }

            if (this.assets.js.indexOf(script_name) === -1 || (typeof script == "object" && script.force)) {
                if (typeof script == 'object') {
                    eval(script.script);
                    if (this.assets.js.indexOf(script_name) === -1) {
                        that.assets.js.push(script_name);
                    }
                    if (typeof callback == 'function') {
                        callback();
                    }
                } else {

                    $.getScript(script, function( data, textStatus, jqxhr ) {
                        that.assets.js.push(script);
                        if (typeof callback == 'function') {
                            callback( data, textStatus, jqxhr );
                        }
                    }).fail(function(jqxhr, settings, exception ) {
                        if (typeof callback == 'function') {
                            callback(jqxhr, settings, exception);
                        }
                        $.jGrowl(exception.message, {
                            sticky:false,
                            theme:'error',
                            life:6000
                        });
                    });
                }

            } else {
                if (typeof callback == 'function') {
                    callback();
                }
            }
        },
        /**
         * Загружает стили, если не загружен
         * @param style
         * @param callback
         */
        loadStyle : function(style, callback) {
            if (this.assets.css.indexOf(style) === -1) {
                $('<link/>', {
                    rel: 'stylesheet',
                    type: 'text/css',
                    href: style
                }).appendTo('head');
                this.assets.css.push(style);

            } else {

            }
            if (typeof callback == 'function') {
                callback()
            }
        },
        loading : function(load) {
            if ($("body").find(".modal-backdrop").length) {
                $("body").find(".loading_body").addClass("loading_white");
            } else {
                $("body").find(".loading_body").removeClass("loading_white");
            }
            if (load) {
                $("body").find(".loading_body").show();
            } else {
                $("body").find(".loading_body").hide();
            }
        },

        /**
         * Добавляет указатель на текущую страницу в историю
         */
        pushState : function(c) {
            if (c.target == "modal") {
                this.stepsInModal = this.stepsInModal + 1;
            } else {
                this.stepsInModal = 0;
            }

            this._state("pushState", c);

        },
        /**
         * Заменяет указатель истории, если произошли изменения в модели
         */
        replaceState : function(c) {
            this._state("replaceState", c);

        },

        _state : function(state, c)
        {

            var url = c.url;
            var data = {
                url : c.url
            }
            if (c.target == "modal")
            {
                data.modal = true;
                var amp = String(c.baseUrl).split("?");
                if (amp.length>1) {
                    amp = "&";
                } else {
                    amp = "?";
                }

                url = c.baseUrl + amp + "z=" + encodeURIComponent(c.url);
                data.url = c.baseUrl;
                data.zUrl = c.url;

            }

            /*
             var attrs = {
             "html":$(this.innerEl).html(),
             "name":c.name,
             "url" : c.url,
             "baseUrl" : c.baseUrl,
             "target" : c.target
             }
             if (c.model) {
             attrs.model = c.model.toJSON();
             } else {
             attrs.model = [];
             }

             var url = c.url;
             if (c.target == "modal")
             {

             var amp = String(c.baseUrl).split("?");
             if (amp.length>1) {
             amp = "&";
             } else {
             amp = "?";
             }

             url = c.baseUrl + amp + "z=" + encodeURIComponent(c.url);


             }

             var data = url;
             if(typeof(Storage) !== "undefined") {
             localStorage.setItem(url, JSON.stringify(attrs));
             } else {
             data = attrs;
             } */
            window.history[state](data, '1', url);
        }

    });

    Yii = typeof Yii != "undefined" ? Yii : {};

})