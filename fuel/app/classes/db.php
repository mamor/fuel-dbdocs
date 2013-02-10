<?php
/**
 * Database object creation helper methods.
 *
 * @package    Fuel/Database
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */

class DB extends \Fuel\Core\DB
{

	public static function list_columns($table = null, $like = null, $db = null)
	{
		return array(Model_Dbdocs::properties());
	}

}
