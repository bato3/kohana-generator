<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 * Description of writer
 *
 * @author burningface
 */
class Generator_Item_Controller extends Generator_Item_Abstract_Item {
    
    protected function init() {
        if(isset($_POST["controller_name"]) && !empty($_POST["controller_name"])){
            $extends = isset($_POST["extends"]) ? Generator_Util_Config::load()->extend_controller[$_POST["extends"]] : "Controller";
            $controller_name = str_replace(" ", "", $_POST["controller_name"]);
            $this->add(
                $item = Generator_File::factory()
                    ->setFileName(strtolower($controller_name))
                    ->setDirectory("application".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."controller")
                    ->addLine("class Controller_".Generator_Util_Text::upperFirst($controller_name)." extends ".$extends." {\n")
                    ->addLine(Generator_Util_Text::space(4)."public function action_index(){")
                    ->addLine(Generator_Util_Text::space(4)."}\n")
                    ->addLine("}")
            );
        }else{
            $this->addErrors(Generator_Util_Lang::get("empty_controller_name", false));
        }
    }
}
?>