<?php

defined('SYSPATH') or die('No direct access allowed.');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of show
 *
 * @author burningface
 */
class Generator_Show {

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

                if (!$writer->fileExists($table_simple_name . ".php", Generator_Filewriter::$SHOW)) {
                    $fields = Generator_Util::listTableFields($table);
                    $writer->addRow(Generator_Util::$SIMPLE_OPEN_FILE);
                    $writer->addRow("<div>");

                    foreach ($fields as $array) {
                        $field = Generator_Field::factory($array);
                        $writer->addRow("          <div class=\"" . $config->get("row_class") . "\"><?php echo \$labels[\"" . $field->getName() . "\"] ?>: <?php echo \$model->" . $field->getName() . "; ?></div>");
                    }
                    $writer->addRow("<div>");
                    $writer->addRow("<div class=\"" . $config->get("back_link_class") . "\"><a href=\"/$table_simple_name/\"><?php echo __(\"back\") ?></a></div>");
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
