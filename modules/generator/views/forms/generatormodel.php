<?php
echo form::open($action, array("id" => "generate_model"));
?>
<div>
    <?php
    echo form::label("date_format", $language["date_format"] . ": ");
    echo form::input("date_format", "Y-m-d");
    ?>
</div>
<div>
    <?php
    echo form::submit("submit", $language["generate_model_button"]);
    echo form::button("clear_button", $language["clear_button"], array("id" => "clear_button"));
    ?>
    <?php
    echo form::close();
    ?>
</div>