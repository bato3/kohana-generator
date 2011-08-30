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
<?php
    echo form::label("template_php", "php");
    echo form::radio("template", "php", true,array("id" => "template_php","class" => "template"));
    echo "&nbsp;&nbsp;";
    echo form::label("template_twig", "twig");
    echo form::radio("template", "twig", false,array("id" => "template_twig","class" => "template"));
?>
</div>

<div>
    <?php
    echo form::submit("generate_curlcontroller_button", $labels["generate_curlcontroller_button"], array("id" => "generate_curlcontroller_button"));
    echo form::button("clear_button", $labels["clear_button"], array("id" => "clear_button"));
    ?>
</div>
<?php
echo form::close();
?>