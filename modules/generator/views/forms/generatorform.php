<div>
    <?php echo form::label("table", $language["table"] . ": ") ?>
    <?php
    echo $tablenames;
    ?>
</div>
<div>
    <?php echo form::button("clear_button", $language["clear_button"], array("id" => "clear_button")) ?>
</div>