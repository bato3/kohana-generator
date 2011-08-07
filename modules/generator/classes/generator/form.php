<?php

defined('SYSPATH') or die('No direct access allowed.');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of form
 *
 * @author burningface
 */
class Generator_Form {

    public static $WRAPPERS = array("table", "div", "p");
    public static $INPUTS = array(
        "disable",
        "input",
        "password",
        "hidden",
        "textarea",
        "select",
        "radio",
        "checkbox",
    );

    private static function label($key) {
        $label = "\$labels[\"" . $key . "\"].\": \"";
        return "        <?php echo form::label(\"$key\", $label) ?>\n";
    }

    private static function radio($key) {
        $yeslabel = "\$labels[\"" . $key . "_yes\"].\": \"";
        $nolabel = "\$labels[\"" . $key . "_no\"].\": \"";
        return "        <?php echo form::label(\"$key\", $yeslabel) ?>\n        <?php echo form::radio(\"$key\", 1, isset(\$values[\"$key\"]) ? \$values[\"$key\"] ?  true : false : false) ?>\n        &nbsp;&nbsp;<?php echo form::label(\"$key\", $nolabel) ?>\n        <?php echo form::radio(\"$key\", 0, isset(\$values[\"$key\"]) ? !\$values[\"$key\"] ? true : false : true) ?>\n";
    }

    private static function checkbox($key) {
        return "        <?php echo form::checkbox(\"$key\", 1, false, array(\"id\" => \"$key\")) ?>\n";
    }

    private static function password($key) {
        return "        <?php echo form::password(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>\n";
    }

    private static function input($key) {
        return "        <?php echo form::input(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>\n";
    }

    private static function select($key) {
        return "        <?php echo form::select(\"$key\", \$$key, isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>\n";
    }

    private static function textarea($key) {
        return "        <?php echo form::textarea(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>\n";
    }

    private static function hidden($key) {
        return "        <?php echo form::hidden(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\") ?>\n";
    }

    private static function submit() {
        $label = "\$labels[\"submit\"]";
        $config = Generator_Util::loadConfig();
        $name = $config->get("csrf_token_name");
        return "        <?php echo form::hidden(\"$name\", Security::token()) ?>\n        <?php echo form::submit(\"submit\", $label, array(\"id\" => \"submit\")) ?>\n";
    }

    private static function error($key) {
        $config = Generator_Util::loadConfig();
        $class = $config->get("error_class");
        return "        <?php echo isset(\$errors[\"$key\"]) ? \"<span class=\\\"$class\\\">\".\$errors[\"$key\"].\"</span>\" : \"\" ?>\n";
    }

    private static function formOpen() {
        return "<?php echo form::open(\$action) ?>";
    }

    private static function formClose() {
        return "<?php echo form::close() ?>";
    }

    private static function tableOpen() {
        return "<table>";
    }

    private static function tableClose() {
        return "</table>";
    }

    private static function flashMessage() {
        return "<?php if(isset(\$flash)){ ?> <div class=\"flash\"><?php echo \$flash ?></div> <?php } ?>";
    }

    private static function wrappDiv($item) {
        $config = Generator_Util::loadConfig();
        $class = $config->get("form_row_class");
        return empty($class) ? "    <div>\n$item    </div>" : "    <div class=\"$class\">\n$item    </div>";
    }

    private static function wrappP($item) {
        $config = Generator_Util::loadConfig();
        $class = $config->get("form_row_class");
        return empty($class) ? "    <p>\n$item    </p>" : "    <p class=\"$class\">\n$item    </p>";
    }

    private static function wrappTD($item) {
        return "        <td>\n    $item        </td>\n";
    }

    private static function wrappTR($item) {
        $config = Generator_Util::loadConfig();
        $class = $config->get("form_row_class");
        return empty($class) ? "    <tr>\n$item    </tr>" : "    <tr class=\"$class\">\n$item    </tr>";
    }

    public static function listTables($with_login=false) {
        $tables = Generator_Util::listTables();

        $names = $with_login ? array("logins" => "logins") : array();
        foreach ($tables as $table) {
            $names[$table] = $table;
        }
        return form::select("tables", $names, "", array("id" => "table_list"));
    }

    public static function inputSuggest($name, $id, $type, $key=null) {
        $suggest = null;
        switch ($type) {
            case "checkbox" :
                $suggest = 7;
                break;
            case "tinyint" :
                $suggest = 6;
                break;
            case "text" :
                $suggest = 4;
                break;
            case "tinytext" :
                $suggest = 4;
                break;
            case "mediumtext" :
                $suggest = 4;
                break;
            case "longtext" :
                $suggest = 4;
                break;
            case "int" :
                $suggest = self::keysuggest($key);
                break;
            case "int unsigned" :
                $suggest = self::keysuggest($key);
                break;
            case "bigint" :
                $suggest = self::keysuggest($key);
                break;
            case "bigint unsigned" :
                $suggest = self::keysuggest($key);
                break;
            case "varchar" :
                $suggest = 1;
                break;
            default :
                $suggest = 1;
        }

        return form::select($name, self::$INPUTS, $suggest, array("class" => "inputsuggest", "id" => $id));
    }

