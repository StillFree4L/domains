calculateMenuWidth = function()
{
    var width = 0;
    var maxwidth = 1000;
    var margin = 20;
    var first;
    $("div.main_menu > div.menu_link_div").each(function() {

        if (width == 0)
        {
            first = $(this);
        }

        if (width + margin + $(this).width() < maxwidth)
        {
            width = width + margin + $(this).width();
        } else {

            $(first).css({marginLeft:((maxwidth-width)/2)+"px"});

            first = $(this);
            width = margin + $(this).width();
            
        }
    });

    $(first).css({marginLeft:((maxwidth-width)/2)+"px"});
     
}

calculateBannersWidth = function()
{
    var width = 0;
    var maxwidth = 1000;
    var margin = 5;
    var first;
    $("div.bottom_banners > a.instance_link").each(function() {

        if (width == 0)
        {
            first = $(this);
        }

        

        if (width + margin + $(this).width() < maxwidth)
        {
            width = width + margin + $(this).width();
        } else {

            $(first).css({marginLeft:((maxwidth-width)/2)+"px"});

            first = $(this);
            width = margin + $(this).width();

        }
    });

    $(first).css({marginLeft:((maxwidth-width)/2)+"px"});

}

$(window).load( function()
{
    calculateBannersWidth();
});

$(function() {

    calculateMenuWidth();
    

    $("div.main_menu > div.menu_link_div > a.menu_link").mouseover(function()
    {
        
    });

    $("div.left_menu > div.menu_link_div > a.menu_link").mouseover(function()
    {
        
    });

    $("div.menu_link_div a.menu_link").hover(function() {

        var link = $(this);
        $("body").oneTime(400, $(link).attr("id"), function()
        {
            $(link).parent().parent().find(".menu_link_childs").hide();
            $(link).parent().find(".menu_link_childs.inner").first().css({
                left:"100%",
                marginLeft:"10px",
                top:"0px"
            })
            $(link).parent().find(".menu_link_childs").first().show(300);
        });
        
    }, function()
        {

            var link = $(this);
            console.log($(link).attr("id"));
            $("body").stopTime($(link).attr("id"));
            $("body").oneTime(400, "list_"+$(link).attr("id"), function()
            {
                $(link).parent().find(".menu_link_childs").hide();
            });
    });

    $("div.menu_link_childs").hover(function()
    {
        $("body").stopTime("list_"+$(this).parent().find("a.menu_link").attr("id"));
        
    }, function()
    {
        var list = $(this);
        $("body").oneTime(400, "list_"+$(list).parent().find("a.menu_link").attr("id"), function()
        {
            $(list).hide();
        });
    })

})
