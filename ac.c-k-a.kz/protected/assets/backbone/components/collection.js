$(function() {
    /**
     *
     * @type Backbone.Collection
     */
    BaseCollection = Backbone.Collection.extend({
        yModel : "BaseModel",
        url:URL_ROOT + "/backbone/request",
        model:BaseModel,
        sync:BaseSync,
        setUrl: function(url, absolute) {
            if (!absolute) {
                this.url = this.url + url;
            } else {
                this.url = url;
            }
        },
        initialize : function(models, options)
        {
            if (options && options.yModel) {
                this.yModel = options.yModel
            }
            this.on("error",this.error, this);
        },
        //Parse the response
        /*parse: function (response) {
            log(response);
            if (response) {
                if (typeof response.collection != 'undefined')  return response.collection;
            }
            return null;
        },*/
        error : function(collection, response, options)
        {
            new BaseModel().callError(response);
        }
    });
})