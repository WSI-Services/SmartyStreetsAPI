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
class FileGetContents extends BaseRequest {

	protected $response = [
		'code' => null,
		'body' => null
	];

	public function send() {
		$options['method'] = $this->getMethod();
		if($options['method'] == 'POST') {
			$body = $this->getBody();
			if(!is_null($body)) {
				$options['content'] = $body;
			}
		}

		$headers = $this->getHeaders();
		if(count($headers)) {
			$options['headers'] = $headers;
		}

		$url = 'https://'.$this->getUrl();
		$query = $this->getQuery();
		if(count($query)) {
			$url .= '?'.http_build_query($query);
		}

		$this->response = $this->__fileGetContents($url, $options);
	}

	public function getResponseCode() {
		return $this->response['code'];
	}

	public function getResponseBody() {
		return $this->response['body'];
	}

	public function __fileGetContents($url, $options = null) {
		if(is_array($options) && count($options)) {
			$options = stream_context_create(['http' => $options]);
		} else {
			$options = null;
		}

		$response['body'] = @file_get_contents($url, false, $options);

		if(preg_match('#HTTP/[0-9\.]+\s+([0-9]+)#', $http_response_header[0], $code)) {
			$response['code'] = intval($code[1]);
		}

		return $response;
	}
}