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
	\http\Client,
	\http\Client\Request,
	\http\Message\Body;

/**
* 
*/
class PeclHttp extends BaseRequest {

	protected $response;

	public function send() {
		if(is_null($this->getBody())) {
			$body = null;
		} else {
			$body = new Body;

			$body->append($this->getBody());
		}

		$request = new Request(
			$this->getMethod(),
			'https://'.$this->getUrl(),
			$this->getHeaders(),
			$body
		);

		$request->addQuery($this->getQuery());

		$client = new Client;

		$client->enqueue($request)->send();

		$this->response = $client->getResponse();
	}

	public function getResponseCode() {
		return $this->response->responseCode;
	}

	public function getResponseBody() {
		return $this->response->body->toString();
	}

}