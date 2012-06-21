<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php

/**
 * Description of writer
 *
 * @author burningface
 */
class Generator_Item_Form extends Generator_Item_Abstract_Item {

    protected function init() {

        if (isset($_POST["table"]) && !empty($_POST["table"])) {

            $file = Generator_File::factory()
                    ->setDirectory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "forms")
                    ->setFileName($_POST["table"])
                    ->addLine("?>")
                    ->addLine("<?php echo form::open() ?>");

            $fields = Generator_Db_Table::factory($_POST["table"])->getTableFields();

            foreach ($fields as $field) {
                $file->addLine("<div>")
                        ->addLine(Generator_Util_Text::space(4) . "<?php echo form::label(\"" . $field->getName() . "\", \"" . $field->getName() . "\") ?>")
                        ->addLine(Generator_Util_Text::space(4) . "<?php echo form::input(\"" . $field->getName() . "\") ?>")
                        ->addLine("</div>");
            }

            $file->addLine("<div>")
                    ->addLine(Generator_Util_Text::space(4) . "<?php echo form::submit(\"submit\") ?>")
                    ->addLine("</div>")
                    ->addLine("<?php echo form::close() ?>")->setDisableCloseTag(true);

            $this->add($file);
        } else {

            $this->addErrors(Generator_Util_Lang::get("empty_table_name", false));
        }
    }

}

?>