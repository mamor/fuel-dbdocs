<?php
Autoloader::add_core_namespace('Dbdocs');

Autoloader::add_classes(array(
	'Dbdocs\\Dbdocs' => __DIR__.'/classes/dbdocs.php',
	'Dbdocs\\View_Dbdocs_Base' => __DIR__.'/classes/view/dbdocs/base.php',
	'Dbdocs\\View_Dbdocs_index' => __DIR__.'/classes/view/dbdocs/index.php',
	'Dbdocs\\View_Dbdocs_indexes' => __DIR__.'/classes/view/dbdocs/indexes.php',
	'Dbdocs\\View_Dbdocs_table' => __DIR__.'/classes/view/dbdocs/table.php',
	'Dbdocs\\View_Dbdocs_tables' => __DIR__.'/classes/view/dbdocs/tables.php',
	'Dbdocs\\View_Dbdocs_view' => __DIR__.'/classes/view/dbdocs/view.php',
	'Dbdocs\\View_Dbdocs_views' => __DIR__.'/classes/view/dbdocs/views.php',
	'Dbdocs\\View_Dbdocs_views' => __DIR__.'/classes/view/dbdocs/views.php',
	'Dbdocs\\Dbdocs_TestCase' => __DIR__.'/classes/testcase.php',
	'Dbdocs\\Dbdocs_ViewModelTestCase' => __DIR__.'/classes/viewmodeltestcase.php',
));
