<div>
    <?php echo form::label("template_name", "Name:") ?>
    <?php echo form::input("template_name", null, array("class" => "send")) ?>
</div>
<?php
    Generator_Util_Html::button("template");
?>