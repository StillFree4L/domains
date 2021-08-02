function submitInstances(type)
{
    $("form#BooksList").append("<input type='hidden' name='submitType' value='"+type+"' />");

    if (type == "delete")
        {
            if (!confirm("Данные будут удалены полностью. Вы уверены?"))
                {
                    return false;
                }
        }
    document.BooksList.submit();
}