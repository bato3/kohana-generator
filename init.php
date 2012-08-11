<?php defined('SYSPATH') or die('No direct script access.');

    Route::set('gmedia', 'gmedia(/<file>)', array('file' => '.+'))
	->defaults(array(
            'controller' => 'gmedia',
            'file'       => NULL,
    ));
    Route::set('generator', 'generator(/<action>(/<id>))')
	->defaults(array(
		'controller' => 'generator',
		'action'     => 'index',
	));
    Route::set('gajax', 'gajax(/<action>(/<id>))')
	->defaults(array(
		'controller' => 'gajax',
		'action'     => 'index',
	));
?>