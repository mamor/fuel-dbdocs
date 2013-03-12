<?php

namespace Dbdocs;

use \Doctrine\DBAL\Platforms\AbstractPlatform;

class Types_PointType extends \Doctrine\DBAL\Types\Type
{

	public function getName()
	{
		return 'point';
	}

	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		//TODO
	}
}
