$(function() {
   $("a.post_quote").live("click", function() {

       html = "[quote]"+
           "[quote_author]"+$(this).parents("div.theme_post").find("span.post_author").text()+"[/quote_author]"+
           $(this).parents("div.post_body").find("div.post_text").html()+
           "[/quote]";

       CKEDITOR.instances['PForumPosts[post]'].insertHtml(html);
       
       
   })
});