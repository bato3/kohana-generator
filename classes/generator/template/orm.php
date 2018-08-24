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
        
        $dir='';
        $class_name='';
        $orm = Generator_Db_Orm::factory($db_table); 
        $loc = explode('_',$db_table->get_name());
        $file_name = ucfirst(strtolower(array_pop($loc)));
        
        if(!empty($loc))
        {
        	foreach($loc as $folder)
        	{
        		$dir.=DIRECTORY_SEPARATOR.ucfirst(strtolower($folder));
        		$class_name .= ucfirst(strtolower($folder)).'_';
        	}
        }
        $class_name .= $file_name;
        $file = Generator_File::factory()
                ->set_file_name($file_name)
                ->set_directory("application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "model" . $dir)
                ->add_row("class Model_" . $class_name . " extends Acl_ORM {\n");
        
        if(!Generator_Util_Config::load()->table_names_plural)
        {
            $file->add_row("protected \$_table_name = "."'".UTF8::strtolower($db_table->get_name())."'".";\n", 4);
        }
       
        $file->add_row($orm->get_pk())
        	 ->add_row($orm->get_relation_ships())
             ->add_row($orm->get_rules())
             ->add_row($orm->get_filters())
             ->add_row($orm->get_labels())
             ->add_row($orm->get_columns())
             ->add_row("}");
        
        return $file;
    }
    
}

?>
