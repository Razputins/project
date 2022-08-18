<?php
	require_once 'core/autoload.php';
	require_once 'config/config_local.php';

	use classes\RequestModel;
	use classes\Settings;

	Settings::set($params);

	$model = new RequestModel();

	$requests = $model->select();

	require_once 'view/main.php';
	?>