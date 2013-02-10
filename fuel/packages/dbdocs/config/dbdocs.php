<?php

return array(
//	'webfont' => 'Roboto Condensed',
	'ignore_table_names' => array(
		Config::get('migrations.table', 'migration'),
	),
	'ignore_table_name_regex' => '',

	/**
	 * Customizable functions
	 */
	'functions' => array(
		/**
		 * Modify comment rule
		 */
		'mod_comment' => function ($comment, $column_name, $table_name)
		{
			if (0 < preg_match('/^[0-9a-zA-Z_]+\.[0-9a-zA-Z_]+$/', $comment))
			{
				list($table_name, $column_name) = explode('.', $comment);
				$comment = "<a href=\"table_{$table_name}.html\">".$comment.'</a>';
			}

			return $comment;
		},
		/**
		 * Modify foreign key rule
		 */
		'mod_foreign_key' => function ($column_name, $table_name)
		{
			$ret = array();

			if (0 < preg_match('/^.+_id$/', $column_name))
			{
				$table_name = str_replace('_id', '', $column_name);

				$dd = Dbdocs::instance('default');

				if (in_array(Inflector::singularize($table_name), $dd->sm->listTableNames()))
				{
					$ret['table_name'] = Inflector::singularize($table_name);
					$ret['column_name'] = 'id';
				}
				else if (in_array(Inflector::pluralize($table_name), $dd->sm->listTableNames()))
				{
					$ret['table_name'] = Inflector::pluralize($table_name);
					$ret['column_name'] = 'id';
				}
			}

			return $ret;
		},
	),
);
