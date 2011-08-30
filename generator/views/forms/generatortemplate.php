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
echo form::button("template_button", $labels["template_button"], array("id" => "template_button"));
echo form::button("clear_button", $labels["clear_button"], array("id" => "clear_button"));
?>
</div>