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
                ->add_row("'action.actions' => 'Actions',", 4)
                ->add_row("'action.edit' => 'Edit',", 4)
                ->add_row("'action.show' => 'Show',", 4)
                ->add_row("'action.delete' => 'Delete',", 4)
                ->add_row("'action.back_to_the_list' => 'Back to the list',", 4)
                ->add_row("'action.create_new' => 'Create new',\n", 4);

        $tables = Database::instance()->list_tables();

        foreach ($tables as $table) {

            $db_table = Generator_Db_Table::factory($table);
            $fields = $db_table->get_table_fields();

            foreach ($fields as $field) {
                $file->add_row("'" . $db_table->get_name() . "." . $field->get_name() . "' => '" . $field->get_name() . "',", 4);
            }
            
            $file->add_row("");
        }
        
        if(Generator_Util_Config::load()->support_multilang)
        {
            $array = Generator_Util_Config::load()->validation;
            
            foreach ($array as $key => $val){
               $file->add_row("'" . $key . "' => '" . $val . "',", 4);
            }
        }

        $file->add_row("\n);");

        return $file;
    }

}

?>
