<?php

namespace Dbdocs;

/**
 * ViewModel for dbdocs/tables
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class View_Dbdocs_Tables extends View_Dbdocs_Base
{
	protected $active = 'tables';

	public function view()
	{
		$this->table_names = array();

		foreach ($this->__tables as $table)
		{
			/* @var $table \Doctrine\DBAL\Schema\Table */
			$this->table_names[] = $table->getName();
		}
	}

}
