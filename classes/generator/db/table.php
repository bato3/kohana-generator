<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Db_Table {

    private $config;
    private $table;
    private $has_many = array();
    private $has_one = array();
    private $belongs_to = array();
    private $referenced_table = array();

    public function __construct($table) 
    {
        $this->config = Generator_Util_Config::load();
        $this->table = $table;
        $this->table_relation_ships();
    }

    public static function factory($table) 
    {
        return new Generator_Db_Table($table);
    }

    private function name($table, $db_name = true) 
    {
        if ($db_name) 
        {
            if ($this->config->table_names_plural) 
            {
                return strtolower(Inflector::singular($table));
            } 
            else 
            {
                return strtolower($table);
            }
        }
        else 
        {
            return strtolower($table);
        }
    }

    private function table_relation_ships() 
    {
        $query = Database::instance()->query(Database::SELECT, 'SELECT * FROM information_schema.key_column_usage WHERE (TABLE_NAME=\''
                . $this->table . '\' OR REFERENCED_TABLE_NAME=\'' . $this->table . '\') AND referenced_column_name IS NOT NULL');
        
        $tables = $this->list_table_inflector();
        
        foreach ($query as $row) {
            
            $foreign_key = $row['COLUMN_NAME'];
            
            if ($row['REFERENCED_TABLE_NAME'] === $this->table) 
            {
                $name = $this->name($row['TABLE_NAME']);
                
                if (in_array($name, $tables)) 
                {
                    $this->has_many[] = array("name" => $name, "foreign_key" => $foreign_key);
                    $this->referenced_table[$foreign_key] = $name;
                }
                
            } 
            else 
            {
                $name = $this->name($row['REFERENCED_TABLE_NAME']);
                
                if (in_array($name, $tables)) 
                {
                    $this->belongs_to[] = array("name" => $name, "foreign_key" => $foreign_key);
                    $this->has_one[] = array("name" => $name, "foreign_key" => $foreign_key);
                    $this->referenced_table[$foreign_key] = $name;
                }
                
            }
            
        }
    }

    public function list_tables() 
    {
        return Database::instance()->list_tables();
    }

    private function list_table_inflector() 
    {
        $tables = $this->list_tables();
        $array = array();
        
        foreach ($tables as $table) {
            $array[] = $this->name($table);
        }
        
        return $array;
    }

    public function list_table_fields() 
    {
        return Database::instance()->list_columns($this->table);
    }

    public function get_has_many() 
    {
        return $this->has_many;
    }

    public function get_has_one() 
    {
        return $this->has_one;
    }

    public function get_belongs_to() 
    {
        return $this->belongs_to;
    }

    public function get_table_fields()
    {
        $list = array();
        $fields = $this->list_table_fields();
        
        foreach ($fields as $array) {
            $list[] = Generator_Db_Field::factory($array);
        }
        
        return $list;
    }
    
    public function get_primary_key_name()
    {
        $fields = $this->get_table_fields();
        
        foreach ($fields as $field){
            if ($field->is_primary_key()){
                return $field->get_name();
            }
        }
        
        return null;
    }

    public function get_name()
    {
        return $this->name($this->table);
    }

    public function get_referenced_table_name($key)
    {
        if(array_key_exists($key, $this->referenced_table))
        {
            return $this->referenced_table[$key];
        }
        
        return null;
    }

}

?>
