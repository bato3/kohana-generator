<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_Language {

    public static function factory($lang) 
    {
        $file = Generator_File::factory()
                ->set_directory("application" . DIRECTORY_SEPARATOR . "i18n")
                ->set_file_name($lang)
                ->add_row("return array(\n")
                ->add_row(Generator_Util_Text::space(4) . "'action.actions' => 'Actions',")
                ->add_row(Generator_Util_Text::space(4) . "'action.edit' => 'Edit',")
                ->add_row(Generator_Util_Text::space(4) . "'action.show' => 'Show',")
                ->add_row(Generator_Util_Text::space(4) . "'action.delete' => 'Delete',")
                ->add_row(Generator_Util_Text::space(4) . "'action.back_to_the_list' => 'Back to the list',")
                ->add_row(Generator_Util_Text::space(4) . "'action.create_new' => 'Create new',\n");

        $tables = Database::instance()->list_tables();

        foreach ($tables as $table) {

            $db_table = Generator_Db_Table::factory($table);
            $fields = $db_table->get_table_fields();

            foreach ($fields as $field) {
                $file->add_row(Generator_Util_Text::space(4) . "'" . $db_table->get_name() . "." . $field->get_name() . "' => '" . $field->get_name() . "',");
            }
            
            $file->add_row("");
        }
        
        if(Generator_Util_Config::load()->support_multilang)
        {
            $array = Generator_Util_Config::load()->validation;
            
            foreach ($array as $key => $val){
               $file->add_row(Generator_Util_Text::space(4) . "'" . $key . "' => '" . $val . "',");
            }
        }

        $file->add_row("\n);");

        return $file;
    }

}

?>
