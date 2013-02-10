<?php
/**
 * Base Controller
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

class Controller_Base extends Controller_Template
{

	public function before()
	{
		parent::before();

		$this->template->active = '';

		if (Input::method() != 'GET')
		{
			if ( ! Security::check_token())
			{
				throw new Exception('Security token is bad.');
			}
		}

	}

}
