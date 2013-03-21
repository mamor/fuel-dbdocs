# fuel-dbdocs [![Build Status](https://travis-ci.org/mp-php/fuel-dbdocs.png)](https://travis-ci.org/mp-php/fuel-dbdocs)

* fuel-dbdocs is generator for database documentation
* You can generate database documentation from command line and browser
* Example(GitLab Database) http://fueldbdocssample.madroom.net/index.html
* Powered by FuelPHP http://fuelphp.com/

---

## Supporting databases

* MySQL (pdo_mysql is required)
* PostgreSQL (pdo_pgsql is required)
* SQLite (pdo_sqlite is required)

## Install

### Getting code

Download zip or git clone

### Install vendors

	$ cd fuel/packages/dbdocs
	$ curl -s http://getcomposer.org/installer | php
	$ php composer.phar install

## Usage

### Command line

	$ php oil r dbdocs:help

### Browser

Access public/index.php

## Test

Create and Edit fuel/app/config/development/dbdocs.php

	<?php

	return array(
		'test_config' => array(
			'db' => array(
				'dbname'   => 'fuel_packages_dbdocs_tests',
				'user'     => 'root',
				'password' => 'root',
				'host'     => 'localhost',
				'driver'   => 'pdo_mysql',
				'description' => 'xxx',
			),
		),
	);

## License

Copyright 2013, Mamoru Otsuka. Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
