<?php

namespace Dbdocs;

/**
 * ViewModel for dbdocs/views
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class View_Dbdocs_Views extends View_Dbdocs_Base
{
	protected $active = 'views';

	public function view()
	{
		$this->view_names = array();

		foreach ($this->__views as $view)
		{
			/* @var $view \Doctrine\DBAL\Schema\View */
			$this->view_names[] = $view->getName();
		}
	}

}
