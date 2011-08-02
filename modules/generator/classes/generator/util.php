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

    public static $SIMPLE_OPEN_FILE = "<?php defined('SYSPATH') or die('No direct access allowed.'); ?>\n";
    public static $OPEN_CLASS_FILE = "<?php defined('SYSPATH') or die('No direct access allowed.'); ?>\n<?php\n";
    public static $CLOSE_CLASS_FILE = "}\n\n?>";

    public static function listTableFields($table) {
        if ($table != "logins") {
            return Database::instance()->list_columns($table);
        } else {
            return array(
                array("column_name" => "username", "data_type" => "varchar"),
                array("column_name" => "password", "data_type" => "varchar"),
                array("column_name" => "remember", "data_type" => "checkbox")
            );
        }
    }
    
    public static function listTables(){
        return Database::instance()->list_tables();
    }

    public static function name($table, $db_name=true) {
        if($db_name){
            $len = strlen($table) - 1;
            return strtolower(substr($table, 0, $len));
        }else{
            return strtolower($table);
        }
    }

    public static function upperFirst($string) {
        return ucfirst(strtolower($string));
    }

    public static function classInfoHead($classname) {
        $config = self::loadConfig();
        return "/**\n*\n* Description of $classname\n*\n* @package\n* @copyright " . $config->get("copyright") . "\n* @license " . $config->get("license") . "\n* @author " . $config->get("author") . "\n*\n*/\n";
    }

    public static function methodInfoHead($return=null) {
        if (!empty($return)) {
            return "\t/**\n\t*\n\t* @return $return\n\t*\n\t*/";
        } else {
            return "\t/**\n\t*\n\t*/";
        }
    }

    public static function loadConfig() {
        return Kohana::$config->load("generator");
    }

}

?>
