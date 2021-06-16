$(document).on('click','#us',function()
{
    let name ='user';
    $.ajax
    ({
        url: 'menu.php',
        type: 'POST',
        dataType: 'json',
        data:
            {
                name: name
            },success:function ()
        {
            $('p.out').text('mission complete!');
        }
    })
})