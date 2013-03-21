<?php

namespace Dbdocs;

/**
 * Test_View_Dbdocs_View class
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

/**
 * View_Dbdocs_View class tests
 *
 * @group Dbdocs
 */
class Test_View_Dbdocs_View extends Dbdocs_ViewModelTestCase
{

	/**
	 * Test for View_Dbdocs_View::view()
	 */
	public function test_view()
    {
		$views = static::$dd->get_views();

		foreach ($views as $view)
		{
			/* @var $view \Doctrine\DBAL\Schema\View */
			$html =
				\ViewModel::forge('dbdocs/view')
				->set('__view', $view)
				->render();

			$dom = new \DOMDocument();
			$dom->loadHTML($html);

			/**
			 * check view name
			 */
			$output = $dom->getElementsByTagName('h1')->item(0)->nodeValue;
			$expected = $view->getName();
			$this->assertEquals($expected, $output);

		}

	}

}
