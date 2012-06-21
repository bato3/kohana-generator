<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 * Description of writer
 *
 * @author burningface
 */
class Generator_Item_Orm extends Generator_Item_Abstract_Item {
    
    protected function init() {
        $tables = Database::instance()->list_tables();
        $extend_orm = $this->config->extend_orm;
        $extend_item = $extend_orm["generate_base_orm"] ? "Model_".Generator_Util_Text::upperFirst($extend_orm["parent"]) : "ORM";    
        
        if($extend_orm["generate_base_orm"]){
            
            $this->add(
                $item = Generator_File::factory()
                    ->setFileName(strtolower($extend_orm["parent"]))
                    ->setDirectory("application".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."model")
                    ->addLine("class Model_".Generator_Util_Text::upperFirst($extend_orm["parent"])." extends ORM {}")
            );
                
        }
                
        if(isset($_POST["table"]) && !empty($_POST["table"])){
            $table = Generator_Db_Table::factory($_POST["table"]);
            $orm = Generator_File_Orm::factory($table);
            
            $this->add(
                $item = Generator_File::factory()
                    ->setFileName(strtolower($table->getName()))
                    ->setDirectory("application".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."model")
                    ->addLine("class Model_".Generator_Util_Text::upperFirst($table->getName())." extends $extend_item {\n")
                    ->addLine($orm->getRelationShips())
                    ->addLine($orm->getRules())
                    ->addLine($orm->getFilters())
                    ->addLine($orm->getLabels())
                    ->addLine("}")
            );
            
        }
    }
}
?>