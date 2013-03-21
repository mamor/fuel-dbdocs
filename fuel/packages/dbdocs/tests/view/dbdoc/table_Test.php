<?php

namespace Dbdocs;

/**
 * Test_View_Dbdocs_Table class
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

/**
 * View_Dbdocs_Table class tests
 *
 * @group Dbdocs
 */
class Test_View_Dbdocs_Table extends Dbdocs_ViewModelTestCase
{

	/**
	 * Test for View_Dbdocs_Table::view()
	 */
	public function test_view_table()
    {
		$tables = static::$dd->get_tables();

		foreach ($tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */
			$html =
				\ViewModel::forge('dbdocs/table')
				->set('__table', $table)
				->render();

			$dom = new \DOMDocument();
			$dom->loadHTML($html);

			/**
			 * check table name
			 */
			$output = $dom->getElementsByTagName('h1')->item(0)->nodeValue;
			$expected = $table->getName();
			$this->assertEquals($expected, $output);

			$tbody = $dom->getElementsByTagName('table')->item(0)
				->getElementsByTagName('tbody')->item(0);

			/**
			 * check elements count
			 */
			$expected = $output = array(
				'column_count' => 0,
				'pk_count' => 0,
				'ui_count' => 0,
				'fk_count' => 0,
				'ai_count' => 0,
				'un_count' => 0,
			);

			foreach ($tbody->getElementsByTagName('tr') as $tr)
			{
				$output['column_count']++;

				foreach ($tr->getElementsByTagName('span') as $span)
				{
					if (0 < preg_match('/^label label-info$/', $span->getAttribute('class')))
					{
						if (0 < preg_match('/^.*PK.*$/', $span->nodeValue))
						{
							$output['pk_count']++;
						}
						else if (0 < preg_match('/^.*UI.*$/', $span->nodeValue))
						{
							$output['ui_count']++;
						}

						if (0 < preg_match('/^.*FK.*$/', $span->nodeValue))
						{
							$output['fk_count']++;
						}

						if (0 < preg_match('/^.*AI.*$/', $span->nodeValue))
						{
							$output['ai_count']++;
						}

						if (0 < preg_match('/^.*UN.*$/', $span->nodeValue))
						{
							$output['un_count']++;
						}

					}
				}

			}

			$indexes = $table->getIndexes();
			$foreign_keys = $table->getForeignKeys();

			foreach ($table->getColumns() as $column)
			{
				/* @var $column \Doctrine\DBAL\Schema\Column */
				$expected['column_count']++;

				$column->getAutoincrement() and $expected['ai_count']++;
				$column->getUnsigned() and $expected['un_count']++;

				foreach ($indexes as $index)
				{
					/* @var $index \Doctrine\DBAL\Schema\Index */

					if (in_array($column->getName(), $index->getColumns()))
					{
						if ($index->isPrimary())
						{
							$expected['pk_count']++;
						}
						else if ($index->isUnique())
						{
							$expected['ui_count']++;
						}
					}

				}

				foreach ($foreign_keys as $foreign_key)
				{
					/* @var $foreign_key \Doctrine\DBAL\Schema\ForeignKeyConstraint */
					if (in_array($column->getName(), $foreign_key->getColumns()))
					{
						$expected['fk_count']++;
					}

				}
			}

			\Log::debug('expected : '.print_r($expected, true));
			\Log::debug('output : '.print_r($output, true));

			foreach ($expected as $k => $v)
			{
				$this->assertEquals($expected[$k], $output[$k]);
			}

		}

	}

	/**
	 * Test for View_Dbdocs_Table::view()
	 */
	public function test_view_indexes()
    {
		$tables = static::$dd->get_tables();

		foreach ($tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */
			$html =
				\ViewModel::forge('dbdocs/table')
				->set('__table', $table)
				->render();

			$dom = new \DOMDocument();
			$dom->loadHTML($html);

			/**
			 * check table name
			 */
			$output = $dom->getElementsByTagName('h1')->item(0)->nodeValue;
			$expected = $table->getName();
			$this->assertEquals($expected, $output);

			$tbody = $dom->getElementsByTagName('table')->item(1)
				->getElementsByTagName('tbody')->item(0);

			/**
			 * check elements count
			 */
			$expected = $output = array(
				'column_count' => 0,
				'pk_count' => 0,
				'ui_count' => 0,
				'fk_count' => 0,
			);

			foreach ($tbody->getElementsByTagName('tr') as $tr)
			{
				$output['column_count']++;

				foreach ($tr->getElementsByTagName('span') as $span)
				{
					if (0 < preg_match('/^label label-info$/', $span->getAttribute('class')))
					{
						if (0 < preg_match('/^.*PK.*$/', $span->nodeValue))
						{
							$output['pk_count']++;
						}
						else if (0 < preg_match('/^.*UI.*$/', $span->nodeValue))
						{
							$output['ui_count']++;
						}

						if (0 < preg_match('/^.*FK.*$/', $span->nodeValue))
						{
							$output['fk_count']++;
						}
					}
				}
			}

			$indexes = $table->getIndexes();
			$foreign_keys = $table->getForeignKeys();

			foreach ($indexes as $index)
			{
				$expected['column_count']++;

				/* @var $index \Doctrine\DBAL\Schema\Index */

				if ($index->isPrimary())
				{
					$expected['pk_count']++;
				}
				else if ($index->isUnique())
				{
					$expected['ui_count']++;
				}

				foreach ($index->getColumns() as $index_column_name)
				{
					foreach ($foreign_keys as $foreign_key)
					{
						/* @var $foreign_key \Doctrine\DBAL\Schema\ForeignKeyConstraint */
						if (in_array($index_column_name, $foreign_key->getColumns()))
						{
							$expected['fk_count']++;
						}

					}
				}
			}

			\Log::debug('expected : '.print_r($expected, true));
			\Log::debug('output : '.print_r($output, true));

			foreach ($expected as $k => $v)
			{
				$this->assertEquals($expected[$k], $output[$k]);
			}

		}
	}
}
