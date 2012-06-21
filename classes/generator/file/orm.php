<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php

/**
 * Description of writer
 *
 * @author burningface
 */
class Generator_File_Orm {

    private $db_table;

    public function __construct(Generator_Db_Table $db_table) {
        $this->db_table = $db_table;
    }

    public static function factory(Generator_Db_Table $db_table) {
        return new Generator_File_Orm($db_table);
    }

    public function getRelationShips() {
        $has_many = $this->db_table->getHasMany();
        $has_one = $this->db_table->getHasOne();
        $belongs_to = $this->db_table->getBelongsTo();
        $string = "";

        if (!empty($has_many)) {
            $string .= Generator_Util_Text::space(4) . "protected \$_has_many = array(\n";

            foreach ($has_many as $array) {
                $string .= Generator_Util_Text::space(8) . "\"" . $array["name"] . "\"" . "=> array(\"model\" => \"" . $array["name"] . "\", \"foreign_key\" => \"" . $array["foreign_key"] . "\"),\n";
            }

            $string .= Generator_Util_Text::space(4) . ");\n\n";
        }

        if (!empty($has_one)) {
            $string .= Generator_Util_Text::space(4) . "protected \$_has_one = array(\n";

            foreach ($has_one as $array) {
                $string .= Generator_Util_Text::space(8) . "\"" . $array["name"] . "\"" . "=> array(\"model\" => \"" . $array["name"] . "\", \"foreign_key\" => \"" . $array["foreign_key"] . "\"),\n";
            }

            $string .= Generator_Util_Text::space(4) . ");\n\n";
        }

        if (!empty($belongs_to)) {
            $string .= Generator_Util_Text::space(4) . "protected \$_belongs_to = array(\n";

            foreach ($belongs_to as $array) {
                $string .= Generator_Util_Text::space(8) . "\"" . $array["name"] . "\"" . "=> array(\"model\" => \"" . $array["name"] . "\", \"foreign_key\" => \"" . $array["foreign_key"] . "\"),\n";
            }

            $string .= Generator_Util_Text::space(4) . ");\n";
        }

        return $string;
    }

    public function getRules() {
        $fields = $this->db_table->getTableFields();
        $string = Generator_Util_Text::space(4) . "public function rules(){\n";
        $string .= Generator_Util_Text::space(8) . "return array(\n";

        foreach ($fields as $field) {
            if (!$field->isPrimaryKey()) {
                $string .= Generator_Util_Text::space(12) . "\"" . $field->getName() . "\" => array(" . $this->fieldRule($field);
                $string .= Generator_Util_Text::space(12) . "),\n";
            }
        }

        $string .= Generator_Util_Text::space(8) . ");\n";
        $string .= Generator_Util_Text::space(4) . "}\n";

        return $string;
    }

    public function getFilters() {
        $fields = $this->db_table->getTableFields();
        $string = Generator_Util_Text::space(4) . "public function filters(){\n";
        $string .= Generator_Util_Text::space(8) . "return array(\n";

        foreach ($fields as $field) {
            if (!$field->isPrimaryKey()) {
                $string .= Generator_Util_Text::space(12) . "\"" . $field->getName() . "\" => array(" . $this->fieldFilters();
                $string .= Generator_Util_Text::space(12) . "),\n";
            }
        }

        $string .= Generator_Util_Text::space(8) . ");\n";
        $string .= Generator_Util_Text::space(4) . "}\n";

        return $string;
    }

    public function getLabels() {
        $string = Generator_Util_Text::space(4) . "public function labels(){\n";
        $string .= Generator_Util_Text::space(8) . $this->fieldLabels();
        $string .= Generator_Util_Text::space(8) . ");\n";
        $string .= Generator_Util_Text::space(4) . "}\n";

        return $string;
    }

    private function fieldRule(Generator_Db_Field $field) {
        $min = $field->getMin();
        $max = $field->getMax();
        $key = $field->getKey();

        $config = Generator_Util_Config::load();

        $validation = "\n" . Generator_Util_Text::space(16) . "array(\"not_empty\"),\n";

        switch ($field->getType()) {
            case "date" : $validation .= Generator_Util_Text::space(16) . "array(\"date\",array(\":value\", \"" . $config->date_format . "\")),\n";
                break;
            case "year" : $validation .= Generator_Util_Text::space(16) . "array(\"date\",array(\":value\", \"Y\")),\n";
                break;
            case "smallint" : $validation .= Generator_Util_Text::space(16) . "array(\"digit\"),\n";
                break;
            case "smallint unsigned" : $validation .= Generator_Util_Text::space(16) . "array(\"digit\"),\n";
                break;
            case "int" : $validation .= Generator_Util_Text::space(16) . "array(\"digit\"),\n";
                break;
            case "int unsigned" : $validation .= Generator_Util_Text::space(16) . "array(\"digit\"),\n";
                break;
            case "bigint" : $validation .= Generator_Util_Text::space(16) . "array(\"digit\"),\n";
                break;
            case "bigint unsigned" : $validation .= Generator_Util_Text::space(16) . "array(\"digit\"),\n";
                break;
            case "float" : $validation .= Generator_Util_Text::space(16) . "array(\"numeric\"),\n";
                break;
            case "float unsigned" : $validation .= Generator_Util_Text::space(16) . "array(\"numeric\"),\n";
                break;
            case "double" : $validation .= Generator_Util_Text::space(16) . "array(\"numeric\"),\n";
                break;
            case "double unsigned" : $validation .= Generator_Util_Text::space(16) . "array(\"numeric\"),\n";
                break;
            case "decimal" : $validation .= Generator_Util_Text::space(16) . "array(\"numeric\"),\n";
                break;
            case "decimal unsigned" : $validation .= Generator_Util_Text::space(16) . "array(\"numeric\"),\n";
                break;
            case "" : $validation .= "";
                break;
        }

        if (!empty($min) && !empty($max)) {
            $validation .= Generator_Util_Text::space(16) . "array(\"min_length\",array(\":value\", $min)),\n";
            $validation .= Generator_Util_Text::space(16) . "array(\"max_length\",array(\":value\", $max)),\n";
        }

        if (empty($min) && !empty($max)) {
            $validation .= Generator_Util_Text::space(16) . "array(\"max_length\",array(\":value\", $max)),\n";
        }

        if (!empty($key) && $key == "UNI") {
            $validation .= Generator_Util_Text::space(16) . "array(array(\$this, \"unique\"), array(\"" . $field->getName() . "\", \":value\")),\n";
        }

        return $validation;
    }

    private function fieldFilters() {
        $filter = "\n" . $validation = Generator_Util_Text::space(16) . "array(\"UTF8::trim\"),\n";
        return $filter;
    }

    private function fieldLabels() {
        $fields = $this->db_table->listTableFields();
        $config = Generator_Util_Config::load();
        $labels = "";

        if ($config->support_multilang_in_model) {

            $labels .= Generator_Util_Text::space(8) . "return array(\n";

            foreach ($fields as $key => $value) {
                $labels .= Generator_Util_Text::space(12) . "\"$key\" => __(\"" . $this->db_table->getName() . ".$key\"),\n";
            }

            $labels .= Generator_Util_Text::space(12) . "\"submit\" => __(\"" . $this->db_table->getName() . ".submit\"),\n";
        } else {

            $labels .= "return array(\n";

            foreach ($fields as $key => $value) {
                $labels .= Generator_Util_Text::space(12) . "\"$key\" => \"$key\",\n";
            }

            $labels .= Generator_Util_Text::space(12) . "\"submit\" => \"submit\",\n";
        }

        return $labels;
    }

}

?>
