<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_Template extends Generator_Item_Abstract_Item {

    protected function init() 
    {
        $name = !empty($_POST["template_name"]) ? str_replace(" ", "_", $_POST["template_name"]) : null;

        $this->add(Generator_Template_Template::factory($name));
    }

}

?>
