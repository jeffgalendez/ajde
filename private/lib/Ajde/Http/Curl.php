<?php

class Ajde_Http_Curl {
	
	static private function rawURLEncodeCallback($value, $key) {
		return "$key=" . rawurlencode($value);
	}
	
	static public function doPostRequest($url, $postData, $async = false) {
		$encodedVariables = array_map ( array("Ajde_Http_Curl", "rawURLEncodeCallback"), $postData, array_keys($postData) );
		$postContent = join('&', $encodedVariables);
		$postContentLen = strlen($postContent);
		
		// HTTP Headers have to be seperated by \r\n;
		define ('CRLF', "\r\n");
		 
		$streamCtx = stream_context_create(
			array (
				'http' => array (
					'method' => 'POST', // Has to be in uppercase
					'content' => $postContent,
					'header'  => "Content-Type: application/x-www-form-urlencoded" . CRLF . "Content-Length: $postContentLen" . CRLF
				)
			)
		);
		if ($async === true) {
			$fp = @fopen($url, 'rb', false, $streamCtx);
     		if (!$fp) { return false; }
     		return true;
		} else {
			// php bug: http://bugs.php.net/bug.php?id=41770
			return @file_get_contents($url, false, $streamCtx);
		}
	}
	
	static public function doAsyncPostRequest($url, $postData) {
		self::doPostRequest($url, $postData, true);
	}
	
}