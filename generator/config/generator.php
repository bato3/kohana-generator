<?php

defined('SYSPATH') or die('No direct script access.');

return array(
    "password" => "admin",
    "author" => "burningface",
    "license" => "GPL",
    "copyright" => "(c) 2011 burningface",
    
    "error_class" => "form_error",
    "form_row_class" => "form_row",
    "row_class" => "row",
    "back_link_class" => "back_to_list",
    
    "date_format" => "Y-m-d",
    "csrf_token_name" => "csrf",
    
    "jquery_url" => "https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js",
    "jquery_name" => "jquery.min.js",
    
    
    "controllers" => array(
        "Controller",
        "Controller_Template",
        "Controller_Template_Twig",
    ),
    
    "disabled_tables" => array(
        "roles", "roles_users", "user_tokens"
    ),
    
    "item_not_found_exception" => "This record %s doesn't exists !",
    
    "multilang_support" => true,
    //first is default
    "languages" => array("hu","de","en","it"),
    "table_names_plural" => true,
    "select_pre_option" => "-- select one --",
    
    "i18_validation" => array(
        "alpha"                 => "alpha",
        "alpha_dash"            => "alpha_dash",
        "alpha_numeric"         => "alpha_numeric",
        "color"                 => "color",
        "credit_card"           => "credit_card",
        "date"                  => "date",
        "decimal"               => "decimal",
        "digit"                 => "digit",
        "email"                 => "email",
        "email_domain"          => "email_domain",
        "equals"                => "equals",
        "exact_length"          => "exact_length",
        "in_array"              => "in_array",
        "ip"                    => "ip",
        "matches"               => "matches",
        "min_length"            => "min_length",
        "max_length"            => "max_length",
        "not_empty"             => "not_empty",
        "numeric"               => "numeric",
        "phone"                 => "phone",
        "range"                 => "range",
        "regex"                 => "regex",
        "url"                   => "url",
        "unique"                => "unique",
        "email_available"       => "email_available",
        "username_available"    => "username_available",
        "already_exists"        => "already_exists",
        "Captcha::valid"        => "Captcha::valid",
        "Upload::not_empty"     => "Upload::not_empty",
        "Upload::type"          => "Upload::type",
        "Upload::size"          => "Upload::size",
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
    ),

);
