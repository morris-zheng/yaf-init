<?php

class UserModel extends BaseModel
{
	protected $table = 'user';

	public static function run($class_name = __CLASS__)
	{
		return parent::run($class_name);
	}
}
