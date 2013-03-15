<?php

namespace Fuel\Tasks;

/**
 * Generate database documentation task
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class Dbdocs
{
	/**
	 * Database connection setting
	 * 
	 * @var array
	 */
	private static $config = array();

	/**
	 * Documentation directory
	 * 
	 * @var string
	 */
	private static $dir = null;

	/**
	 * Database connection setting for MySQL
	 * 
	 * @var array
	 */
	private static $config_mysql = array(
		'host'     => '',
		'dbname'   => '',
		'user'     => '',
		'password' => '',
		'charset'  => '',
		'driver'   => 'pdo_mysql',
		'description' => '',
	);

	/**
	 * Database connection setting for PostgreSQL
	 * 
	 * @var array
	 */
	private static $config_pgsql = array(
		'host'     => '',
		'dbname'   => '',
		'user'     => '',
		'password' => '',
		'charset'  => '',
		'driver'   => 'pdo_pgsql',
		'description' => '',
	);

	/**
	 * Database connection setting for SQLite
	 * 
	 * @var array
	 */
	private static $config_sqlite = array(
		'path'     => '',
		'charset'  => '',
		'driver'   => 'pdo_sqlite',
		'description' => '',
	);

	/**
	 * Show help
	 *
	 * Usage (from command line):
	 * 
	 * php oil r dbdocs
	 */
	public static function run()
	{
		static::help();
	}

	/**
	 * Show help
	 *
	 * Usage (from command line):
	 * 
	 * php oil r dbdocs:help
	 */
	public static function help()
	{
		$output = <<<HELP

Description:
  Generate database documentation

Commands:
  php oil refine dbdocs:mysql  <directory>
  php oil refine dbdocs:pgsql  <directory>
  php oil refine dbdocs:sqlite <directory>
  php oil refine dbdocs:help

Runtime options:
  -f, [--force]           # Overwrite documentation that already exist
  -n, [--non-interactive] # Non interactive mode

Runtime options with non interactive mode:
MySQL and PostgreSQL:
  --host=<host>
  --dbname=<dbname>
  --user=<user>
  --password=<password>
  --charset=<charset>
  --description=<description>

SQLite:
  --path=<path>
  --charset=<charset>
  --description=<description>

HELP;
		\Cli::write($output);
	}

	/**
	 * Generate Database Documentation for MySQL
	 *
	 * Usage (from command line):
	 * 
	 * php oil r dbdocs:mysql
	 * 
	 * @param  $dir Documentation directory
	 */
	public static function mysql($dir = null)
	{
		static::$config = static::$config_mysql;

		if ($dir === null)
		{
			static::help();
			exit();
		}

		static::$dir = rtrim($dir, DS).DS.'dbdoc'.DS;

		static::process();
	}

	/**
	 * Generate Database Documentation for PostgreSQL
	 *
	 * Usage (from command line):
	 * 
	 * php oil r dbdocs:pgsql
	 * 
	 * @param  $dir Documentation directory
	 */
	public static function pgsql($dir = null)
	{
		static::$config = static::$config_pgsql;

		if ($dir === null)
		{
			static::help();
			exit();
		}

		static::$dir = rtrim($dir, DS).DS.'dbdoc'.DS;

		static::process();
	}

	/**
	 * Generate Database Documentation for SQLite
	 *
	 * Usage (from command line):
	 * 
	 * php oil r dbdocs:sqlite
	 * 
	 * @param  $dir Documentation directory
	 */
	public static function sqlite($dir = null)
	{
		static::$config = static::$config_sqlite;

		if ($dir === null)
		{
			static::help();
			exit();
		}

		static::$dir = rtrim($dir, DS).DS.'dbdoc'.DS;

		static::process();
	}

	/*******************************************************
	 * Private Methods
	 ******************************************************/
	/**
	 * Generate process
	 */
	private static function process()
	{
		/**
		 * interactive mode?
		 */
		if (\Cli::option('n', false) or \Cli::option('non-interactive', false))
		{
			/**
			 * option values into config
			 */
			foreach (static::$config as $k => &$v)
			{
				if (($k != 'driver'))
				{
					$v = \Cli::option($k);
				}
			}
		}
		else
		{
			/**
			 * enter values into config
			 */
			static::prompt();
			static::confirm();
		}

		/**
		 * generate documentation
		 */
		$dd = \Dbdocs::forge('default', static::$config);
		$ret = $dd->generate(static::$dir, (\Cli::option('f') or \Cli::option('force')));

		if ($ret === true)
		{
			\Cli::write('Generated MySQL Documentation in "'.static::$dir.'"', 'green');
		}
		else if (is_string($ret))
		{
			\Cli::write($ret, 'red');
		}
		else
		{
			\Cli::write('System error occurred.', 'red');
		}

		exit();

	}

	/**
	 * Enter database connection setting
	 * 
	 * @param  $index Number of setting to correct
	 */
	private static function prompt($index = null)
	{
		if($index === null)
		{
			/**
			 * enter all values
			 */
			foreach (static::$config as $k => &$v)
			{
				if (($k != 'driver'))
				{
					do
					{
						$v = trim(\Cli::prompt($k));
					}
					while ( ! in_array($k, array('password', 'description')) and strlen($v) === 0);
				}
			}
		}
		else
		{
			/**
			 * enter selected value
			 */
			$i = 1;
			foreach (static::$config as $k => &$v)
			{
				if ($index == $i)
				{
					$v = \Cli::prompt($k);
					break;
				}

				($k != 'driver') and $i++;
			}
		}
	}

	/**
	 * Confirm database connection setting
	 */
	private static function confirm()
	{
		while(true)
		{
			$output = null;
			$options = array();

			/**
			 * entered values
			 */
			$i = 1;
			foreach (static::$config as $k => &$v)
			{
				if (($k != 'driver'))
				{
					$options[] = $i;
					$display = ($k === 'password') ? str_pad('', strlen($v), '*') : $v;
					$output .= '('.$i.') '.$k.':'.$display."\n";
					$i++;
				}
			}

			/**
			 * ok
			 */
			$output .= "(y) OK.\n";
			$options[] = 'y';

			/**
			 * cancel
			 */
			$output .= "(c) Cancel.\n";
			$options[] = 'c';

			\Cli::write("\nconfirm");
			\Cli::write($output);

			$answer = \Cli::prompt('Select a number, "y" or "c".', $options);

			switch ($answer)
			{
				case 'y':
					return;
				case 'c':
					\Cli::write('Good bye!!');
					exit();
				default:
					static::prompt($answer);
					break;
			}
		}

	}

}

/* End of file tasks/dbdocs.php */
