<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php

/**
 * Description of field
 *
 * @author burningface
 */
class Generator_Field {

    private $field;

    public function __construct($array) {
        $this->field = $array;
    }

    public static function factory($array) {
        return new Generator_Field($array);
    }

    public function getMin() {
        return isset($this->field["min"]) ? $this->field["min"] : 0;
    }

    public function getMax() {
        if (isset($this->field["max"])) {
            return $this->field["max"];
        } else if (isset($this->field["character_maximum_length"])) {
            return $this->field["character_maximum_length"];
        } else {
            return 0;
        }
    }

    public function getType() {
        return isset($this->field["data_type"]) ? $this->field["data_type"] : "";
    }

    public function getName() {
        return isset($this->field["column_name"]) ? $this->field["column_name"] : "";
    }

    public function getKey() {
        return isset($this->field["key"]) ? $this->field["key"] : "";
    }
    
    public function getReferencedTableName(){
        return $this->field["REFERENCED_TABLE_NAME"];
    }
        
    public function getReferencedModelName(){
        return "Model_".Generator_Util::upperFirst(Generator_Util::name($this->getReferencedTableName()));
    }
    
    public function getReferencedColumnName(){
        return isset($this->field["COLUMN_NAME"]) ? $this->field["COLUMN_NAME"] : "";
    }

    public function __toString() {
        return "name: " . $this->getName() . " type: " . $this->getType() . " key: " . $this->getKey() . " min: " . $this->getMin() . " max: " . $this->getMax() . "";
    }

    public function isPrimaryKey() {
        return $this->getKey() == "PRI" ? true : false;
    }

    public function isForeignKey() {
        return $this->getKey() == "MUL" ? true : false;
    }

}

?>
