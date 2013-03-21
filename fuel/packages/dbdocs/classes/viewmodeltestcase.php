<?php

namespace Dbdocs;

/**
 * Dbdocs_ViewModelTestCase class
 * @abstract
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
abstract class Dbdocs_ViewModelTestCase extends Dbdocs_TestCase
{

	public static function setUpBeforeClass()
	{
		parent::setUpBeforeClass();

		/**
		 * disable error reporting occurred by HTML5 tags in DOMDocument class
		 */
		libxml_use_internal_errors(true);
	}

	public static function tearDownAfterClass()
	{
		libxml_clear_errors();

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
