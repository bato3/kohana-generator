<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_Show {
    
    public static function factory($table)
    {
        $db_table = Generator_Db_Table::factory($table);
        $fields = $db_table->getTableFields();
            
        $file = Generator_File::factory()
                    ->setDirectory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "shows")
                    ->setFileName($db_table->getName())
                    ->addLine("?>")
                    ->addLine("\n<h1>" . ucfirst($db_table->getName()) . "</h1>\n");

            

            if (Generator_Util_Config::load()->support_multilang) 
            {

                foreach ($fields as $field) {
                    $file->addLine("<p><strong><?php echo __('" . $db_table->getName() . "." . $field->getName() . "') ?>:</strong> <?php echo html::chars(\$model->" . $field->getName() . ") ?></p>");
                }
                
                $file->addLine("\n<ul>")
                        ->addLine(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->getName()."', __('action.back_to_the_list')) ?></li>")
                        ->addLine(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->getName()."/edit/'.\$model->".$db_table->getPrimaryKeyName().", __('action.edit')) ?></li>")
                        ->addLine(Generator_Util_Text::space(4)."<li>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::open('/".$db_table->getName()."/delete') ?>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::hidden('id', \$model->".$db_table->getPrimaryKeyName().") ?>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::submit('submit', __('action.delete')) ?>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::close() ?>")
                        ->addLine(Generator_Util_Text::space(4)."</li>")
                        ->addLine("</ul>");
                
            } 
            else 
            {

                foreach ($fields as $field) {
                    $file->addLine("<p><strong>" . ucfirst($field->getName()) . ":</strong> <?php echo html::chars(\$model->" . $field->getName() . ") ?></p>");
                }
                
                $file->addLine("\n<ul>")
                        ->addLine(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->getName()."', 'Back to the list') ?></li>")
                        ->addLine(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->getName()."/edit/'.\$model->".$db_table->getPrimaryKeyName().",'Edit') ?></li>")
                        ->addLine(Generator_Util_Text::space(4)."<li>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::open('/".$db_table->getName()."/delete') ?>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::hidden('id', \$model->".$db_table->getPrimaryKeyName().") ?>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::submit('submit', 'Delete') ?>")
                        ->addLine(Generator_Util_Text::space(8)."<?php echo form::close() ?>")
                        ->addLine(Generator_Util_Text::space(4)."</li>")
                        ->addLine("</ul>");
                
            }
            
            $file->setDisableCloseTag(true);
            
            return $file;
    }
    
}

?>
