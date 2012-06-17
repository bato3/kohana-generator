(function($){
    
    $.fn.extend({ 
    
        k_loader_show : function(){
            return $(this).html('<div id="ajax-loader"><center><img src="gmedia/img/ajax-loader.gif" alt="load" title="load" /></center></div>');
        },

        k_loader_remove : function(){
            return $(this).remove("#ajax-loader");
        },
        
        k_show_template : function(){
            $(this).unbind("click");
            $(this).click(function(){
                $("#result").html("");
                var item = $("#template_result");
                item.k_loader_show();
                $.get("/gajax/show?template="+$(this).attr("id"), function(data){
                    
                    if(data.logout){
                        $("#page_wrapper").html("")
                        $("#login_form_div").init_login_form()
                    }else{
                        item.k_loader_remove();
                        item.html(data.html)
                        $(".button").button();
                        $(".button").init_button();
                    }
                    
                });
            });
            
        },
        
        init_button : function(){
            $(this).unbind("click");
            $(this).click(function(){
                var id = $(this).attr("id");
                var item = $("#result");
                item.k_loader_show();
                var data = "";
                var length = $(".send").length;
                $(".send").each(function(i){
                    
                    if($(this).is("select")){
                        data += $(this).attr("name")+"="+$(this, "option :selected").val()
                        if(i != length-1){ data += "&"}
                    }else if($(this).attr("type") == "checkbox" || $(this).attr("type") == "radio"){
                        if($(this).is(":checked")){
                            data += $(this).attr("name")+"="+$(this, ":checked").val()
                            if(i != length-1){ data += "&"}
                        }
                    }else if($(this).attr("type") == "text"){
                        data += $(this).attr("name")+"="+$(this).val()
                        if(i != length-1){ data += "&"}
                    }
                    
                });
                                
                $.post("/gajax/generate?cmd="+id, data, function(data){
                    $(".send").each(function(){
                        if($(this).attr("type") == "text"){
                            $(this).val("")
                        }
                    });
                    item.k_loader_remove();
                    item.html(data);
                    $(".button").button();
                    $(".button").init_button();
                    $("#accordion").accordion();
                });         
            });
        },
        
        post_form : function(callback){
            
            $(this).children(".submit").button();
            var url = $(this).attr("action");
            
            $(this).submit(function(){
                $.post(url, $(this).serialize(), function(data){
                    callback(data)
                });
                return false;
            });
        },
        
        init_login_form : function(){
            $(this).unbind("dialog")
            $("#login_error").html("");
            $(this).dialog({"minWidth": 380, "resizable": false, "position": [null,100]});
            $this = $(this)
            $("#login_form").post_form(function(data){
                var item = $("#page_wrapper") //.css("display", "none");

                if(data.error){
                    $("#login_error").html(data.error_message);
                    $this.parent().effect("shake", {}, 100);
                }else{
                    $this.dialog("close")
                    item.html(data.html);
                    $(".menu_button").button();
                    $(".menu_button").k_show_template();
                }
            });
        }
        
        
    });
        
    init_page = function(){
        var item = $("#page_wrapper")
        $.get("/gajax", function(data){
            item.html("")
            item.html(data.html);
            
            if(data.error){
                $("#login_form_div").init_login_form()
            }else{    
                $(".menu_button").button();
                $(".menu_button").k_show_template();
            }
        });
    }
             
})(jQuery);

$(document).ready(function(){
      
  init_page()
            
});