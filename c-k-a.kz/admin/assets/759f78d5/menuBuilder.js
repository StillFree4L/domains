saveMenu = function()
{
    var items = {};
    items = serializeMenu(".menu_body");

    $.post(window.location.href, {
        saveMenu:'1',
        menuItems:items
    }, function(text)
    {
       window.location.href = window.location.href;
    }, "text");


}
serializeMenu = function(parent)
{
    var i = 1;
    var items = {};

    $(parent + " > div.instance_container").each(function()
    {
        
        items[i] = {};
        items[i]['menu'] = String($(this).attr("id")).split("_")[1];
        items[i]['childs'] = serializeMenu("#cc_"+$(this).attr("id").split("_")[1]);
        i++;
    })
    
    return items;

}

$(function()
{

    $("select#menu_group").live("change",function()
    {
        window.location.href = $(this).attr("value");
    });

    $("div.instance_child_container").sortable({
        connectWith:".instance_child_container",
        items:".instance_container",
        placeholder: "ui-state-highlight",
        forcePlaceholderSize:true,
        helper:"clone",
        opacity:"0.3",
        cursor:"move",
        scroll:true,
        tolerance:"intersect"
    })

    $("div.instance_item").hover(function()
    {
        $(this).find(".close").show();
    }, function()
    {
        $(this).find(".close").hide(); 
    });

    $("div.instance_item .close").click(function(){
       $(this).parent().parent().remove();
    });

    $("span.instance_type").click(function() {
        $(this).parent().parent().find(".instance_child_container").first().slideToggle(0);
    });

    $("span.instance_caption").live("dblclick",function() {

        var caption = $(this).html();
        var m_id = $(this).attr("m_id");
        $(this).html("<input type='text' value='"+caption+"' class='instance_caption' m_id='"+m_id+"' />");

        $("input.instance_caption").blur(function() {
           var el = $(this).parents("span.instance_caption");
           var newCaption = $(this).attr("value");
           $.post(window.location.href, {
               updateMenuCaption:m_id,
               menuCaption:newCaption
           }, function(text) {
               if (text == 1)
                   {
                       console.log(el);
                       $(el).html(newCaption);
                   } else {
                       $(el).html(caption);
                   }
           }, "text");
        });

    });
    


})