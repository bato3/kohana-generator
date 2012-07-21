<?php defined('SYSPATH') or die('No direct script access.') ?>
<?php
return array(
    
    "password" => "admin",
    
    "jquery_url" => "http://code.jquery.com/jquery-latest.min.js",
    "jquery_name" => "jquery.min",
    
    "reset_css_url" => "http://meyerweb.com/eric/tools/css/reset/reset.css",
    "reset_css_name" => "reset",
    
    "author" => "",
    "license" => "",
    "date_format" => "Y-m-d",
    "datetime_format" => "Y-m-d H:i:s",
    "languages" => array("hu", "en"),
    
    "extend_controller" => array(
        "Controller",
        "Controller_Template",
        "Controller_Template_Twig",
    ),
        
    "start_php_file" => "<?php defined('SYSPATH') or die('No direct script access.') ?>",
    "open_php" => "<?php",
    "close_php" => "?>",
    "table_names_plural" => true,
    "support_multilang" => true,
    
    
    "register" => array(
        "asset" => array(
            "menu" => "menu_asset",
            "class" => "Generator_Item_Asset",
        ),
        "template" => array(
            "menu" => "menu_template",
            "class" => "Generator_Item_Template",
        ),
        "controller" => array(
            "menu" => "menu_controller",
            "class" => "Generator_Item_Controller",
        ),
        "form" => array(
            "menu" => "menu_form",
            "class" => "Generator_Item_Form",
        ),
        "list" => array(
            "menu" => "menu_list",
            "class" => "Generator_Item_List",
        ),
        "show" => array(
            "menu" => "menu_show",
            "class" => "Generator_Item_Show",
        ),
        "language" => array(
            "menu" => "menu_language",
            "class" => "Generator_Item_Language",
        ),
        "orm" => array(
            "menu" => "menu_orm",
            "class" => "Generator_Item_Orm",
        ),
        "crud" => array(
            "menu" => "menu_crud",
            "class" => "Generator_Item_Crud",
        ),
        
    ),
    
    "validation" => array(
        "alpha"                 => ":field must contain only letters",
        "alpha_dash"            => ":field must contain only numbers, letters and dashes",
        "alpha_numeric"         => ":field must contain only letters and numbers",
        "color"                 => ":field must be a color",
        "credit_card"           => ":field must be a credit card number",
        "date"                  => ":field must be a date",
        "decimal"               => ":field must be a decimal with :param2 places",
        "digit"                 => ":field must be a digit",
        "email"                 => ":field must be a email address",
        "email_domain"          => ":field must contain a valid email domain",
        "equals"                => ":field must equal :param2",
        "exact_length"          => ":field must be exactly :param2 characters long",
        "in_array"              => ":field must be one of the available options",
        "ip"                    => ":field must be an ip address",
        "matches"               => ":field must be the same as :param2",
        "min_length"            => ":field must be at least :param2 characters long",
        "max_length"            => ":field must not exceed :param2 characters long",
        "not_empty"             => ":field must not be empty",
        "numeric"               => ":field must be numeric",
        "phone"                 => ":field must be a phone number",
        "range"                 => ":field must be within the range of :param2 to :param3",
        "regex"                 => ":field does not match the required format",
        "url"                   => ":field must be a url",
        "unique"                => ":value already exsits",
        "email_available"       => ":value already exsits",
        "username_available"    => ":value already exsits",
        "already_exists"        => ":value already exsits",
        "Captcha::valid"        => ":field is invalid",
        "Upload::not_empty"     => ":field must not be empty",
        "Upload::type"          => ":field type must be :param2",
        "Upload::size"          => ":field size must be :param2",
        "Security::check"       => "Invalid Csrf token",
    ),
    
    
);
?>
