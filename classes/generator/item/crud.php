<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_Crud extends Generator_Item_Abstract_Item {
    
    protected function init() {
        
        if (isset($_POST["table"]) && !empty($_POST["table"])) {

            $this->add(Generator_Template_Crud::factory($_POST["table"]));
            $this->add(Generator_Template_Form::factory($_POST["table"]));
            $this->add(Generator_Template_List::factory($_POST["table"]));
            $this->add(Generator_Template_Show::factory($_POST["table"]));
            $this->add(Generator_Template_Orm::factory($_POST["table"]));
            
            foreach ($this->config->languages as $lang){
                $this->add(Generator_Template_Language::factory($lang));
            }
            
            $this->add(Generator_Template_Template::factory());
            
        } else {

            $this->addErrors(Generator_Util_Lang::get("empty_table_name", false));
        }
        
    }
}
?>