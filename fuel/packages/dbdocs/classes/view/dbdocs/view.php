<?php

namespace Dbdocs;

/**
 * ViewModel for dbdocs/view
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */
class View_Dbdocs_View extends View_Dbdocs_Base
{
	protected $active = 'views';

	public function view()
	{
		/* @var $view \Doctrine\DBAL\Schema\View */
		$view = $this->__view;

		$this->view_name = $view->getName();
		$this->set('view_sql', \SqlFormatter::format($view->getSql()), false);

	}

}
