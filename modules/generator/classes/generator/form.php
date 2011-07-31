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
        "checkbox"
    );

    private static function label($key) {
        $label = "\$labels[\"" . $key . "\"].\": \"";
        return "<?php echo form::label(\"$key\", $label) ?>";
    }

    private static function radio($key) {
        $yeslabel = "\$labels[\"" . $key . "_yes\"].\": \"";
        $nolabel = "\$labels[\"" . $key . "_no\"].\": \"";
        return "<?php echo form::label(\"$key\", $yeslabel) ?><?php echo form::radio(\"$key\", 1, isset(\$values[\"$key\"]) ? \$values[\"$key\"] ?  true : false : false) ?>&nbsp;&nbsp;<?php echo form::label(\"$key\", $nolabel) ?><?php echo form::radio(\"$key\", 0, isset(\$values[\"$key\"]) ? !\$values[\"$key\"] ? true : false : true) ?>";
    }

    private static function checkbox($key) {
        return "<?php echo form::checkbox(\"$key\", 1, false, array(\"id\" => \"$key\")) ?>";
    }

    private static function password($key) {
        return "<?php echo form::password(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>";
    }

    private static function input($key) {
        return "<?php echo form::input(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>";
    }

    private static function select($key) {
        return "<?php echo form::select(\"$key\", \$$key, isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>";
    }

    private static function textarea($key) {
        return "<?php echo form::textarea(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\", array(\"id\" => \"$key\")) ?>";
    }

    private static function hidden($key) {
        return "<?php echo form::hidden(\"$key\", isset(\$values[\"$key\"]) ? \$values[\"$key\"] : \"\") ?>";
    }

    private static function submit() {
        $label = "\$labels[\"submit\"]";
        return "<?php echo form::submit(\"submit\", $label, array(\"id\" => \"submit\")) ?>";
    }

    private static function error($key) {
        return "\t<?php echo isset(\$errors[\"$key\"]) ? \"<span class=\\\"errors\\\">\".\$errors[\"$key\"].\"</span>\" : \"\" ?>";
    }

    private static function csrfToken() {
        $config = Generator_Util::loadConfig();
        $name = $config->get("csrf_token_name");
        return "<?php echo form::hidden(\"$name\", Security::token()) ?>";
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

    private static function wrappDiv($item, $attributes=array()) {
        return empty($attributes) ? "<div>$item</div>" : "<div" . html::attributes($attributes) . ">$item</div>";
    }

    private static function wrappP($item, $attributes=array()) {
        return empty($attributes) ? "<p>$item</p>" : "<p" . html::attributes($attributes) . ">$item</p>";
    }

    private static function wrappTD($item, $attributes=array()) {
        return empty($attributes) ? "<td>$item</td>" : "<td" . html::attributes($attributes) . ">$item</td>";
    }

    private static function wrappTR($item, $attributes=array()) {
        return empty($attributes) ? "<tr>$item</tr>" : "<tr" . html::attributes($attributes) . ">$item</tr>";
    }

    public static function listTables($with_login=false) {
        $tables = Database::instance()->list_tables();

        $names = $with_login ? array("logins" => "logins") : array();
        foreach ($tables as $name) {
            $names[$name] = $name;
        }
        return form::select("tables", $names, "", array("id" => "table_list"));
    }

    public static function suggestInput($name, $type, $key=null) {
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

        return form::select($name, self::$INPUTS, $suggest);
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

    private static function getInput($key=null, $val=null) {
        if (is_numeric($val) && !empty($val) && !empty($key)) {
            $method = self::$INPUTS[$val];

            if (!empty($method)) {
                if (method_exists("Generator_Form", $method)) {
                    if ($method != "hidden") {
                        return self::label($key) . call_user_func("Generator_Form::$method", $key);
                    } else {
                        return call_user_func("Generator_Form::$method", $key);
                    }
                }
            }
        } else if (empty($val) && $key == "submit") {
            return self::label("submit") . call_user_func("Generator_Form::submit");
        }
    }

    private static function rowWrapper($row, $wrapper, $key=null, $val=null) {
        if (!empty($row) && !empty($wrapper)) {
            if (!empty($key) && self::$INPUTS[$val] != "hidden") {
                switch ($wrapper) {
                    case "table" :
                        return "\t" . self::wrappTR(self::wrappTD($row) . self::wrappTD(self::error($key)));
                        break;
                    case "p" :
                        return "\t" . self::wrappP($row . self::error($key));
                        break;
                    default :
                        return "\t" . self::wrappDiv($row . self::error($key));
                }
            } else {
                switch ($wrapper) {
                    case "table" :
                        return "\t" . self::wrappTR(self::wrappTD($row));
                        break;
                    case "p" :
                        return "\t" . self::wrappP($row);
                        break;
                    default :
                        return "\t" . self::wrappDiv($row);
                }
            }
        }
    }

    private static function orderPost($post) {
        $places = $post["place"];
        unset($post["place"]);

        $rev = array();
        foreach ($places as $name => $place) {
            if (!array_key_exists($place, $rev)) {
                $rev[$place] = $name;
            } else {
                $ok = false;
                while (!$ok) {
                    if (!array_key_exists($place, $rev)) {
                        $rev[$place] = $name;
                        $ok = true;
                    }
                    $place++;
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
    }

    public static function generate($post_array) {
        $wrapper = self::$WRAPPERS[$post_array["wrapper"]];
        $filename = Generator_Util::name($post_array["generate_form_name"]);

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
                $writer->addRow(self::rowWrapper(Generator_Form::getInput($key, $val), $wrapper, $key, $val));
            }
        }
        $writer->addRow("\t" . self::csrfToken());
        $writer->addRow(self::rowWrapper(Generator_Form::getInput("submit"), $wrapper));

        if ($wrapper == "table") {
            $writer->addRow(self::tableClose());
        }

        $writer->addRow(self::formClose());
        $writer->write(Generator_Filewriter::$FORM);
        return $writer;
    }

}

?>
