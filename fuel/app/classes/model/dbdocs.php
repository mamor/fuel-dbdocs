<?php
/**
 * Dbdocs Model
 * 
 * This model not use database table
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

class Model_Dbdocs extends \Orm\Model
{
	/**
	 * properties for fieldset and validation
	 * 
	 * @var array
	 */
	protected static $__properties = array(
		'mysql' => array(
			/**
			 * for application values
			 */
			'platform' => array(
				'label'      => false,
				'validation' => array('required', 'match_value' => array('mysql')),
				'form'       => array('type' => 'hidden', 'value' => 'mysql'),
			),
			'name' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Document name'),
			),
			/**
			 * for doctrine values
			 */
			'host' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Host'),
			),
			'dbname' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Database'),
			),
			'user' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'User'),
			),
			'password' => array(
				'label'      => false,
				'validation' => array('trim', 'max_length' => array(255)),
				'form'       => array('type' => 'password', 'placeholder' => 'Password'),
			),
			'charset' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Charset'),
			),
			'driver' => array(
				'label'      => false,
				'validation' => array('required', 'match_value' => array('pdo_mysql')),
				'form'       => array('type' => 'hidden', 'value' => 'pdo_mysql'),
			),
		),
		'pgsql' => array(
			/**
			 * for application values
			 */
			'platform' => array(
				'label'      => false,
				'validation' => array('required', 'match_value' => array('pgsql')),
				'form'       => array('type' => 'hidden', 'value' => 'pgsql'),
			),
			'name' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Document name'),
			),
			/**
			 * for doctrine values
			 */
			'host' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Host'),
			),
			'dbname' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Database'),
			),
			'user' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'User'),
			),
			'password' => array(
				'label'      => false,
				'validation' => array('trim', 'max_length' => array(255)),
				'form'       => array('type' => 'password', 'placeholder' => 'Password'),
			),
			'charset' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Charset'),
			),
			'driver' => array(
				'label'      => false,
				'validation' => array('required', 'match_value' => array('pdo_pgsql')),
				'form'       => array('type' => 'hidden', 'value' => 'pdo_pgsql'),
			),
		),
		'sqlite' => array(
			/**
			 * for application values
			 */
			'platform' => array(
				'label'      => false,
				'validation' => array('required', 'match_value' => array('sqlite')),
				'form'       => array('type' => 'hidden', 'value' => 'sqlite'),
			),
			'name' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Document name'),
			),
			/**
			 * for doctrine values
			 */
			'path' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Path'),
			),
			'charset' => array(
				'label'      => false,
				'validation' => array('trim', 'required', 'max_length' => array(255)),
				'form'       => array('type' => 'text', 'placeholder' => 'Charset'),
			),
			'driver' => array(
				'label'      => false,
				'validation' => array('required', 'match_value' => array('pdo_sqlite')),
				'form'       => array('type' => 'hidden', 'value' => 'pdo_sqlite'),
			),
		),
	);

	/**
	 * properties for fieldset and validation
	 * 
	 * @var array
	 */
	protected static $_properties = array();

	/**
	 * set static::$_properties from static::$__properties
	 * 
	 * @param  string $platform the key of static::$__properties
	 */
	public static function set_properties($platform)
	{
		if ( ! isset(static::$__properties[$platform]))
		{
			throw new Exception('Bad platform.');
		}

		static::$_properties = static::$__properties[$platform];
	}

}
