<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Util_Lang {
    
    public static function get($string, $print=true)
    {
        if($print)
        {
            echo I18n::get($string, "generator-".I18n::$lang);
        }
        else
        {
            return I18n::get($string, "generator-".I18n::$lang);
        }
    }
}

?>
