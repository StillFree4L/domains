EProfile = BaseWidget.extend({

    view : null,
    afterInitialize : function(args) {

        this.loadTemplate("EProfile", this.afterLoad);

    },

    afterLoad : function(data) {
        this.view = new BaseItem({
            template : $(data),
            data : "model",
            model : this.model
        });
        this.view.render();
    }


})