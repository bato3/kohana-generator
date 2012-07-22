<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_Orm extends Generator_Item_Abstract_Item {

    protected function init() 
    {    
        if (isset($_POST["table"]) && !empty($_POST["table"])) 
        {       
            $this->add(Generator_Template_Orm::factory($_POST["table"]));
        }
    }

}

?>
