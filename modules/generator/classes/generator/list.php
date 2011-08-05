<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of list
 *
 * @author burningface
 */
class Generator_List {
    
    private static $generated_files;
    private static $is_ok = array();

    public static function getIsOkArray() {
        return self::$is_ok;
    }

    public static function generate() {
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
                    $foot = "           <td><?php echo html::anchor(\"".$table_simple_name."/create\", \$labels[\"create\"]); ?></td>\n";
                    foreach ($fields as $array){
                        $field = Generator_Field::factory($array);
                        $head .= "          <th><?php echo \$labels[\"".$field->getName()."\"] ?></th>\n";
                        $body .= "          <td><?php echo \$object->".$field->getName()."; ?></td>\n";
                        if($field->isPrimaryKey()){
                            $edithead .= "          <th><?php echo \$labels[\"edit\"] ?></th>\n";
                            $edithead .= "          <th><?php echo \$labels[\"delete\"] ?></th>\n";
                            $edit .= "          <td><?php echo html::anchor(\"".$table_simple_name."/edit/\".\$object->".$field->getName().", \$labels[\"edit\"]); ?></td>\n";
                            $edit .= "          <td><?php echo html::anchor(\"".$table_simple_name."/delete/\".\$object->".$field->getName().", \$labels[\"delete\"]); ?></td>\n";
                        }
                    }
                    
                    "";
                    "html::anchor(\"".$table_simple_name."/edit\")";
                    
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
                self::$is_ok[] = $writer->writeIsOk();
                self::$generated_files .= $writer->getPath() . "<br />";
            }
        }
        return self::$generated_files;
    }

}

?>
