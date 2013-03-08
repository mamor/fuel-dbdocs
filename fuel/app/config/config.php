<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.5
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * If you want to override the default configuration, add the keys you
 * want to change here, and assign new values to them.
 */

ini_set('default_charset', 'UTF-8');

return array(
	'locale' => null,
	'always_load' => array(
		'packages' => array(
			'orm',
			'dbdocs',
			'log',
		),
		'config' => array(
			'dbdocs' => true,
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
