<div>
    <?php echo form::label("table", $language["table"] . ": ") ?>
    <?php
    echo $tablenames;
    ?>
</div>
<div>
    <?php echo form::button("form_table_button", $language["form_table_button"], array("id" => "form_table_button")) ?>
    <?php echo form::button("clear_button", $language["clear_button"], array("id" => "clear_button")) ?>
</div>