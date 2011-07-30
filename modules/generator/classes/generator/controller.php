<?php
defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller
 *
 * @author burningface
 */
class Generator_Controller {
    
    public static $EXTENDS_FROM = array(
        "Controller", 
        "Controller_Template", 
        "Controller_Template_Twig",
        "Controller_Template_Smarty"
        );
    
    private static function getExtends($extends){
        $html = " extends ";
        switch ($extends){
            case 1: 
                $html .= self::$EXTENDS_FROM[1];
                break;
            case 2: 
                $html .= self::$EXTENDS_FROM[2];
                break;
            case 3: 
                $html .= self::$EXTENDS_FROM[3]; 
                break;
            default :
                $html .= self::$EXTENDS_FROM[0]; 
        }
        return $html." {\n";
    }
    
    private static function getAction($actions_array){
        $actions = array();
        foreach ($actions_array as $action){
            if(!empty ($action)){
                $name = strtolower($action);
                $actions[] = "\tpublic function action_$name() {}\n";
            }
        }
        return $actions;
    }
    
    public static function generateController($post){
        $extends = $post["extends"];
        $controllername = $post["controllername"];
        $actions = $post["actions"];
        $writer = new Generator_Filewriter($controllername);
        
        $controllername = "Controller_".ucfirst(strtolower($controllername));
        
        $writer->addRow(Generator_Util::$pagehead);
        $writer->addRow("<?php\n");
        $writer->addRow(Generator_Util::classInfoHead($controllername));
        $writer->addRow("class ". $controllername.self::getExtends($extends));
        $actions_array = self::getAction($actions);
        foreach($actions_array as $action){
            $writer->addRow(Generator_Util::methodInfoHead());
            $writer->addRow($action);
        }
        $writer->addRow("}\n");
        $writer->addRow("?>");
        $writer->write(Generator_Filewriter::$CONTROLLER);
        return $writer;
    }
}

?>
