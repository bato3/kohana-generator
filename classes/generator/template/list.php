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
                ->add_row("<table class=\"width-100 striped\">")
                ->add_row("<caption>" . ucfirst($db_table->get_name()) . "</caption>")
                ->add_row("<thead class=\"thead-gray\">", 4)
                ->add_row("<tr>", 8);

        

        if (Generator_Util_Config::load()->support_multilang) 
        {
            foreach ($fields as $field) {
                $file->add_row("<th><?php echo __('" . $db_table->get_name() . "." . $field->get_name() . "') ?></th>", 12);
            }
            
            $file->add_row("<th><?php echo __('action.actions') ?></th>", 12);
        } 
        else 
        {
            foreach ($fields as $field) {
                $file->add_row("<th>" . $field->get_name() . "</th>", 12);
            }
            
            $file->add_row("<th>Actions</th>", 12);
        }
        
        $file->add_row("</thead>", 4)
             ->add_row("<tfoot>", 4);
        if (Generator_Util_Config::load()->support_multilang) 
        {    
            foreach ($fields as $field) {
                $file->add_row("<td>&nbsp;</td>", 12);
            }
            $file->add_row("<td><?php echo html::anchor('".$db_table->get_name()."/new', __('action.create_new')) ?></td>", 12);
        } 
        else 
        {    
            foreach ($fields as $field) {
                $file->add_row("<td>&nbsp;</td>", 12);
            }
            $file->add_row("<td><?php echo html::anchor(\"/".$db_table->get_name()."/new\", \"Create new\") ?></td>", 12);
        }
        $file->add_row("</tfoot>", 4);

        $file->add_row("<tbody>", 4)
             ->add_row("<?php foreach(\$result as \$item): ?>", 4)
             ->add_row("<tr>", 8);

        foreach ($fields as $field) {
            $file->add_row("<td><?php echo html::chars(\$item->" . $field->get_name() . ") ?></td>", 12);
        }

        $file->add_row("<td>", 12)
             ->add_row("<ul>", 16)
             ->add_row("<li>", 20);

        if (Generator_Util_Config::load()->support_multilang) 
        {
            $file->add_row("<?php echo html::anchor('/".$db_table->get_name()."/show/'.\$item->".$db_table->get_primary_key_name().", __('action.show')) ?>", 24)
                 ->add_row("</li>", 20)
                 ->add_row("<li>", 20)
                 ->add_row("<?php echo html::anchor('/".$db_table->get_name()."/edit/'.\$item->".$db_table->get_primary_key_name().", __('action.edit')) ?>", 24)
                 ->add_row("</li>", 20)
                 ->add_row("<li>", 20)
                 ->add_row("<?php echo form::open('/".$db_table->get_name()."/delete') ?>", 24)
                 ->add_row("<?php echo form::hidden('id', \$item->".$db_table->get_primary_key_name().") ?>", 24)
                 ->add_row("<?php echo form::submit('submit', __('action.delete'), array('class' => 'btn btn-small')) ?>", 24)
                 ->add_row("<?php echo form::close() ?>", 24)
                 ->add_row("</li>", 20);
                 
        } 
        else 
        {
            $file->add_row("<?php echo html::anchor(\"\", 'show') ?>", 24)
                 ->add_row("</li>", 20)
                 ->add_row("<li>", 20)
                 ->add_row("<?php echo html::anchor(\"\", 'edit') ?>", 24)
                 ->add_row("</li>", 20)
                 ->add_row("<li>", 20)
                 ->add_row("<?php echo form::open('/".$db_table->get_name()."/delete') ?>", 24)
                 ->add_row("<?php echo form::hidden('id', \$item->".$db_table->get_primary_key_name().") ?>", 24)
                 ->add_row("<?php echo form::submit('submit', 'Delete', array('class' => 'btn btn-small')) ?>", 24)
                 ->add_row("<?php echo form::close() ?>", 24)
                 ->add_row("</li>", 20);
        }

        $file->add_row("</ul>", 16)
             ->add_row("</td>", 12)
             ->add_row("</tr>", 8)
             ->add_row("<?php endforeach; ?>", 4)
             ->add_row("</tbody>", 4)
             ->add_row("</table>");

        
        $file->set_disable_close_tag(true);

        return $file;
    }

}

?>
