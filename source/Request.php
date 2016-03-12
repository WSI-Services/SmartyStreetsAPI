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

namespace WSIServices\SmartyStreetsAPI;

use \WSIServices\SmartyStreetsAPI\Factory;

/**
* 
*/
abstract class Request implements RequestInterface {

	protected $url;

	protected $query = [];

	protected $headers = [
		'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION,
		// Use the next line if you prefer to use your Javascript API token rather than your REST API token.
		// 'Referer' => 'http://YOUR-AUTHORIZED-DOMAIN-HERE',
		// 'Content-Type' => 'application/x-www-form-urlencoded',
		// 'Content-Length' => 0
	];

	protected $contentType;

	protected $method;

	protected $body;

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getUrl() {
		return $this->url;
	}

	public function addQuery(array $query) {
		$this->query = array_merge($this->query, $query);
	}

	public function setQuery(array $query) {
		$this->query = $query;
	}

	public function getQuery() {
		return $this->query;
	}

	public function addHeaders(array $headers) {
		$this->headers = array_merge($this->headers, $headers);
	}

	public function setHeaders(array $headers) {
		$this->headers = $headers;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function setContentType($contentType) {
		$this->contentType = $contentType;
	}

	public function getContentType() {
		return $this->contentType;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	public function getMethod() {
		return $this->method;
	}

	public function setBody($body) {
		$this->body = $body;
	}

	public function getBody() {
		return $this->body;
	}

	abstract public function send();

	abstract public function getResponseCode();

	abstract public function getResponseBody();

}