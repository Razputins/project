<?php
	spl_autoload_register(function ($class) {
		$request = '';
		foreach (explode('\\', $class) as $item) {
			$request .= '/' . $item;
		}
		if (file_exists($file = dirname(__DIR__) . $request . ".php"))
			require_once $file;
	});