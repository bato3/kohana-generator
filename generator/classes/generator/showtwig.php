<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of showtwig
 *
 * @author burningface
 */
class Generator_Showtwig {
    
    public static function generate() {
        $result = new Generator_Result();

        $tables = Generator_Util::listTables();
        $config = Generator_Util::loadConfig();
        $twig_extension = $config->get("twig_extension");
        $disabled_tables = $config->get("disabled_tables");
        $show_div_class = $config->get("show_div_class");
        foreach ($tables as $key => $table) {
            if (!in_array($table, $disabled_tables)) {
                $table_simple_name = Generator_Util::name($table);
                $model_name = Generator_Util::upperFirst($table_simple_name);

                $writer = new Generator_Filewriter($table_simple_name.".$twig_extension", true);

                if (!$writer->fileExists($table_simple_name . ".$twig_extension", Generator_Filewriter::$SHOW)) {
                    $fields = Generator_Util::listTableFields($table);
                    $writer->addRow("<div class=\"".$show_div_class."\">");

                    foreach ($fields as $array) {
                        $field = Generator_Field::factory($array);
                        $writer->addRow("      <div class=\"" . $config->get("row_class") . "\">{{ labels." . $field->getName() . " }}: {{ model." . $field->getName() . " }}</div>");
                    }
                    $writer->addRow("</div>");
                    $writer->addRow("<div class=\"" . $config->get("back_link_class") . "\"><a href=\"/$table_simple_name/\">{% autoescape false %}{{ back }}{% endautoescape %}</a></div>");
                }

                $writer->write(Generator_Filewriter::$SHOW);
                $result->addItem($writer->getFilename(), $writer->getPath(), $writer->getRows());
                $result->addWriteIsOk($writer->writeIsOk());
            }
        }
        return $result;
    }
}

?>
