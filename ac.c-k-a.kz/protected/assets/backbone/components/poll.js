$(function() {

    Poll = BaseComponent.extend({
        _events : null,
        errors : 0,
        working : false,
        _initialize : function()
        {

        },
        addEvent : function(name, data, callback, _this, options) {
            if (!this._events) this._events = {};
            this._events[name] = {
                data : data,
                callback : callback,
                _this : _this,
                options : (options) ? options : {}
            }
        },
        clear : function() {
            var that = this;
            _(this._events).each(function(e, n) {
                if (!e.options.global) {
                    delete(that._events[n]);
                }
            });
        },
        reset : function() {

        },
        start : function() {


            if (!this.working) {
                var that = this;
                this.working = true;
                if (this._events && !_.isEmpty(this._events)) {

                    log("get Events data");
                    data = {};
                    _(this._events).each(function(e, n) {
                        data[n] = e.data;
                    });

                    $.ajax(POLL_URL + "?access-token=" + Yii.app.user.model.poll,{
                        method : "post",
                        crossDomain : true,
                        dataType : "json",
                        beforeSend: function (xhr) {
                            //xhr.setRequestHeader ("Authorization", "Basic "+Yii.app.user.model.poll);
                        },
                        data : data,
                        success : function(response) {
                            that.errors = 0;
                            if (response) {
                                _(response).each(function(e, n) {

                                    if (that._events[n]) {
                                        if (that._events[n].options && that._events[n].options.updateAttribute && e.updateValue)
                                        {
                                            that._events[n].data[that._events[n].options.updateAttribute] = e.updateValue;
                                        }

                                        that._events[n].callback.call(that._events[n]._this, e, that._events[n])
                                    }

                                });
                            }


                            setTimeout(function() {
                                that.working = false;
                                that.start();
                            }, 3000);
                        },
                        error : function(response) {
                            if (response.error == 1) {}
                            else {
                                if (that.errors < 5) {
                                    setTimeout(function () {
                                        that.working = false;
                                        that.start();
                                    }, 3000);
                                }
                                that.errors++;
                            }
                        }
                    });

                } else {
                    setTimeout(function() {
                        that.working = false;
                        that.start();
                    }, 3000);
                }
            }

        }

    })

})