<div>
    <?php
        $langs = Generator_Util_Config::load()->languages;
        foreach ($langs as $lang){
            echo form::label($lang, $lang);
            echo form::checkbox("lang[]", $lang, null, array("class" => "send"));
        }
    ?>
</div>
<?php
    Generator_Util_Html::button("language");
?>