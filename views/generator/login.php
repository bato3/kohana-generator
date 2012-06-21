<div id="login_form_div" title="<?php Generator_Util_Lang::get("login") ?>">
    <div id="login_error"></div>
    <?php echo form::open("/gajax/login", array("id" => "login_form")) ?>
    <?php echo form::label("password", Generator_Util_Lang::get("password", false) . ": ") ?>
    <?php echo form::password("password", null, array("id" => "password")) ?>
    <?php echo form::submit("submit", Generator_Util_Lang::get("login", false), array("class" => "submit")) ?>
    <?php echo form::close() ?>
</div>