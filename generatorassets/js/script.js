$(document).ready(function() {
    
    // global-------------------------------------------------------------------
    var i = 0;
    
    $("#clear_button").click(function(){
        $("#result").fadeOut("slow", function(){
            $(this).html("")
        });
        $("#post_result").fadeOut("slow", function(){
            $(this).html("")
        });
        $("#methods_holder > div").fadeOut("slow", function(){
            $(this).html("")
        });
        $("#rows_holder > tbody > tr").fadeOut("slow", function(){
            $(this).html("")
        });
        $("#controllername").val("");
        $("#generate_form_name").val("")
        return false;
    });
    
    //form generator------------------------------------------------------------
    $("#table_list").change(function(){
        var selected = $("#table_list :selected").val()
        $("#post_result").fadeOut("slow");
        $.get("/generatorajax/formfieldslist/"+selected, function(data){
            $("#result").html(data).fadeIn("slow");
            $(".inputsuggest").change(function(){
                var val = $(this).attr("id").valueOf();
                var selected = $("#"+val+" option:selected").val();
                if(parseInt(selected) == 0){
                    $("#pozition_"+val).attr("disabled","disabled").fadeOut("slow");
                }else if($("#pozition_"+val).attr("disabled") == "disabled"){
                    $("#pozition_"+val).removeAttr("disabled").fadeIn("slow");
                }
            });
            
            postForm("#generate_form", "/generatorajax/form");
                        
        });
    });
    
    //form builder generator----------------------------------------------------
    $("#add_row_button").click(function(){
        var name = $("#input_name").val();
        if(0 < name.length){
            $("#input_name").val("");
            $.get("/generatorajax/inputs?id=in_"+i+"&name="+name, function(data){
                var row_class = i % 2 == 0 ? "a" : "b";
                var item = "<tr class=\""+row_class+"\" id=\"row_div_"+i+"\"><td><label for=\""+name+"\">"+name+":</label></td><td>"+data+"</td><td><span class=\"delete_tr\" id=\""+i+"\"><img src=\"/generatorassets/img/delete.png\"></span><input type=\"hidden\" name=\"place["+name+"]\" value=\""+i+"\" /></td></tr>";
                $("#rows_holder > tbody").append(item);
                $("#"+i).click(function(){
                    var id = $(this).attr("id").valueOf();
                    $("#row_div_"+id).remove();
                });
                i++;
            });
        }else{
            alert("Input name is empty!")
        }
        return false;
    });
    
    $("#generate_formbuilder").submit(function(){
        if(0 < $("#generate_form_name").val().length){
            show_ajax_loader("#post_result");
            $.post("/generatorajax/form", $("#generate_formbuilder").serialize(), function(data){
                remove_ajax_loader("#post_result");
                $("#post_result").html(data).fadeIn("slow");
                details();
            });            
        }else{
            alert("File name is empty!")
        }
        return false;
    });
    
    
    //assets generator----------------------------------------------------------
    $("#assets_button").click(function(){
        show_ajax_loader("#result");
        $.get("/generatorajax/assets", function(data){
            remove_ajax_loader("#result");
            $("#result").html(data).fadeIn("slow");
            details();
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
    
    //curl controller generator-------------------------------------------------
    postForm("#generate_curlcontroller", "/generatorajax/curlcontroller");
    
    //template generator----------------------------------------------------------
    $("#template_button").click(function(){
        show_ajax_loader("#result");
        var template_type = $(".template:checked").val();
        $.get("/generatorajax/template?template="+template_type, function(data){
            remove_ajax_loader("#result");
            $("#result").html(data).fadeIn("slow");
            details();
        });
    });
    
    //model generator-----------------------------------------------------------
    $("#model_button").click(function(){
        show_ajax_loader("#result");
        $.get("/generatorajax/model", function(data){
            remove_ajax_loader("#result");
            $("#result").html(data).fadeIn("slow");
            details();
        });
    });
    
    //list generator------------------------------------------------------------
    $("#list_button").click(function(){
        show_ajax_loader("#result");
        var template_type = $(".template:checked").val();
        $.get("/generatorajax/list?template="+template_type, function(data){
            remove_ajax_loader("#result");
            $("#result").html(data).fadeIn("slow");
            details();
        });
    });
    
    //show generator------------------------------------------------------------
    $("#show_button").click(function(){
        show_ajax_loader("#result");
        var template_type = $(".template:checked").val();
        $.get("/generatorajax/show?template="+template_type, function(data){
            remove_ajax_loader("#result");
            $("#result").html(data).fadeIn("slow");
            details();
        });
    });
    
});

function postForm(form_id,url){
    $(form_id).submit(function(){
        show_ajax_loader("#post_result");
        $.post(url, $(form_id).serialize(), function(data){
            remove_ajax_loader("#post_result");
            $("#post_result").html(data).fadeIn("slow");
            details();
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

function details(){
    $(".details_link").click(function(){
        var id = $(this).attr("id").valueOf();
        $("#details_"+id).toggle("slow");
    });
}