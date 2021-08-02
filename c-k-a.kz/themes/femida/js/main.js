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
    $("div.bottom_banners").each(function() {
        var width = 0;
        var maxwidth = 1000;
        var margin = 15;
        var first;
        $(this).find("a.instance_link").each(function() {

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
    });
}

calculateHeight = function()
{
    lheight = $("div#left_content").height();
    rheight = $("div#main_content").height();

    if (lheight > rheight)
        {
            $("div#main_content").height(lheight-2);
        }
        

}

$(window).load( function()
{
    calculateBannersWidth();
    calculateHeight();
});


$(function() {


    
    $(".modal").each(function() {
        $("body").append($(this));
    })

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
                marginLeft:"0px",
                top:"0px"
            })
            $(link).parent().find(".menu_link_childs").first().show();
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
