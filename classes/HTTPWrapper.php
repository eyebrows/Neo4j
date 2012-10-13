<?php
class HTTPWrapper {

//the vars we pass in to the object
	private $url, $method, $params;
//the ones it generates itself
	private $headers, $body, $http_code;

//not sure doing this as an object (rather than a static "library object") is the best way, but might pay off as more features are needed
//also, it just needs to work so I can get to actually working out Neo4j stuffs
	function __construct() {
	}

	public function request($url, $method='GET', $params='') {
		$this->url = $url;
		$this->method = strtoupper($method);
		$this->params = $params;

		$c = curl_init();
		$this->addParams();

		curl_setopt($c, CURLOPT_URL, $this->url);
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
		$this->headers = trim(substr($response, 0, $headers_size));
		$this->body = trim(substr($response, $headers_size));
		$this->http_code = curl_getinfo($c, CURLINFO_HTTP_CODE);
		curl_close($c);

		return array($this->body, $this->http_code);
	}

	private function addParams() {
		if($this->method=='GET') {
			if($this->params)
				$this->url.=(strpos($this->url, '?')===false?'?':'&').$this->compressParams();
		}
		else if($this->method=='POST') {
			$params = $this->compressParams();
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, $params);
			$headers = array(
				'Content-Length: '.strlen($params),
				'Content-Type: application/json',
				'Accept: application/json',
			);
			curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
		}
	}

	private function compressParams() {
		if(!$this->params || (is_array($this->params) && !count($this->params)))
			return '';
		$params = array();
		foreach($this->params as $key=>$value) {
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