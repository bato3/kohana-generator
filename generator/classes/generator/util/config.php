<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 * Description of config
 *
 * @author burningface
 */
class Generator_Util_Config {
       
    public static function load(){
        return Kohana::$config->load("generator");
    }
    
}

?>
