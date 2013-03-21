<?php

namespace Dbdocs;

/**
 * Generate database documentation utility
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class Dbdocs
{

	/**
	 * @var string The version of Dbdocs
	 */
	const VERSION = '1.0-rc2';

	/**
	 * default instance
	 *
	 * @var \Dbdocs\Dbdocs
	 */
	protected static $_instance;

	/**
	 * All the \Dbdocs\Dbdocs instances
	 *
	 * @var array
	 */
	protected static $_instances = array();

	/**
	 * Database connection
	 * 
	 * @var \Doctrine\DBAL\Connection
	 */
	public $conn;

	/**
	 * Schema Manager
	 * 
	 * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	public $sm;

	/**
	 * Database connection setting
	 * 
	 * @var array
	 */
	public $config = array();

	/**
	 * Relations of FuelPHP Models
	 * 
	 * @var array
	 */
	public $fuel_relations = array();

	/**
	 * Class initialization callback
	 */
	public static function _init()
	{
		require_once __DIR__.'/../vendor/autoload.php';

		require_once PKGPATH.'dbdocs/classes/types/pointtype.php';

		! \Doctrine\DBAL\Types\Type::hasType('point') and
			\Doctrine\DBAL\Types\Type::addType('point', 'Dbdocs\Types_PointType');

		\Config::load('dbdocs', true);
	}

	/**
	 * Default constructor
	 *
	 * @param  $config array Database connection setting
	 */
	public function __construct($config)
	{
		$this->config = $config;

		$this->conn = \Doctrine\DBAL\DriverManager::getConnection($this->config);

		$this->conn->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'point');

		$this->sm = $this->conn->getSchemaManager();
	}

	/**
	 * Gets a new instance of the \Dbdocs\Dbdocs class
	 *
	 * @param  $name string Instance name
	 * @param  $config array Database connection setting
	 * @return \Dbdocs\Dbdocs instance
	 */
	public static function forge($name = 'default', array $config = array())
	{

		if ($exists = static::instance($name))
		{
			\Error::notice('Dbdocs with this name exists already, cannot be overwritten.');
			return $exists;
		}

		static::$_instances[$name] = new static($config);

		if ($name == 'default')
		{
			static::$_instance = static::$_instances[$name];
		}

		return static::$_instances[$name];
	}

	/**
	 * Gets a singleton instance of \Dbdocs\Dbdocs
	 *
	 * @param  $name string Instance name
	 * @return \Dbdocs\Dbdocs
	 */
	public static function instance($name = null)
	{
		if ($name !== null)
		{
			if ( ! array_key_exists($name, static::$_instances))
			{
				return false;
			}

			return static::$_instances[$name];
		}

		if (static::$_instance === null)
		{
			static::$_instance = static::forge();
		}

		return static::$_instance;
	}

	/*******************************************************
	 * Utility
	 ******************************************************/
	/**
	 * Gets array \Doctrine\DBAL\Schema\Table
	 *
	 * @return array
	 */
	public function get_tables()
	{
		/**
		 * get tables
		 */
		$tables = $this->sm->listTables();

		/**
		 * unset ignore tables
		 */
		$ignore_table_names = \Config::get('dbdocs.ignore_table_names', array());
		foreach ($ignore_table_names as $ignore_table_name)
		{
			foreach ($tables as $index => $table)
			{
				/* @var $table \Doctrine\DBAL\Schema\Table */
				if ($table->getName() == $ignore_table_name)
				{
					unset($tables[$index]);
				}
			}
		}

		$ignore_table_name_regex = \Config::get('dbdocs.ignore_table_name_regex');
		if ( ! empty($ignore_table_name_regex))
		{
			foreach ($tables as $index => $table)
			{
				/* @var $table \Doctrine\DBAL\Schema\Table */
				if (0 < preg_match($ignore_table_name_regex, $table->getName()))
				{
					unset($tables[$index]);
				}
			}
		}

		return array_merge($tables, array());
	}

	/**
	 * Gets array \Doctrine\DBAL\Schema\View
	 *
	 * @return array
	 */
	public function get_views()
	{
		/**
		 * get views
		 */
		$views = $this->sm->listViews();

		return $views;
	}

	/**
	 * Gets array database information
	 *
	 * @return array
	 */
	public function get_information()
	{
		$information = array();

		$information['platform'] = $this->conn->getDatabasePlatform()->getName();
		$information['database'] = $this->conn->getDatabase();

		return $information;
	}

	/**
	 * Generate database documentation
	 *
	 * @param  $dir string Documentation directory
	 * @return mixed (true or error message)
	 */
	public function generate($dir, $force = false)
	{
		/**
		 * delete and create dbdocs dir
		 */
		if (file_exists($dir))
		{
			if ($force === false)
			{
				return "{$dir} already exist, please use -f option to force delete and generate.";
			}

			$ret = \File::delete_dir($dir);
			if ($ret === false)
			{
				return "Could not delete directory \"{$dir}\"";
			}
		}

		$ret = mkdir($dir, 0777, true);
		if ($ret === false)
		{
			return "Could not create directory \"{$dir}\"";
		}

		/**
		 * copy assets
		 */
		\File::copy_dir(__DIR__.DS.'..'.DS.'assets', $dir.'assets');

		/**
		 * get tables and views
		 */
		$tables = $this->get_tables();
		$views = $this->get_views();

		foreach ($tables as $table)
		{
			$this->set_fuel_relations($table);
		}

		/**
		 * generate index.html
		 */
		$html =
			\ViewModel::forge('dbdocs/index')
			->set('information', $this->get_information())
			->set('description', $this->config['description'])
			->set('__tables', $tables)
			->set('__views', $views)
			->render();

		\File::create($dir, 'index.html', $html);

		/**
		 * generate indexes.html
		 */
		$html =
			\ViewModel::forge('dbdocs/indexes')
			->set('__tables', $tables)
			->render();

		\File::create($dir, 'indexes.html', $html);

		/**
		 * generate tables.html
		 */
		$html =
			\ViewModel::forge('dbdocs/tables')
			->set('__tables', $tables)
			->render();

		\File::create($dir, 'tables.html', $html);

		/**
		 * generate table_*.html
		 */
		foreach ($tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */
			$html =
				\ViewModel::forge('dbdocs/table')
				->set('__table', $table)
				->render();

			\File::create($dir, 'table_'.$table->getName().'.html', $html);
		}

		/**
		 * generate views.html
		 */
		$html =
			\ViewModel::forge('dbdocs/views')
			->set('__views', $views)
			->render();

		\File::create($dir, 'views.html', $html);

		/**
		 * generate view_*.html
		 */
		foreach ($views as $view)
		{
			/* @var $view \Doctrine\DBAL\Schema\View */
			$html =
				\ViewModel::forge('dbdocs/view')
				->set('__view', $view)
				->render();

			\File::create($dir, 'view_'.$view->getName().'.html', $html);
		}

		return true;
	}

	/**
	 * Gets array for chosen
	 *
	 * @param  $tables array \Doctrine\DBAL\Schema\Table
	 * @param  $views array \Doctrine\DBAL\Schema\View
	 * @return array
	 */
	public function get_chosen_data($tables, $views)
	{
		$ret = array(
			'tables' => array(),
			'columns' => array(),
			'indexes' => array(),
			'views' => array(),
		);

		foreach ($tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */
			$ret['tables'][] = array(
				'text' => $table->getName(),
				'href' => 'table_'.$table->getName().'.html',
			);
		}

		foreach ($tables as $table)
		{
			foreach ($table->getColumns() as $column)
			{
				$random = \Str::random('unique');

				/* @var $column \Doctrine\DBAL\Schema\Column */
				$ret['columns'][] = array(
					'text' => $column->getName().' / '.$table->getName(),
					'href' => 'table_'.$table->getName().".html?{$random}#_column_".$column->getName(),
				);
			}
		}

		foreach ($tables as $table)
		{
			foreach ($table->getIndexes() as $index)
			{
				$column_names = $index->getColumns();

				$random = \Str::random('unique');

				/* @var $index \Doctrine\DBAL\Schema\Index */
				$ret['indexes'][] = array(
					'text' => $index->getName().' / '.$table->getName(),
					'href' => 'table_'.$table->getName().".html?{$random}#_column_".$column_names[0],
				);
				
			}
		}

		foreach ($views as $view)
		{

			/* @var $view \Doctrine\DBAL\Schema\View */
			$ret['views'][] = array(
				'text' => $view->getName(),
				'href' => 'view_'.$view->getName().'.html',
			);
		}

		return $ret;
	}

	/*******************************************************
	 * Private methods
	 ******************************************************/
	/**
	 * Sets relations of FuelPHP Models
	 *
	 * @param  $table array \Doctrine\DBAL\Schema\Table
	 */
	private function set_fuel_relations(\Doctrine\DBAL\Schema\Table $table)
	{
		$table_name = $table->getName();

		$fuel_model_name = $this->get_fuel_model_name($table_name);
		if ($fuel_model_name === false)
		{
			return;
		}

		foreach ($fuel_model_name::relations() as $relation)
		{
			switch (\Inflector::denamespace(get_class($relation))) {
				case 'BelongsTo':
					$model_from = $relation->model_from;
					$model_to = $relation->model_to;

					$this->fuel_relations[$table_name]['belongs_to'][] = array(
						'key_from' => $relation->key_from[0],
						'table_to' => $model_to::table(),
						'key_to' => $relation->key_to[0],
					);
				break;

				case 'HasOne':
					$model_from = $relation->model_from;
					$model_to = $relation->model_to;

					$this->fuel_relations[$table_name]['has_one'][] = array(
						'key_from' => $relation->key_from[0],
						'table_to' => $model_to::table(),
						'key_to' => $relation->key_to[0],
					);
				break;

				case 'HasMany':
					$model_from = $relation->model_from;
					$model_to = $relation->model_to;

					$this->fuel_relations[$table_name]['has_many'][] = array(
						'is_many_many' => false,
						'key_from' => $relation->key_from[0],
						'table_to' => $model_to::table(),
						'key_to' => $relation->key_to[0],
					);
				break;

				case 'ManyMany':
					$this->fuel_relations[$table_name]['has_many'][] = array(
						'is_many_many' => true,
						'key_from' => $relation->key_from[0],
						'table_to' => $relation->table_through,
						'key_to' => $relation->key_through_from[0],
					);
				break;

				default:
				break;
			}
		}
	}
	/**
	 * Gets FuelPHP model's name
	 *
	 * @param  $table_name string
	 * @return string
	 */
	private function get_fuel_model_name($table_name)
	{
		$table_name = ucwords(strtolower($table_name));

		$fuel_model_name = 'Model_'.$table_name;
		if (class_exists($fuel_model_name)) return $fuel_model_name;

		$fuel_model_name = 'Model_'.\Inflector::singularize($table_name);
		if (class_exists($fuel_model_name)) return $fuel_model_name;

		$fuel_model_name = 'Model_'.\Inflector::singularize(\Inflector::camelize($table_name));
		if (class_exists($fuel_model_name)) return $fuel_model_name;

		return false;
	}

}

/* end of file dbdocs.php */
