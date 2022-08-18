<?php
	namespace helpers;

	class CurlHelper{

		public static function sendUrl($url){
			$data = [
				'url' => $url,
				'code' => 0,
				'header' => '',
				'content' => '',
			];

			$c = curl_init($url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_HEADERFUNCTION,
				function ($curl, $header) use (&$headers) {
					$len = strlen($header);
					$header = explode(':', $header, 2);
					if (count($header) < 2) // ignore invalid headers
						return $len;

					$headers[strtolower(trim($header[0]))][] = trim($header[1]);

					return $len;
				}
			);
			$response = curl_exec($c);

			if (curl_error($c)) {
				$data['header'] =  json_encode(curl_error($c));
				return $data;
			};

			$headers['new'] = rand(0,1);

			$data['code'] = curl_getinfo($c, CURLINFO_HTTP_CODE);
			$data['header'] = json_encode($headers);
			$data['content'] = $response;

			curl_close($c);

			return $data;
		}
	}