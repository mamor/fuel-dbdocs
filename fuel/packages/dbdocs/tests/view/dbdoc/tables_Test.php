<?php

namespace Dbdocs;

/**
 * Test_View_Dbdocs_Tables class
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

/**
 * View_Dbdocs_Tables class tests
 *
 * @group Dbdocs
 */
class Test_View_Dbdocs_Tables extends Dbdocs_ViewModelTestCase
{

	/**
	 * Test for View_Dbdocs_Tables::view()
	 */
	public function test_view()
    {
		$tables = static::$dd->get_tables();

		$html =
			\ViewModel::forge('dbdocs/tables')
			->set('__tables', $tables)
			->render();

		$dom = new \DOMDocument();
		$dom->loadHTML($html);

		$tbody = $dom->getElementsByTagName('table')->item(0)
			->getElementsByTagName('tbody')->item(0);

		/**
		 * check a link tables
		 */
		$output = array();
		foreach ($tbody->getElementsByTagName('a') as $a)
		{
			if (0 < preg_match('/^table_.*\.html$/', $a->getAttribute('href')))
			{
				$output[] = $a->nodeValue;
			}
		}

		foreach ($tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */
			$this->assertTrue(in_array($table->getName(), $output));
		}

	}

}
