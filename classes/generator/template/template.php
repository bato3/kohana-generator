<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php

/**
 *
 * @author burningface
 */
class Generator_Template_Template {

    public static function factory($name = null) 
    {
        if($name == null){ $name = "template"; }
        
        return Generator_File::factory()
                ->set_file_name(strtolower($name))
                ->set_directory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "templates")
                ->add_row("?>")
                ->add_row("<!DOCTYPE html>")
                ->add_row(Generator_Util_Text::space(4) . "<html>")
                ->add_row(Generator_Util_Text::space(8) . "<head>")
                ->add_row(Generator_Util_Text::space(12) . "<title></title>")
                ->add_row(Generator_Util_Text::space(12) . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">")
                ->add_row(Generator_Util_Text::space(8) . "</head>")
                ->add_row(Generator_Util_Text::space(4) . "<body>")
                ->add_row(Generator_Util_Text::space(8) . "<div>")
                ->add_row(Generator_Util_Text::space(12) . "<?php echo \$content ?>")
                ->add_row(Generator_Util_Text::space(8) . "</div>")
                ->add_row(Generator_Util_Text::space(4) . "</body>")
                ->add_row("</html>")
                ->set_disable_close_tag(true);
    }

}

?>
