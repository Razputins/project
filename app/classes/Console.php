<?php

	namespace classes;

	use core\Rabbit;
	use helpers\CurlHelper;
	use classes\RequestModel;
	use PhpAmqpLib\Wire\AMQPTable;

	class Console extends Rabbit
	{
		public function __construct()
		{
			parent::__construct();
			$channel = $this->connect->channel();

			$metadata = new AMQPTable([
				'x-message-ttl' => 15000,
				'x-dead-letter-exchange' => 'amq.direct',
				'x-dead-letter-routing-key' => $this->routin_key
			]);


			$channel->queue_declare($this->routin_key, false, true, false, false);
			$channel->queue_bind($this->routin_key, 'amq.direct',$this->routin_key);
			$channel->queue_declare($this->repeat_key, false, true, false, false, false, $metadata);
			$channel->queue_bind($this->repeat_key, 'amq.direct',$this->repeat_key);
		}

		public function producer()
		{
			$url_list = [
				'https://www.php.net/',
				'https://www.php.net/',
				'https://www.php.net/23123123',
				'https://www.php.ne3',
			];

			while (true) {
				$this->add($url_list[rand(0, 3)]);
				sleep(6);
			}
		}

		public function consumer()
		{
			$channel = $this->connect->channel();

			echo "\e[92m Waiting for messages. To exit press CTRL+C \e[0m\n";

			$callback = function ($msg) {
				$data = CurlHelper::sendUrl($msg->body);

				if($data['code'] != 200){
					$native_data = $msg->get('application_headers')->getNativeData();
					if(empty($native_data['repeat'])){
						$this->repeat($msg->body);
					}
					echo "\e[93m Delay message url {$msg->body} \e[0m\n";
					$msg->ack();
					sleep(30);
					return;
				}

				$model = new RequestModel();
				$model->insert($data);
				echo "\e[93m Insert to DB info from url {$msg->body} \e[0m\n";
				$msg->ack();
				sleep(30);
			};

			$channel->basic_consume($this->routin_key, '', false, false, false, false, $callback);

			while ($channel->is_open()) {
				$channel->wait();
			}
			$channel->close();
		}
	}