<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Item_Language extends Generator_Item_Abstract_Item {

    protected function init() 
    {
        if (isset($_POST["lang"]) && !empty($_POST["lang"])) 
        {
            foreach ($_POST["lang"] as $lang) {
                $this->add(Generator_Template_Language::factory($lang));
            }
        } 
        else 
        {
            $this->add_errors(Generator_Util_Lang::get("empty_language_name", false));
        }
    }

}

?>
