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
			/* @var $dd \Dbdocs\Dbdocs */
			$dd = Dbdocs::instance('default');

			if (0 < preg_match('/^[0-9a-zA-Z_]+\.[0-9a-zA-Z_]+$/', $comment))
			{
				list($c_table_name, $c_column_name) = explode('.', $comment);
				$comment = "<a href=\"table_{$c_table_name}.html#_column_{$c_column_name}\">".$comment.'</a>'
					."\n<span class=\"_foreign_key\" title=\"{$comment}\" ><i class=\"icon-question-sign\"></i></span>";
			}
			else if (empty($comment) && isset($dd->fuel_relations[$table_name]['belongs_to']))
			{
				foreach ($dd->fuel_relations[$table_name]['belongs_to'] as $belongs_to)
				{
					if($belongs_to['key_from'] == $column_name)
					{
						$c_table_name = $belongs_to['table_to'];
						$c_column_name = $belongs_to['key_to'];

						$comment = "<a href=\"table_{$c_table_name}.html#_column_{$c_column_name}\">".$c_table_name.'.'.$c_column_name.'</a>'
							."\n<span class=\"_foreign_key\" title=\"{$c_table_name}.{$c_column_name}\" ><i class=\"icon-question-sign\"></i></span>";

						break;
					}
				}
			}

			return $comment;
		},
		/**
		 * Modify foreign key rule
		 */
		'mod_foreign_key' => function ($column_name, $table_name)
		{
			$ret = array();

			/* @var $dd \Dbdocs\Dbdocs */
			$dd = Dbdocs::instance('default');

			if (isset($dd->fuel_relations[$table_name]['belongs_to']))
			{
				foreach ($dd->fuel_relations[$table_name]['belongs_to'] as $belongs_to)
				{
					if($belongs_to['key_from'] == $column_name)
					{
						$ret['table_name'] = $belongs_to['table_to'];
						$ret['column_name'] = $belongs_to['key_to'];
						break;
					}
				}
			}
			else if (0 < preg_match('/^.+_id$/', $column_name))
			{
				$table_name = str_replace('_id', '', $column_name);

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
