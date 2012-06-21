<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 * Description of writer
 *
 * @author burningface
 */
class Generator_Item_Template extends Generator_Item_Abstract_Item {
    
    protected function init() {
        $name = !empty($_POST["template_name"]) ? str_replace(" ", "_", $_POST["template_name"]) : "template"; 
        $this->add(
                Generator_File::factory()
                    ->setFileName($name)
                    ->setDirectory("application".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."templates")
                    ->addLine("?>")
                    ->addLine("<!DOCTYPE html>")
                    ->addLine(Generator_Util_Text::space(4)."<html>")
                    ->addLine(Generator_Util_Text::space(8)."<head>")
                    ->addLine(Generator_Util_Text::space(12)."<title></title>")
                    ->addLine(Generator_Util_Text::space(12)."<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">")
                    ->addLine(Generator_Util_Text::space(8)."</head>")
                    ->addLine(Generator_Util_Text::space(4)."<body>")
                    ->addLine(Generator_Util_Text::space(8)."<div>")
                    ->addLine(Generator_Util_Text::space(12)."<?php echo \$content ?>")
                    ->addLine(Generator_Util_Text::space(8)."</div>")
                    ->addLine(Generator_Util_Text::space(4)."</body>")
                    ->addLine("</html>")
                    ->setDisableCloseTag(true)
                );
    }
}
?>