function submitInstances(type)
{
    $("form#InstancesList").append("<input type='hidden' name='submitType' value='"+type+"' />");

    if (type == "forse_delete")
        {
            if (!confirm("Данные будут удалены полностью. Вы уверены?"))
                {
                    return false;
                }
        }
    document.instancesList.submit();
}

$(function()
{   
});