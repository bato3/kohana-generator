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
        $fields = $db_table->get_table_fields();
            
        $file = Generator_File::factory()
                ->set_directory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "shows")
                ->set_file_name($db_table->get_name())
                ->add_row("?>")
                ->add_row("\n<h1>" . ucfirst($db_table->get_name()) . "</h1>\n");

            if (Generator_Util_Config::load()->support_multilang) 
            {
                foreach ($fields as $field) {
                    $file->add_row("<p><strong><?php echo __('" . $db_table->get_name() . "." . $field->get_name() . "') ?>:</strong> <?php echo html::chars(\$model->" . $field->get_name() . ") ?></p>");
                }
                
                $file->add_row("\n<ul>")
                     ->add_row(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->get_name()."', __('action.back_to_the_list')) ?></li>")
                     ->add_row(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->get_name()."/edit/'.\$model->".$db_table->get_primary_key_name().", __('action.edit')) ?></li>")
                     ->add_row(Generator_Util_Text::space(4)."<li>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::open('/".$db_table->get_name()."/delete') ?>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::hidden('id', \$model->".$db_table->get_primary_key_name().") ?>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::submit('submit', __('action.delete')) ?>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::close() ?>")
                     ->add_row(Generator_Util_Text::space(4)."</li>")
                     ->add_row("</ul>");   
            } 
            else 
            {
                foreach ($fields as $field) {
                    $file->add_row("<p><strong>" . ucfirst($field->get_name()) . ":</strong> <?php echo html::chars(\$model->" . $field->get_name() . ") ?></p>");
                }
                
                $file->add_row("\n<ul>")
                     ->add_row(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->get_name()."', 'Back to the list') ?></li>")
                     ->add_row(Generator_Util_Text::space(4)."<li><?php echo html::anchor('/".$db_table->get_name()."/edit/'.\$model->".$db_table->get_primary_key_name().",'Edit') ?></li>")
                     ->add_row(Generator_Util_Text::space(4)."<li>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::open('/".$db_table->get_name()."/delete') ?>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::hidden('id', \$model->".$db_table->get_primary_key_name().") ?>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::submit('submit', 'Delete') ?>")
                     ->add_row(Generator_Util_Text::space(8)."<?php echo form::close() ?>")
                     ->add_row(Generator_Util_Text::space(4)."</li>")
                     ->add_row("</ul>");
            }
            
            $file->set_disable_close_tag(true);
            
            return $file;
    }
    
}

?>
