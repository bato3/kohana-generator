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
        
        
        $fields_array = array();
        
        foreach ($fields as $field) {
            if(!$field->is_primary_key()){
                $fields_array[] = $field->get_name();
            }
        }
        
        $fields_array_count = count($fields_array);
        $fields_string = "array(";
        $i = 0;
        
        foreach ($fields_array as $field){
            if($fields_array_count - 1 == $i){
                $fields_string .= "'$field'"." => 'input'";
            }else{
                $fields_string .= "'$field'"." => 'input',";
            }
            $i++;
        }
        
        $fields_string .= ");";
        
            
        $file = Generator_File::factory()
                    ->set_directory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "forms")
                    ->set_file_name($db_table->get_name())
                    ->add_row("?>")
                    ->add_row("<fieldset>")
                    ->add_row("\n<legend>" . ucfirst($db_table->get_name()) . "</legend>\n")
                    ->add_row("<?php \$classes = $fields_string ?>\n")
                    ->add_row("<?php if(isset(\$errors)): ?>")
                    ->add_row("<ul>")
                    ->add_row("<?php foreach (\$errors as \$error): ?>", 4)
                    ->add_row("<li class=\"error\"><?php echo \$error ?></li>", 8)
                    ->add_row("<?php endforeach; ?>", 4)
                    ->add_row("</ul>")
                    ->add_row("<?php")
                    ->add_row("foreach (\$classes as \$key => \$value):", 4)
                    ->add_row("\$error = Arr::get(\$errors, \$key);", 8)
                    ->add_row("\$classes[\$key] = !empty(\$error) ? 'input-error' : 'input-success';", 8)
                    ->add_row("endforeach;", 4)
                    ->add_row("?>")
                    ->add_row("<?php endif; ?>\n\n")
                    ->add_row("<?php echo form::open(\$action, array('class' => 'forms')) ?>")
                    
                    ->add_row("<?php if(!isset(\$values)): \$values = array(); endif; ?>")
                    ->add_row("<ul>");            

        
        foreach ($fields as $field) { 
                
            $label = "'".$field->get_name().":'";
               
            if(Generator_Util_Config::load()->support_multilang)
            {
                $label = "__('" . $db_table->get_name() . "." . $field->get_name() . "'), array('class' => 'bold')";
            }
            
            if(!$field->is_primary_key() && !$field->is_foreign_key())
            {
                    $file->add_row("<li>", 4)
                         ->add_row("<?php echo form::label('" . $field->get_name() . "', " . $label . ") ?>", 8)
                         ->add_row("<?php echo form::input('" . $field->get_name() . "', Arr::get(\$values, '" . $field->get_name() . "'), array('id' => '" . $field->get_name() . "', 'class' => \$classes['".$field->get_name()."'])) ?>", 8)
                         ->add_row("</li>", 4);
            }
                
            if($field->is_foreign_key())
            {   
                $file->add_row("<li>", 4)
                     ->add_row("<?php echo form::label('" . $field->get_name() . "', " . $label . ") ?>", 8)
                     ->add_row("<?php echo form::select('" . $field->get_name() . "', \$" . $field->get_name() . ", Arr::get(\$values, '" . $field->get_name() . "'), array('id' => '" . $field->get_name() . "', 'class' => \$classes['".$field->get_name()."'])) ?>", 8)
                     ->add_row("</li>", 4);   
            }
                
        }
           
        $file->add_row("<li>", 4)
             ->add_row("<?php echo form::submit('submit', 'Submit', array('class' => 'btn')) ?>", 4)
             ->add_row("</li>", 4);
             
            
        if (Generator_Util_Config::load()->support_multilang) 
        {
            $file->add_row("\n<li><?php echo html::anchor('/" . $db_table->get_name() . "', __('action.back_to_the_list')) ?></li>");   
        }
        else 
        {
            $file->add_row("\n<li><?php echo html::anchor('/" . $db_table->get_name() . "', 'Back to the list') ?></li>");   
        }
          
        $file->add_row("</fieldset>")
             ->add_row("</ul>")
             ->add_row("<?php echo form::close() ?>")
             ->set_disable_close_tag(true);
        
        return $file;
    }
    
}

?>
