<?php
echo form::open($action, array("id" => "generate_formbuilder"));
?>
<div>
    <?php
    echo form::label("generate_form_name", "Form filename: ") . form::input("generate_form_name", "", array("id" => "generate_form_name"));
    ?>
    <span>.php</span>
</div>
<?php
echo form::button("clear_button", $language["clear_button"], array("id" => "clear_button"));
?>
<div>
    <?php
    echo form::input("input_name", "", array("id" => "input_name")).form::button("add_row_button", $language["add_row_button"], array("id" => "add_row_button"));
    ?>
</div>
<table id="rows_holder">
    <thead>
            <tr>
                <th>Input name</th>
                <th>Input type</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
</table>
<div class="wrappers_div">
    <?php
    foreach (Generator_Form::$WRAPPERS as $num => $wrapper) {
        if ($wrapper == "div") {
            $checked = true;
        } else {
            $checked = false;
        }
        echo "<div>" . form::label($wrapper, "Wrappping $wrapper") . form::radio("wrapper", $num, $checked, array("id" => $wrapper)) . "</div>";
    }
    ?>
</div>
<div class="flash_div">
    <?php
    echo form::label("flash", "Flash message: ") . form::checkbox("flashmessage", 1, true, array("id" => "flash"));
    ?>
</div>
<?php
echo form::hidden("formbuilder", 1);
echo form::submit("generate_formbuilder_button", $language["generate_formbuilder_button"], array("id" => "generate_formbuilder_button"));
echo form::close();
?>
