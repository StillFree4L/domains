/**
 * @param options - {
 *      el - Element selector
 *      orientation - Page orientation ( P - page, L - landscape)
 * }
 *
 * @type {Function}
 */
var mpdf = (function(options) {

    this.options = {
        el : null,
        orientation : "P",
        ignore : []
    };

    this.options = $.extend(this.options, options);

    var that = this;

    var getHtmlWithStyles = function()
    {

        var sheets = [];
        for (var key in document.styleSheets) {
            sheets.push(document.styleSheets[key].href);
        }

        var data = {};
        data.styles = sheets;

        data.html = $(that.options.el).clone();

        _(that.options.ignore).each(function(i) {
            $(data.html).find(i).remove();
        })

        data.html = $(data.html).html();

        return data;

    }

    this.doExport = function() {

        var sendData = getHtmlWithStyles();

        sendData.options = that.options;

        delete(sendData.options.el);

        var form = $('<form/>', {
            action: '/mpdf',
            method: 'POST',
            target: '_blank',
            enctype: 'multipart/form-data',
            style: { display: 'none' }
        });

        form.append($('<input/>',{
            type: 'hidden',
            name: 'data',
            value: JSON.stringify(sendData)
        }));

        form.appendTo(document.body);
        form.submit();

        $(form).remove();

    }

});
