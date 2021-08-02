$(function() {

    /**
     * Базовый класс контроллера
     * @type {*|void|extend|extend|extend|extend}
     */
    BaseController = BaseComponent.extend({
        // Название контроллера
        name : null,
        // Загружен ли контроллера (пришел не через аякс)
        loaded : false,
        // Текущий урл контроллера
        url : null,
        // Базовый урл ( в случае модального окна )
        baseUrl : null,
        // Модель контроллера
        model : null,
        // Класс базовой модели контроллера
        modelClass : "BaseModel",

        action : null,

        target : null,

        template : null,

        noState : false,

        sync : BaseSync,

        scrollTop : false,

        /**
         * Инициализация контроллера
         * @param args
         * @returns {boolean}
         */
        _initialize : function(args) {

            Yii.app.poll.clear();
            if (typeof args.url != 'undefined') {
                this.url = args.url;
            } else {
                $.jGrowl("Set controller url", {
                    sticky:false,
                    theme:'error',
                    life:6000
                });
                //return false; //asd
            }

            this.app = args.app;

            if (typeof args.baseUrl != 'undefined') {
                this.baseUrl = args.baseUrl;
            }

            if (typeof args.noState != 'undefined') {
                this.noState = args.noState;
            }

            if (typeof args.target != 'undefined') {
                this.target = args.target;
            }

            if (typeof args.name != 'undefined') {
                this.name = args.name;
            } else {
                $.jGrowl("Set controller name", {
                    sticky:false,
                    theme:'error',
                    life:6000
                });
                //return false;
            }
            if (typeof args.loaded != 'undefined') {
                this.loaded = args.loaded;
            }

            if (args.template) {
                if ($(args.template).length) {
                    this.template = _.template($(args.template).html());
                }
            }

            this.initializeModel();
        },


        /**
         * Рендеринг контроллера, регистрируются скрипты, определяется действие
         * действие определяется на основании аттрибута модели (action) и собирается
         * из слов "action" + model.action с большой буквы ( пр : actionIndex )
         */
        _render : function() {

            //this.breadcrumbs();
            this.getFlash();

            var that = this;
            this.registerScripts(function(){

                // Получаем действие контроллера из модели
                var a = that.model.get("action");
                if (a) {

                    // Составляем строку метода действия типа actionIndex
                    var a_words = a.split("-");
                    if (a_words.length > 1) {
                        a = "";
                        _(a_words).each(function(w) {
                            a = a + w.charAt(0).toUpperCase() + w.slice(1);
                        })
                    }
                    var action = "action" + a.charAt(0).toUpperCase() + a.slice(1);


                    // Проверяем на наличие обьекта представления действия
                    if (typeof that[action] != "undefined") {

                        // Проверяем если в модели указан элемент действия для представления
                        var ael = that.model.get("action_element");
                        if (!ael)
                        {
                            ael = ".action-content";
                        }

                        if (!$(that.el).find(ael).length)
                        {
                            throw "No action element present '" + ael + "' in DOM";
                        }

                        var f = [
                            "function (){ return parent.apply(this, arguments); } ",
                            "function () { return parent.apply(this, arguments); }",
                            "function(){return c.apply(this,arguments)}",
                            "function (){ return parent.apply(this, arguments); }",
                            "function (){return c.apply(this,arguments)}",
                            "function () {return c.apply(this,arguments);}",
                            "function(){ return parent.apply(this, arguments); }"
                        ]

                        if (f.indexOf(that[action].toString(-1)) !== -1){
                            // Создаем экземпляр представления действия
                            that.action = new that[action]({
                                el : $(that.el).find(ael),
                                controller : that
                            });
                            that.action.render();
                            that.action.ga();
                        } else {
                            that[action].call(that,$(that.el).find(ael));
                            that.ga();
                        }

                    } else {

                        // Если ни класса, ни метода нет, вызываем стандартный метод контроллера _action
                        that._action(action);
                        that.ga();
                    }


                }
                //$(that.el).addClass("fadein");

                // Если контроллер запущен в модальном окне
                if (that.target == "modal") {
                    // Показываем модальное окно
                    $(".modal").on('shown.bs.modal', function () {
                        Yii.app.top = $(window).scrollTop();
                        $("body").css({overflow:"hidden"});
                        $(".wrapper").css({marginTop:"-"+Yii.app.top+"px"});
                    });

                    // Показываем модальное окно
                    $(that.el).modal("show");

                    $(".modal").on("hidden.bs.modal", function() {
                        $("body").css({overflow:"visible"});
                        $(".wrapper").css({marginTop:0});
                        $(window).scrollTop(Yii.app.top);

                        if (!that.navigating) {
                            that.target = null;
                            Yii.app.navigate(that.baseUrl, null, {
                                scroll: false
                            });
                        }

                        //Yii.app.currentController.target = null;
                        //window.history.go(0 - Yii.app.stepsInModal);
                    })

                } else {
                    // Выставляем титл страницы
                    document.title = that.model.get("pageTitle");
                    if (that.scrollTop) {
                        $(window).scrollTop(0);
                    }  else {
                        if (Yii.app.top > 0) {
                            $(window).scrollTop(Yii.app.top);
                        }
                        Yii.app.top = 0;
                    }

                }



            });

            Yii.app.poll.start();
            $("*[data-toggle='tooltip']").tooltip();

            $("#page_generation_time").html(this.model.get("generationTime"));
            Yii.app.trigger("controllerRendered");
        },
        /**
         * Базовый метод действия
         * @param action
         * @private
         */
        _action : function(action) {

        },
        /**
         * Если действие не обьект, то выполняем аналитику с контроллера
         */
        ga : function() {
            if (typeof ga != "undefined") {
                ga('send', {
                    'hitType': 'pageview',
                    'page': this.url
                });
            }
        },
        /**
         * Загрузка данных контроллера аяксом
         * Подгружаются :
         * html контроллера
         * model - модель контроллера, которая содержит всю необходимую информацию
         */
        load : function(callback) {
            var that = this;

            // Если данные контроллера еще не загружены
            if (!this.loaded) {

                Yii.app.loading(true);

                // Вызываем аяксом данные по контроллеру
                $.ajax({
                    type : "post",
                    data : {
                        target : that.target
                    },
                    url : this.url,
                    dataType : "json",
                    success : function(response) {
                        // Подключаем возвращенную модель
                        that.loadModel(response.model);
                        // Выводим сообщения
                        // Если вернулось перенаправление, то перенаправляем
                        if (response.redirect) {
                            if (response.redirect) {
                                if (response.full) {
                                    window.location.href = response.redirect;
                                } else {
                                    Yii.app.navigate(response.redirect, response.target);
                                }
                            }
                            return false;
                        }

                        if (response.refresh) {
                            if (response.full) {
                                window.location.href = window.location.href;
                            } else {
                                Yii.app.navigate(window.location.href);
                            }
                            return false;
                        }

                        that.removeModal();

                        // Если указан шаблон контроллера, а не элемент, то вызываем шаблон и заполняем данными
                        if (that.template) {

                            var html = that.template({
                                html : response.html,
                                data:that.model.toJSON()
                            });

                            $(response.html).filter('script[type!="text/template"]').each(function(){
                                $.globalEval(this.text || this.textContent || this.innerHTML || '');
                            });

                            // Устанавливаем элемент
                            that.setElement($(html));

                            // Если контроллер загружается в модальном окне, по добавляем в дом, если нет, то заменяем
                            if (that.target == "modal") {
                                $(Yii.app.innerEl).append($(that.el));
                            } else {
                                $(Yii.app.innerEl).find(".body").html($(that.el));
                            }
                        } else {
                            // Если же указан элемент, то заполняем его вернутым хтмлом
                            $(that.el).html(response.html);
                        }
                        // Вызываем метод после загрузки
                        that.afterLoad(response);
                        // Рендерим контроллер
                        that.render();
                        // Добавляем запись в историю
                        callback.call(that, 1);
                        Yii.app.loading(false);

                    },
                    error : function(response) {
                        Yii.app.loading(false);

                        try {

                            var r = JSON.parse(response.responseText);

                            if (r.message) {
                                $.jGrowl(JSON.parse(response.responseText).message, {
                                    sticky: false,
                                    theme: 'error',
                                    life: 6000
                                });
                            } else {
                                window.location.href = this.url;
                            }
                        } catch(error) {
                            window.location.href = this.url;
                        }
                        callback.call(that,3);
                    }
                });

            } else {
                // Если контроллер уже загружен, то рендерим и заменяем запись в истории
                that.render();
                callback.call(that,2);

            }

        },
        /**
         * Инициализирует модель, если контроллер был загружен изначально (не аяксом)
         */
        initializeModel : function() {
            this.model = new window[this.modelClass](this.model);
            this.model.on("change", this.pushState, this);
        },
        /**
         * Инициализирует модель после загрузки контроллера через аякс
         * @param model
         */
        loadModel : function(model) {
            this.model = new window[this.modelClass](model);
        },
        /**
         * Регистрирует подключаемые скрипты и стили
         */
        registerScripts : function(callback) {
            var that = this;
            if (this.model) {
                var css = this.model.get("css");
                var js = this.model.get("js");

                if (css && css.length) {
                    _(css).each(function(css_file) {
                        Yii.app.loadStyle(css_file);
                    });
                }

                var loadSync = function(i) {
                    if (typeof js[i] != 'undefined') {
                        Yii.app.loadScript(js[i], function() {
                            i++;
                            loadSync(i);
                        });
                    } else {
                        if (typeof callback == "function") {
                            callback();
                        }
                    }
                }
                if (js && js.length) {
                    loadSync(0);
                } else {
                    if (typeof callback == "function") {
                        callback();
                    }
                }

            } else {
                if (typeof callback == "function") {
                    callback();
                }
            }

        },
        /**
         * Выводит сообщения зарегистрированные в \Yii::$app->session->setFlash()
         */
        getFlash : function() {
            var flash = this.model.get("flash");
            if (flash) {

                _(flash).each(function(f,k) {
                    $.jGrowl(f, {
                        sticky:false,
                        theme:k,
                        life:6000
                    });
                })
            }
        },
        /**
         * Показать хлебные крошки
         */
        breadcrumbs : function() {
            if (this.target != 'modal') {
                Yii.app.breadcrumbs(this.model.get("breadCrumbs"));
            }
        },
        /**
         * Выполняется после загрузки контроллера
         * @param response - результат аякс запроса
         */
        afterLoad : function(response) {
        },
        removeModal : function() {
            if ($("#controller_modal").length) {
                $(".modal-backdrop").remove();
                $("#controller_modal").remove();
            }
        },
        scroll : function(id) {
            $(document.body).animate({
                'scrollTop':   $("#"+id).offset().top - 100
            }, 300);
        }
    })

})
