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

    private static $generated_files;
    private static $is_ok = array();

    public static function getIsOkArray() {
        return self::$is_ok;
    }

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

    private static function getSelect($primary_key) {
        $config = Generator_Util::loadConfig();
        return "    public function selectOptions(\$value_field=\"$primary_key\", \$key_field=\"$primary_key\", \$preoption=\"" . $config->get("select_pre_option") . "\") {
        if(empty(\$key_field)){ \$key_field = \"$primary_key\"; }
            
        if (!empty(\$preoption)) {
            \$array = array();
            \$array[\" \"] = \$preoption;  
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

    private static function labels($array) {
        $html = "    public function labels(){\n        return array(\n";
        foreach ($array as $key => $value) {
            $html .= "            \"$key\" => \"$value\",\n";
        }
        $html .= "            \"submit\" => \"submit\",\n";
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
        $tables = Generator_Util::listTables();
        $config = Generator_Util::loadConfig();
        $disabled_tables = $config->get("disabled_tables");
        $i18n = array("back" => "back", "create" => "new", "edit" => "edit", "delete" => "delete");
        $i18n_langs = array("hu", "de", "en", "it");
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
                    $writer->addRow(self::labels($labels));

                    $writer->addRow(Generator_Util::methodInfoHead("array"));
                    $writer->addRow(self::getFormErrors());

                    $writer->addRow(Generator_Util::methodInfoHead("Validation"));
                    $writer->addRow(self::getCsrf());

                    if (!empty($primary_key)) {
                        $writer->addRow(Generator_Util::methodInfoHead("array"));
                        $writer->addRow(self::getSelect($primary_key));
                    }

                    $writer->addRow(Generator_Util::$CLOSE_CLASS_FILE);
                    $i18n[$table_simple_name] = $labels;
                }
                $writer->write(Generator_Filewriter::$MODEL);

                self::$is_ok[] = $writer->writeIsOk();
                self::$generated_files .= $writer->getPath() . "<br />";
            }
        }
        if ($config->get("multilang_support")) {
            $ok = in_array(false, self::$is_ok) ? false : true;
            if ($ok) {
                foreach ($i18n_langs as $lang_file) {
                    $lang_writer = new Generator_Filewriter($lang_file);
                    if (!$lang_writer->fileExists($lang_file . ".php", Generator_Filewriter::$I18n)) {
                        $lang_writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);
                        $lang_writer->addRow("<?php");
                        $lang_writer->addRow("return array(");
                        foreach ($i18n as $key => $val) {
                            if (is_array($val)) {
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
                        $lang_writer->addRow(");");
                        $lang_writer->addRow("?>");
                    }
                    $lang_writer->write(Generator_Filewriter::$I18n);
                    self::$generated_files .= $lang_writer->getPath() . "<br />";
                }
            } else {
                self::$generated_files .= "<div class=\"error\">I18n languages support skipped! Delete models first !</div><br />";
            }
        }
        return self::$generated_files;
    }

}

?>
