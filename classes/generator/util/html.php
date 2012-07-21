<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Util_Html {
    
    public static function button($generator_name){
        $register = Generator_Util_Config::load()->register;
        $lang = Generator_Util_Lang::get($register[$generator_name]["menu"], false);
        echo "<div>
                <button id=\"$generator_name\" class=\"button\">$lang</button>
            </div>";
    }
    
    public static function select(){
        echo "<div class=\"ui-widget\">";
        $options = array(" ");
        $tables = Database::instance()->list_tables();
        foreach($tables as $table){
            $options[$table] =  $table;
        }
        
        echo form::select("table", $options, null, array("class" => "send")); 
        echo "</div>";
    }
}

?>
