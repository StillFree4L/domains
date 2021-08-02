$(function() {

    /**
     * @type {*|void|extend|extend|extend|extend}
     */
    EChat = BaseWidget.extend({
        el : ".chats",
        model : new BaseModel({
            messages : 0
        }, {
            yModel : "Chats"
        }),
        active : false,
        events : {
            "click .chats-icon, .chats-window .close-chats" : function() {
                this.active = false;
                $(".messages-window").hide();
                $(".chats-window").toggle(0);
            },
            "click .messages-window .close-chats" : function() {
                this.active = false;
                $(".messages-window").hide();
            }
        },
        chats : null,
        beforeRender : function() {
            var that = this;
            this.model.on("change", function() {
                _(that.model.get("messages")).each(function(m) {
                    var chat = that.chats.findWhere({
                        id : parseInt(m.target_id)
                    });
                    if (chat) {
                        if (!that.active || that.active.get("id") != chat.get("id")) {
                            chat.set("new_messages", chat.get("new_messages")*1 + 1);
                        }
                        if (chat.view.messages) {
                            chat.view.messages.add(m);
                        }
                    }
                });

                var sum = _(that.model.get("messages")).filter(function(m) {
                    return (!that.active || that.active.get("id") != m.target_id)
                });
                if (sum.length) $(that.el).find(".chats-icon").find(".count").html(sum.length);
                else $(that.el).find(".chats-icon").find(".count").empty();
            });
        },
        afterRender : function() {
            var that = this;
            $(that.el).find(".chat-search-results").empty();
            $(that.el).find(".chats-list").empty();
            var searchCollection = new BaseCollection({}, {
                yModel : "Users",
                model : BaseModel.extend({
                }, {
                    yModel : "Users"
                })
            });
            searchCollection.on("add", function(m) {
                var view = new that.SearchUserView({
                    model : m,
                    parent : that
                });
                m.view = view;
                $(that.el).find(".chat-search-results").append($(view.render().el));

            });
            searchCollection.on("remove", function(m) {
                if (m.view) {
                    m.view.remove();
                }
            });

            this.chats = new BaseCollection({}, {
                yModel : "Chats",
                comparator : function(chat) {
                    return -chat.get("last_ts");
                }
            });
            this.chats.on("add", function(m) {
                if (that.chats.length) {
                    $(".no-chats").hide();
                } else {
                    $(".no-chats").show();
                }
                var view = new that.ChatView({
                    model : m,
                    parent : that,
                    sortSelector : ".chat-item",
                    sortType : "DESC"
                });
                m.view = view;
                view.render().appendTo($(that.el).find(".chats-list"));

            });
            this.chats.on("remove", function(m) {
                if (m.view) {
                    m.view.remove();
                }
            });
            this.model.fetch({
                data : {
                    type : "newMessages",
                    last_ts : 0
                }
            });
            this.chats.fetch({
                success : function(a, b) {
                    Yii.app.poll.addEvent("chat_messages", {
                        yModel : "Chats",
                        type : "newMessages",
                        last_ts : that.model.get("last_ts")
                    }, that.newMessages, that, {
                        global : true,
                        updateAttribute : "last_ts"
                    })
                }
            });

            $(that.el).find(".chat-search-user .user-search").keyup(function() {

                var text = $(this).val();

                if (text == "") {
                    searchCollection.remove(searchCollection.models);
                } else {
                    $(this).stopTime("search");
                    $(this).oneTime(500, "search", function () {
                        searchCollection.fetch({
                            data: {
                                fio: text
                            },
                            remove: true
                        })
                    })
                }

            })

            $(this.el).find(".panel-body").bind( 'mousewheel DOMMouseScroll', function ( e ) {
                var e0 = e.originalEvent,
                    delta = e0.wheelDelta || -e0.detail;

                this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
                e.preventDefault();
            });

        },
        SearchUserView : BaseItem.extend({
            template : "#chat_search_user_template",
            data : "item",
            events : {
                "click" : "createChat"
            },
            createChat : function() {
                var that = this;
                var chat = new BaseModel({}, {
                    yModel : "Chats"
                })

                chat.save({
                    user_id : this.model.get("id")
                }, {
                    success : function(model, response, xhr) {
                        $(that.parent.el).find(".chat-search-user .user-search").val("").keyup();
                        if (!that.parent.chats.findWhere({
                                id : model.get("id")
                            })) {
                            that.parent.chats.add(model);
                            model.view.showMessagesWindow();
                        }
                    }
                })

            }
        }),
        ChatView : BaseItem.extend({
            template : "#chat_template",
            data : "item",
            messages : false,
            events : {
                "click" : "showMessagesWindow"
            },
            messagesWindow : false,
            showMessagesWindow : function() {
                if (!this.messagesWindow) {
                    this.messages = new BaseCollection({}, {
                        yModel : "Comments",
                        comparator : "ts"
                    });
                    this.messages.fetch({
                        data : {
                            noInfo : 1,
                            target_id : this.model.get('id'),
                            target_type : Yii.app.targetTypes.chat
                        }
                    });
                    this.messagesWindow = new this.MessagesWindow({
                        parent : this
                    });
                    $(this.parent.el).append($(this.messagesWindow.render().el));
                }
                $(this.parent.el).find(".messages-window").hide();
                $(this.messagesWindow.el).show();
                this.parent.active = this.model;
                this.model.set("new_messages", 0);
                this.parent.model.trigger("change");
                this.updateView();
                $(this.messagesWindow.el).find(".send-message-input").focus();
            },
            beforeRender : function() {
                if (this.parent.active != this.model) {
                    var messages = _(this.parent.model.get("messages")).where({
                        target_id: this.model.get('id')
                    });
                    if (messages.length > 0) {
                        this.model.set("new_messages", messages.length, {
                            silent: true
                        });
                    }
                }
            },
            updateView : function() {
                var updateView = new BaseModel({
                    id : this.model.get("id")
                }, {
                    yModel : "Chats"
                })
                updateView.save({
                    type : "updateView"
                });
            },
            MessagesWindow : BaseItem.extend({
                template : "#messages_window_template",
                model : new BaseModel({}, {
                    yModel : "Comments"
                }),
                data : "item",
                events : {
                    "keydown .send-message-input" : function(e) {
                        if (e.keyCode == 13) {
                            this.sendMessage();
                            return false;
                        }
                    },
                    "click .send-message-button" : function() {
                        this.sendMessage();
                    }
                },
                sendMessage : function(e) {
                    var that = this;
                    var send = new BaseModel({
                        comment : $(that.el).find(".send-message-input").val(),
                        target_id : this.parent.model.get("id"),
                        target_type : Yii.app.targetTypes.chat
                    }, {
                        yModel : "Comments"
                    })
                    send.save({}, {
                        success : function(model, response, xhr) {
                            $(that.el).find(".send-message-input").val("");
                            that.parent.messages.add(response);
                        }
                    })
                },
                setModelEvent: function () {
                    //this.model.on("change", this.render, this);
                    var that = this; //asd
                    this.parent.messages.on("add", this.newMessage, this);
                    this.parent.messages.each(function(m) {
                        that.newMessage(m);
                    })
                },
                newMessage : function(m) {
                    var that = this;
                    if (!m.view) {
                        var view = new that.MessageView({
                            model: m,
                            parent: that,
                            sortSelector : ".message-item"
                        });
                        m.view = view;
                        view.render().appendTo($(that.el).find(".messages-list"));
                        if (m.get("ts") > this.parent.model.get("last_ts")) {
                            if (that.parent.parent.active == that.parent.model) {
                                this.parent.updateView();
                            }
                            this.parent.model.set("last_ts", m.get('ts'));
                        }
                    }

                    $(that.el).find(".messages-list").parent().scrollTop($(that.el).find(".messages-list").height());

                },
                afterRender : function() {
                    $( this.el).find(".messages-list").parent().bind( 'mousewheel DOMMouseScroll', function ( e ) {
                        var e0 = e.originalEvent,
                            delta = e0.wheelDelta || -e0.detail;

                        this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
                        e.preventDefault();
                    });
                },
                MessageView : BaseItem.extend({
                    template : "#message_template",
                    data : "item"
                })
            })
        }),
        newChats : function(response, event) {
            if (response.data) {
                this.chats.add(response.data);
            }
        },
        newMessages : function(response, event) {
            var that = this;
            if (response.data) {
                var data = _.mapObject(response.data, function(c) {
                    c.target_id = parseInt(c.target_id);
                    $(that.el).find(".sound").html('<audio autoplay="autoplay"><source src="' + BASE_ASSETS + '/media/new_message.mp3" type="audio/mpeg" /><embed hidden="true" autostart="true" loop="false" src="' + BASE_ASSETS + '/media/new_message.mp3" /></audio>');
                    return c;
                });

                this.model.set("messages", data, {
                    silent : true
                });
                this.model.trigger("change");
            }
        }
    });

});