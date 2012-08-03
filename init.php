<?php defined('SYSPATH') or die('No direct script access.');

    Route::set('gmedia', 'gmedia(/<file>)', array('file' => '.+'))
	->defaults(array(
            'controller' => 'gmedia',
            'file'       => NULL,
    ));
    Route::set('generator', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'welcome',
		'action'     => 'index',
	));
?>