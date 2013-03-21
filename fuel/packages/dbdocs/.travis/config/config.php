<?php

return array(
	'always_load' => array(
		'packages' => array(
			'dbdocs',
		),
	),

	'security' => array(
		'uri_filter' => array('htmlentities'),
		'output_filter' => array('Security::htmlentities'),
		'whitelisted_classes' => array(
			'Fuel\\Core\\Response',
			'Fuel\\Core\\View',
			'Fuel\\Core\\ViewModel',
			'Closure',
			'Doctrine\\DBAL\\Schema\\Table',
			'Doctrine\\DBAL\\Schema\\View',
		),
	),

);
