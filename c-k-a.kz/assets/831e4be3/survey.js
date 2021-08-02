function addVariant()
{
    html = "<div class=\"variant\"><input class=\"span5\" type=\"text\" name=\"PSurveyVariants[][name]\">";
    html += "<a onclick=\"deleteVariant(this);\" style=\"margin-bottom:10px; margin-left:10px;\" class=\"btn btn-warning\">";
    html += "<i class=\"icon-remove icon-white\"></i> </a></div>";
    $("div.survey_variants").append(html);
}

function deleteVariant(el)
{
    $(el).parents("div.variant").remove();
}

function submitInstances(type)
{
    $("form#SurveyList").append("<input type='hidden' name='submitType' value='"+type+"' />");

    if (type == "delete")
        {
            if (!confirm("Данные будут удалены полностью. Вы уверены?"))
                {
                    return false;
                }
        }
    document.SurveyList.submit();
}