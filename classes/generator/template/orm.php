<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_Orm {
    
    public static function factory($table)
    {    
        $db_table = Generator_Db_Table::factory($table);
        $orm = Generator_Db_Orm::factory($db_table); 
        
        $file = Generator_File::factory()
                ->set_file_name(strtolower($db_table->get_name()))
                ->set_directory("application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "model")
                ->add_row("class Model_" . Generator_Util_Text::upper_first($db_table->get_name()) . " extends ORM {\n");
        
        if(!Generator_Util_Config::load()->table_names_plural)
        {
            $file->add_row(Generator_Util_Text::space(4)."protected \$_table_name = "."'".UTF8::strtolower($db_table->get_name())."'".";\n");
        }
       
        $file->add_row($orm->get_relation_ships())
             ->add_row($orm->get_rules())
             ->add_row($orm->get_filters())
             ->add_row($orm->get_labels())
             ->add_row("}");
        
        return $file;
    }
    
}

?>
