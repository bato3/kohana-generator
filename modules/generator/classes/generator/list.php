<?php defined('SYSPATH') or die('No direct access allowed.'); ?>
<?php

/**
 * Description of list
 *
 * @author burningface
 */
class Generator_List {

    public static function generate() {
        $result = new Generator_Result();
        
        $tables = Generator_Util::listTables();
        $config = Generator_Util::loadConfig();
        $disabled_tables = $config->get("disabled_tables");
        foreach ($tables as $key => $table) {
            if (!in_array($table, $disabled_tables)) {
                $table_simple_name = Generator_Util::name($table);
                $model_name = Generator_Util::upperFirst($table_simple_name);

                $writer = new Generator_Filewriter($table_simple_name);

                if (!$writer->fileExists($table_simple_name . ".php", Generator_Filewriter::$LIST)) {
                    $fields = Generator_Util::listTableFields($table);
                    $head = "";
                    $body = "";
                    $edithead = "";
                    $edit = "";
                    $foot = "          <td><?php echo html::anchor(\"".$table_simple_name."/create\", __(\"create\")); ?></td>\n";
                    $foot .= "          <td>&nbsp;</td>\n";
                    $foot .= "          <td>&nbsp;</td>\n";
                    
                    foreach ($fields as $array){
                        $field = Generator_Field::factory($array);
                        $head .= "          <th><?php echo \$labels[\"".$field->getName()."\"] ?></th>\n";
                        $body .= "          <td><?php echo htmlspecialchars(\$object->".$field->getName().", ENT_QUOTES); ?></td>\n";
                        $foot .= "          <td>&nbsp;</td>\n";
                        
                        if($field->isPrimaryKey()){
                            $edithead .= "          <th><?php echo __(\"show_head\") ?></th>\n";
                            $edithead .= "          <th><?php echo __(\"edit_head\") ?></th>\n";
                            $edithead .= "          <th><?php echo __(\"delete_head\") ?></th>\n";
                            $edit .= "          <td><?php echo html::anchor(\"".$table_simple_name."/show/\".\$object->".$field->getName().", __(\"show\")); ?></td>\n";
                            $edit .= "          <td><?php echo html::anchor(\"".$table_simple_name."/edit/\".\$object->".$field->getName().", __(\"edit\")); ?></td>\n";
                            $edit .= "          <td><?php echo html::anchor(\"".$table_simple_name."/delete/\".\$object->".$field->getName().", __(\"delete\")); ?></td>\n";
                        }
                        
                    }
                                        
                    $writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);
                    $writer->addRow("<table>");
                    $writer->addRow("   <thead>");
                    $writer->addRow("       <tr>\n");
                    $writer->addRow($head);
                    $writer->addRow($edithead);
                    $writer->addRow("       </tr>");
                    $writer->addRow("   </thead>");
                    $writer->addRow("<tfoot>");
                    $writer->addRow("       <tr>\n");
                    $writer->addRow($foot);
                    $writer->addRow("       </tr>");
                    $writer->addRow("</tfoot>");
                    $writer->addRow("   <tbody>");
                    $writer->addRow("   <?php");
                    $writer->addRow("   foreach(\$result as \$object) {");
                    $writer->addRow("   ?>");
                    $writer->addRow("   <tr>\n");
                    $writer->addRow($body);
                    $writer->addRow($edit);
                    $writer->addRow("   </tr>");
                    $writer->addRow("   <?php");
                    $writer->addRow("   }");
                    $writer->addRow("   ?>");
                    $writer->addRow("   </tbody>");
                    $writer->addRow("</table>");
                    
                }
                $writer->write(Generator_Filewriter::$LIST);
                $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
                $result->addWriteIsOk($writer->writeIsOk());
            }
        }
        return $result;
    }

}

?>