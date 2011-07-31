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

    private static $date_format = "Y-m-d";
    private static $disabled_tables = array("roles", "roles_users", "user_tokens");
    private static $generated_files;
    private static $is_ok = array();

    public static function getIsOkArray() {
        return self::$is_ok;
    }

    public static function setDateFormat($date_format) {
        if (!empty($date_format)) {
            self::$date_format = $date_format;
        }
    }

    private static function getCsrf() {
        $config = Generator_Util::loadConfig();
        return "\tpublic function csrf(\$values){\n\t\treturn Validation::factory(array(\"csrf\" => \$values[\"" . $config->get("csrf_token_name") . "\"]))->rule(\"csrf\", \"Security::check\");\n\t}\n";
    }

    private static function getFormErrors() {
        return "\tpublic function formErrors(){\n\t\treturn \$this->validation()->errors(\"form_errors\"); \n\t}\n";
    }

    private static function getRules($type, $min, $max) {
        $validation = "\n\t\t\t\tarray(\"not_empty\"),\n";
        switch ($type) {
            case "date" : $validation .= "\t\t\t\tarray(\"date\",array(\":value\", \"" . self::$date_format . "\")),\n";
                break;
            case "year" : $validation .= "\t\t\t\tarray(\"date\",array(\":value\", \"Y\")),\n";
                break;
            case "smallint" : $validation .= "\t\t\t\tarray(\"digit\"),\n";
                break;
            case "smallint unsigned" : $validation .= "\t\t\t\tarray(\"digit\"),\n";
                break;
            case "int" : $validation .= "\t\t\t\tarray(\"digit\"),\n";
                break;
            case "int unsigned" : $validation .= "\t\t\t\tarray(\"digit\"),\n";
                break;
            case "bigint" : $validation .= "\t\t\t\tarray(\"digit\"),\n";
                break;
            case "bigint unsigned" : $validation .= "\t\t\t\tarray(\"digit\"),\n";
                break;
            case "float" : $validation .= "\t\t\t\tarray(\"numeric\"),\n";
                break;
            case "float unsigned" : $validation .= "\t\t\t\tarray(\"numeric\"),\n";
                break;
            case "double" : $validation .= "\t\t\t\tarray(\"numeric\"),\n";
                break;
            case "double unsigned" : $validation .= "\t\t\t\tarray(\"numeric\"),\n";
                break;
            case "decimal" : $validation .= "\t\t\t\tarray(\"numeric\"),\n";
                break;
            case "decimal unsigned" : $validation .= "\t\t\t\tarray(\"numeric\"),\n";
                break;
            case "" : $validation .= "";
                break;
        }
        if (!empty($min) && !empty($max)) {
            $validation .= "\t\t\t\tarray(\"min_length\",array(\":value\", $min)),\n\t\t\t\tarray(\"max_length\",array(\":value\", $max)),\n";
        }
        if (empty($min) && !empty($max)) {
            $validation .= "\t\t\t\tarray(\"max_length\",array(\":value\", $max)),\n";
        }
        return $validation;
    }

    private static function getFilters($type) {
        $validation = "\n\t\t\t\tarray(\"trim\"),\n";
        switch ($type) {
            case "varchar" : $validation .= "\t\t\t\tarray(\"strtolower\"),\n\t\t\t\tarray(\"ucwords\"),\n";
                break;
        }
        return $validation . "";
    }

    private static function genORM($filename) {
        return "class Model_" . $filename . " extends ORM {\n";
    }

    private static function getTableRelationShips($table) {
        $has_many = array("\tprotected \$_has_many = array(");
        $belongs_to = array("\tprotected \$_belongs_to = array(");

        $db = Database::instance();
        $query = $db->query(Database::SELECT, 'SELECT * FROM information_schema.key_column_usage WHERE (TABLE_NAME=\''
                        . $table . '\' OR REFERENCED_TABLE_NAME=\'' . $table . '\') AND referenced_column_name IS NOT NULL');

        foreach ($query as $row) {
            $foreign_key = $row['COLUMN_NAME'];
            if ($row['REFERENCED_TABLE_NAME'] === $table) {
                $name = Generator_Util::name($row['TABLE_NAME']);
                $has_many[] = "\t\t\"$name\" => array(\"model\" => \"$name\", \"foreign_key\" => \"$foreign_key\"),";
            } else {
                $name = Generator_Util::name($row['REFERENCED_TABLE_NAME']);
                $belongs_to[] = "\t\t\"$name\" => array(\"model\" => \"$name\", \"foreign_key\" => \"$foreign_key\"),";
            }
        }

        $has_many[] = "\t);\n";
        $belongs_to[] = "\t);\n";
        return array_merge($belongs_to, $has_many);
    }

    private static function labels($array) {
        $html = "\tpublic function labels(){\n\t\treturn array(\n";
        foreach ($array as $key => $value) {
            $html .= "\t\t\t\"$key\" => \"$value\",\n";
        }
        $html .= "\t\t\t\"submit\" => \"submit\",\n";
        return $html . "\n\t\t);\n\t}\n";
    }

    private static function rules($array) {
        $html = "\tpublic function rules(){\n\t\treturn array(\n";
        foreach ($array as $key => $value) {
            $html .= "\t\t\t\"$key\" => array(" . $value . "\t\t\t),\n";
        }
        return $html . "\n\t\t);\n\t}\n";
    }

    private static function filters($array) {
        $html = "\tpublic function filters(){\n\t\treturn array(\n";
        foreach ($array as $key => $value) {
            $html .= "\t\t\t\"$key\" => array(" . $value . "\t\t\t),\n";
        }
        return $html . "\n\t\t);\n\t}\n";
    }

    public static function generate() {
        $tables = Database::instance()->list_tables();
        foreach ($tables as $key => $table) {
            if (!in_array($table, self::$disabled_tables)) {

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
                    foreach ($fields as $array) {
                        $field = Generator_Field::factory($array);

                        if (!$field->isPrimaryKey()) {
                            if (!array_key_exists($field->getName(), $rules)) {
                                $rules[$field->getName()] = self::getRules($field->getType(), $field->getMin(), $field->getMax());
                            }

                            if (!array_key_exists($field->getName(), $filters)) {
                                $filters[$field->getName()] = self::getFilters($field->getType());
                            }
                            if (!array_key_exists($field->getName(), $labels)) {
                                $labels[$field->getName()] = $field->getName();
                            }
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

                    $writer->addRow(Generator_Util::$CLOSE_CLASS_FILE);
                }
                $writer->write(Generator_Filewriter::$MODEL);
                self::$is_ok[] = $writer->writeIsOk();
                self::$generated_files .= $writer->getPath() . "<br />";
            }
        }
        return self::$generated_files;
    }

}

?>
