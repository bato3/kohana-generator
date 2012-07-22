<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_List {

    public static function factory($table) 
    {
        $db_table = Generator_Db_Table::factory($table);
        $fields = $db_table->get_table_fields();
        
        $file = Generator_File::factory()
                ->set_directory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "lists")
                ->set_file_name($db_table->get_name())
                ->add_row("?>")
                ->add_row("\n<h1>" . ucfirst($db_table->get_name()) . "</h1>\n")
                ->add_row("<table>")
                ->add_row(Generator_Util_Text::space(4) . "<thead>")
                ->add_row(Generator_Util_Text::space(8) . "<tr>");

        

        if (Generator_Util_Config::load()->support_multilang) 
        {
            foreach ($fields as $field) {
                $file->add_row(Generator_Util_Text::space(12) . "<th><?php echo __('" . $db_table->get_name() . "." . $field->get_name() . "') ?></th>");
            }
            
            $file->add_row(Generator_Util_Text::space(12) . "<th><?php echo __('action.actions') ?></th>");
        } 
        else 
        {
            foreach ($fields as $field) {
                $file->add_row(Generator_Util_Text::space(12) . "<th>" . $field->get_name() . "</th>");
            }
            
            $file->add_row(Generator_Util_Text::space(12) . "<th>Actions</th>");
        }


        $file->add_row(Generator_Util_Text::space(4) . "</thead>")
             ->add_row(Generator_Util_Text::space(4) . "<tbody>")
             ->add_row(Generator_Util_Text::space(4) . "<?php foreach(\$result as \$item): ?>")
             ->add_row(Generator_Util_Text::space(8) . "<tr>");

        foreach ($fields as $field) {
            $file->add_row(Generator_Util_Text::space(12) . "<td><?php echo html::chars(\$item->" . $field->get_name() . ") ?></td>");
        }

        $file->add_row(Generator_Util_Text::space(12) . "<td>")
             ->add_row(Generator_Util_Text::space(16) . "<ul>")
             ->add_row(Generator_Util_Text::space(20) . "<li>");

        if (Generator_Util_Config::load()->support_multilang) 
        {
            $file->add_row(Generator_Util_Text::space(24) . "<?php echo html::anchor('/".$db_table->get_name()."/show/'.\$item->".$db_table->get_primary_key_name().", __('action.show')) ?>")
                 ->add_row(Generator_Util_Text::space(20) . "</li>")
                 ->add_row(Generator_Util_Text::space(20) . "<li>")
                 ->add_row(Generator_Util_Text::space(24) . "<?php echo html::anchor('/".$db_table->get_name()."/edit/'.\$item->".$db_table->get_primary_key_name().", __('action.edit')) ?>");
        } 
        else 
        {
            $file->add_row(Generator_Util_Text::space(24) . "<?php echo html::anchor(\"\", 'show') ?>")
                 ->add_row(Generator_Util_Text::space(20) . "</li>")
                 ->add_row(Generator_Util_Text::space(20) . "<li>")
                 ->add_row(Generator_Util_Text::space(24) . "<?php echo html::anchor(\"\", 'edit') ?>");
        }

        $file->add_row(Generator_Util_Text::space(20) . "</li>")
             ->add_row(Generator_Util_Text::space(16) . "</ul>")
             ->add_row(Generator_Util_Text::space(12) . "</td>")
             ->add_row(Generator_Util_Text::space(8) . "</tr>")
             ->add_row(Generator_Util_Text::space(4) . "<?php endforeach; ?>")
             ->add_row(Generator_Util_Text::space(4) . "</tbody>")
             ->add_row("</table>");

        if (Generator_Util_Config::load()->support_multilang) 
        {    
            $file->add_row("\n<p><?php echo html::anchor('".$db_table->get_name()."/new', __('action.create_new')) ?></p>");   
        } 
        else 
        {    
            $file->add_row("\n<p><?php echo html::anchor(\"/".$db_table->get_name()."/new\", \"Create new\") ?></p>");   
        }

        $file->set_disable_close_tag(true);

        return $file;
    }

}

?>
