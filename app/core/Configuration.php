<?php
	namespace core;

	class Configuration
	{
		function __construct($config_list = [])
		{
			foreach ($config_list as $key => $val) {
				$this->$key = $val;
			}
		}
	}