<?php
echo form::open($action, array("id" => "generate_curlcontroller"));
?>
<div>
    <?php echo form::label("models", $labels["models"] . ": ") ?>
    <?php
    echo form::select("model", $models, null, array("id" => "models"));
    ?>
</div>
<div>
    <?php echo form::button("clear_button", $labels["clear_button"], array("id" => "clear_button")) ?>
</div>
<div>
    <?php
    echo form::submit("generate_curlcontroller_button", $labels["generate_curlcontroller_button"], array("id" => "generate_curlcontroller_button"));
    ?>
</div>
<?php
echo form::close();
?>