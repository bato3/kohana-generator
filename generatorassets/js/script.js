$(document).ready(function() {
    var i = 0;
    // global-------------------------------------------------------------------
    $("#clear_button").click(function(){
        $("#result").fadeOut("slow");
        $("#post_result").fadeOut("slow");
        $("#methods_holder > div").html("");
        $("#controllername").val("");
        return false;
    });
    
    //end global----------------------------------------------------------------
    
    
    //form generator------------------------------------------------------------
    $("#form_table_button").click(function(){
        var selected = $("#table_list :selected").val()
        
        $.get("/generatorajax/formfieldslist/"+selected, function(data){
            $("#result").html(data).fadeIn("slow");
            postForm("#generate_form", "/generatorajax/form");
                        
        });
    });
    
    //assets generator----------------------------------------------------------
    $("#assets_button").click(function(){
        $.get("/generatorajax/assets", function(data){
            $("#result").html(data).fadeIn("slow");
        });
    });
    
    //controller generator------------------------------------------------------
    $("#add_action_button").click(function(){
        var input_id = "action_"+i;
        var item = "<div class=\"action_div\"><label for=\""+input_id+"\">action_</label><input type=\"text\" name=\"actions[]\" id=\""+input_id+"\" /></div>";
        $("#methods_holder").append(item);
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