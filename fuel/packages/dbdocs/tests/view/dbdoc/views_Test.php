<?php

namespace Dbdocs;

/**
 * Test_View_Dbdocs_Views class
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

/**
 * View_Dbdocs_Views class tests
 *
 * @group Dbdocs
 */
class Test_View_Dbdocs_Views extends Dbdocs_ViewModelTestCase
{

	/**
	 * Test for View_Dbdocs_Views::view()
	 */
	public function test_view()
    {
		$views = static::$dd->get_views();

		$html =
			\ViewModel::forge('dbdocs/views')
			->set('__views', $views)
			->render();

		$dom = new \DOMDocument();
		$dom->loadHTML($html);

		$tbody = $dom->getElementsByTagName('table')->item(0)
			->getElementsByTagName('tbody')->item(0);

		/**
		 * check a link views
		 */
		$output = array();
		foreach ($tbody->getElementsByTagName('a') as $a)
		{
			if (0 < preg_match('/^view_.*\.html$/', $a->getAttribute('href')))
			{
				$output[] = $a->nodeValue;
			}
		}

		foreach ($views as $view)
		{
			/* @var $view \Doctrine\DBAL\Schema\View */
			$this->assertTrue(in_array($view->getName(), $output));
		}

	}

}
