<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_Orm {
    
    public static function factory($table){
        
        $db_table = Generator_Db_Table::factory($table);
        $orm = Generator_Db_Orm::factory($db_table); 
        
        $file = Generator_File::factory()
                ->setFileName(strtolower($db_table->getName()))
                ->setDirectory("application" . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "model")
                ->addLine("class Model_" . Generator_Util_Text::upperFirst($db_table->getName()) . " extends ORM {\n");
        if(!Generator_Util_Config::load()->table_names_plural){
            $file->addLine(Generator_Util_Text::space(4)."protected \$_table_name = "."'".UTF8::strtolower($db_table->getName())."'".";\n");
        }
       
        $file->addLine($orm->getRelationShips())
                ->addLine($orm->getRules())
                ->addLine($orm->getFilters())
                ->addLine($orm->getLabels())
                ->addLine("}");
        
        return $file;
    }
    
}

?>
