(function(){
//Section 1 : Code to execute when the toolbar button is pressed
var a= {
exec:function(editor){
    txt = prompt("Введите текст, который хотите цитировать?");
    if (txt) 
        {
            editor.insertHtml("[quote]"+txt+"[/quote]");
        }
}
},

//Section 2 : Create the button and add the functionality to it
b='quote';
CKEDITOR.plugins.add(b,{
init:function(editor){
editor.addCommand(b,a);
editor.ui.addButton("quote",{
    label:'Цитировать', 
    icon:this.path+"quote.png",
    command:b
    });
}
}); 
})();