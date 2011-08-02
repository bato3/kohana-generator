<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Kohana generator</title>
        <meta name="author" content="burningface"/>
        <?php
        echo html::style("generatorassets/css/style.css");
        echo html::script("generatorassets/js/jquery.min.js");
        echo html::script("generatorassets/js/script.js");
        ?>
    </head>
    <body>
        <div id="wrapper">
            <div id="menu">
                <ul>
                    <li><?php echo html::anchor("generator/", "index", array("id" => "index", "class" => "menu_link")) ?></li>
                    <?php
                    foreach ($links as $path => $link) {
                        ?>
                        <li><?php echo html::anchor("generator/$path", $link, array("id" => $link, "class" => "menu_link")) ?></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div id="content">
                <fieldset>
                    <legend><?php echo $legend ?></legend>
                    <?php
                    if (isset($flash)) {
                        echo "<div class=\"flash\">" . $flash . "</div>";
                    }
                    echo $content;
                    ?>
                    <div id="result"></div>
                    <div id="post_result"></div>
                </fieldset>
            </div>
        </div>

    </body>
</html>