<?php
/**
 * The Index Controller
 *
 * @author    Mamoru Otsuka http://madroom-project.blogspot.jp/
 * @copyright 2013 Mamoru Otsuka
 * @license   MIT License http://www.opensource.org/licenses/mit-license.php
 */

class Controller_Index extends Controller_Base
{

	/**
	 * Root
	 *
	 * @access public
	 * @return Response
	 */
	public function action_index()
	{
		$data = array(
			'dirs' => array(),
		);

		try
		{
			/**
			 * get documentation list
			 */
			$dirs = array_keys(File::read_dir(DOCROOT.'dbdocs'.DS, 1));
			foreach ($dirs as $dir)
			{
				$i = count($data['dirs']);
				$data['dirs'][$i]['name'] = rtrim($dir, DS).'/';

				$file_info = File::file_info(DOCROOT.'dbdocs'.DS.$dir);
				$data['dirs'][$i]['time_ago'] = Date::time_ago($file_info['time_created']);
			}
		}
		catch (Fuel\Core\InvalidPathException $e)
		{
			//do nothing 
		}

		$this->template->content = View::forge('index/index', array('data' => $data));
	}

	/**
	 * Generate database documentation
	 *
	 * @access public
	 * @return Response
	 */
	public function action_generate()
	{
		$this->template->active = 'generate';

		$data = array();

		if (Input::method() === 'POST')
		{
			/**
			 * get Fieldset instance
			 */
			$platform = Input::post('platform');
			$fieldset = static::_get_form($platform);

			/**
			 * validation
			 */
			$validation = $fieldset->validation();
			if ($validation->run(Input::post()))
			{
				$config = $validated = $validation->validated();

				Arr::delete($config, 'platform');
				Arr::delete($config, 'name');
				Arr::delete($config, 'submit');
				Arr::delete($config, Config::get('security.csrf_token_key'));

				/**
				 * directory
				 */
				$dir = DOCROOT.'dbdocs'.DS.$validated['name'].DS.'dbdoc'.DS;

				/**
				 * generate
				 */
				$dd = Dbdocs::forge('default', $config);

				try
				{
					$ret = $dd->generate($dir, true);

					if ($ret !== true)
					{
						Session::set_flash('error', 'System error occurred.');
					}

					Session::set_flash('success', 'Generated database documentation "'.$validated['name'].'" !!');
				}
				catch (Exception $e)
				{
					Session::set_flash('error', 'System error occurred.');
				}

				Response::redirect();

			}
			else
			{
				$validated = $validation->validated();

				$data['platform'] = $platform;
				Arr::delete($validated, Config::get('security.csrf_token_key'));
				$data['form'] = $fieldset->populate($validated);

				$this->template->content = View::forge('index/generate', array('data' => $data));

				Session::set_flash('error', $validation->error());
			}
		}
		else
		{
			$this->template->content = View::forge('index/generate', array('data' => $data));
		}

	}

	/**
	 * delete database documentation
	 *
	 * @access public
	 * @return Response
	 */
	public function action_delete($name = null)
	{
		$name === null and Response::redirect();

		try
		{
			File::delete_dir(DOCROOT.'dbdocs'.DS.$name.DS);
			Session::set_flash('success', "Deleted database documentation \"{$name}\".");
		}
		catch (Fuel\Core\InvalidPathException $e)
		{
			Session::set_flash('error', "Documentation \"{$name}\" does not exist.");
		}

		Response::redirect();
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access public
	 */
	public function action_404()
	{
		Response::redirect();
	}

	/*******************************************************
	 * Private Method
	 ******************************************************/
	/**
	 * Gets form by platform
	 *
	 * @access private
	 * @param  $platform platform of database
	 * @return Fieldset
	 */
	private static function _get_form($platform)
	{
		Model_Dbdocs::set_properties($platform);
		$fieldset = Fieldset::forge()->add_model(Model_Dbdocs::forge());

		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'Generate'));
		$fieldset->add(Config::get('security.csrf_token_key'), Config::get('security.csrf_token_key'),
			array('type' => 'hidden', 'value' => Security::fetch_token()));

		return $fieldset;
	}

	/*******************************************************
	 * Ajax
	 ******************************************************/
	/**
	 * Gets form by Ajax
	 *
	 * @access public
	 * @param  $platform platform of database
	 */
	public function get_form($platform = null)
	{
		! Input::is_ajax() and die();
		$platform === null and die();

		$fieldset = static::_get_form($platform);

		return $fieldset->build(Uri::create('index/generate'));
	}

}