    private static function keysuggest($key) {
        if ($key == "PRI") {
            return 3;
        } else if ($key == "MUL") {
            return 5;
        } else {
            return 1;
        }
    }

    private static function getInput($key, $val=null) {
        if ($key == "submit") {
            return call_user_func("Generator_Form::submit");
        } else if (is_numeric($val) && !empty($val)) {
            if (array_key_exists($val, self::$INPUTS)) {
                $method = self::$INPUTS[$val];

                if (!empty($method)) {
                    if (method_exists("Generator_Form", $method)) {
                        if ($method != "hidden") {
                            return call_user_func("Generator_Form::$method", $key);
                        } else {
                            return call_user_func("Generator_Form::$method", $key);
                        }
                    }
                }
            }
        }
    }

    private static function rowWrapper($key, $val, $wrapper="div") {
        if (!empty($key)) {
            $input = self::getInput($key, $val);
            $label = self::label($key);

            if (!empty($wrapper) && !empty($input)) {
                if (array_key_exists($val, self::$INPUTS) && self::$INPUTS[$val] != "hidden" || $key == "submit") {
                    switch ($wrapper) {
                        case "table" :
                            return self::wrappTR(self::wrappTD($label) . self::wrappTD($input) . self::wrappTD(self::error($key)));
                            break;
                        case "p" :
                            return self::wrappP($label . $input . self::error($key));
                            break;
                        case "div" :
                            return self::wrappDiv($label . $input . self::error($key));
                            break;
                        default :
                            return self::wrappDiv($label . $input . self::error($key));
                    }
                } else {
                    switch ($wrapper) {
                        case "table" :
                            return self::wrappTR(self::wrappTD("        &nbsp;\n") . self::wrappTD("        &nbsp;\n") . self::wrappTD($input));
                            break;
                        case "p" :
                            return self::wrappP($input);
                            break;
                        case "div" :
                            return self::wrappDiv($input);
                            break;
                        default :
                            return self::wrappDiv($input);
                    }
                }
            }
        }
    }

    private static function orderPost($post) {
        if (isset($post["place"])) {
            $places = $post["place"];
            unset($post["place"]);

            $rev = array();
            foreach ($places as $name => $place) {
                if (!array_key_exists($place, $rev)) {
                    $rev[$place] = $name;
                } else {
                    $ok = false;
                    $new_place = 0;
                    while (!$ok) {
                        if (!array_key_exists($new_place, $rev)) {
                            $rev[$new_place] = $name;
                            $ok = true;
                        }
                        $new_place++;
                    }
                }
            }
            ksort($rev);

            $array = array();
            foreach ($rev as $index => $name) {
                foreach ($post as $key => $value) {
                    if ($name == $key) {
                        if (!array_key_exists($key, $array)) {
                            $array[$key] = $value;
                        }
                    }
                }
            }
            return $array;
        } else {
            return array();
        }
    }

    public static function generate($post_array, $db_name=true) {
        $result = new Generator_Result();

        $config = Generator_Util::loadConfig();
        $wrapper = self::$WRAPPERS[$post_array["wrapper"]];
        $filename = Generator_Util::name($post_array["generate_form_name"], $db_name);

        $writer = new Generator_Filewriter($filename);

        $writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);

        if (isset($post_array["flashmessage"])) {
            if ($post_array["flashmessage"] == 1) {
                $writer->addRow(self::flashMessage());
            }
            unset($post_array["flashmessage"]);
        }

        $writer->addRow(self::formOpen());
        if ($wrapper == "table") {
            $writer->addRow(self::tableOpen());
        }

        $post_array = self::orderPost($post_array);
        foreach ($post_array as $key => $val) {
            if (is_numeric($val) && !empty($key) && $key != "wrapper") {
                $writer->addRow(self::rowWrapper($key, $val, $wrapper));
            }
        }
        $writer->addRow(self::rowWrapper("submit", null, $wrapper));

        if ($wrapper == "table") {
            $writer->addRow(self::tableClose());
        }

        $writer->addRow(self::formClose());
        $writer->addRow("<div class=\"" . $config->get("back_link_class") . "\"><a href=\"/$filename/\"><?php echo __(\"back\") ?></a></div>");
        $writer->write(Generator_Filewriter::$FORM);

        $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
        $result->addWriteIsOk($writer->writeIsOk());

        return $result;
    }

}

?>
