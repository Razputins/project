<?php
	namespace core;
	require_once __DIR__ . '/../vendor/autoload.php';

	use classes\Settings;
	use PhpAmqpLib\Connection\AMQPStreamConnection;
	use PhpAmqpLib\Message\AMQPMessage;
	use PhpAmqpLib\Wire\AMQPTable;

	class Rabbit{

		public $connect;
		public $routin_key = 'task_queue';
		public $repeat_key = 'repeat_queue';

		function __construct(){
			$params = Settings::$app->rabbitmq;
			$this->connect = new AMQPStreamConnection($params['host'], $params['port'], $params['username'], $params['password']);
		}

		public function add($str){
			$channel = $this->connect->channel();
			$msg = new AMQPMessage($str, array('application_headers' => new AMQPTable([
				'repeat' => 0,
			])));

			$channel->basic_publish($msg, 'amq.direct', $this->routin_key);
			$channel->close();
		}

		public function repeat($str){
			$channel = $this->connect->channel();
			$msg = new AMQPMessage($str, array('application_headers' => new AMQPTable([
				'repeat' => 1,
			])));
			$channel->basic_publish($msg, 'amq.direct', $this->repeat_key);
			$channel->close();
		}
	}
