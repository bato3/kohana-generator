<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="/gmedia/img/k.png" rel="icon" />
        <?php
        echo html::style("gmedia/css/ui-lightness/jquery-ui-1.8.20.custom.css");
        echo html::script("gmedia/js/jquery-1.7.2.min.js");    
        echo html::script("gmedia/js/jquery-ui-1.8.20.custom.min.js");
        echo html::style("gmedia/css/main.css");
        echo html::script("gmedia/js/main.js");
        ?>
    </head>
    <body>
        <div id="page_wrapper">
            <?php echo $content ?>
        </div>
    </body>
</html>