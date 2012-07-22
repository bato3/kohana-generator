<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Db_Orm {

    private $db_table;

    public function __construct(Generator_Db_Table $db_table) 
    {
        $this->db_table = $db_table;
    }

    public static function factory(Generator_Db_Table $db_table) 
    {
        return new Generator_Db_Orm($db_table);
    }

    public function get_relation_ships() 
    {
        $has_many = $this->db_table->get_has_many();
        $has_one = $this->db_table->get_has_one();
        $belongs_to = $this->db_table->get_belongs_to();
        $string = "";

        if (!empty($has_many)) 
        {
            $string .= Generator_Util_Text::space(4) . "protected \$_has_many = array(\n";

            foreach ($has_many as $array) {
                $string .= Generator_Util_Text::space(8) . "'" . $array["name"] . "'" . "=> array('model' => '" . $array["name"] . "', 'foreign_key' => '" . $array["foreign_key"] . "'),\n";
            }

            $string .= Generator_Util_Text::space(4) . ");\n";
        }

        if (!empty($has_one)) 
        {
            $string .= Generator_Util_Text::space(4) . "protected \$_has_one = array(\n";

            foreach ($has_one as $array) {
                $string .= Generator_Util_Text::space(8) . "'" . $array["name"] . "'" . "=> array('model' => '" . $array["name"] . "', 'foreign_key' => '" . $array["foreign_key"] . "'),\n";
            }

            $string .= Generator_Util_Text::space(4) . ");\n";
        }

        if (!empty($belongs_to))
        {
            $string .= Generator_Util_Text::space(4) . "protected \$_belongs_to = array(\n";

            foreach ($belongs_to as $array) {
                $string .= Generator_Util_Text::space(8) . "'" . $array["name"] . "'" . "=> array('model' => '" . $array["name"] . "', 'foreign_key' => '" . $array["foreign_key"] . "'),\n";
            }

            $string .= Generator_Util_Text::space(4) . ");\n";
        }

        return $string;
    }

    public function get_rules() 
    {
        $fields = $this->db_table->get_table_fields();
        $string = Generator_Util_Text::space(4) . "public function rules()\n";
        $string .= Generator_Util_Text::space(4) . "{\n";
        $string .= Generator_Util_Text::space(8) . "return array(\n";

        foreach ($fields as $field) {
            if (!$field->is_primary_key()) 
            {
                $string .= Generator_Util_Text::space(12) . "'" . $field->get_name() . "' => array(" . $this->field_rule($field);
                $string .= Generator_Util_Text::space(12) . "),\n";
            }
        }

        $string .= Generator_Util_Text::space(8) . ");\n";
        $string .= Generator_Util_Text::space(4) . "}\n";

        return $string;
    }

    public function get_filters() 
    {
        $fields = $this->db_table->get_table_fields();
        $string = Generator_Util_Text::space(4) . "public function filters()\n";
        $string .= Generator_Util_Text::space(4) . "{\n";
        $string .= Generator_Util_Text::space(8) . "return array(\n";
                
        foreach ($fields as $field) {
            if (!$field->is_primary_key() && !$field->is_foreign_key() && in_array($field->get_type(), array("varchar", "text"))) 
            {
                $string .= Generator_Util_Text::space(12) . "'" . $field->get_name() . "' => array(\n" . Generator_Util_Text::space(16) . "array('UTF8::trim'),\n";
                $string .= Generator_Util_Text::space(12) . "),\n";
            }
        }

        $string .= Generator_Util_Text::space(8) . ");\n";
        $string .= Generator_Util_Text::space(4) . "}\n";

        return $string;
    }

    public function get_labels() 
    {
        $string = Generator_Util_Text::space(4) . "public function labels()\n";
        $string .= Generator_Util_Text::space(4) . "{\n";
        $string .= $this->field_labels();
        $string .= Generator_Util_Text::space(8) . ");\n";
        $string .= Generator_Util_Text::space(4) . "}\n";

        return $string;
    }

    private function field_rule(Generator_Db_Field $field) 
    {
        $min = $field->get_min();
        $max = $field->get_max();
        $key = $field->get_key();

        $config = Generator_Util_Config::load();

        $validation = "\n" . Generator_Util_Text::space(16) . "array('not_empty'),\n";

        switch ($field->get_type()) {
            case "datetime": $validation .= Generator_Util_Text::space(16) . "array('date',array(':value', '" . $config->datetime_format . "')),\n";
                break;
            case "date" : $validation .= Generator_Util_Text::space(16) . "array('date',array(':value', '" . $config->date_format . "')),\n";
                break;
            case "year" : $validation .= Generator_Util_Text::space(16) . "array('date',array(':value', 'Y')),\n";
                break;
            case "smallint" : $validation .= Generator_Util_Text::space(16) . "array('digit'),\n";
                break;
            case "smallint unsigned" : $validation .= Generator_Util_Text::space(16) . "array('digit'),\n";
                break;
            case "int" : $validation .= Generator_Util_Text::space(16) . "array('digit'),\n";
                break;
            case "int unsigned" : $validation .= Generator_Util_Text::space(16) . "array('digit'),\n";
                break;
            case "bigint" : $validation .= Generator_Util_Text::space(16) . "array('digit'),\n";
                break;
            case "bigint unsigned" : $validation .= Generator_Util_Text::space(16) . "array('digit'),\n";
                break;
            case "float" : $validation .= Generator_Util_Text::space(16) . "array('numeric'),\n";
                break;
            case "float unsigned" : $validation .= Generator_Util_Text::space(16) . "array('numeric'),\n";
                break;
            case "double" : $validation .= Generator_Util_Text::space(16) . "array('numeric'),\n";
                break;
            case "double unsigned" : $validation .= Generator_Util_Text::space(16) . "array('numeric'),\n";
                break;
            case "decimal" : $validation .= Generator_Util_Text::space(16) . "array('numeric'),\n";
                break;
            case "decimal unsigned" : $validation .= Generator_Util_Text::space(16) . "array('numeric'),\n";
                break;
            case "" : $validation .= "";
                break;
        }

        if (!empty($min) && !empty($max)) 
        {
            $validation .= Generator_Util_Text::space(16) . "array('min_length',array(':value', $min)),\n";
            $validation .= Generator_Util_Text::space(16) . "array('max_length',array(':value', $max)),\n";
        }

        if (empty($min) && !empty($max)) 
        {
            $validation .= Generator_Util_Text::space(16) . "array('max_length',array(':value', $max)),\n";
        }

        if (!empty($key) && $key == "UNI") 
        {
            $validation .= Generator_Util_Text::space(16) . "array(array(\$this, 'unique'), array('" . $field->get_name() . "', ':value')),\n";
        }

        return $validation;
    }

    private function field_labels() 
    {
        $fields = $this->db_table->list_table_fields();
        $config = Generator_Util_Config::load();
        $labels = "";

        if ($config->support_multilang) 
        {

            $labels .= Generator_Util_Text::space(8) . "return array(\n";

            foreach ($fields as $key => $value) {
                $labels .= Generator_Util_Text::space(12) . "'$key' => __('" . $this->db_table->get_name() . ".$key'),\n";
            }

            $labels .= Generator_Util_Text::space(12) . "'submit' => __('" . $this->db_table->get_name() . ".submit'),\n";
            
        } 
        else 
        {

            $labels .= "return array(\n";

            foreach ($fields as $key => $value) {
                $labels .= Generator_Util_Text::space(12) . "'$key' => '$key',\n";
            }

            $labels .= Generator_Util_Text::space(12) . "'submit' => 'submit',\n";
        }

        return $labels;
    }

}

?>
