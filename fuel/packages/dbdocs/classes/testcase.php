<?php

namespace Dbdocs;

/**
 * Dbdocs_TestCase class
 * @abstract
 * 
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
abstract class Dbdocs_TestCase extends \TestCase
{
	/**
	 * Instance of Dbdocs\Dbdocs
	 * 
     * @var Dbdocs\Dbdocs
     */
	protected static $dd = null;

	/**
	 * For Travis CI https://travis-ci.org/
	 * 
     * @var array
	 */
	protected static $travis_dbs = array(
		'mysql' => array(
			'dbname'   => 'fuel_packages_dbdocs_tests',
			'user'     => 'root',
			'password' => '',
			'host'     => 'localhost',
			'driver'   => 'pdo_mysql',
			'description' => 'xxx',
		),
		'pgsql' => array(
			'dbname'   => 'fuel_packages_dbdocs_tests',
			'user'     => 'postgres',
			'password' => '',
			'host'     => 'localhost',
			'driver'   => 'pdo_pgsql',
			'description' => 'yyy',
		),
		'sqlite' => array(
			'path'     => './fuel_packages_dbdocs_tests.sqlite',
			'driver'   => 'pdo_sqlite',
			'description' => 'zzz',
		),
	);

	/**
	 * Must be sorted A to Z
	 * 
     * @var array
	 */
	protected static $test_tables = array(
		'test' => array(
			'columns' => array(
				array(
					'name' => 'id',
					'type' => 'integer',
					'options' => array('autoincrement' => true, 'unsigned' => true),
				),
				array(
					'name' => 'name',
					'type' => 'string',
					'options' => array('length' => 255),
				),
			),
			'primary_keys' => array('id'),
		),
		'test2' => array(
			'columns' => array(
				array(
					'name' => 'id',
					'type' => 'integer',
					'options' => array('unsigned' => true),
				),
				array(
					'name' => 'name',
					'type' => 'string',
					'options' => array('length' => 255),
				),
			),
			'primary_keys' => array('id'),
		),
		'test3' => array(
			'columns' => array(
				array(
					'name' => 'id',
					'type' => 'integer',
					'options' => array('unsigned' => true),
				),
				array(
					'name' => 'name',
					'type' => 'string',
					'options' => array('length' => 255),
				),
			),
			'primary_keys' => array('id'),
		),
		'test4' => array(
			'columns' => array(
				array(
					'name' => 'id',
					'type' => 'integer',
					'options' => array('unsigned' => true),
				),
				array(
					'name' => 'name',
					'type' => 'string',
					'options' => array('length' => 255),
				),
				array(
					'name' => 'test3_id',
					'type' => 'integer',
					'options' => array('unsigned' => true, 'comment' => 'test3.id'),
				),
				array(
					'name' => 'text_column',
					'type' => 'text',
					'options' => array('unsigned' => true, 'comment' => 'This is a comment <script>xxx</script>'),
				),
			),
			'primary_keys' => array('id'),
			'foreign_keys' => array(
				array(
					'from' => 'test3_id',
					'to' => 'test3.id'
				)
			),
		),
	);

	/**
	 * Must be sorted A to Z
	 * 
     * @var array
	 */
	protected static $test_views = array(
		'test_view_1' => '
			select
				test.id as test_id,
				test.name as test_name,
				test2.id as test2_id,
				test2.name as_test2_name
			from
				test,
				test2',
		'test_view_2' => '
			select
				test3.id as test3_id,
				test3.name as test3_name,
				test4.text_column as test4_text_column
			from
				test3,
				test4',
	);

	/**
	 * Config for \Dbdocs\Dbdocs
	 * 
     * @var array
	 */
	protected static $config = array();

	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		/**
		 * get config
		 */
		if (isset($_SERVER['TRAVIS_DB']))
		{
			static::$config = static::$travis_dbs[$_SERVER['TRAVIS_DB']];
		}
		else
		{
			static::$config = include APPPATH.'config'.DS.\Fuel::DEVELOPMENT.DS.'dbdocs.php';
			static::$config = \Arr::get(static::$config, 'test_config.db');
		}

		/**
		 * connect to db
		 */
		static::$dd = Dbdocs::forge('default', static::$config);

		/**
		 * drop views
		 */
		krsort(static::$test_views);
		foreach (static::$test_views as $test_view_name => $sql)
		{
			try
			{
				//TODO: check view exist
				static::$dd->sm->dropView($test_view_name);
			}
			catch (\Doctrine\DBAL\DBALException $e)
			{
				//do nothing
			}
		}

		/**
		 * drop tables
		 */
		krsort(static::$test_tables);
		foreach (static::$test_tables as $test_table_name => $infos)
		{
			static::$dd->sm->tablesExist($test_table_name) and static::$dd->sm->dropTable($test_table_name);
		}

		/**
		 * create tables
		 */
		ksort(static::$test_tables);
		foreach (static::$test_tables as $test_table_name => $infos)
		{
			$table = new \Doctrine\DBAL\Schema\Table($test_table_name);

			foreach ($infos['columns'] as $column)
			{
				empty($column['options']) and $column['options'] = array();
				$table->addColumn($column['name'], $column['type'], $column['options']);
			}

			$table->setPrimaryKey($infos['primary_keys']);

			if ( ! empty($infos['foreign_keys']))
			{
				foreach ($infos['foreign_keys'] as $foreign_key)
				{
					list($to_table_name, $to_column_name) = explode(".", $foreign_key['to']);

					$table->addForeignKeyConstraint(
						$to_table_name, array($foreign_key['from']), array($to_column_name));
				}
			}

			static::$dd->sm->createTable($table);
		}

		/**
		 * create views
		 */
		ksort(static::$test_views);
		foreach (static::$test_views as $test_view_name => $sql)
		{
			$view = new \Doctrine\DBAL\Schema\View($test_view_name, $sql);
			static::$dd->sm->createView($view);
		}

	}

	public static function tearDownAfterClass()
	{
		parent::tearDownAfterClass();
	}

	protected function setUp()
	{
		parent::setUp();
	}

	protected function tearDown()
    {
		parent::tearDown();
    }

}
