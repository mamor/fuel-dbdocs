<?php

namespace Dbdocs;

/**
 * Test_Dbdocs class
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

/**
 * Dbdocs class tests
 *
 * @group Dbdocs
 */
class Test_Dbdocs extends Dbdocs_TestCase
{

	/**
	 * Test for Dbdocs::forge()
	 */
	public function test_forge()
    {
		static::$dd = Dbdocs::forge(\Str::random('unique'), static::$config);
		$this->assertTrue(static::$dd instanceof Dbdocs);
	}

	/**
	 * Test for Dbdocs::get_tables()
	 */
	public function test_get_tables()
    {
		\Config::set('dbdocs.ignore_table_names', array('test2'));
		\Config::set('dbdocs.ignore_table_name_regex', '/^test3$/');

		$tables = static::$dd->get_tables();

		$output = count($tables);
		$expected = count(static::$test_tables) - 2;
		$this->assertEquals($expected, $output);
	}

}
