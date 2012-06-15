<div class="ui-widget">
    <?php
        $options = array(" ");
        echo form::select("table", array_merge($options, Database::instance()->list_tables()), null, array("class" => "send-selected")); 
    ?>
</div>
<?php
    Generator_Util_Html::button("crud");
?>