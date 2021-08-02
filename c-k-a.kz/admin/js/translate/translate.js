$(function()
{
    $("a.word_delete").live("click", function() {
        if (confirm("Вы уверены?")) {
            $(this).parents("div.word").remove();
        }
    })
    
    $("a.add_word").live("click", function() {
        
        last_i = $("div.word:last-child").attr("i")*1 +1;
        
        html = '<div i="'+last_i+'" class="word">'+
               '<input type="text" name="words['+last_i+'][key]" class="key_word" value="" /> <span class="word_equals">=</span> <input type="text" name="words['+last_i+'][value]" class="value_word" value="" /><a class="word_delete icon icon-remove"></a>'+
               '</div>';
           
       $("div.words_page").append(html);
       
    });
    
})