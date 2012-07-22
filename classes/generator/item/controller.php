<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_Controller extends Generator_Item_Abstract_Item {

    protected function init() 
    {
        if (isset($_POST["controller_name"]) && !empty($_POST["controller_name"])) 
        {
            $extends = isset($_POST["extends"]) ? Generator_Util_Config::load()->extend_controller[$_POST["extends"]] : "Controller";
            $controller_name = str_replace(" ", "", $_POST["controller_name"]);

            $this->add(
                    Generator_File::factory()
                            ->set_file_name(strtolower($controller_name))
                            ->set_directory("application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "controller")
                            ->add_row("class Controller_" . Generator_Util_Text::upper_first($controller_name) . " extends " . $extends . " {\n")
                            ->add_row(Generator_Util_Text::space(4) . "public function action_index()")
                            ->add_row(Generator_Util_Text::space(4) . "{\n")
                            ->add_row(Generator_Util_Text::space(4) . "}\n")
                            ->add_row("}")
            );
 
        } 
        else 
        {
            $this->add_errors(Generator_Util_Lang::get("empty_controller_name", false));
        }
    }

}

?>
