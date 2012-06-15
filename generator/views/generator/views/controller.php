<div>
    <?php echo form::label("controller_name", "Name:") ?>
    <?php echo form::input("controller_name", null, array("class" => "send")) ?>
</div>
<div>
    <p>
        <?php
        $extends = Generator_Util_Config::load()->extend_controller;
        foreach ($extends as $i => $lang) {
            $checked = $i == 0 ? true : false;
            echo "<div>";
            echo form::label($lang, $lang);
            echo form::radio("extends", $i, $checked, array("class" => "send"));
            echo "</div>";
        }
        ?>
    </p>
</div>
<?php
Generator_Util_Html::button("controller");
?>