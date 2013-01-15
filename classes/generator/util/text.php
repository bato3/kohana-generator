<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Util_Text {
    
    public static function upper_first($string)
    {
        return ucfirst(strtolower($string));
    }
    
    /**
     * patched by alrusdi
     * thanks!
     */
    public static function space($num=0)
    {        
        return str_repeat(" ", $num);
    }
}

?>
