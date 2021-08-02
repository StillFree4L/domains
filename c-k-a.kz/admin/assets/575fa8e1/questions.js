function submitInstances(type)
{
    $("form#QuestionsList").append("<input type='hidden' name='submitType' value='"+type+"' />");

    if (type == "delete")
        {
            if (!confirm("Данные будут удалены полностью. Вы уверены?"))
                {
                    return false;
                }
        }
    document.QuestionsList.submit();
}

setInputs = function(id)
{
    $("#PQuestionAnswer_id").attr("value",id);
    $.post(window.location.href, {
        "getPQAnswer":id,
    }, function(answer) {
        $("#PQuestionAnswer_answer").attr("value",answer);
    }, "text");
}
$(function() {
    
})