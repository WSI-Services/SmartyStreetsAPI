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

use \WSIServices\SmartyStreetsAPI\Request as BaseRequest,
	\GuzzleHttp\Client;

/**
* 
*/
class GuzzleHttp extends BaseRequest {

	protected $response;

	public function send() {
		$options = [
			'timeout'  => 2.0,
		];

		if(count($this->getHeaders())) {
			$options['headers'] = $this->getHeaders();
		}

		if(!is_null($this->getBody())) {
			$options['body'] = $this->getBody();
		}

		if(count($this->getQuery())) {
			$options['query'] = $this->getQuery();
		}

		$client = $this->__newClient();

		$request = $client->createRequest(
			$this->getMethod(),
			'https://'.$this->getUrl(),
			$options
		);

		$this->response = $client->send($request);
	}

	public function getResponseCode() {
		return $this->response->getStatusCode();
	}

	public function getResponseBody() {
		return $this->response->getBody()->getContents();
	}

	public function __newClient(array $config = []) {
		return new Client($config);
	}

}