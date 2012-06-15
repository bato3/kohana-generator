<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php

/**
 * Description of controller
 *
 * @author burningface
 */
class Generator_Controller {

    private static $default_action = array("index");
    private static $default_controller = "default";

    public static function getControllers() {
        $config = Generator_Util::loadConfig();
        return $config->get("controllers");
    }

    protected static function getExtends($extends) {
        $html = " extends ";
        $controllers = self::getControllers();
        if (array_key_exists($extends, $controllers)) {
            $html .= $controllers[$extends];
        }
        return $html . " {\n";
    }

    protected static function getActions($actions_array) {
        $actions = "";
        foreach ($actions_array as $action) {
            if (!empty($action)) {
                $name = strtolower($action);
                $actions .= Generator_Util::methodInfoHead();
                $actions .= "   public function action_$name() {}\n\n";
            }
        }
        return $actions;
    }

    protected static function getActionPaths($controller, $actions_array) {
        $paths = "";
        $controller = strtolower($controller);
        foreach ($actions_array as $action) {
            if (!empty($action)) {
                $name = strtolower($action);
                if($action == "index"){
                    $paths .= "    private \$" . $name . "_url = \"$controller/\";\n";                    
                }else{
                    $paths .= "    private \$" . $name . "_url = \"$controller/$name\";\n";
                }
            }
        }
        return $paths;
    }

    public static function generate($post) {
        $result = new Generator_Result();
        $extends = $post["extends"];

        $controller = !empty($post["controllername"]) ? $post["controllername"] : self::$default_controller;
        $actions = isset($post["actions"]) ? $post["actions"] : self::$default_action;

        $writer = new Generator_Filewriter($controller);

        $controllername = "Controller_" . Generator_Util::upperFirst($controller);

        $writer->addRow(Generator_Util::$OPEN_CLASS_FILE);
        $writer->addRow(Generator_Util::classInfoHead($controllername));
        $writer->addRow("class " . $controllername . self::getExtends($extends));

        $writer->addRow(self::getActionPaths($controller, $actions));
        $writer->addRow(self::getActions($actions));

        $writer->addRow(Generator_Util::$CLOSE_CLASS_FILE);
        $writer->write(Generator_Filewriter::$CONTROLLER);
        
        $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
        $result->addWriteIsOk($writer->writeIsOk());
        
        return $result;
    }

}

?>
