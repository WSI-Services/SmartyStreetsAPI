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

namespace WSIServices\SmartyStreetsAPI\Tests\Request;

use \http\Client\Response,
	\WSIServices\SmartyStreetsAPI\Factory,
	\WSIServices\SmartyStreetsAPI\Request\PeclHttp,
	\WSIServices\SmartyStreetsAPI\Tests\BaseTestCase;

class PeclHttpTest extends BaseTestCase {

	protected $peclHttp;

	public function setUp() {
		parent::setUp();

		$this->peclHttp = $this->getMockery(
			PeclHttp::class
		)->makePartial();
	}

	public function tearDown() {
		parent::tearDown();

		$this->peclHttp = null;
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\PeclHttp::send()
	 */
	public function testSendWithNullBody() {
		$requestData = [
			'body' => null,
			'method' => 'GET',
			'url' => 'api.smartystreets.com',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION,
			],
			'query' => [
				'q' => 'SmartyStreetsAPI'
			],
		];

		$this->peclHttp->shouldReceive('getBody')
			->once()
			->andReturn($requestData['body']);

		$this->peclHttp->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->peclHttp->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$this->peclHttp->shouldReceive('getQuery')
			->once()
			->andReturn($requestData['query']);

		$this->peclHttp->shouldReceive('getHeaders')
			->once()
			->andReturn($requestData['headers']);

		$this->peclHttp->send();

		$response = $this->getProtectedProperty(
			$this->peclHttp,
			'response'
		);

		$this->assertEquals(
			200,
			$response->responseCode,
			'Response code failed'
		);

		$this->assertEquals(
			$requestData['body'],
			$response->parentMessage->body,
			'Body not correct'
		);

		$this->assertEquals(
			$requestData['method'],
			$response->parentMessage->requestMethod,
			'Method not correct'
		);

		$query = ($requestData['query'] ? '/?'.http_build_query($requestData['query']) : '');

		$this->assertEquals(
			'https://'.$requestData['url'].$query,
			$response->parentMessage->requestUrl,
			'Url not correct'
		);

		$this->assertArrayHasKey(
			'User-Agent',
			$response->parentMessage->headers,
			'User-Agent not added to headers'
		);

		$this->assertEquals(
			$requestData['headers']['User-Agent'],
			$response->parentMessage->headers['User-Agent'],
			'User-Agent not correct'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\PeclHttp::send()
	 */
	public function testSendWithNonNullBody() {
		$requestData = [
			'body' => '{test:true}',
			'method' => 'GET',
			'url' => 'api.smartystreets.com',
			'headers' => [
				'User-Agent' => 'SmartyStreetsAPI/'.Factory::VERSION,
			],
			'query' => [
				'q' => 'SmartyStreetsAPI'
			],
		];

		$this->peclHttp->shouldReceive('getBody')
			->twice()
			->andReturn($requestData['body']);

		$this->peclHttp->shouldReceive('getMethod')
			->once()
			->andReturn($requestData['method']);

		$this->peclHttp->shouldReceive('getUrl')
			->once()
			->andReturn($requestData['url']);

		$this->peclHttp->shouldReceive('getQuery')
			->once()
			->andReturn($requestData['query']);

		$this->peclHttp->shouldReceive('getHeaders')
			->once()
			->andReturn($requestData['headers']);

		$this->peclHttp->send();

		$response = $this->getProtectedProperty(
			$this->peclHttp,
			'response'
		);

		$this->assertEquals(
			200,
			$response->responseCode,
			'Response code failed'
		);

		$this->assertEquals(
			$requestData['body'],
			$response->parentMessage->body,
			'Body not correct'
		);

		$this->assertEquals(
			$requestData['method'],
			$response->parentMessage->requestMethod,
			'Method not correct'
		);

		$query = ($requestData['query'] ? '/?'.http_build_query($requestData['query']) : '');

		$this->assertEquals(
			'https://'.$requestData['url'].$query,
			$response->parentMessage->requestUrl,
			'Url not correct'
		);

		$this->assertArrayHasKey(
			'User-Agent',
			$response->parentMessage->headers,
			'User-Agent not added to headers'
		);

		$this->assertEquals(
			$requestData['headers']['User-Agent'],
			$response->parentMessage->headers['User-Agent'],
			'User-Agent not correct'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\PeclHttp::getResponseCode()
	 */
	public function testGetResponseCode() {
		$response = $this->getMockery(Response::class)
			->makePartial();

		$response->responseCode = 200;

		$this->setProtectedProperty(
			$this->peclHttp,
			'response',
			$response
		);

		$this->assertEquals(
			$response->responseCode,
			$this->peclHttp->getResponseCode(),
			'Response code not returned correctly'
		);
	}

	/**
	 * @covers WSIServices\SmartyStreetsAPI\Request\PeclHttp::getResponseBody()
	 */
	public function testGetResponseBody() {
		$response = $this->getMockery(Response::class)
			->makePartial();

		$response->body = $this->getMockery(Response::class)
			->makePartial();

		$response->body->append('body');


		$this->setProtectedProperty(
			$this->peclHttp,
			'response',
			$response
		);

		$this->assertEquals(
			$response->body->toString(),
			$this->peclHttp->getResponseBody(),
			'Response body not returned correctly'
		);
	}

}