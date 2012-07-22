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
    
    public static function space($num=0)
    {
        $space = "";
        
        for($i=1; $i<=$num; ++$i){
            $space .= " ";
        }
        
        return $space;
    }
}

?>
