var xmlhttp = new XMLHttpRequest();

xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        reqListener(this.responseText);
    }
};

function export_xlsx() {
    xmlhttp.open("GET", "reference.php?export_xlsx", true);
    xmlhttp.send();
}

function reqListener (data) {
    $(".response").text(data);
}

$(document).ready(function() {
    $("#search-field").keypress(function() {
        var search_str = $("#search-field").val();

        $(".row").each(function() {
            var text = $(this).find("#sitename").text();

            if(text.indexOf(search_str) !== -1) {
                $(this).css("display", "block");
            } else {
                $(this).css("display", "none");
            }
        })
    })
})