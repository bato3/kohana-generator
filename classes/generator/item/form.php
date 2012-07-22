<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_Form extends Generator_Item_Abstract_Item {

    protected function init() 
    {
        if (isset($_POST["table"]) && !empty($_POST["table"])) 
        {
            $this->add(Generator_Template_Form::factory($_POST["table"]));    
        } 
        else 
        {
            $this->add_errors(Generator_Util_Lang::get("empty_table_name", false));
        }
    }

}

?>
