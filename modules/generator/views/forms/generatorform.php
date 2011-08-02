<div>
    <?php echo form::label("table", $labels["table"] . ": ") ?>
    <?php
    echo $tablenames;
    ?>
</div>
<div>
    <?php echo form::button("clear_button", $labels["clear_button"], array("id" => "clear_button")) ?>
</div>