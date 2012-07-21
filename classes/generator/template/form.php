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
        $fields = $db_table->getTableFields();
            
        $file = Generator_File::factory()
                    ->setDirectory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "forms")
                    ->setFileName($db_table->getName())
                    ->addLine("?>")
                    ->addLine("\n<h1>" . ucfirst($db_table->getName()) . "</h1>\n")
                    ->addLine("<?php if(!isset(\$errors)){ \$errors = array(); } ?>")
                    ->addLine("<?php if(!isset(\$values)){ \$values = array(); } ?>\n")
                    ->addLine("<?php echo form::open(\$action) ?>");

            

            foreach ($fields as $field) {
                
                if(!$field->isPrimaryKey() && !$field->isForeignKey())
                {
                    
                    $file->addLine("<div>")
                            ->addLine(Generator_Util_Text::space(4) . "<?php echo form::label('" . $field->getName() . "', '" . $field->getName() . "') ?>")
                            ->addLine(Generator_Util_Text::space(4) . "<?php echo form::input('" . $field->getName() . "', Arr::get(\$values, '".$field->getName()."')) ?>")
                            ->addLine(Generator_Util_Text::space(4) . "<?php echo Arr::get(\$errors, '".$field->getName()."') ?>")
                            ->addLine("</div>");
                    
                }
                
                if($field->isForeignKey())
                {
                    
                    $file->addLine("<div>")
                            ->addLine(Generator_Util_Text::space(4) . "<?php echo form::label('" . $field->getName() . "', '" . $field->getName() . "') ?>")
                            ->addLine(Generator_Util_Text::space(4) . "<?php echo form::select('" . $field->getName() . "', \$".$field->getName().", Arr::get(\$values, '".$field->getName()."')) ?>")
                            ->addLine(Generator_Util_Text::space(4) . "<?php echo Arr::get(\$errors, '".$field->getName()."') ?>")
                            ->addLine("</div>");
                    
                }
                
            }

            $file->addLine("<div>")
                    ->addLine(Generator_Util_Text::space(4) . "<?php echo form::submit('submit', 'Submit') ?>")
                    ->addLine("</div>")
                    ->addLine("<?php echo form::close() ?>")->setDisableCloseTag(true);
            
            if (Generator_Util_Config::load()->support_multilang) 
            {
                
                $file->addLine("\n<p><?php echo html::anchor('/".$db_table->getName()."', __('action.back_to_the_list')) ?></p>");
                
            }
            else 
            {
                
                $file->addLine("\n<p><?php echo html::anchor('/".$db_table->getName()."', 'Back to the list') ?></p>");
                
            }
            
            return $file;
    }
    
}

?>
