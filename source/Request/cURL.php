<?php
/**
 * Author: Sam Likins <sam.likins@wsi-services.com>
 * Copyright: Copyright (c) 2015-2016, WSI-Services
 * 
 * License: http://opensource.org/licenses/gpl-3.0.html
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace WSIServices\SmartyStreetsAPI\Request;

use \WSIServices\SmartyStreetsAPI\Request as BaseRequest;

/**
* 
*/
class cURL extends BaseRequest {

	protected $response = [
		'code' => null,
		'body' => null
	];

	public function getHeaders() {
		$headers = parent::getHeaders();

		return array_map(
			function($header, $value) {
				return $header.': '.$value;
			},
			array_keys($headers),
			$headers
		);
	}

	public function send() {
		$options = [
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_VERBOSE			=> 0,
		];

		$method = $this->getMethod();
		if($method == 'POST') {
			$options[CURLOPT_POST] = true;

			$body = $this->getBody();
			if(!is_null($body)) {
				$options[CURLOPT_POSTFIELDS] = $body;
			}
		}

		$headers = $this->getHeaders();
		if(count($headers)) {
			$options[CURLOPT_HTTPHEADER] = $headers;
		}

		$options[CURLOPT_URL] = 'https://'.$this->getUrl();
		$query = $this->getQuery();
		if(count($query)) {
			$options[CURLOPT_URL] .= '?'.http_build_query($query);
		}

		$this->response = $this->__curl($options);
	}

	public function getResponseCode() {
		return $this->response['code'];
	}

	public function getResponseBody() {
		return $this->response['body'];
	}

	public function __curl($options) {
		$curlHandle = curl_init();

		curl_setopt_array($curlHandle, $options);

		$return = [
			'body' => curl_exec($curlHandle),
			'code' => curl_getinfo($curlHandle, CURLINFO_HTTP_CODE)
		];

		curl_close($curlHandle);

		return $return;
	}
}