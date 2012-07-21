<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_List extends Generator_Item_Abstract_Item {

    protected function init() {

        if (isset($_POST["table"]) && !empty($_POST["table"])) {

            $this->add(Generator_Template_List::factory($_POST["table"]));
            
        } else {

            $this->addErrors(Generator_Util_Lang::get("empty_table_name", false));
        }
    }

}

?>