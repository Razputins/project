<?php
	namespace classes;

	use core\Configuration;

	class Settings{
		static $app;

		public static function set($config_list){
			self::$app = new Configuration($config_list);
		}
	}
