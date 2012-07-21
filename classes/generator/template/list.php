<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
/**
 *
 * @author burningface
 */
class Generator_Template_List {

    public static function factory($table) {
        $db_table = Generator_Db_Table::factory($table);
        $fields = $db_table->getTableFields();
        
        $file = Generator_File::factory()
                ->setDirectory("application" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "lists")
                ->setFileName($db_table->getName())
                ->addLine("?>")
                ->addLine("\n<h1>" . ucfirst($db_table->getName()) . "</h1>\n")
                ->addLine("<table>")
                ->addLine(Generator_Util_Text::space(4) . "<thead>")
                ->addLine(Generator_Util_Text::space(8) . "<tr>");

        

        if (Generator_Util_Config::load()->support_multilang) {
            foreach ($fields as $field) {
                $file->addLine(Generator_Util_Text::space(12) . "<th><?php echo __('" . $db_table->getName() . "." . $field->getName() . "') ?></th>");
            }
            $file->addLine(Generator_Util_Text::space(12) . "<th><?php echo __('action.actions') ?></th>");
        } else {
            foreach ($fields as $field) {
                $file->addLine(Generator_Util_Text::space(12) . "<th>" . $field->getName() . "</th>");
            }
            $file->addLine(Generator_Util_Text::space(12) . "<th>Actions</th>");
        }


        $file->addLine(Generator_Util_Text::space(4) . "</thead>")
                ->addLine(Generator_Util_Text::space(4) . "<tbody>")
                ->addLine(Generator_Util_Text::space(4) . "<?php foreach(\$result as \$item): ?>")
                ->addLine(Generator_Util_Text::space(8) . "<tr>");

        foreach ($fields as $field) {
            $file->addLine(Generator_Util_Text::space(12) . "<td><?php echo html::chars(\$item->" . $field->getName() . ") ?></td>");
        }

        $file->addLine(Generator_Util_Text::space(12) . "<td>")
                ->addLine(Generator_Util_Text::space(16) . "<ul>")
                ->addLine(Generator_Util_Text::space(20) . "<li>");

        if (Generator_Util_Config::load()->support_multilang) {

            $file->addLine(Generator_Util_Text::space(24) . "<?php echo html::anchor('/".$db_table->getName()."/show/'.\$item->".$db_table->getPrimaryKeyName().", __('action.show')) ?>")
                    ->addLine(Generator_Util_Text::space(20) . "</li>")
                    ->addLine(Generator_Util_Text::space(20) . "<li>")
                    ->addLine(Generator_Util_Text::space(24) . "<?php echo html::anchor('/".$db_table->getName()."/edit/'.\$item->".$db_table->getPrimaryKeyName().", __('action.edit')) ?>");
        } else {

            $file->addLine(Generator_Util_Text::space(24) . "<?php echo html::anchor(\"\", 'show') ?>")
                    ->addLine(Generator_Util_Text::space(20) . "</li>")
                    ->addLine(Generator_Util_Text::space(20) . "<li>")
                    ->addLine(Generator_Util_Text::space(24) . "<?php echo html::anchor(\"\", 'edit') ?>");
        }

        $file->addLine(Generator_Util_Text::space(20) . "</li>")
                ->addLine(Generator_Util_Text::space(16) . "</ul>")
                ->addLine(Generator_Util_Text::space(12) . "</td>")
                ->addLine(Generator_Util_Text::space(8) . "</tr>")
                ->addLine(Generator_Util_Text::space(4) . "<?php endforeach; ?>")
                ->addLine(Generator_Util_Text::space(4) . "</tbody>")
                ->addLine("</table>");

        if (Generator_Util_Config::load()->support_multilang) {
            
            $file->addLine("\n<p><?php echo html::anchor('".$db_table->getName()."/new', __('action.create_new')) ?></p>");
            
        } else {
            
            $file->addLine("\n<p><?php echo html::anchor(\"/".$db_table->getName()."/new\", \"Create new\") ?></p>");
            
        }

        $file->setDisableCloseTag(true);

        return $file;
    }

}

?>
