<?php
echo form::open($action, array("id" => "generate_form"));
?>
<div class="fields_div">
    <?php
    $i = 1;
    foreach ($fields as $array) {
        $field = Generator_Field::factory($array);
        echo "<div>";
        echo form::label($field->getName(), $field->getName() . ": ");
        echo Generator_Form::suggestInput($field->getName(), $field->getType(), $field->getKey());
        echo form::input("place[" . $field->getName() . "]", $i, array("class" => "place"));
        echo "</div>";
        ++$i;
    }
    ?>
</div>
<?php
echo form::hidden("generate_form_name", $name);
?>
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
echo form::submit("generate_form_button", $language["generate_form_button"], array("id" => "generate_form_button"));
echo form::close();
?>
