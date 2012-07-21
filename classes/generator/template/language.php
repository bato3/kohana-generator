<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_Language {

    public static function factory($lang) {
        $file = Generator_File::factory()
                ->setDirectory("application" . DIRECTORY_SEPARATOR . "i18n")
                ->setFileName($lang)
                ->addLine("return array(\n")
                ->addLine(Generator_Util_Text::space(4) . "'action.actions' => 'Actions',")
                ->addLine(Generator_Util_Text::space(4) . "'action.edit' => 'Edit',")
                ->addLine(Generator_Util_Text::space(4) . "'action.show' => 'Show',")
                ->addLine(Generator_Util_Text::space(4) . "'action.delete' => 'Delete',")
                ->addLine(Generator_Util_Text::space(4) . "'action.back_to_the_list' => 'Back to the list',")
                ->addLine(Generator_Util_Text::space(4) . "'action.create_new' => 'Create new',\n");

        $tables = Database::instance()->list_tables();

        foreach ($tables as $table) {

            $db_table = Generator_Db_Table::factory($table);
            $fields = $db_table->getTableFields();

            foreach ($fields as $field) {
                $file->addLine(Generator_Util_Text::space(4) . "'" . $db_table->getName() . "." . $field->getName() . "' => '" . $field->getName() . "',");
            }
            $file->addLine("");
        }

        $file->addLine(");");

        return $file;
    }

}

?>
