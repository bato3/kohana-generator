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
                ->add_row("<fieldset>")
                ->add_row("\n<legend>" . ucfirst($db_table->get_name()) . "</legend>\n");

            if (Generator_Util_Config::load()->support_multilang) 
            {
                foreach ($fields as $field) {
                    $file->add_row("<div><strong><?php echo __('" . $db_table->get_name() . "." . $field->get_name() . "') ?>:</strong> <?php echo html::chars(\$model->" . $field->get_name() . ") ?></div>");
                }
                
                $file->add_row("\n<ul>")
                     ->add_row("<li><?php echo html::anchor('/".$db_table->get_name()."', __('action.back_to_the_list')) ?></li>", 4)
                     ->add_row("<li><?php echo html::anchor('/".$db_table->get_name()."/edit/'.\$model->".$db_table->get_primary_key_name().", __('action.edit')) ?></li>", 4)
                     ->add_row("<li>", 4)
                     ->add_row("<?php echo form::open('/".$db_table->get_name()."/delete') ?>", 8)
                     ->add_row("<?php echo form::hidden('id', \$model->".$db_table->get_primary_key_name().") ?>", 8)
                     ->add_row("<?php echo form::submit('submit', __('action.delete'), array('class' => 'btn btn-small')) ?>", 8)
                     ->add_row("<?php echo form::close() ?>", 8)
                     ->add_row("</li>", 4)
                     ->add_row("</ul>");   
            } 
            else 
            {
                foreach ($fields as $field) {
                    $file->add_row("<p><strong>" . ucfirst($field->get_name()) . ":</strong> <?php echo html::chars(\$model->" . $field->get_name() . ") ?></p>");
                }
                
                $file->add_row("\n<ul>")
                     ->add_row("<li><?php echo html::anchor('/".$db_table->get_name()."', 'Back to the list') ?></li>", 4)
                     ->add_row("<li><?php echo html::anchor('/".$db_table->get_name()."/edit/'.\$model->".$db_table->get_primary_key_name().",'Edit') ?></li>", 4)
                     ->add_row("<li>", 4)
                     ->add_row("<?php echo form::open('/".$db_table->get_name()."/delete') ?>", 8)
                     ->add_row("<?php echo form::hidden('id', \$model->".$db_table->get_primary_key_name().") ?>", 8)
                     ->add_row("<?php echo form::submit('submit', 'Delete', array('class' => 'btn btn-small')) ?>", 8)
                     ->add_row("<?php echo form::close() ?>", 8)
                     ->add_row("</li>", 4)
                     ->add_row("</ul>")
                     ->add_row("</fieldset>");
            }
            
            $file->set_disable_close_tag(true);
            
            return $file;
    }
    
}

?>
