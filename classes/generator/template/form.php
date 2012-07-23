<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_Form {
    
    public static function factory($table)
    {
        $db_table = Generator_Db_Table::factory($table);
        $fields = $db_table->get_table_fields();
            
        $file = Generator_File::factory()
                    ->set_directory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "forms")
                    ->set_file_name($db_table->get_name())
                    ->add_row("?>")
                    ->add_row("\n<h1>" . ucfirst($db_table->get_name()) . "</h1>\n")
                    ->add_row("<?php if(!isset(\$errors)){ \$errors = array(); } ?>")
                    ->add_row("<?php if(!isset(\$values)){ \$values = array(); } ?>\n")
                    ->add_row("<?php echo form::open(\$action) ?>");

            

            foreach ($fields as $field) {
                
                $label = "'".$field->get_name().":'";
                
                if(Generator_Util_Config::load()->support_multilang)
                {
                    $label = "__('" . $db_table->get_name() . "." . $field->get_name() . "') . ':'";
                }
                                
                if(!$field->is_primary_key() && !$field->is_foreign_key())
                {
                    $file->add_row("<div>")
                         ->add_row(Generator_Util_Text::space(4) . "<?php echo form::label('" . $field->get_name() . "', " . $label . ") ?>")
                         ->add_row(Generator_Util_Text::space(4) . "<?php echo form::input('" . $field->get_name() . "', Arr::get(\$values, '" . $field->get_name() . "'), array('id' => '" . $field->get_name() . "')) ?>")
                         ->add_row(Generator_Util_Text::space(4) . "<?php echo Arr::get(\$errors, '" . $field->get_name() . "') ?>")
                         ->add_row("</div>");
                }
                
                if($field->is_foreign_key())
                {   
                    $file->add_row("<div>")
                         ->add_row(Generator_Util_Text::space(4) . "<?php echo form::label('" . $field->get_name() . "', " . $label . ") ?>")
                         ->add_row(Generator_Util_Text::space(4) . "<?php echo form::select('" . $field->get_name() . "', \$" . $field->get_name() . ", Arr::get(\$values, '" . $field->get_name() . "'), array('id' => '" . $field->get_name() . "')) ?>")
                         ->add_row(Generator_Util_Text::space(4) . "<?php echo Arr::get(\$errors, '" . $field->get_name() . "') ?>")
                         ->add_row("</div>");   
                }
                
            }

            $file->add_row("<div>")
                 ->add_row(Generator_Util_Text::space(4) . "<?php echo form::submit('submit', 'Submit') ?>")
                 ->add_row("</div>")
                 ->add_row("<?php echo form::close() ?>")->set_disable_close_tag(true);
            
            if (Generator_Util_Config::load()->support_multilang) 
            {
                $file->add_row("\n<p><?php echo html::anchor('/" . $db_table->get_name() . "', __('action.back_to_the_list')) ?></p>");   
            }
            else 
            {
                $file->add_row("\n<p><?php echo html::anchor('/" . $db_table->get_name() . "', 'Back to the list') ?></p>");   
            }
            
            return $file;
    }
    
}

?>
