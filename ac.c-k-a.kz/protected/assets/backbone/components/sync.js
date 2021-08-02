$(function() {

    var methodMap = {
        'create': 'POST',
        'update': 'PUT',
        'patch':  'PATCH',
        'delete': 'DELETE',
        'read':   'GET'
    };

    BaseSync = function(method, model, options) {

        try {
            Yii.app.loading(true);

            var data_combined = false;

            var type = methodMap[method];

            _.defaults(options || (options = {}), {
                emulateHTTP: Backbone.emulateHTTP,
                emulateJSON: Backbone.emulateJSON
            });

            var params = {type: type, dataType: 'json'};


            if (!options.url) {
                params.url = _.result(model, 'url') || urlError();
            }

            var d = {};
            d['yModel'] = model.yModel;

            if (options.data == null && model && (method === 'create' || method === 'update' || method === 'patch' || method === 'delete')) {
                d[model.yModel] = options.attrs || model.toJSON(options);
                data_combined = true;
                params.contentType = 'application/json';
                params.data = JSON.stringify(d);
            }


            var _flatten = function( obj, o ) {
                if (!o) o = 1;
                var output = {};
                for (var i in obj) {
                    if (!obj.hasOwnProperty(i)) continue;
                    if (typeof obj[i] == 'object') {

                        var flatObject = _flatten(obj[i], o + 1);
                        for (var x in flatObject) {
                            if (!flatObject.hasOwnProperty(x)) continue;

                            var v = x;

                            v1 = '[' + i + ']';
                            if (o == 1) {
                                v1 = i;
                            }
                            output[v1 + v] = flatObject[x];
                        }
                    } else {
                        var v1 = i;
                        if (o != 1) {
                            v1 = '[' + i + ']';
                        }
                        output[v1] = obj[i];
                    }
                }
                return output;
            }

            if (method != 'read' && (options.formData === true
                || options.formData !== false
                && this.attributes[ this.fileAttribute ]
                && (this.attributes[ this.fileAttribute ] instanceof File || this.attributes[ this.fileAttribute ] instanceof Blob))) {

                // Flatten Attributes reapplying File Object
                var formAttrs = _.clone( this.attributes ),
                    fileAttr = this.attributes[ this.fileAttribute ];

                delete(formAttrs[this.fileAttribute]);
                d[model.yModel] = formAttrs;
                formAttrs = _flatten(d);
                formAttrs[ model.yModel + "[" + this.fileAttribute + "]" ] = fileAttr;
                // Converting Attributes to Form Data
                var formData = new FormData();
                _.each( formAttrs, function( value, key ){
                    formData.append( key, value );
                });

                // Set options for AJAX call
                options = options || {};
                options.data = formData;
                options.processData = false;
                options.contentType = false;

                // Apply custom XHR for processing status & listen to "progress"

                options.xhr = function() {
                    var xhr = $.ajaxSettings.xhr();
                    xhr.upload.addEventListener('progress', function(event){
                        model.trigger("progress", event);
                    }, false);
                    return xhr;
                }

                data_combined = true;
            }

            if (options.emulateJSON) {
                params.contentType = 'application/x-www-form-urlencoded';
                params.data = params.data ? {model: params.data} : {};
            }

            if (options.emulateHTTP && (type === 'PUT' || type === 'DELETE' || type === 'PATCH')) {
                params.type = 'POST';
                if (options.emulateJSON) params.data._method = type;
                var beforeSend = options.beforeSend;
                options.beforeSend = function(xhr) {
                    xhr.setRequestHeader('X-HTTP-Method-Override', type);
                    if (beforeSend) return beforeSend.apply(this, arguments);
                };
            }

            if (params.type !== 'GET' && !options.emulateJSON) {
                params.processData = false;
            }

            if (params.type === 'PATCH' && noXhrPatch) {
                params.xhr = function() {
                    return new ActiveXObject("Microsoft.XMLHTTP");
                };
            }

            params = _.extend(params, options)

            if (!data_combined)
            {
                d[model.yModel] = params.data;
                params.data = d;
            }

            try {
                var d_parsed = JSON.parse(params.data);
                if (model.yModel && typeof d_parsed != "undefined" && d_parsed[model.yModel]['_csrf']) {
                    d_parsed['_csrf'] = d_parsed[model.yModel]['_csrf'];
                    params.data = JSON.stringify(d_parsed);
                }
            } catch (err) {
                console.log("NO JSON DATA");
            }

            var xhr = options.xhr = Backbone.ajax(params);
            xhr.complete(function () {
                Yii.app.loading(false);
            })

            model.trigger('request', model, xhr, options);
        } catch (e) {
            Yii.app.loading(false);
            console.log(e);
        }

        return xhr;
    };

})
