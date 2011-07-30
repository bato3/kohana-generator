<?php
defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of util
 *
 * @author burningface
 */
class Generator_Util {
    
    public static $pagehead = "<?php defined('SYSPATH') or die('No direct access allowed.'); ?>";
    
    public static function listTableFields($table) {
        if($table != "logins"){
            return Database::instance()->list_columns($table);
        }else{
            return array(
                array("column_name" => "username","data_type" => "varchar"),
                array("column_name" => "password","data_type" => "varchar"),
                array("column_name" => "remember","data_type" => "checkbox")
            );
        }
    }
    
    public static function name($table) {
        $len = strlen($table) - 1;
        return substr($table, 0, $len);
    }
    
    public static function classInfoHead($classname){
        $config = Kohana::$config->load("generator");
        return "/**\n*\n* Description of $classname\n*\n* @package\n* @copyright ".$config->get("copyright")."\n* @license ".$config->get("license")."\n* @author ".$config->get("author")."\n*\n*/\n";
    }
    
    public static function methodInfoHead($return=null){
        if(!empty ($return)){
            return "\t/**\n\t*\n\t* @return $return\n\t*\n\t*/";
        }else{
            return "\t/**\n\t*\n\t*/";
        }
    }
}

?>
