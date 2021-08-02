$(function() {

    populateDicInputs = function(values) {
        if (values) {

            _(values).each(function(v, dname) {

                if (v) {
                    _(v).each(function(i, dvalue) {
                        if (i) {
                            _(i).each(function(ar, iv) {
                                var ii = 0;
                                console.log("input[dname='"+dname+"'][dvalue='"+dvalue+"'][i='"+iv+"']");
                                $("input[dname='"+dname+"'][dvalue='"+dvalue+"'][i='"+iv+"']").each(function() {
                                    console.log(ii);
                                    $(this).val(ar[ii]);
                                    ii++;
                                })
                            });
                        }

                    })
                }

            })
        }
    }

    $.fn.extend({

        choser : function(options) {

            options = $.extend({
                dic_inputs : null
            }, options);

            var box = $(this).find(".choser-list");
            var input = $(this).find(".choser-input");
            var compose = function() {
                var chosen = [];
                $(box).find(".choser-item.active").each(function() {
                    chosen.push(parseInt($(this).attr("vid")));
                })
                $(input).val(JSON.stringify(chosen));
            }

            $(box).find('.choser-item').click(function(e) {
                if ($(e.target).prop("tagName") == "INPUT") {
                    if (!$(this).hasClass("active")) {
                        $(this).addClass("active");
                    }
                    compose();
                    return;
                }
                $(this).toggleClass("active");
                compose();
            });



        }

    })

})