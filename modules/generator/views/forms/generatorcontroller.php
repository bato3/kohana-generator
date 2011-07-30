<?php
echo form::open($action, array("id" => "generate_controller"));
?>
<div>
    <?php
    echo form::label("controllername", "Controller_") . form::input("controllername", "", array("id" => "controllername"));
    ?>
</div>
<?php
echo form::button("clear_button", $language["clear_button"], array("id" => "clear_button"));
?>
<div class="extends_div">
    <?php
    foreach (Generator_Controller::$EXTENDS_FROM as $num => $extends) {
        if ($extends == Generator_Controller::$EXTENDS_FROM[0]) {
            $checked = true;
        } else {
            $checked = false;
        }
        echo "<div>" . form::label(strtolower($extends), "extends " . $extends) . form::radio("extends", $num, $checked, array("id" => strtolower($extends))) . "</div>";
    }
    ?>
</div>
<div>
    <?php
    echo form::button("add_action_button", $language["add_action_button"], array("id" => "add_action_button"));
    ?>
</div>
<div id="methods_holder"></div>
<div>
    <?php
    echo form::submit("generate_controller_button", $language["generate_controller_button"], array("id" => "generate_controller_button"));
    ?>
</div>
<?php
echo form::close();
?>
