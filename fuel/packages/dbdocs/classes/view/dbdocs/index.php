<?php

namespace Dbdocs;

/**
 * ViewModel for dbdocs/index
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class View_Dbdocs_Index extends View_Dbdocs_Base
{

	public function view()
	{
		$this->tables_count = count($this->__tables);
		$this->views_count = count($this->__views);

		$this->indexes_count = 0;

		foreach ($this->__tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */
			foreach ($table->getIndexes() as $index)
			{
				$this->indexes_count += count($index->getColumns());
			}
		}
	}

}
