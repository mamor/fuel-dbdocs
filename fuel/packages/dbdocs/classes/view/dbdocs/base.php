<?php

namespace Dbdocs;

/**
 * ViewModel base class
 * @abstract
 * 
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
abstract class View_Dbdocs_Base extends \Fuel\Core\ViewModel
{
	/**
	 * active nav in HTML
	 *
	 * @var string
	 */
	protected $active = null;

	/**
	 * Merge \Doctrine\DBAL\Schema\Index and \Doctrine\DBAL\Schema\ForeignKeyConstraint
	 *
	 * @param  $indexes array \Doctrine\DBAL\Schema\Index
	 * @param  $foreign_keys array \Doctrine\DBAL\Schema\ForeignKeyConstraint
	 * @param  $table \Doctrine\DBAL\Schema\Table
	 * @return array
	 */
	protected static function merge_indexes_and_foreign_keys(array $indexes, array $foreign_keys, \Doctrine\DBAL\Schema\Table $table)
	{
		/* @var $index \Doctrine\DBAL\Schema\Index */
		/* @var $foreign_key \Doctrine\DBAL\Schema\ForeignKeyConstraint */

		$ret = array();
		$functions = \Config::get('dbdocs.functions');

		foreach ($indexes as $index)
		{
			/* @var $index \Doctrine\DBAL\Schema\Index */
			$ret[$index->getName()]['columns'] = $index->getColumns();

			$ret[$index->getName()]['extras'] = array();

			if ($index->isPrimary())
			{
				$ret[$index->getName()]['extras'][] = 'PK';
			}
			else if ($index->isUnique())
			{
				$ret[$index->getName()]['extras'][] = 'UI';
			}
			else if ($index->isSimpleIndex())
			{
				$ret[$index->getName()]['extras'][] = 'I';
			}

			foreach ($index->getColumns() as $index_column_name)
			{
				foreach ($foreign_keys as $foreign_key)
				{
					/* @var $foreign_key \Doctrine\DBAL\Schema\ForeignKeyConstraint */
					if (in_array($index_column_name, $foreign_key->getColumns()))
					{
						$flip = array_flip($ret[$index->getName()]['extras']);
						if (isset($flip['I']))
						{
							unset($flip['I']);
							$ret[$index->getName()]['extras'] = array_flip($flip);
						}

						$ret[$index->getName()]['extras'][] = 'FK';
						$ret[$index->getName()]['foreign_key']['table_name'] = $foreign_key->getForeignTableName();

						$foreign_key_columns = $foreign_key->getForeignColumns();
						$ret[$index->getName()]['foreign_key']['column_name'] = $foreign_key_columns[0];
					}

				}

				if ( ! isset($ret[$index->getName()]['foreign_key']))
				{
					if (is_callable($functions['mod_foreign_key']))
					{
						$ret[$index->getName()]['foreign_key'] = $functions['mod_foreign_key'](
							$index_column_name, $table->getName());
					}
				}

			}
		}

		return $ret;
	}

	/**
	 * Add variables through method and after() and create template as a string
	 */
	public function render()
	{
		return \View::forge('dbdocs/template', array(
			'active' => $this->active,
			'content' => parent::render(),
		))->render();
	}
}
