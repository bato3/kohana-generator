<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php

/**
 * Description of template
 *
 * @author burningface
 */
class Generator_Template {
    
    public static function generate(){
        $result = new Generator_Result();
        $template = 
"<!DOCTYPE html>
<html>
    <head>
        <title><?php echo \$title ?></title>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
        <?php
        echo html::style(\"assets/css/reset.css\");
        echo html::style(\"assets/css/main.css\");
        echo html::script(\"assets/js/jquery.min.js\");
        echo html::script(\"assets/js/main.js\");
        ?>
    </head>
    <body>
        <?php
        echo \$content;
        ?>
    </body>
</html>";
        
        $writer = new Generator_Filewriter("template");
        $writer->addRow($template);
        $writer->write(Generator_Filewriter::$TEMPLATE);
        $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
        $result->addWriteIsOk($writer->writeIsOk());
        return $result;
    }
}

?>
