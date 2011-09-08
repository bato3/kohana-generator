<?php

defined('SYSPATH') or die('No direct access allowed.');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model
 *
 * @author burningface
 */
class Generator_Model {

    private static function getCsrf() {
        $config = Generator_Util::loadConfig();
        return "    public function csrf(\$values){
            return Validation::factory(array(\"csrf\" => \$values[\"" . $config->get("csrf_token_name") . "\"]))->rule(\"csrf\", \"Security::check\");
       }
        ";
    }

    private static function getFormErrors() {
        return "    public function formErrors() {
            return \$this->validation()->errors(\"form_errors\"); 
       }
        ";
    }

    private static function getSelect($table_name) {
        $config = Generator_Util::loadConfig();
        return "    public function selectOptions() {
        \$config = Kohana::\$config->load(\"models\");
        \$options = \$config->get(\"$table_name\");
        \$key_field = \$options[\"select_option_key\"];
        \$value_field = \$options[\"select_option_value\"];
        \$pre_option = \$options[\"select_option_pre_option\"];
            
        if (!empty(\$pre_option)) {
            \$array = array();
            \$array[\" \"] = \$pre_option;  
            \$result = \$this->order_by(\$value_field)->find_all()->as_array(\$key_field, \$value_field);
            foreach (\$result as \$key => \$value) {
                \$array[\$key] = \$value;
            }
            return \$array;
        }else{
            return \$this->order_by(\$value_field)->find_all()->as_array(\$key_field, \$value_field);
        }
    }
    ";
    }

    private static function getRules(Generator_Field $field) {
        $min = $field->getMin();
        $max = $field->getMax();
        $key = $field->getKey();

        $config = Generator_Util::loadConfig();
        $date_format = $config->get("date_format");
        $validation = "\n                array(\"not_empty\"),\n";

        switch ($field->getType()) {
            case "date" : $validation .= "                array(\"date\",array(\":value\", \"" . $date_format . "\")),\n";
                break;
            case "year" : $validation .= "                array(\"date\",array(\":value\", \"Y\")),\n";
                break;
            case "smallint" : $validation .= "                array(\"digit\"),\n";
                break;
            case "smallint unsigned" : $validation .= "                array(\"digit\"),\n";
                break;
            case "int" : $validation .= "                array(\"digit\"),\n";
                break;
            case "int unsigned" : $validation .= "                array(\"digit\"),\n";
                break;
            case "bigint" : $validation .= "                array(\"digit\"),\n";
                break;
            case "bigint unsigned" : $validation .= "                array(\"digit\"),\n";
                break;
            case "float" : $validation .= "                array(\"numeric\"),\n";
                break;
            case "float unsigned" : $validation .= "                array(\"numeric\"),\n";
                break;
            case "double" : $validation .= "                array(\"numeric\"),\n";
                break;
            case "double unsigned" : $validation .= "                array(\"numeric\"),\n";
                break;
            case "decimal" : $validation .= "                array(\"numeric\"),\n";
                break;
            case "decimal unsigned" : $validation .= "                array(\"numeric\"),\n";
                break;
            case "" : $validation .= "";
                break;
        }

        if (!empty($min) && !empty($max)) {
            $validation .= "                array(\"min_length\",array(\":value\", $min)),\n                array(\"max_length\",array(\":value\", $max)),\n";
        }
        if (empty($min) && !empty($max)) {
            $validation .= "                array(\"max_length\",array(\":value\", $max)),\n";
        }
        if (!empty($key) && $key == "UNI") {
            $validation .= "                array(array(\$this, \"unique\"), array(\"" . $field->getName() . "\", \":value\")),\n";
        }
        return $validation;
    }

    private static function getFilters(Generator_Field $field) {
        $validation = "\n                array(\"trim\"),\n";
        switch ($field->getType()) {
            case "varchar" : $validation .= "                array(\"strtolower\"),\n                array(\"ucwords\"),\n";
                break;
        }
        return $validation . "";
    }

    private static function genORM($filename) {
        return "class Model_" . $filename . " extends ORM {\n";
    }

    private static function getTableRelationShips($table) {
        $has_many = array("    protected \$_has_many = array(");
        $belongs_to = array("    protected \$_belongs_to = array(");

        $db = Database::instance();
        $query = $db->query(Database::SELECT, 'SELECT * FROM information_schema.key_column_usage WHERE (TABLE_NAME=\''
                . $table . '\' OR REFERENCED_TABLE_NAME=\'' . $table . '\') AND referenced_column_name IS NOT NULL');

        foreach ($query as $row) {
            $foreign_key = $row['COLUMN_NAME'];
            if ($row['REFERENCED_TABLE_NAME'] === $table) {
                $name = Generator_Util::name($row['TABLE_NAME']);
                $has_many[] = "        \"$name\" => array(\"model\" => \"$name\", \"foreign_key\" => \"$foreign_key\"),";
            } else {
                $name = Generator_Util::name($row['REFERENCED_TABLE_NAME']);
                $belongs_to[] = "        \"$name\" => array(\"model\" => \"$name\", \"foreign_key\" => \"$foreign_key\"),";
            }
        }

        $has_many[] = "    );\n";
        $belongs_to[] = "    );\n";
        return array_merge($belongs_to, $has_many);
    }

    private static function labels($array, $model, $multilang) {
        $html = "    public function labels(){\n";
        if($multilang){
            $html .= "        \$lang = I18n::get(\"$model\");\n";
            $html .= "        return array(\n";
            foreach ($array as $key => $value) {
                $html .= "            \"$key\" => \$lang[\"$value\"],\n";
            }
            $html .= "            \"submit\" => \$lang[\"submit\"],\n";
        }else{
            $html .= "        return array(\n";
            foreach ($array as $key => $value) {
                $html .= "            \"$key\" => \"$value\",\n";
            }
            $html .= "            \"submit\" => \"submit\",\n";
        }
        return $html . "\n        );\n    }\n";
    }

    private static function rules($array) {
        $html = "    public function rules(){\n        return array(\n";
        foreach ($array as $key => $value) {
            $html .= "            \"$key\" => array(" . $value . "            ),\n";
        }
        return $html . "\n        );\n    }\n";
    }

    private static function filters($array) {
        $html = "    public function filters(){\n        return array(\n";
        foreach ($array as $key => $value) {
            $html .= "            \"$key\" => array(" . $value . "            ),\n";
        }
        return $html . "\n        );\n    }\n";
    }

    public static function generate() {
        $result = new Generator_Result();

        $tables = Generator_Util::listTables();
        $config = Generator_Util::loadConfig();
        $disabled_tables = $config->get("disabled_tables");
        $i18n = array("back" => "back", 
            "edit_head" => "edit", "delete_head" => "delete", "show_head" => "show",
            "create" => "new", "edit" => "edit", "delete" => "delete", "show" => "show",
            "save_success" => "Save success!",
            "update_success" => "Update success!",
            "delete_success" => "Delete success!",
            "save_failed" => "Save failed!",
            "update_failed" => "Update failed!",
            "delete_failed" => "Delete failed!",
            "First" => "First",
            "Previous" => "&laquo;Previous",
            "Next" => "Next&raquo;",
            "Last" => "Last",
        );
        $model_names = array();
        foreach ($tables as $key => $table) {
            if (!in_array($table, $disabled_tables)) {

                $table_simple_name = Generator_Util::name($table);
                $model_name = Generator_Util::upperFirst($table_simple_name);

                $writer = new Generator_Filewriter($table_simple_name);

                if (!$writer->fileExists($table_simple_name . ".php", Generator_Filewriter::$MODEL)) {

                    $writer->addRow(Generator_Util::$OPEN_CLASS_FILE);
                    $writer->addRow(Generator_Util::classInfoHead("Model_" . $model_name));
                    $writer->addRow(self::genORM($model_name));

                    $relations = self::getTableRelationShips($table);
                    foreach ($relations as $relation) {
                        $writer->addRow($relation);
                    }

                    $fields = Generator_Util::listTableFields($table);
                    $rules = array();
                    $filters = array();
                    $labels = array();
                    $primary_key = "";
                    foreach ($fields as $array) {
                        $field = Generator_Field::factory($array);

                        if (!$field->isPrimaryKey()) {

                            if (!array_key_exists($field->getName(), $rules)) {
                                $rules[$field->getName()] = self::getRules($field);
                            }

                            if (!array_key_exists($field->getName(), $filters)) {
                                $filters[$field->getName()] = self::getFilters($field);
                            }
                        } else {
                            $primary_key = $field->getName();
                        }

                        if (!array_key_exists($field->getName(), $labels)) {
                            $labels[$field->getName()] = $field->getName();
                        }
                    }
                    $writer->addRow(Generator_Util::methodInfoHead("array"));
                    $writer->addRow(self::rules($rules));
                    $writer->addRow(Generator_Util::methodInfoHead("array"));
                    $writer->addRow(self::filters($filters));
                    $writer->addRow(Generator_Util::methodInfoHead("array"));
                    $writer->addRow(self::labels($labels, $table_simple_name, $config->get("multilang_support")));

                    $writer->addRow(Generator_Util::methodInfoHead("array"));
                    $writer->addRow(self::getFormErrors());

                    $writer->addRow(Generator_Util::methodInfoHead("Validation"));
                    $writer->addRow(self::getCsrf());

                    if (!empty($primary_key)) {
                        $writer->addRow(Generator_Util::methodInfoHead("array"));
                        $writer->addRow(self::getSelect($table_simple_name));
                        $model_names[$table_simple_name] = $primary_key;
                    }

                    $writer->addRow(Generator_Util::$CLOSE_CLASS_FILE);
                    $i18n[$table_simple_name] = $labels;
                }
                $writer->write(Generator_Filewriter::$MODEL);

                $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
                $result->addWriteIsOk($writer->writeIsOk());
            }
        }
        if ($config->get("multilang_support")) {
            if ($result->writeIsOK()) {
                $i18n_langs = $config->get("languages");
                foreach ($i18n_langs as $lang_file) {
                    $lang_writer = new Generator_Filewriter($lang_file);
                    if (!$lang_writer->fileExists($lang_file . ".php", Generator_Filewriter::$I18n)) {
                        $lang_writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);
                        $lang_writer->addRow("<?php");
                        $lang_writer->addRow("return array(");
                        foreach ($i18n as $key => $val) {
                            if (is_array($val)) {
                                $lang_writer->addRow("    //$key");
                                $lang_writer->addRow("    \"$key\" => array(");
                                foreach ($val as $k => $v) {
                                    $lang_writer->addRow("        \"$k\" => \"$v\",");
                                }
                                $lang_writer->addRow("        \"submit\" => \"submit\",");
                                $lang_writer->addRow("    ),\n");
                            } else {
                                $lang_writer->addRow("    \"$key\" => \"$val\",");
                            }
                        }
                        $lang_writer->addRow("    \"item_not_found_exception\" => \"" . $config->get("item_not_found_exception") . "\",");
                        $validation = $config->get("validation");
                        foreach ($validation as $key => $val) {
                            $lang_writer->addRow("    \"$key\" => \"$val\",");
                        }
                        $lang_writer->addRow(");");
                        $lang_writer->addRow("?>");
                    }
                    $lang_writer->write(Generator_Filewriter::$I18n);
                    $result->addItem($lang_writer->getFilename(), $lang_writer->getPath(), $lang_writer->getRows());
                    $result->addWriteIsOk($lang_writer->writeIsOk());
                }
            } else {
                $result->addItem("i18n", "<div class=\"error\">I18n languages support skipped! Delete models first !</div>");
                $result->addWriteIsOk(false);
            }

            $validation = $config->get("validation");
            $validation_writer = new Generator_Filewriter("validation");
            if (!$validation_writer->fileExists("validation.php", Generator_Filewriter::$MESSAGES)) {
                $validation_writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);
                $validation_writer->addRow("<?php");
                $validation_writer->addRow("return array(");
                foreach ($validation as $key => $val) {
                    $validation_writer->addRow("    \"$key\" => \"$key\",");
                }
                $validation_writer->addRow(");");
                $validation_writer->addRow("?>");
                $validation_writer->write(Generator_Filewriter::$MESSAGES);
                $result->addItem($validation_writer->getFilename(), $validation_writer->getPath(), $validation_writer->getRows());
                $result->addWriteIsOk($validation_writer->writeIsOk());
            } else {
                $result->addItem("validation.php", "<div class=\"error\">validation.php is exsists! Please delete first !</div>");
                $result->addWriteIsOk(false);
            }
        } else {

            $validation = $config->get("validation");
            $validation_writer = new Generator_Filewriter("validation");
            if (!$validation_writer->fileExists("validation.php", Generator_Filewriter::$MESSAGES)) {
                $validation_writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);
                $validation_writer->addRow("<?php");
                $validation_writer->addRow("return array(");
                foreach ($validation as $key => $val) {
                    $validation_writer->addRow("    \"$key\" => \"$val\",");
                }
                $validation_writer->addRow(");");
                $validation_writer->addRow("?>");
                $validation_writer->write(Generator_Filewriter::$MESSAGES);
                $result->addItem($validation_writer->getFilename(), $validation_writer->getPath(), $validation_writer->getRows());
                $result->addWriteIsOk($validation_writer->writeIsOk());
            } else {
                $result->addItem("validation", "<div class=\"error\">validation.php is exsists! Please delete first !</div>");
                $result->addWriteIsOk(false);
            }
        }
        $config_writer = new Generator_Filewriter("models");
        if (!$validation_writer->fileExists("models.php", Generator_Filewriter::$CONFIG)) {
            $config_writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);
            $config_writer->addRow("<?php");
            $config_writer->addRow("return array(");
            foreach ($model_names as $name => $primary_key) {
                $config_writer->addRow("    \"$name\" => array(");
                $config_writer->addRow("        \"select_option_key\" => \"$primary_key\",");
                $config_writer->addRow("        \"select_option_value\" => \"$primary_key\",");
                $config_writer->addRow("        \"select_option_pre_option\" => \"" . $config->get("select_pre_option") . "\",");
                $config_writer->addRow("    ),\n");
            }
            $config_writer->addRow(");");
            $config_writer->addRow("?>");
            $config_writer->write(Generator_Filewriter::$CONFIG);
            $result->addItem($config_writer->getFilename(), $config_writer->getPath(), $config_writer->getRows());
            $result->addWriteIsOk($config_writer->writeIsOk());
        } else {
            $result->addItem("models.php", "<div class=\"error\">models.php is exsists! Please delete first !</div>");
            $result->addWriteIsOk(false);
        }

        return $result;
    }

}

?>
