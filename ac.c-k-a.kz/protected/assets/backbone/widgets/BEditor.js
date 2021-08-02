$(function() {
    BEditor = BaseComponent.extend({
        styles : {

        },
        toolbars : {
            "selected" : [
                "bold",
                "italic",
                "link",
                "H1",
                "H2"
            ],
            "insert" : [
                "file",
                "image",
                "video"
            ]
        },
        templates : {
            "popup_toolbar" : "<div class='popup-toolbar'>123</div>"
        },
        events : {
            "mouseup" : "showToolbarOnSelectedText"
            //"keyup" : "showToolbarOnSelectedText"
        },
        _initialize : function(args) {

            if (args.toolbars)
            {
                this.toolbars = args.toolbars;
            }
            if (args.templates)
            {
                this.templates = args.templates;
            }

            $(this.el).attr("contentEditable", true);

        },
        showToolbarOnSelectedText : function(event)
        {
            selectedText = this.getSelectedText();
            if (selectedText != "") {
                log($(selectedText.focusNode).offset().left);
                this.showToolbar("popup_toolbar");
            }

        },
        getSelectedText : function()
        {
            var t = '';
            if(window.getSelection){
                t = window.getSelection();
            }else if(document.getSelection){
                t = document.getSelection();
            }else if(document.selection){
                t = document.selection.createRange().text;
            }
            return t;
        },
        showToolbar : function(toolbar)
        {

        }

    });
})