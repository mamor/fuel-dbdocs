<?php

namespace Dbdocs;

/**
 * ViewModel for dbdocs/indexes
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class View_Dbdocs_Indexes extends View_Dbdocs_Base
{
	protected $active = 'indexes';

	public function view()
	{
		$this->tables = array();

		foreach ($this->__tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */

			$indexes = $table->getIndexes();
			$foreign_keys = $table->getForeignKeys();
			$this->tables[$table->getName()]['indexes'] = static::merge_indexes_and_foreign_keys($indexes, $foreign_keys, $table);
		}
	}

}
