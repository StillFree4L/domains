$(function () {

    $(".btn-group[data-toggle='buttons']:not(.group-static)").find("input[type='checkbox']").live("change",function() {
        var type = $(this).parents(".btn-group").attr("type");
        if ($(this).attr("checked")) {
            $(this).parent().removeClass("btn-"+type).addClass("btn-success");
            $(this).parent().find("input[type='text']").prop("disabled", false);
        } else {
            $(this).parent().removeClass("btn-success").addClass("btn-"+type);
            $(this).parent().find("input[type='text']").prop("disabled", true);
        }
    });

    $(".btn-group[data-toggle='buttons']:not(.group-static)").find("input[type='radio']").live("change",function() {
        var type = $(this).parents(".btn-group").attr("type");
        $(this).parents(".btn-group").find("input").parent().removeClass("btn-success").addClass("btn-"+type);
        if ($(this).attr("checked")) {
            $(this).parent().removeClass("btn-"+type).addClass("btn-success");

        }
    });

});

$(function () {

    $(".btn-group[data-toggle='buttons']:not(.group-static)").on("change","input[type='checkbox']",function() {
        var type = $(this).parents(".btn-group").attr("type");
        if ($(this).attr("checked")) {
            $(this).parent().removeClass("btn-"+type).addClass("btn-success");
            $(this).parent().find("input[type='text']").prop("disabled", false);
        } else {
            $(this).parent().removeClass("btn-success").addClass("btn-"+type);
            $(this).parent().find("input[type='text']").prop("disabled", true);
        }
    });

    $(".btn-group[data-toggle='buttons']:not(.group-static)").on("change","input[type='radio']",function() {
        var type = $(this).parents(".btn-group").attr("type");
        $(this).parents(".btn-group").find("input").parent().removeClass("btn-success").addClass("btn-"+type);
        if ($(this).attr("checked")) {
            $(this).parent().removeClass("btn-"+type).addClass("btn-success");

        }
    });

});