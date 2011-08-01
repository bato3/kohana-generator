<?php

$tables = Generator_Util::listTables();
foreach ($tables as $table) {
    echo "<h3>".$table . "</h3><br>";
    $r = Generator_Util::listTableFields($table);
    foreach ($r as $k => $v) {
        echo $k . "<br>";
        if (is_array($v)) {
            foreach ($v as $key => $val) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $key . " => " . $val . "<br>";
            }
        }
        echo "<br>";
    }
}
?>
