$(document).ready(function(){
    
    if(window.location.href.search('db_clear') != -1){ setTimeout(function(){window.location.href="admin.php";}, 2400); }
    
    
    $("#db_clear").click(function(){
        $("#error").text('Ожидайте...');
    });
    
    $("#db_export_csv3").click(function(){
        $("#error").text('Ожидайте результат...');
    });
    
    $("#db_export_csv").click(function(){
        $("#error").text('Ожидайте результат...');
    });

    $("#db_export_csv4").click(function(){
        $("#error").text('Ожидайте результат...');
    });
    
    $("#db_export_csv2").click(function(){
        $("#error").text('Ожидайте результат...');
    });

    $("#db_import_csv").click(function(){
        if($('#import_file').val() == ''){
            alert("Необходимо выбрать CSV файл для импорта!");
            $('#import_file').focus();
            return false;
        }
        $("#error").text('Ожидайте результат...');
        return true;
    });

    
    $("#resume_url").focus( function() {
        //$('#resume_url').css('height', '60px');
    });
    $("#resume_url").blur( function() {
        //$('#resume_url').css('height', '15px');
    });
    
    $("#maincheck").click( function() {
            if($('#maincheck').attr('checked')){
                $('.checkbox').attr('checked', true);
            } else {
                $('.checkbox').attr('checked', false);
            }
    });
    
    $("#find_ext").click(function(){
        if($("#find_ext").val() == 'Открыть фильтр'){
            $("#find_ext").val('Сбросить фильтр');
            $("#find_ext_submit").show();
            $("#find_ext_div").show(350);
        }else{
            $("#find_ext").val('Открыть фильтр');
            $("#find_ext_submit").hide();
            $("#find_ext_div").hide(200);
            
            window.location.href="admin.php?find_ext_clear";
        }
    });
    
    $("#find_txt").keypress(function (e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $("#find").click();
            return false;
        } else {
            return true;
        }
    });
    
    $(".list_stat").change(function(){
        var rec_id = $(this).attr('id');
        var name = 'list_status';
        var val = $(this).prop("checked");
        $.get("save_url_stat.php", {rec_id: rec_id, param_name: name, param_val: val}, rec_stat);
    });

    function rec_stat(data){
        //alert(data);
    }


    $("#page_str").change(function(){
        window.location.href="admin.php?page_str="+$("#page_str").val();
    });
    
    //$("#parser_id").change(function(){
    //    window.location.href="admin.php?parser_id="+$("#parser_id").val();
    //});

    /*
    $("#parser_hh").click(function(){
        var resume_url_val = $("#resume_url").val();
        $.post("parser.hh.ru.php", { resume_url: resume_url_val } );
    });
    */

});    
