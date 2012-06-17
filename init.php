<?php defined('SYSPATH') or die('No direct script access.');
//if(Kohana::$environment !== Kohana::PRODUCTION)
//{	
    // Static file serving (CSS, JS, images)
    Route::set('gmedia', 'gmedia(/<file>)', array('file' => '.+'))
	->defaults(array(
            'controller' => 'gmedia',
            'file'       => NULL,
    ));

//}