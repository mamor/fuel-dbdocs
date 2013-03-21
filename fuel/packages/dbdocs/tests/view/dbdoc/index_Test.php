<?php

namespace Dbdocs;

/**
 * Test_View_Dbdocs_Index class
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

/**
 * View_Dbdocs_Index class tests
 *
 * @group Dbdocs
 */
class Test_View_Dbdocs_Index extends Dbdocs_ViewModelTestCase
{

	/**
	 * Test for View_Dbdocs_Index::view()
	 */
	public function test_view()
    {
		$html =
			\ViewModel::forge('dbdocs/index')
			->set('information', static::$dd->get_information())
			->set('description', static::$dd->config['description'])
			->set('__tables', static::$dd->get_tables())
			->set('__views', static::$dd->get_views())
			->render();

		$dom = new \DOMDocument();
		$dom->loadHTML($html);

		$tbody = $dom->getElementsByTagName('table')->item(0)
			->getElementsByTagName('tbody')->item(0);

		/**
		 * check platform
		 */
		$output = $tbody->getElementsByTagName('td')->item(0)->nodeValue;
		$expected = static::$dd->conn->getDatabasePlatform()->getName();
		$this->assertEquals($expected, $output);

		/**
		 * check database
		 */
		$output = $tbody->getElementsByTagName('td')->item(1)->nodeValue;
		$expected = static::$dd->conn->getDatabase();
		$this->assertEquals($expected, $output);

		/**
		 * check description
		 */
		$output = $tbody->getElementsByTagName('td')->item(2)->nodeValue;
		$expected = static::$dd->config['description'];
		$this->assertEquals($expected, $output);
	}

}
