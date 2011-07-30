<?php echo form::open($action); ?>
<div>
    <?php echo form::label("password", $language["password"] . ": ") ?>
    <?php echo form::password("password", "", array("id" => "password")) ?>
    <?php
    if (isset($errors)) {
        echo "<div class=\"errors\">" . $errors["password"] . "</div>";
    }
    ?>
</div>
<div><?php echo form::submit("submit", $language["login"]) ?></div>
<?php echo form::close(); ?>
