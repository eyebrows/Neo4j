<?php
class HTTPWrapper {

//not sure doing this as an object (rather than a static "library object") is the best way, but might pay off as more features are needed
//also, it just needs to work so I can get to actually working out Neo4j stuffs
	function __construct() {
	}

	public function get($url) {
		return $this->request($url);
	}

	public function post($url, $params=null) {
		return $this->request($url, 'POST', $params);
	}

	public function request($url, $method='GET', $params=null) {
		$url = str_replace(' ', '%20', $url);
		$method = strtoupper($method);

		$c = curl_init();

		if($method=='POST' && is_array($params)) {
			$params = json_encode($params, JSON_FORCE_OBJECT);
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, $params);
			$headers = array(
				'Content-Length: '.strlen($params),
				'Content-Type: application/json',
				'Accept: application/json',
			);
			curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
		}

		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_HEADER, true);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($c, CURLOPT_TIMEOUT, 40);

		$response = curl_exec($c);

		if($error = curl_error($c)) {
			curl_close($c);
			throw new Exception('CURL Error: '.$error);
		}

		$headers_size = curl_getinfo($c, CURLINFO_HEADER_SIZE);
		$headers = trim(substr($response, 0, $headers_size));
		$body = trim(substr($response, $headers_size));
		$http_code = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);

		return array($body, $http_code);
	}

//not currently used, takes array and turns into traditional HTTP POST string
	private function compressParams($params) {
		if(!$params || (is_array($params) && !count($params)))
			return '';
		$params = array();
		foreach($params as $key=>$value) {
			if(is_array($value))
				foreach($value as $sub_value)
					$params[] = $key.'[]='.$sub_value;
			else
				$params[] = $key.'='.$value;
		}
		return implode('&', $params);
	}
}
?>