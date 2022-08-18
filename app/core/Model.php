<?php

namespace core;

use classes\Settings;

class Model
{
	protected $db;

	function __construct()
	{
		$params = Settings::$app->db;
		$this->db = new \PDO("mysql:host={$params['host']};port={$params['port']};dbname={$params['database']}", $params['username'], $params['password'], array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
	}
}