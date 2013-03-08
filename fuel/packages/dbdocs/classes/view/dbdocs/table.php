<?php

namespace Dbdocs;

/**
 * ViewModel for dbdocs/table
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class View_Dbdocs_Table extends View_Dbdocs_Base
{
	protected $active = 'tables';

	public function view()
	{
		/* @var $table \Doctrine\DBAL\Schema\Table */
		$table = $this->__table;

		$this->table_name = $table->getName();

		$this->columns = array();

		$indexes = $table->getIndexes();
		$foreign_keys = $table->getForeignKeys();

		$functions = \Config::get('dbdocs.functions');

		foreach ($table->getColumns() as $column)
		{
			/* @var $column \Doctrine\DBAL\Schema\Column */
			$comment = \Security::htmlentities($column->getComment());
			if (is_callable($functions['mod_comment']))
			{
				$comment = $functions['mod_comment']($comment, $column->getName(), $table->getName());
			}

			$this->columns[$column->getName()] = array(
				'type' => $column->getType()->getName(),
				'length' => $column->getLength(),
				'null' => ! $column->getNotnull(),
				'default' => $column->getDefault(),
				'comment' => $comment,
				'extras' => array(),
			);

			foreach ($indexes as $index)
			{
				if (in_array($column->getName(), $index->getColumns()))
				{
					if ($index->isPrimary())
					{
						$this->columns[$column->getName()]['extras'][] = 'PK';
					}
					else if ($index->isUnique())
					{
						$this->columns[$column->getName()]['extras'][] = 'UI';
					}
					else if ($index->isSimpleIndex())
					{
						$this->columns[$column->getName()]['extras'][] = 'I';
					}
				}

			}

			$column->getAutoincrement() and $this->columns[$column->getName()]['extras'][] = 'AI';
			$column->getUnsigned() and $this->columns[$column->getName()]['extras'][] = 'UN';

			foreach ($foreign_keys as $foreign_key)
			{
				/* @var $foreign_key \Doctrine\DBAL\Schema\ForeignKeyConstraint */
				if (in_array($column->getName(), $foreign_key->getColumns()))
				{
					$flip = array_flip($this->columns[$column->getName()]['extras']);
					if (isset($flip['I']))
					{
						unset($flip['I']);
						$this->columns[$column->getName()]['extras'] = array_flip($flip);
					}

					$this->columns[$column->getName()]['extras'][] = 'FK';

					$this->columns[$column->getName()]['foreign_key']['table_name'] = $foreign_key->getForeignTableName();

					$foreign_key_columns = $foreign_key->getForeignColumns();
					$this->columns[$column->getName()]['foreign_key']['column_name'] = $foreign_key_columns[0];
				}

			}

			if ( ! isset($this->columns[$column->getName()]['foreign_key']))
			{
				if (is_callable($functions['mod_foreign_key']))
				{
					$this->columns[$column->getName()]['foreign_key'] = $functions['mod_foreign_key'](
						$column->getName(), $table->getName());
				}
			}

		}

		/**
		 * get real information of columns
		 */
		$dd = Dbdocs::instance('default');
		$platform = $dd->conn->getDatabasePlatform()->getName();

		switch ($platform)
		{
			case 'mysql':
				$rows = $dd->conn->executeQuery('
					select
						*
					from
						information_schema.columns
					where
						table_schema = :table_schema
					and
						table_name = :table_name
					order by
						ordinal_position',
					array('table_name' => $this->table_name, 'table_schema' => $dd->conn->getDatabase()))->fetchAll();

				foreach ($rows as $row)
				{
					$real_columns[$row['COLUMN_NAME']] = array(
						'type' => $row['DATA_TYPE'],
						'length' => $row['CHARACTER_MAXIMUM_LENGTH'],
						'default' => $row['COLUMN_DEFAULT'],
					);
				}

				break;
			default :
				$real_columns = array();

				break;
		}

		$this->set('columns', \Arr::merge($this->columns, $real_columns), false);

		$this->indexes = static::merge_indexes_and_foreign_keys($indexes, $foreign_keys, $table);

	}

}
