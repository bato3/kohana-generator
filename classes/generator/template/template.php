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
                ->add_row("<html>")
                ->add_row("<head>", 4)
                ->add_row("<title></title>", 8)
                ->add_row("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">", 8)
                ->add_row("<?php echo html::script('assets/js/jquery.min.js') ?>", 8)
                ->add_row("<?php echo html::style('assets/css/kube.min.css') ?>", 8)
                ->add_row("<?php echo html::style('assets/css/master.css') ?>", 8)
                ->add_row("</head>", 4)
                ->add_row("<body>", 4)
                ->add_row("<div id=\"page\">", 8)
                ->add_row("<?php echo \$content ?>", 12)
                ->add_row("</div>", 8)
                ->add_row("</body>", 4)
                ->add_row("</html>")
                ->set_disable_close_tag(true);
    }

}

?>
