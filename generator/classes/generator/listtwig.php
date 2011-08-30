<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of listtwig
 *
 * @author burningface
 */
class Generator_Listtwig {
    
    public static function generate() {
        $result = new Generator_Result();
        
        $tables = Generator_Util::listTables();
        $config = Generator_Util::loadConfig();
        $disabled_tables = $config->get("disabled_tables");
        foreach ($tables as $key => $table) {
            if (!in_array($table, $disabled_tables)) {
                $table_simple_name = Generator_Util::name($table);
                $model_name = Generator_Util::upperFirst($table_simple_name);

                $writer = new Generator_Filewriter($table_simple_name.".html", true);

                if (!$writer->fileExists($table_simple_name . ".html", Generator_Filewriter::$LIST)) {
                    $fields = Generator_Util::listTableFields($table);
                    $head = "";
                    $body = "";
                    $edithead = "";
                    $edit = "";
                    $foot = "          <td><a href=\"/".$table_simple_name."/create\">{{ create }}</a></td>\n";
                    $foot .= "          <td>&nbsp;</td>\n";
                    $foot .= "          <td>&nbsp;</td>\n";
                    
                    foreach ($fields as $array){
                        $field = Generator_Field::factory($array);
                        $head .= "          <th>{{ labels.".$field->getName()." }}</th>\n";
                        $body .= "          <td>{{ object.".$field->getName()." }}</td>\n";
                        $foot .= "          <td>&nbsp;</td>\n";
                        
                        if($field->isPrimaryKey()){
                            $edithead .= "          <th>{{ show }}</th>\n";
                            $edithead .= "          <th>{{ edit }}</th>\n";
                            $edithead .= "          <th>{{ delete }}</th>\n";
                            $edit .= "          <td><a href=\"/".$table_simple_name."/show/{{ object.".$field->getName()." }}\">{{ show }}</a></td>\n";
                            $edit .= "          <td><a href=\"/".$table_simple_name."/edit/{{ object.".$field->getName()." }}\">{{ edit }}</a></td>\n";
                            $edit .= "          <td><a href=\"/".$table_simple_name."/delete/{{ object.".$field->getName()." }}\">{{ delete }}</a></td>\n";
                        }
                        
                    }
                                        
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
                    $writer->addRow("   {% for object in result %}");
                    $writer->addRow("   <tr>\n");
                    $writer->addRow($body);
                    $writer->addRow($edit);
                    $writer->addRow("   </tr>");
                    $writer->addRow("   {% endfor %}");
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
