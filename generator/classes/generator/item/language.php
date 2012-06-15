<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 * Description of writer
 *
 * @author burningface
 */
class Generator_Item_Language extends Generator_Item_Abstract_Item {
    
    protected function init() {
        if(isset($_POST["lang"]) && !empty($_POST["lang"])){
            foreach($_POST["lang"] as $lang){
                $file = Generator_File::factory()
                        ->setDirectory("application".DIRECTORY_SEPARATOR."i18n")
                        ->setFileName($lang)
                        ->addLine("return array(");
                
                $tables = Database::instance()->list_tables();
                foreach($tables as $table){
                    $fields = Generator_Db_Table::factory($table)->getTableFields();
                    $file->addLine(Generator_Util_Text::space(4)."\"".$table."\" => array(");
                    foreach ($fields as $field){
                        $file->addLine(Generator_Util_Text::space(8)."\"".$field->getName()."\" => \"".$field->getName()."\",");
                    }
                    $file->addLine(Generator_Util_Text::space(4)."),");                   
                }
                $file->addLine(");");
                $this->add($file);
            }
        }else{
            $this->addErrors(Generator_Util_Lang::get("empty_language_name", false));
        }
    }
}
?>