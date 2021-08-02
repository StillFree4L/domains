$(function() {

    BaseAction = BaseComponent.extend({

        controller : null,
        defineArguments : function(args)
        {
            this.controller = args.controller;
        },
        ga : function() {
            if (typeof ga != "undefined") {
                ga('send', {
                    'hitType': 'pageview',
                    'page': this.controller.url
                });
            }
        }

    })

})
