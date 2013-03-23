* fuel-dbdocs is generator for database documentation
* You can generate database documentation from command line and browser
* Example(GitLab Database) http://fueldbdocssample.madroom.net/index.html
* Powered by FuelPHP http://fuelphp.com/

If you need analysis of the FuelPHP's models relations, please use https://github.com/mp-php/fuel-packages-dbdocs

---

## Supporting databases

* MySQL (pdo_mysql is required)
* PostgreSQL (pdo_pgsql is required)
* SQLite (pdo_sqlite is required)

## Install

### Getting code

	$ git clone --recursive git://github.com/mp-php/fuel-dbdocs.git fuel-dbdocs

### Install vendors

	$ cd fuel/packages/dbdocs
	$ curl -s http://getcomposer.org/installer | php
	$ php composer.phar install

## Usage

### Command line

	$ php oil r dbdocs:help

### Browser

Access public/index.php

## Test in fuel/packages/dbdocs

Create and Edit fuel/app/config/development/dbdocs.php

	<?php

	return array(
		'test_config' => array(
			'db' => array(
	            'dbname'   => '[test_dbname]', // You must also create database.
				'user'     => 'root',
				'password' => 'root',
				'host'     => 'localhost',
				'driver'   => 'pdo_mysql',
				'description' => 'xxx',
			),
		),
	);

Run

	$ phpunit --group=Dbdocs --coverage-text -c fuel/packages/dbdocs/phpunit.xml

## License

Copyright 2013, Mamoru Otsuka. Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
