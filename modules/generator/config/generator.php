<?php defined('SYSPATH') or die('No direct script access.');

return array(
	"password" => "admin",
        "author" => "burningface",
        "license" => "GPL",
        "copyright" => "(c) 2011 burningface",
        "csrf_token_name" => "csrf",
        "jquery_url" => "https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js",
        "jquery_name" => "jquery.min.js",
        "controllers" => array(
            "Controller", 
            "Controller_Template", 
            "Controller_Template_Twig",
            "Controller_Template_Smarty"
        ),
);
