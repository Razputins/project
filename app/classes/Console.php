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

			// Очередь на обработку

			$channel->queue_declare($this->routin_key, false, true, false, false);
			$channel->queue_bind($this->routin_key, 'amq.direct',$this->routin_key);


			// Описываем параметры для задержки обработки через rabbit

			$metadata = new AMQPTable([
				'x-message-ttl' => 15000,
				'x-dead-letter-exchange' => 'amq.direct',
				'x-dead-letter-routing-key' => $this->routin_key
			]);

			// Очередь на обработку

			$channel->queue_declare($this->repeat_key, false, true, false, false, false, $metadata);
			$channel->queue_bind($this->repeat_key, 'amq.direct',$this->repeat_key);
		}

		public function producer()
		{
			$url_list = [
				'https://www.php.net/',            // 200 code
				'https://www.php.net/',           // 200 code
				'https://www.php.net/23123123',  // 302 code
				'https://www.php.ne3',          // 0 code
			];

			while (true) {
				$this->addTask($url_list[rand(0, 500)]);
				sleep(1);
			}
		}

		public function consumer()
		{
			$channel = $this->connect->channel();

			echo "\e[92m Waiting for messages. To exit press CTRL+C \e[0m\n";

			$callback = function ($msg) {
				$native_data = $msg->get('application_headers')->getNativeData();
				if(empty($native_data['repeat'])){
					sleep(30);
				}

				$data = CurlHelper::sendUrl($msg->body);

				if($data['code'] != 200){
					if(empty($native_data['repeat'])){
						$this->addRepeat($msg->body);
					}
					echo "\e[93m Delay message url {$msg->body} \e[0m\n";
					$msg->ack();
					return;
				}

				$model = new RequestModel();
				$model->insert($data);
				echo "\e[93m Insert to DB info from url {$msg->body} \e[0m\n";
				$msg->ack();
			};

			$channel->basic_consume($this->routin_key, '', false, false, false, false, $callback);

			while ($channel->is_open()) {
				$channel->wait();
			}
			$channel->close();
		}
	}