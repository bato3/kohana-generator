<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php

/**
 *
 * @author burningface
 */
class Generator_Template_Template {

    public static function factory($name = null) {
        if($name == null){ $name = "template"; }
        
        return Generator_File::factory()
                        ->setFileName($name)
                        ->setDirectory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "templates")
                        ->addLine("?>")
                        ->addLine("<!DOCTYPE html>")
                        ->addLine(Generator_Util_Text::space(4) . "<html>")
                        ->addLine(Generator_Util_Text::space(8) . "<head>")
                        ->addLine(Generator_Util_Text::space(12) . "<title></title>")
                        ->addLine(Generator_Util_Text::space(12) . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">")
                        ->addLine(Generator_Util_Text::space(8) . "</head>")
                        ->addLine(Generator_Util_Text::space(4) . "<body>")
                        ->addLine(Generator_Util_Text::space(8) . "<div>")
                        ->addLine(Generator_Util_Text::space(12) . "<?php echo \$content ?>")
                        ->addLine(Generator_Util_Text::space(8) . "</div>")
                        ->addLine(Generator_Util_Text::space(4) . "</body>")
                        ->addLine("</html>")
                        ->setDisableCloseTag(true);
    }

}

?>
