$(document).ready(function() {
    var i = 0;
    // global-------------------------------------------------------------------
    $("#clear_button").click(function(){
        $("#result").fadeOut("slow").html("");
        $("#post_result").fadeOut("slow").html("");
        $("#methods_holder").html("");
        $("#controllername").val("");
        return false;
    });
    
    //form generator------------------------------------------------------------
    $("#table_list").change(function(){
        var selected = $("#table_list :selected").val()
        $("#post_result").fadeOut("slow");
        $.get("/generatorajax/formfieldslist/"+selected, function(data){
            $("#result").html(data).fadeIn("slow");
            postForm("#generate_form", "/generatorajax/form");
                        
        });
    });
    
    //assets generator----------------------------------------------------------
    $("#assets_button").click(function(){
        show_ajax_loader("#result");
        $.get("/generatorajax/assets", function(data){
            remove_ajax_loader("#result");
            $("#result").html(data).fadeIn("slow");
        });
    });
    
    //controller generator------------------------------------------------------
    $("#add_action_button").click(function(){
        var input_id = "action_"+i;
        var item = "<div class=\"action_div\" id=\"action_div_"+i+"\"><label for=\""+input_id+"\">action_</label><input type=\"text\" name=\"actions[]\" id=\""+input_id+"\" /><span class=\"delete\" id=\""+i+"\"><img src=\"/generatorassets/img/delete.png\"></span></div>";
        $("#methods_holder").append(item);
        $("#"+i).click(function(){
            var id = $(this).attr("id").valueOf();
            $("#action_div_"+id).remove();
        });
        i++;
        return false;
    });
    postForm("#generate_controller", "/generatorajax/controller");
    
    //model generator-----------------------------------------------------------
    postForm("#generate_model", "/generatorajax/model");

});

function postForm(form_id,url){
    $(form_id).submit(function(){
        show_ajax_loader("#post_result");
        $.post(url, $(form_id).serialize(), function(data){
            remove_ajax_loader("#post_result");
            $("#post_result").html(data).fadeIn("slow");
        });
        return false;
    });
}

function show_ajax_loader(elem){
    var ajax_loader = $("<div id=\"ajax_loader\"><center><img src=\"/generatorassets/img/ajax-loader.gif\" alt=\"loading...\" title=\"loading...\" /></center></div>")
    $(elem).show();
    $(elem).html(ajax_loader);
}

function remove_ajax_loader(elem){
    $(elem).remove("#ajax_loader");
    $(elem).hide();
}